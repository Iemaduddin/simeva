<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Event;
use BaconQrCode\Writer;
use App\Models\EventStep;
use App\Models\Organizer;
use App\Models\EventPrice;
use App\Models\TeamMember;
use App\Models\AssetBooking;
use App\Models\EventSpeaker;
use Illuminate\Http\Request;
use App\Models\EventAttendance;

use BaconQrCode\Encoder\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EventParticipant;
use App\Models\EventTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\AssetBookingDocument;
use Illuminate\Support\Facades\Auth;

use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Support\Facades\Storage;
use App\Notifications\RegistrationEvent;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use App\Notifications\BookingAssetInternalCampus;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class EventController extends Controller
{
    public function listEventPage()
    {

        $organizers = Organizer::with('user')->get()->groupBy('organizer_type');
        return view('dashboardPage.events.listEvent', compact('organizers'));
    }
    public function eventPage($shorten_name)
    {
        if (Auth::user()->roles()->where('name', 'Organizer')->exists()) {
            if (optional(Auth::user()->organizer)->shorten_name !== $shorten_name) {
                return abort(403, 'Unauthorized');
            }
        }
        return view('dashboardPage.events.index', compact('shorten_name'));
    }

    // Super Admin
    public function getDataListEvent(Request $request)
    {

        $events = Event::with('organizers')->where('organizer_id', $request->organizer_id)->get();

        return DataTables::of($events)
            ->addIndexColumn()
            ->editColumn('status', function ($event) {

                $badgeClass = '';
                $textButton = '';
                if ($event->status === 'planned') {
                    $textButton = 'Direncanakan';
                    $badgeClass = 'bg-primary-600';
                } elseif ($event->status === 'published') {
                    $textButton = 'Terpublikasi';
                    $badgeClass = 'bg-success-600';
                } elseif ($event->status === 'blocked') {
                    $textButton = 'Terblokir';
                    $badgeClass = 'bg-danger-600';
                } elseif ($event->status === 'completed') {
                    $textButton = 'Selesai';
                    $badgeClass = 'bg-dark';
                }
                return '<span class="badge text-sm ' . $badgeClass . ' px-20 py-9 radius-4 text-white">' . $textButton . '</span>';
            })
            ->addColumn('action', function ($event) {

                $html = '<div class="d-flex gap-8">';

                if ($event->status === 'blocked') {
                    $html .= '
                        <form action="' . route('block.event', ['type' => 'unblock_event', 'id' => $event->id]) . '" method="POST" class="block-form" data-table="listEventTable">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="button" class="block-btn w-40-px h-40-px bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Unblock" data-action="unblock_event">
                                <iconify-icon icon="gg:unblock" width="25"></iconify-icon>
                            </button>
                        </form>';
                } elseif ($event->status === 'published') {
                    // Tombol Block
                    $html .= '
                        <form action="' . route('block.event', ['type' => 'block_event', 'id' => $event->id]) . '" method="POST" class="block-form" data-table="listEventTable">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="button" class="block-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Block" data-action="block_event">
                                <iconify-icon icon="ic:sharp-block" width="20"></iconify-icon>
                            </button>
                        </form>';
                }

                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function blockEvent($type, $id)
    {
        try {
            // Ambil data event yang ingin dihapus
            $event = Event::findOrFail($id);
            // Hapus event
            $event->update(['status' => $type == 'block_event' ? 'blocked' : 'published']);
            return response()->json([
                'status' => 'success',
                'message' => $type == 'block_event' ? 'Blokir event berhasil!' :  'Membuka blokir event berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $type == 'block_event' ? 'Terjadi kesalahan saat memblokir event.' : 'Terjadi kesalahan saat membuka blokir event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // Organizer
    public function getDataEvents($shorten_name)
    {

        $organizer_id = Organizer::where('shorten_name', $shorten_name)->pluck('id')->first();
        $events = Event::with('steps')->where('organizer_id', $organizer_id)->get();
        $tableId = $shorten_name . '-EventsTable';


        return DataTables::of($events)
            ->addIndexColumn()
            ->editColumn('event_date_location', function ($event) {
                $eventSteps = EventStep::where('event_id', $event->id)->orderBy('event_date')->get();
                $eventCount = $eventSteps->count();

                return $eventSteps->map(function ($step, $index) use ($eventCount) {
                    $stepInfo = $eventCount > 1 ? ($index + 1) . ". " . ucwords($step->step_name) . " (" : "";
                    $stepInfo .= Carbon::parse($step->event_date)->translatedFormat('d M Y') . ", " .
                        Carbon::parse($step->event_time_start)->translatedFormat('H.i') . " - " .
                        Carbon::parse($step->event_time_end)->translatedFormat('H.i') . ")";

                    // Decode lokasi (JSON)
                    $locations = json_decode($step->location, true);
                    $locationText = collect($locations)->map(function ($loc) {
                        switch ($loc['type']) {
                            case 'offline':
                                $assetName = Asset::where('id', $loc['location'])->value('name');
                                return ($assetName ?? $loc["location"]) . " <strong>(Offline)</strong>";

                            case 'online':
                                return  $loc['location'] . " <strong>(Online)</strong>";
                            case 'hybrid':
                                $assetName = Asset::where('id', $loc['location_offline'])->value('name');
                                return "<ul>
                                            <li><strong>Hybrid</strong>
                                            <li>Offline: " . ($assetName ?? $loc['location_offline']) . "</li>
                                            <li>Online: " . $loc['location_online'] . "</li>
                                        </ul>";

                            default:
                                return "📌 Lokasi tidak diketahui";
                        }
                    })->implode('<br>');

                    return $stepInfo . "<br>" . $locationText;
                })->implode('<br><br>');
            })
            ->editColumn('quota', function ($event) {
                return $event->remaining_quota . '/' . $event->quota;
            })
            ->addColumn('action', function ($event) use ($tableId) {
                return '<div class="d-flex gap-8">
                <a href="' . route('detail.event.page', ['id' => $event->id]) . '" class="w-40-px h-40-px cursor-pointer bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="gg:more-o"></iconify-icon>
                </a>
                
                <a href="' . route('update.event.page', ['id' => $event->id]) . '" class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <form action="' . route('destroy.event', ['id' => $event->id]) . '" method="POST" class="delete-form" data-table="' . $tableId . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })
            ->rawColumns(['event_date_location', 'quota', 'action'])
            ->make(true);
    }



    public function addEventPage()
    {
        $organizer_id = Auth::user()->organizer->id;
        $shorten_name = Auth::user()->organizer->shorten_name;
        $buildings = Asset::with('jurusan')->where('type', 'building')->where('booking_type', 'daily')->get();
        $transportations = Asset::with('jurusan')->where('type', 'transportation')->where('booking_type', 'daily')->get();

        $team_members = TeamMember::select('id', 'name')->where('organizer_id', $organizer_id)->get();
        return view('dashboardPage.events.add-event', compact('buildings', 'transportations', 'team_members', 'shorten_name'));
    }
    public function updateEventPage($id)
    {
        $organizer_id = Auth::user()->organizer->id;
        $shorten_name = Auth::user()->organizer->shorten_name;
        $buildings = Asset::with('jurusan')->where('type', 'building')->where('booking_type', 'daily')->get();
        $transportations = Asset::with('jurusan')->where('type', 'transportation')->where('booking_type', 'daily')->get();

        $team_members = TeamMember::select('id', 'name')->where('organizer_id', $organizer_id)->get();

        $event = Event::with(['prices', 'steps.event_speaker'])->findOrFail($id);
        $assetBookings = AssetBooking::where('event_id', $id)->get();
        return view('dashboardPage.events.update-event', compact('event', 'assetBookings', 'buildings', 'transportations', 'team_members', 'shorten_name'));
    }
    public function detailEventPage($id)
    {
        $organizer_id = Auth::user()->organizer->id;
        $shorten_name = Auth::user()->organizer->shorten_name;
        $buildings = Asset::with('jurusan')->where('type', 'building')->where('booking_type', 'daily')->get();
        $transportations = Asset::with('jurusan')->where('type', 'transportation')->where('booking_type', 'daily')->get();

        $team_members = TeamMember::select('id', 'name')->where('organizer_id', $organizer_id)->get();

        $event = Event::with(['prices', 'steps.event_speaker'])->findOrFail($id);
        $assetBookings = AssetBooking::with(['asset'])->where('event_id', $id)->get();
        $documentPath = AssetBookingDocument::where('event_id', $id)->value('document_path');
        return view('dashboardPage.events.detail-event', compact('event', 'documentPath', 'assetBookings', 'buildings', 'transportations', 'team_members', 'shorten_name'));
    }
    public function storeEvent(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'theme' => 'required',
            'description' => 'required',
            'scope' => 'required',
            'quota' => 'required',
            'name_category_prices' => 'array',
            'prices' => 'array',
            'scopes' => 'array',
            'event_leader' => 'required',
            'is_publish' => 'required|in:0,1',
            'is_free' => 'required|in:0,1',
            // 'benefit' => 'array',
            // 'contact_person' => 'array',
            // 'date_registration_start' => 'required',
            // 'date_registration_end' => 'required',
            'event_dates' => 'required|array',
            'event_time_starts' => 'required|array',
            'event_time_ends' => 'required|array',
            'execution_systems' => 'required|array',
            'locations' => 'array',
            'event_date_speakers' => 'array',
            'speaker_name' => 'array',
            'role' => 'array',
            // 'questions' => 'required|array',
            // 'field_types' => 'required|array',
            // 'answers' => 'required|array',
            // 'is_required' => 'required|in:0,1',
            // 'pamphlet_path' => 'required',
            // 'banner_path' => 'required',
            // 'sponsored_by' => 'required',
            // 'media_partner' => 'required',

        ], [
            'title.required' => 'Judul acara wajib diisi.',
            'theme.required' => 'Tema acara wajib diisi.',
            'description.required' => 'Deskripsi acara wajib diisi.',
            'scope.required' => 'Lingkup acara wajib diisi.',
            'quota.required' => 'Kuota peserta wajib diisi.',
            'event_leader.required' => 'Nama ketua acara wajib diisi.',
            // 'name_category_prices.array' => 'Waktu mulai acara harus berupa array.',
            // 'prices.array' => 'Waktu mulai acara harus berupa array.',
            // 'scopes.array' => 'Waktu mulai acara harus berupa array.',
            'is_free.required' => 'Status biaya event wajib diisi.',
            'is_free.in' => 'Status biaya event  harus bernilai Free atau Paid.',
            'is_publish.required' => 'Status publikasi wajib diisi.',
            'is_publish.in' => 'Status publikasi harus bernilai Ya atau Tidak.',
            // 'benefit.array' => 'Benefit harus berupa array.',
            // 'contact_person.array' => 'Contact person harus berupa array.',

            'date_registration_start.required' => 'Tanggal mulai pendaftaran wajib diisi.',
            'date_registration_end.required' => 'Tanggal akhir pendaftaran wajib diisi.',
            'event_dates.required' => 'Tanggal acara wajib diisi.',
            // 'event_dates.array' => 'Tanggal acara harus berupa array.',
            'event_time_starts.required' => 'Waktu mulai acara wajib diisi.',
            // 'event_time_starts.array' => 'Waktu mulai acara harus berupa array.',
            'event_time_ends.required' => 'Waktu selesai acara wajib diisi.',
            // 'event_time_ends.array' => 'Waktu selesai acara harus berupa array.',
            'execution_systems.required' => 'Sistem pelaksanaan wajib diisi.',
            // 'execution_systems.array' => 'Sistem pelaksanaan harus berupa array.',
            // 'locations.required' => 'Lokasi acara wajib diisi.',
            // 'locations.array' => 'Lokasi acara harus berupa array.',

            // 'event_date_speakers.required' => 'Tanggal sesi pembicara wajib diisi.',
            // 'event_date_speakers.array' => 'Tanggal sesi pembicara harus berupa array.',
            // 'speaker_name.required' => 'Nama pembicara wajib diisi.',
            // 'speaker_name.array' => 'Nama pembicara harus berupa array.',
            // 'role.required' => 'Peran pembicara wajib diisi.',
            // 'role.array' => 'Peran pembicara harus berupa array.',
            // 'questions.required' => 'Pertanyaan wajib diisi.',
            // 'questions.array' => 'Pertanyaan harus berupa array.',
            // 'field_types.required' => 'Tipe field wajib diisi.',
            // 'field_types.array' => 'Tipe field harus berupa array.',
            // 'answers.required' => 'Jawaban wajib diisi.',
            // 'answers.array' => 'Jawaban harus berupa array.',
            // 'is_required.required' => 'Status required wajib diisi.',
            // 'is_required.in' => 'Status required harus bernilai Ya atau Tidak.',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Ambil error pertama dari validasi
            notyf()->ripple(true)->error($firstError);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {

            // Ambil maksimal 3 kata dari title
            $part_event_name = implode(' ', array_slice(explode(' ', $request->title), 0, 3));
            // Ambil tahun saat ini
            $yearNow = Carbon::now()->year;

            // Nama organizer (gunakan shorten_name dari Auth)
            $organizerName = Auth::user()->organizer->shorten_name;

            // Handle pamflet upload
            $filePaths = [];
            if ($request->hasFile('pamphlet_path')) {
                $file = $request->file('pamphlet_path');
                $extension = $file->getClientOriginalExtension();

                // Buat nama file
                $fileName = 'Pamflet.' . $extension;

                // Simpan file dengan struktur path yang sesuai
                $pamphletPath = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name,
                    $fileName,
                    'public'
                );

                // Simpan path file ke array
                $filePaths['pamphlet_path'] = $pamphletPath;
            }


            // Handle banner upload
            if ($request->hasFile('banner_path')) {
                $file = $request->file('banner_path');
                $extension = $file->getClientOriginalExtension();

                // Buat nama file
                $fileName = 'Banner.' . $extension;

                // Simpan file dengan struktur path yang sesuai
                $bannerPath = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name,
                    $fileName,
                    'public'
                );

                // Simpan path file ke array
                $filePaths['banner_path'] = $bannerPath;
            }

            // Handle sponsor uploads (multiple files)
            if ($request->hasFile('sponsored_by')) {
                $sponsoredPaths = [];
                foreach ($request->file('sponsored_by') as $sponsoredFile) {
                    $extension = $sponsoredFile->getClientOriginalExtension();

                    // Buat nama file
                    $fileName = 'Sponsor_' . uniqid() . '.' . $extension;

                    // Simpan file dengan struktur path yang sesuai
                    $path = $sponsoredFile->storeAs(
                        'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/sponsors',
                        $fileName,
                        'public'
                    );

                    $sponsoredPaths[] = $path;
                }
                $filePaths['sponsored_by'] = $sponsoredPaths;
            }

            // Handle media partner uploads (multiple files)
            if ($request->hasFile('media_partner')) {
                $mediaPartnerPaths = [];
                foreach ($request->file('media_partner') as $mediaFile) {
                    $extension = $mediaFile->getClientOriginalExtension();

                    // Buat nama file
                    $fileName = 'MediaPartner_' . uniqid() . '.' . $extension;


                    // Simpan file dengan struktur path yang sesuai
                    $path = $mediaFile->storeAs(
                        'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/media_partners',
                        $fileName,
                        'public'
                    );

                    $mediaPartnerPaths[] = $path;
                }
                $filePaths['media_partner'] = $mediaPartnerPaths;
            }


            $event = Event::create(
                [
                    'organizer_id' => Auth::user()->organizer->id,
                    'title' => $request->title,
                    'theme' => $request->theme,
                    'description' => $request->description,
                    'scope' => $request->scope,
                    'event_category' => $request->event_category,
                    'quota' => $request->quota,
                    'event_leader' => $request->event_leader,
                    'is_free' => $request->is_free,
                    'is_publish' => $request->is_publish,
                    'status' => $request->is_publish ? 'published' : 'planned',
                    'benefit' => $request->benefit,
                    'contact_person' => $request->contact_person,
                    'registration_date_start' => $request->date_registration_start,
                    'registration_date_end' => $request->date_registration_end,
                    'pamphlet_path' => $filePaths['pamphlet_path'] ?? null,
                    'banner_path' => $filePaths['banner_path'] ?? null,
                    'sponsored_by' => json_encode($filePaths['sponsored_by'] ?? []),
                    'media_partner' => json_encode($filePaths['media_partner'] ?? []),
                ]
            );
            if ($request->has('prices') && is_array($request->prices)) {
                // Filter untuk memeriksa agar isi konten tidak bernilai null

                $filteredPrices = array_filter($request->prices, function ($price) {
                    return !is_null($price);
                });

                // diproses jika validasi berhasil

                if (!empty($filteredPrices)) {
                    foreach ($filteredPrices as $index => $price) {
                        EventPrice::create([
                            'event_id' => $event->id,
                            'category_name' => $request->name_category_prices[$index] ?? null,
                            'scope' => $request->scopes[$index] ?? null,
                            'price' => $price,
                        ]);
                    }
                }
            }

            $bookingNo = null;
            $jurusanAssets = [];
            $umumAssets = [];

            $locationsOfflineIndex = 0;
            $locationsOnlineIndex = 0;
            $locationIndex = 0;
            $addressIndex = 0; // Tambahkan index untuk address offline
            $event_steps = [];
            $asset_bookings = [];
            foreach ($request->event_dates as $index => $event_date) {
                $executionSystem = $request->execution_systems[$index];
                $locations = [];

                // ** OFFLINE **
                if ($executionSystem === 'offline') {
                    $location = $request->locations[$locationIndex] ?? null;
                    $locationData = [
                        'type' => 'offline',
                        'location' => $location,
                    ];

                    // Jika location_type manual, tambahkan address
                    if ($request->location_type[$index] === 'manual') {
                        $addressLocation = $request->address_locations[$addressIndex] ?? null;
                        if (!is_null($addressLocation)) {
                            $locationData['address'] = $addressLocation;
                        }
                        $addressIndex++; // Hanya tambah jika offline manual
                    }

                    $locations[] = $locationData;

                    if (!is_null($location)) {
                        $locationIndex++;
                    }
                }

                // ** ONLINE **
                elseif ($executionSystem === 'online') {
                    $location = $request->locations[$locationIndex] ?? null;

                    $locations[] = [
                        'type' => 'online',
                        'location' => $location,
                    ];

                    if (!is_null($location)) {
                        $locationIndex++;
                    }
                }

                // ** HYBRID **
                elseif ($executionSystem === 'hybrid') {
                    $locationOffline = $request->locations_offline[$locationsOfflineIndex] ?? null;
                    $addressLocationOffline = $request->address_locations_offline[$locationsOfflineIndex] ?? null;
                    $locationOnline = $request->locations_online[$locationsOnlineIndex] ?? null;

                    $locationData = [
                        'type' => 'hybrid',
                        'location_offline' => $locationOffline,
                        'location_online' => $locationOnline,
                    ];

                    if ($request->location_type[$index] === 'manual' && !is_null($addressLocationOffline)) {
                        $locationData['address_location_offline'] = $addressLocationOffline;
                    }

                    $locations[] = $locationData;

                    if (!is_null($locationOffline)) {
                        $locationsOfflineIndex++;
                    }
                    if (!is_null($locationOnline)) {
                        $locationsOnlineIndex++;
                    }
                }

                // Simpan data ke tabel EventStep
                $event_steps[] =  EventStep::create([
                    'event_id' => $event->id,
                    'step_name' => count($request->event_dates) > 1 ? $request->step_names[$index] : null,
                    'event_date' => $event_date,
                    'event_time_start' => $request->event_time_starts[$index],
                    'event_time_end' => $request->event_time_ends[$index],
                    'description' => $request->event_step_descriptions[$index] ?? null,
                    'execution_system' => $executionSystem,
                    'location_type' => $request->location_type[$index],
                    'location' => json_encode($locations), // Simpan dalam format JSON
                ]);

                if ($request->location_type[$index] === 'campus' && $executionSystem !== 'online') {

                    if ($executionSystem === 'offline') {
                        $location_id = $request->locations[$locationIndex - 1] ?? null; // Ambil lokasi terakhir
                    } elseif ($executionSystem === 'hybrid') {
                        $location_id = $request->locations_offline[$locationsOfflineIndex - 1] ?? null;
                    }

                    // **Pastikan location_id adalah UUID yang ada di tabel assets**
                    $locationExists = Asset::where('id', $location_id)->exists();
                    if (!$locationExists) {
                        continue; // Lewati jika tidak valid
                    }
                    $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->event_dates[$index] . ' ' . $request->event_time_starts[$index]);
                    $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->event_dates[$index] . ' ' . $request->event_time_ends[$index]);

                    // **Cek apakah ada booking yang bentrok dengan waktu yang dipilih**
                    $conflict = AssetBooking::where('asset_id', $location_id)
                        ->where(function ($query) {
                            $query->where('status', 'NOT LIKE', 'submission%')
                                ->where('status', '!=', 'rejected_booking')
                                ->where('status', '!=', 'rejected')
                                ->where('status', '!=', 'cancelled');
                        })
                        ->where(function ($query) use ($usageDateStart, $usageDateEnd) {
                            $query->where(function ($q) use ($usageDateStart, $usageDateEnd) {
                                $q->where('usage_date_start', '<', $usageDateEnd)
                                    ->where('usage_date_end', '>', $usageDateStart);
                            });
                        })
                        ->exists();


                    if ($conflict) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.'
                        ], 422);
                    }
                    if ($location_id) {
                        $booking = AssetBooking::create([
                            'asset_id' => $location_id,
                            'user_id' => Auth::user()->id,
                            'booking_number' => '',
                            'event_id' => $event->id,
                            'usage_date_start' => $usageDateStart,
                            'usage_date_end' => $usageDateEnd,
                            'usage_event_name' => $event->title,
                            'status' => 'submission_booking',
                        ]);
                        $asset_bookings[] = $booking;
                    }
                    if ($index === 0) {
                        $scope = optional($booking->asset)->facility_scope == 'umum' ? 'FU' : 'FJ';
                        $bookingDate = $booking->created_at->format('Ymd'); // Tanggal Booking

                        // Ambil 4 karakter dari UUID dengan cara yang lebih aman
                        $uuidPart1 = substr(str_replace('-', '', $booking->id), 0, 4);  // 4 karakter pertama tanpa "-"
                        $uuidPart2 = substr(str_replace('-', '', $booking->id), -4); // 4 karakter terakhir tanpa "-"

                        // Generate booking number
                        $bookingNo = "{$scope}{$bookingDate}{$uuidPart1}{$uuidPart2}";

                        // Update booking_number
                        $booking->update(['booking_number' => strtoupper($bookingNo)]);
                    }

                    if (optional($booking->asset)->facility_scope === 'umum') {
                        $umumAssets[] = $booking->asset->name;
                    } elseif ($booking->asset->facility_scope === 'jurusan') {
                        $jurusanAssets[] = [
                            'name' => $booking->asset->name,
                            'jurusan_id' => $booking->asset->jurusan_id,
                        ];
                    }
                }
            }


            $asset_bookings = [];
            if ($request->has('loan_dates') && is_array($request->loan_dates)) {
                $filteredLoanDates = array_filter($request->loan_dates, function ($loanDate) {
                    return !is_null($loanDate) && !empty(trim($loanDate));
                });
                if (!empty($filteredLoanDates)) {
                    foreach ($filteredLoanDates as $index => $date) {

                        // Validasi apakah semua data yang dibutuhkan tersedia
                        if (
                            empty($request->loan_dates[$index]) ||
                            empty($request->loan_time_starts[$index]) ||
                            empty($request->loan_time_ends[$index]) ||
                            empty($request->loan_assets[$index])
                        ) {
                            continue; // Lewati jika ada data yang tidak valid
                        }

                        try {
                            $usageDateLoanStart = Carbon::createFromFormat('Y-m-d H:i', $request->loan_dates[$index] . ' ' . $request->loan_time_starts[$index]);
                            $usageDateLoanEnd = Carbon::createFromFormat('Y-m-d H:i', $request->loan_dates[$index] . ' ' . $request->loan_time_ends[$index]);

                            $asset = Asset::find($request->loan_assets[$index]);
                            if (!$asset) {
                                continue; // Lewati jika asset tidak ditemukan
                            }
                            // **Cek apakah ada booking yang bentrok dengan waktu yang dipilih**
                            $conflict = AssetBooking::where('asset_id', $request->loan_assets[$index])
                                ->where(function ($query) {
                                    $query->where('status', 'NOT LIKE', 'submission%')
                                        ->where('status', '!=', 'rejected_booking')
                                        ->where('status', '!=', 'rejected')
                                        ->where('status', '!=', 'cancelled');
                                })
                                ->where(function ($query) use ($usageDateLoanStart, $usageDateLoanEnd) {
                                    $query->where(function ($q) use ($usageDateLoanStart, $usageDateLoanEnd) {
                                        $q->where('usage_date_start', '<', $usageDateLoanEnd)
                                            ->where('usage_date_end', '>', $usageDateLoanStart);
                                    });
                                })
                                ->exists();


                            if ($conflict) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.'
                                ], 422);
                            }

                            $asset_loan_type = $asset->type;

                            $booking = AssetBooking::create([
                                'asset_id' => $request->loan_assets[$index],
                                'user_id' => Auth::user()->id,
                                'booking_number' => strtoupper($bookingNo),
                                'event_id' => $event->id,
                                'usage_date_start' => $usageDateLoanStart,
                                'usage_date_end' => $usageDateLoanEnd,
                                'usage_event_name' => $asset_loan_type === 'building' ? "Izin Dekorasi" : $event->title,
                                'status' => 'submission_booking',
                            ]);
                            $asset_bookings[]  = $booking;
                            if (optional($booking->asset)->facility_scope === 'umum') {
                                $umumAssets[] = $booking->asset->name;
                            }
                        } catch (\Exception $e) {
                            \Log::error("Error processing loan booking: " . $e->getMessage());
                            continue; // Lewati jika terjadi error
                        }
                    }
                }
            }

            if ($request->has('speaker_name') && is_array($request->speaker_name)) {
                // Filter untuk memeriksa agar isi konten tidak bernilai null
                $filteredSpeakers = array_filter($request->speaker_name, function ($speaker) {
                    return !is_null($speaker);
                });

                // diproses jika validasi berhasil
                if (!empty($filteredSpeakers)) {
                    foreach ($filteredSpeakers as $index => $speaker) {
                        $event_date = $request->event_date_speakers[$index];
                        $event_step_id = EventStep::where('event_date', $event_date)
                            ->where('event_id', $event->id)
                            ->value('id');

                        // Menentukan role
                        $role = $request->role[$index] === 'other' ? $request->other_role[$index] : $request->role[$index];

                        EventSpeaker::create([
                            'event_step_id' => $event_step_id,
                            'name' => $speaker,
                            'role' => $role,
                        ]);
                    }
                }
            }

            $userSender = Auth::user();
            // Kirim notifikasi untuk aset tipe jurusan
            if (!empty($jurusanAssets)) {
                // Kelompokkan aset berdasarkan jurusan_id
                $groupedJurusanAssets = collect($jurusanAssets)->groupBy('jurusan_id');

                foreach ($groupedJurusanAssets as $jurusan_id => $assets) {
                    $assetNames = collect($assets)->pluck('name')->join(', ');
                    $adminJurusanUsers = User::role('Admin Jurusan'); // Semua Tenant

                    $adminJurusanUsers->where('jurusan_id', $jurusan_id)
                        ->get();

                    $message = "Peminjaman aset berikut telah dilakukan oleh " . Auth::user()->name . ": {$assetNames}.";
                    foreach ($adminJurusanUsers as $user) {
                        Notification::send($adminJurusanUsers, new BookingAssetInternalCampus($userSender, $message));
                    }
                }
            }

            // Kirim notifikasi untuk aset tipe umum
            if (!empty($umumAssets)) {
                $assetNames = collect($umumAssets)->join(', ');
                $kaurUsers = User::role('Kaur RT')->get();

                $message = "Peminjaman aset berikut telah dilakukan oleh " . Auth::user()->name . ": {$assetNames}.";
                foreach ($kaurUsers as $user) {

                    Notification::send($kaurUsers, new BookingAssetInternalCampus($userSender, $message));
                }
            }
            DB::commit();
            notyf()->ripple(true)->success('Event berhasil ditambahkan!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->ripple(true)->error('Terjadi kesalahan saat menambahkan event.');
            return redirect()->back();
        }
    }
    public function updateEvent(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'theme' => 'required',
            'description' => 'required',
            'scope' => 'required',
            'quota' => 'required',
            'name_category_prices' => 'array',
            'prices' => 'array',
            'scopes' => 'array',
            'event_leader' => 'required',
            'is_publish' => 'required|in:0,1',
            'is_free' => 'required|in:0,1',
            // 'benefit' => 'array',
            // 'contact_person' => 'array',
            'date_registration_start' => 'required',
            'date_registration_end' => 'required',
            'event_dates' => 'required|array',
            'event_time_starts' => 'required|array',
            'event_time_ends' => 'required|array',
            'execution_systems' => 'required|array',
            'locations' => 'required|array',
            'event_date_speakers' => 'array',
            'speaker_name' => 'required|array',
            'role' => 'required|array',
            // 'questions' => 'required|array',
            // 'field_types' => 'required|array',
            // 'answers' => 'required|array',
            // 'is_required' => 'required|in:0,1',
            // 'pamphlet_path' => 'required',
            // 'banner_path' => 'required',
            // 'sponsored_by' => 'required',
            // 'media_partner' => 'required',

        ], [
            'title.required' => 'Judul acara wajib diisi.',
            'theme.required' => 'Tema acara wajib diisi.',
            'description.required' => 'Deskripsi acara wajib diisi.',
            'scope.required' => 'Lingkup acara wajib diisi.',
            'quota.required' => 'Kuota peserta wajib diisi.',
            'event_leader.required' => 'Nama ketua acara wajib diisi.',
            // 'name_category_prices.array' => 'Waktu mulai acara harus berupa array.',
            // 'prices.array' => 'Waktu mulai acara harus berupa array.',
            // 'scopes.array' => 'Waktu mulai acara harus berupa array.',
            'is_free.required' => 'Status biaya event wajib diisi.',
            'is_free.in' => 'Status biaya event  harus bernilai Free atau Paid.',
            'is_publish.required' => 'Status publikasi wajib diisi.',
            'is_publish.in' => 'Status publikasi harus bernilai Ya atau Tidak.',
            // 'benefit.array' => 'Benefit harus berupa array.',
            // 'contact_person.array' => 'Contact person harus berupa array.',

            'date_registration_start.required' => 'Tanggal mulai pendaftaran wajib diisi.',
            'date_registration_end.required' => 'Tanggal akhir pendaftaran wajib diisi.',
            'event_dates.required' => 'Tanggal acara wajib diisi.',
            // 'event_dates.array' => 'Tanggal acara harus berupa array.',
            'event_time_starts.required' => 'Waktu mulai acara wajib diisi.',
            // 'event_time_starts.array' => 'Waktu mulai acara harus berupa array.',
            'event_time_ends.required' => 'Waktu selesai acara wajib diisi.',
            // 'event_time_ends.array' => 'Waktu selesai acara harus berupa array.',
            'execution_systems.required' => 'Sistem pelaksanaan wajib diisi.',
            // 'execution_systems.array' => 'Sistem pelaksanaan harus berupa array.',
            'locations.required' => 'Lokasi acara wajib diisi.',
            // 'locations.array' => 'Lokasi acara harus berupa array.',

            // 'event_date_speakers.required' => 'Tanggal sesi pembicara wajib diisi.',
            // 'event_date_speakers.array' => 'Tanggal sesi pembicara harus berupa array.',
            'speaker_name.required' => 'Nama pembicara wajib diisi.',
            // 'speaker_name.array' => 'Nama pembicara harus berupa array.',
            'role.required' => 'Peran pembicara wajib diisi.',
            // 'role.array' => 'Peran pembicara harus berupa array.',
            // 'questions.required' => 'Pertanyaan wajib diisi.',
            // 'questions.array' => 'Pertanyaan harus berupa array.',
            // 'field_types.required' => 'Tipe field wajib diisi.',
            // 'field_types.array' => 'Tipe field harus berupa array.',
            // 'answers.required' => 'Jawaban wajib diisi.',
            // 'answers.array' => 'Jawaban harus berupa array.',
            // 'is_required.required' => 'Status required wajib diisi.',
            // 'is_required.in' => 'Status required harus bernilai Ya atau Tidak.',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Ambil error pertama dari validasi
            notyf()->ripple(true)->error($firstError);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $event = Event::findOrFail($id);
            // Ambil maksimal 3 kata dari title
            $part_event_name = implode(' ', array_slice(explode(' ', $request->title), 0, 3));
            // Ambil tahun saat ini
            $yearNow = Carbon::now()->year;

            // Nama organizer (gunakan shorten_name dari Auth)
            $organizerName = Auth::user()->organizer->shorten_name;

            // Handle pamflet upload
            $filePaths = [];
            if ($request->hasFile('pamphlet_path')) {
                $file = $request->file('pamphlet_path');
                $extension = $file->getClientOriginalExtension();

                // Buat nama file
                $fileName = 'Pamflet.' . $extension;


                // Hapus gambar lama jika ada
                if ($event->pamphlet_path && Storage::exists('public/' . $event->pamphlet_path)) {
                    Storage::delete('public/' . $event->pamphlet_path);
                }
                // Simpan file dengan struktur path yang sesuai
                $pamphletPath = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name,
                    $fileName,
                    'public'
                );

                // Simpan path file ke array
                $filePaths['pamphlet_path'] = $pamphletPath;
            }


            // Handle banner upload
            if ($request->hasFile('banner_path')) {
                $file = $request->file('banner_path');
                $extension = $file->getClientOriginalExtension();

                // Buat nama file
                $fileName = 'Banner.' . $extension;

                // Hapus gambar lama jika ada
                if ($event->banner_path && Storage::exists('public/' . $event->banner_path)) {
                    Storage::delete('public/' . $event->banner_path);
                }
                // Simpan file dengan struktur path yang sesuai
                $bannerPath = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name,
                    $fileName,
                    'public'
                );

                // Simpan path file ke array
                $filePaths['banner_path'] = $bannerPath;
            }

            // Handle sponsor uploads (multiple files)
            if ($request->hasFile('sponsored_by')) {
                $sponsoredPaths = [];

                // Hapus gambar lama jika ada (dan sponsored_by adalah array of paths)
                if (is_array($event->sponsored_by)) {
                    foreach ($event->sponsored_by as $oldSponsorPath) {
                        if (Storage::exists('public/' . $oldSponsorPath)) {
                            Storage::delete('public/' . $oldSponsorPath);
                        }
                    }
                }
                foreach ($request->file('sponsored_by') as $sponsoredFile) {
                    $extension = $sponsoredFile->getClientOriginalExtension();

                    // Buat nama file
                    $fileName = 'Sponsor_' . uniqid() . '.' . $extension;

                    // Simpan file dengan struktur path yang sesuai
                    $path = $sponsoredFile->storeAs(
                        'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/sponsors',
                        $fileName,
                        'public'
                    );

                    $sponsoredPaths[] = $path;
                }
                $filePaths['sponsored_by'] = $sponsoredPaths;
            }

            // Handle media partner uploads (multiple files)
            if ($request->hasFile('media_partner')) {
                $mediaPartnerPaths = [];

                // Hapus gambar lama jika ada (dan media_partner adalah array of paths)
                if (is_array($event->media_partner)) {
                    foreach ($event->media_partner as $oldMediaPartnerPath) {
                        if (Storage::exists('public/' . $oldMediaPartnerPath)) {
                            Storage::delete('public/' . $oldMediaPartnerPath);
                        }
                    }
                }

                foreach ($request->file('media_partner') as $mediaFile) {
                    $extension = $mediaFile->getClientOriginalExtension();

                    // Buat nama file
                    $fileName = 'MediaPartner_' . uniqid() . '.' . $extension;


                    // Simpan file dengan struktur path yang sesuai
                    $path = $mediaFile->storeAs(
                        'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/media_partners',
                        $fileName,
                        'public'
                    );

                    $mediaPartnerPaths[] = $path;
                }
                $filePaths['media_partner'] = $mediaPartnerPaths;
            }


            $event->update(
                [
                    'organizer_id' => Auth::user()->organizer->id,
                    'title' => $request->title,
                    'theme' => $request->theme,
                    'description' => $request->description,
                    'scope' => $request->scope,
                    'event_category' => $request->event_category,
                    'quota' => $request->quota,
                    'event_leader' => $request->event_leader,
                    'is_free' => $request->is_free,
                    'is_publish' => $request->is_publish,
                    'status' => $request->is_publish ? 'published' : 'planned',
                    'benefit' => $request->benefit,
                    'contact_person' => $request->contact_person,
                    'registration_date_start' => $request->date_registration_start,
                    'registration_date_end' => $request->date_registration_end,
                    'pamphlet_path' => $filePaths['pamphlet_path'] ?? $event->pamphlet_path,
                    'banner_path' => $filePaths['banner_path'] ?? $event->banner_path,
                    'sponsored_by' => json_encode($filePaths['sponsored_by'] ?? $event->sponsored_by),
                    'media_partner' => json_encode($filePaths['media_partner'] ?? $event->media_partner),
                ]
            );
            if ($request->has('prices') && is_array($request->prices)) {
                // Filter untuk memeriksa agar isi konten tidak bernilai null

                $filteredPrices = array_filter($request->prices, function ($price) {
                    return !is_null($price);
                });

                // diproses jika validasi berhasil
                if (!empty($filteredPrices)) {

                    // Ambil semua ID price yang dikirim dari form
                    $submittedPriceIds = collect($request->price_ids)->filter()->toArray(); // filter untuk buang null

                    // Ambil semua ID price lama dari database
                    $existingPriceIds = $event->prices()->pluck('id')->toArray();

                    // Cari price yang sudah dihapus oleh user (tidak dikirim di form)
                    $pricesToDelete = array_diff($existingPriceIds, $submittedPriceIds);

                    // Hapus price yang tidak ada di form
                    EventPrice::destroy($pricesToDelete);

                    foreach ($filteredPrices as $index => $price) {
                        $priceId = $request->price_ids[$index] ?? null;
                        if ($priceId) {
                            $eventPrice = EventPrice::findOrFail($priceId);
                            $eventPrice->update([
                                'event_id' => $event->id,
                                'category_name' => $request->name_category_prices[$index] ?? null,
                                'scope' => $request->scopes[$index] ?? null,
                                'price' => $price,
                            ]);
                        } else {
                            EventPrice::create([
                                'event_id' => $id,
                                'category_name' => $request->name_category_prices[$index],
                                'scope' => $request->scopes[$index],
                                'price' => $request->prices[$index],
                            ]);
                        }
                    }
                }
            }
            $jurusanAssets = [];
            $umumAssets = [];

            $locationsOfflineIndex = 0;
            $locationsOnlineIndex = 0;
            $locationIndex = 0;
            $addressIndex = 0;

            // Ambil semua ID step yang dikirim dari form
            $submittedStepIds = collect($request->step_ids)->filter()->toArray(); // filter untuk buang null

            // Ambil semua ID step lama dari database
            $existingStepIds = $event->steps()->pluck('id')->toArray();

            // Cari step yang sudah dihapus oleh user (tidak dikirim di form)
            $stepsToDelete = array_diff($existingStepIds, $submittedStepIds);

            // Hapus step yang tidak ada di form
            EventStep::destroy($stepsToDelete);

            foreach ($request->event_dates as $index => $event_date) {
                $stepId = $request->step_ids[$index] ?? null;

                $executionSystem = $request->execution_systems[$index];
                $locations = [];

                // ** OFFLINE **
                if ($executionSystem === 'offline') {
                    $location = $request->locations[$locationIndex] ?? null;
                    $locationData = [
                        'type' => 'offline',
                        'location' => $location,
                    ];

                    // Jika location_type manual, tambahkan address
                    if ($request->location_type[$index] === 'manual') {
                        $addressLocation = $request->address_locations[$addressIndex] ?? null;
                        if (!is_null($addressLocation)) {
                            $locationData['address'] = $addressLocation;
                        }
                        $addressIndex++; // Hanya tambah jika offline manual
                    }

                    $locations[] = $locationData;

                    if (!is_null($location)) {
                        $locationIndex++;
                    }
                }

                // ** ONLINE **
                elseif ($executionSystem === 'online') {
                    $location = $request->locations[$locationIndex] ?? null;

                    $locations[] = [
                        'type' => 'online',
                        'location' => $location,
                    ];

                    if (!is_null($location)) {
                        $locationIndex++;
                    }
                }

                // ** HYBRID **
                elseif ($executionSystem === 'hybrid') {
                    $locationOffline = $request->locations_offline[$locationsOfflineIndex] ?? null;
                    $addressLocationOffline = $request->address_locations_offline[$locationsOfflineIndex] ?? null;
                    $locationOnline = $request->locations_online[$locationsOnlineIndex] ?? null;

                    $locationData = [
                        'type' => 'hybrid',
                        'location_offline' => $locationOffline,
                        'location_online' => $locationOnline,
                    ];

                    if ($request->location_type[$index] === 'manual' && !is_null($addressLocationOffline)) {
                        $locationData['address_location_offline'] = $addressLocationOffline;
                    }

                    $locations[] = $locationData;

                    if (!is_null($locationOffline)) {
                        $locationsOfflineIndex++;
                    }
                    if (!is_null($locationOnline)) {
                        $locationsOnlineIndex++;
                    }
                }


                if ($stepId) {
                    $eventStep = EventStep::findOrFail($stepId);
                    $eventStep->update([
                        'step_name' => count($request->event_dates) > 1 ? $request->step_names[$index] : null,
                        'event_date' => $event_date,
                        'event_time_start' => $request->event_time_starts[$index],
                        'event_time_end' => $request->event_time_ends[$index],
                        'description' => $request->event_step_descriptions[$index] ?? null,
                        'execution_system' => $executionSystem,
                        'location_type' => $request->location_type[$index],
                        'location' => json_encode($locations), // Simpan dalam format JSON
                    ]);
                } else {
                    EventStep::create([
                        'event_id' => $event->id,
                        'step_name' => count($request->event_dates) > 1 ? $request->step_names[$index] : null,
                        'event_date' => $event_date,
                        'event_time_start' => $request->event_time_starts[$index],
                        'event_time_end' => $request->event_time_ends[$index],
                        'description' => $request->event_step_descriptions[$index] ?? null,
                        'execution_system' => $executionSystem,
                        'location_type' => $request->location_type[$index],
                        'location' => json_encode($locations), // Simpan dalam format JSON
                    ]);

                    if ($request->location_type[$index] === 'campus' && $executionSystem !== 'online') {

                        if ($executionSystem === 'offline') {
                            $location_id = $request->locations[$locationIndex - 1] ?? null; // Ambil lokasi terakhir
                        } elseif ($executionSystem === 'hybrid') {
                            $location_id = $request->locations_offline[$locationsOfflineIndex - 1] ?? null;
                        }

                        // **Pastikan location_id adalah UUID yang ada di tabel assets**
                        $locationExists = Asset::where('id', $location_id)->exists();
                        if (!$locationExists) {
                            continue; // Lewati jika tidak valid
                        }
                        $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->event_dates[$index] . ' ' . $request->event_time_starts[$index]);
                        $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->event_dates[$index] . ' ' . $request->event_time_ends[$index]);

                        if ($location_id) {
                            $booking = AssetBooking::create([
                                'asset_id' => $location_id,
                                'user_id' => Auth::user()->id,
                                'booking_number' => '',
                                'event_id' => $event->id,
                                'usage_date_start' => $usageDateStart,
                                'usage_date_end' => $usageDateEnd,
                                'usage_event_name' => $event->title,
                                'status' => 'submission_booking',
                            ]);
                            $asset_bookings[] = $booking;
                        }


                        if (optional($booking->asset)->facility_scope === 'umum') {
                            $umumAssets[] = $booking->asset->name;
                        } elseif ($booking->asset->facility_scope === 'jurusan') {
                            $jurusanAssets[] = [
                                'name' => $booking->asset->name,
                                'jurusan_id' => $booking->asset->jurusan_id,
                            ];
                        }
                    }
                }
            }


            if ($request->has('loan_dates') && is_array($request->loan_dates)) {
                $filteredLoanDates = array_filter($request->loan_dates, function ($loanDate) {
                    return !is_null($loanDate) && !empty(trim($loanDate));
                });

                if (!empty($filteredLoanDates)) {
                    foreach ($filteredLoanDates as $index => $date) {
                        // Validasi apakah semua data yang dibutuhkan tersedia
                        if (
                            empty($request->loan_dates[$index]) ||
                            empty($request->loan_time_starts[$index]) ||
                            empty($request->loan_time_ends[$index]) ||
                            empty($request->loan_assets[$index])
                        ) {
                            continue; // Lewati jika ada data yang tidak valid
                        }

                        try {
                            $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->loan_dates[$index] . ' ' . $request->loan_time_starts[$index]);
                            $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->loan_dates[$index] . ' ' . $request->loan_time_ends[$index]);

                            $asset = Asset::find($request->loan_assets[$index]);
                            if (!$asset) {
                                continue; // Lewati jika asset tidak ditemukan
                            }

                            // Mengambil booking number agar sama dengan data yang memiliki event_id yang sama
                            $bookingNo = AssetBooking::where('event_id', $id)->value('booking_number');

                            $asset_loan_name = $asset->name;
                            $asset_loan_type = $asset->type;

                            $booking = AssetBooking::create([
                                'asset_id' => $request->loan_assets[$index],
                                'user_id' => Auth::user()->id,
                                'booking_number' => $bookingNo,
                                'event_id' => $event->id,
                                'usage_date_start' => $usageDateStart,
                                'usage_date_end' => $usageDateEnd,
                                'usage_event_name' => $asset_loan_type === 'building' ? "Izin Dekorasi" : $event->title,
                                'status' => 'submission_booking',
                            ]);

                            if (optional($booking->asset)->facility_scope === 'umum') {
                                $umumAssets[] = $booking->asset->name;
                            }
                        } catch (\Exception $e) {
                            \Log::error("Error processing loan booking: " . $e->getMessage());
                            continue; // Lewati jika terjadi error
                        }
                    }
                }
            }

            if ($request->has('speaker_name') && is_array($request->speaker_name)) {
                // Filter untuk memeriksa agar isi konten tidak bernilai null
                $filteredSpeakers = array_filter($request->speaker_name, function ($speaker) {
                    return !is_null($speaker);
                });

                // diproses jika validasi berhasil
                if (!empty($filteredSpeakers)) {
                    // Ambil semua event_step_id yang dimiliki oleh event ini
                    $stepIds = EventStep::where('event_id', $event->id)->pluck('id')->toArray();

                    // Ambil semua speaker lama berdasarkan step yang terkait dengan event
                    $existingSpeakerIds = EventSpeaker::whereIn('event_step_id', $stepIds)->pluck('id')->toArray();

                    // Ambil ID speaker dari form
                    $submittedSpeakerIds = collect($request->speaker_ids)->filter()->toArray();

                    // Deteksi speaker yang dihapus user
                    $speakersToDelete = array_diff($existingSpeakerIds, $submittedSpeakerIds);

                    // Hapus speaker yang tidak ada di form
                    EventSpeaker::destroy($speakersToDelete);


                    foreach ($filteredSpeakers as $index => $speaker) {
                        $speakerId = $request->speaker_ids[$index] ?? null;

                        $event_date = $request->event_date_speakers[$index];
                        $event_step_id = EventStep::where('event_date', $event_date)
                            ->where('event_id', $event->id)
                            ->value('id');

                        // Menentukan role
                        $role = $request->role[$index] === 'other' ? $request->other_role[$index] : $request->role[$index];
                        if ($speakerId) {
                            $eventSpeaker = EventSpeaker::findOrFail($speakerId);
                            $eventSpeaker->update([
                                'name' => $speaker,
                                'role' => $role,
                            ]);
                        } else {
                            EventSpeaker::create([
                                'event_step_id' => $event_step_id,
                                'name' => $speaker,
                                'role' => $role,
                            ]);
                        }
                    }
                }
            }
            $userSender = Auth::user();

            // Kirim notifikasi untuk aset tipe jurusan
            if (!empty($jurusanAssets)) {
                // Kelompokkan aset berdasarkan jurusan_id
                $groupedJurusanAssets = collect($jurusanAssets)->groupBy('jurusan_id');

                foreach ($groupedJurusanAssets as $jurusan_id => $assets) {
                    $assetNames = collect($assets)->pluck('name')->join(', ');
                    $adminJurusanUsers = User::role('Admin Jurusan'); // Semua Tenant

                    $adminJurusanUsers->where('jurusan_id', $jurusan_id)
                        ->get();

                    $message = "Peminjaman aset berikut telah dilakukan oleh " . Auth::user()->name . ": {$assetNames}.";
                    foreach ($adminJurusanUsers as $user) {
                        Notification::send($adminJurusanUsers, new BookingAssetInternalCampus($userSender, $message));
                    }
                }
            }
            // Kirim notifikasi untuk aset tipe umum
            if (!empty($umumAssets)) {
                $assetNames = collect($umumAssets)->join(', ');
                $kaurUsers = User::role('Kaur RT')->get();

                $message = "Peminjaman aset berikut telah dilakukan oleh " . Auth::user()->name . ": {$assetNames}.";
                foreach ($kaurUsers as $user) {

                    Notification::send($kaurUsers, new BookingAssetInternalCampus($userSender, $message));
                }
            }
            DB::commit();

            notyf()->ripple(true)->success('Event berhasil diperbarui!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->ripple(true)->error('Terjadi kesalahan saat memperbarui event.');
            return redirect()->back();
        }
    }

    public function destroyEvent($id) {}
}