<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Event;
use App\Models\Jurusan;
use App\Models\Organizer;
use App\Models\TeamMember;
use App\Models\Stakeholder;
use Illuminate\Support\Str;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetBookingDocument;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Exports\InternalAssetBookingsExport;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingAssetConfirmDone;
use App\Notifications\BookingAssetEventCancelled;
use App\Notifications\BookingAssetEventConfirmed;
use App\Notifications\BookingAssetEventUploadDoc;
use App\Notifications\BookingAssetInternalConfirmDocument;

class AssetBookingEventController extends Controller
{
    public function indexAssetBookingEvent()
    {
        $jurusans = Jurusan::all();
        $assets = Asset::where('facility_scope', 'umum')->pluck('name', 'id');
        $organizers = Organizer::with('user')->get()->pluck('user.name', 'id');
        return view('dashboardPage.assets.asset-booking-event.index', ['kode_jurusan' => null, 'jurusans' => $jurusans, 'assets' => $assets, 'organizers' => $organizers]);
    }

    // Untuk Kaur RT dan Admin Jurusan (Peminjaman dari dalam atau untuk event)
    public function getDataAssetBookingEvent(Request $request, $kode_jurusan = null)
    {
        $statusBooking = $request->status_booking;
        $jurusan = Jurusan::where('kode_jurusan', $kode_jurusan)->first();

        // Tentukan facility_scope berdasarkan keberadaan kode_jurusan
        $scope = $kode_jurusan ? 'jurusan' : 'umum';

        // Inisialisasi query utama
        $query = AssetBooking::with(['event', 'asset', 'user.organizer', 'asset_category'])
            ->whereHas('asset', function ($q) use ($scope, $jurusan) {
                $q->where('facility_scope', $scope);
                if ($scope === 'jurusan' && $jurusan) {
                    $q->where('jurusan_id', $jurusan->id);
                }
            })->where('asset_category_id', NULL);

        $statusCategories = [
            'submission_booking' => ['submission_booking'],
            'booked' => ['booked'],
            'submission_full_payment' => ['submission_full_payment'],
            'approved' => ['approved'],
            'rejected' => ['rejected_booking',  'rejected'],
            'cancelled' => ['cancelled'],
        ];

        // Pastikan kategori yang diminta ada di daftar yang telah ditentukan
        if (!array_key_exists($statusBooking, $statusCategories)) {
            return response()->json(['error' => 'Kategori tidak valid'], 400);
        }

        // Filter berdasarkan status_booking (kecuali 'all')
        if ($statusBooking !== 'all') {
            $query->whereIn('status', $statusCategories[$statusBooking]);

            // if ($statusBooking === 'done') {
            //     $query->whereIn('status', $statusCategories[$statusBooking])
            //         ->whereDate('usage_date_end', '<', now()); // Pastikan `usage_date_end` sudah lewat
            // }
        }
        if ($statusBooking === 'submission_booking') {
            $query->where('event_id', '!=', null);
        }

        // Eksekusi query
        $allAssetBookings = $query->get();

        // Group data yang memiliki event_id
        $groupedByEvent = $allAssetBookings
            ->filter(fn($item) => $item->event_id !== null)
            ->groupBy('event_id')
            ->map(function ($items, $event_id) {
                $first = $items->first();
                return [
                    'type' => 'grouped',
                    'event_id' => $event_id,
                    'usage_event_name' => $first->usage_event_name ?? 'Tidak diketahui',
                    'user' => optional(optional($first->user)->organizer)->shorten_name ?? '-',
                    'asset_bookings' => $items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'asset_name' => $item->asset->name ?? '-',
                            'usage_date_start' => $item->usage_date_start,
                            'usage_date_end' => $item->usage_date_end,
                        ];
                    }),
                    'status' => $first->status,
                    'event_name' => optional($first->event)->title ?? '-',
                    'usage_date_start' => $first->usage_date_start,
                ];
            });

        // Ambil data yang event_id-nya null (tidak dikelompokkan)
        $noEventIdData = $allAssetBookings
            ->filter(fn($item) => $item->event_id === null)
            ->map(function ($item) {

                return [
                    'type' => 'single',
                    'id' => $item->id,
                    'event_id' => null,
                    'usage_event_name' => $item->usage_event_name ?? 'Tidak diketahui',
                    'user' => optional($item->user)->name ?? '-',
                    'asset_name' => optional($item->asset)->name ?? '-',
                    'usage_date_start' => $item->usage_date_start,
                    'usage_date_end' => $item->usage_date_end,
                    'status' => $item->status,
                    'event_name' => $item->usage_event_name,
                ];
            });


        // Gabungkan dan urutkan berdasarkan tanggal
        $groupedData = collect($groupedByEvent->values())
            ->concat($noEventIdData->values()) // concat tidak mencoba menyamakan key
            ->sortBy('usage_date_start')
            ->values();

        $tableId = $kode_jurusan
            ? "assetBookingFasilitasJurusan{$kode_jurusan}-Table-{$statusBooking}"
            : "assetBookingFasilitasUmumTable-{$statusBooking}";

        return DataTables::of($groupedData)
            ->addIndexColumn()
            ->addColumn('shorten_name', function ($assetBooking) {
                return $assetBooking['user'] ?? '-';
            })
            ->addColumn('description', function ($assetBooking) {

                if ($assetBooking['type'] === 'single') {
                    $startDate = Carbon::parse($assetBooking['usage_date_start']);
                    $endDate = Carbon::parse($assetBooking['usage_date_end']);

                    $date = $startDate->translatedFormat('d F Y');
                    $timeRange = $startDate->format('H.i') . ' - ' . $endDate->format('H.i');
                    $assetName = $assetBooking['asset_name'] ?? '-';
                    $usageEventName = $assetBooking['usage_event_name'] ?? '-';

                    return "<div><h6 class='mb-1 fw-bold'>{$usageEventName}</h6><ul style='padding-left: 20px;'>
                    <div><strong>{$assetName}</strong><br>{$date} ({$timeRange})</div>";
                } else {

                    $groupedAssets = [];

                    // Gabungkan berdasarkan asset_name dan tanggal (Y-m-d)
                    foreach ($assetBooking['asset_bookings'] as $booking) {
                        $assetName = $booking['asset_name'] ?? '-';
                        $startDate = Carbon::parse($booking['usage_date_start']);
                        $endDate = Carbon::parse($booking['usage_date_end']);

                        $dateKey = $startDate->format('Y-m-d'); // Key grouping berdasarkan tanggal
                        $timeRange = $startDate->format('H.i') . ' - ' . $endDate->format('H.i');

                        $groupedAssets[$assetName][$dateKey][] = [
                            'date' => $startDate->format('d F Y'),
                            'time_range' => $timeRange,
                            'start_timestamp' => $startDate->timestamp // untuk sort
                        ];
                    }

                    // Mulai generate HTML
                    $html = "<div><h6 class='mb-1 fw-bold'>{$assetBooking['usage_event_name']}</h6><ul style='padding-left: 20px;'>";

                    foreach ($groupedAssets as $assetName => $dates) {
                        // Untuk setiap aset
                        $html .= "<li><strong>{$assetName}</strong><ul style='padding-left: 20px;'>";

                        // Urutkan berdasarkan timestamp terlama
                        uasort($dates, function ($a, $b) {
                            return $a[0]['start_timestamp'] <=> $b[0]['start_timestamp'];
                        });

                        foreach ($dates as $dateKey => $sessions) {
                            // Ambil semua range waktu
                            $allTimes = array_column($sessions, 'time_range');
                            $uniqueTimes = array_unique($allTimes);

                            $timeDisplay = count($uniqueTimes) > 1 ? implode(', ', $uniqueTimes) : "({$uniqueTimes[0]})";
                            $html .= "<li>{$sessions[0]['date']} {$timeDisplay}</li>";
                        }

                        $html .= "</ul></li>";
                    }

                    $html .= "</ul></div>";
                    return $html;
                }
            })



            ->addColumn('action', function ($assetBooking) use ($tableId, $jurusan) {
                $eventId = $assetBooking['event_id'] ?? '-';

                $assetJurusanBookingsId = AssetBooking::with('asset')->where('event_id', $eventId)
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('asset', function ($a) {
                        $a->where('facility_scope', 'jurusan')->where('jurusan_id',  auth()->user()->jurusan_id);
                    })->pluck('id')->first();
                // Ambil semua asset_id yang termasuk fasilitas jurusan
                $assetJurusanIds = AssetBooking::with('asset')
                    ->where('event_id', $eventId)
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('asset', function ($a) {
                        $a->where('facility_scope', 'jurusan')->where('jurusan_id',  auth()->user()->jurusan_id);
                    })
                    ->pluck('asset_id');

                // Ambil semua booking untuk asset_id yang termasuk dalam daftar jurusan
                $listAssetBookings = collect();
                if ($jurusan) {
                    $listAssetBookings = AssetBooking::where('event_id', $eventId)
                        ->whereIn('asset_id', $assetJurusanIds)
                        ->where('status', '!=', 'cancelled')
                        ->get();
                    $documentPath = AssetBookingDocument::where('booking_id', $assetJurusanBookingsId)->value('document_path');
                } else {
                    $listAssetBookings = AssetBooking::where('event_id', $eventId)
                        ->whereNotIn('asset_id', $assetJurusanIds)
                        ->where('status', '!=', 'cancelled')
                        ->get();
                    $documentPath = AssetBookingDocument::where('event_id', $eventId)->value('document_path');
                }
                $assetBookingIdNoEvent = '';
                if ($assetBooking['type'] === 'single') {
                    $assetBookingIdNoEvent = $assetBooking['id'];
                }

                // Render modal konfirmasi booking dan pembayaran
                $confirmBookingModal = view('dashboardPage.assets.asset-booking-event.modal.confirmBooking-asset', compact('assetBooking', 'listAssetBookings', 'eventId', 'tableId'))->render();
                $confirmApproved = view('dashboardPage.assets.asset-booking-event.modal.confirm-approved', compact('assetBooking', 'documentPath', 'eventId', 'tableId'))->render();
                $scriptCancelledBooking = view('dashboardPage.assets.asset-booking-event.modal.cancelledBookingAssetEvent', compact('assetBooking', 'listAssetBookings', 'eventId', 'tableId'))->render();
                $confirmDone = view('dashboardPage.assets.asset-booking-event.modal.confirm-done', compact('assetBookingIdNoEvent', 'eventId', 'tableId'))->render();
                $uploadSuratDisposisi = view('dashboardPage/assets.modal.uploadSuratDisposisi', compact('eventId', 'tableId'))->render();

                // Tombol aksi berdasarkan status
                $buttons = '<div class="d-flex align-items-center gap-2">';
                $modals = ''; // Untuk menampung modal agar ditambahkan di luar kondisi


                if ($assetBooking['status'] !== 'cancelled' && $assetBooking['status'] !== 'submission_booking') {
                    // Tombol Batalkan (Selalu Tampil)
                    $buttons .= "<a class='btn btn-sm btn-outline-danger cursor-pointer' 
                            data-bs-toggle='modal' 
                            data-bs-target='#modalCancelBooking-{$eventId}'>
                            Batalkan
                        </a>";
                    // Tambahkan modal ke dalam tombol aksi
                    $buttons .= $scriptCancelledBooking;
                }
                switch ($assetBooking['status']) {
                    case 'submission_booking':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                            data-bs-toggle='modal' data-bs-target='#modalConfirmAssetBooking-{$eventId}'>
                                            ✅ Konfirmasi Booking
                                        </a>";
                        $modals .= $confirmBookingModal;
                        break;

                    case 'booked':
                        $id = $assetBookingIdNoEvent !== "" ? $assetBookingIdNoEvent : $eventId;
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                data-bs-toggle='modal' data-bs-target='#confirmAssetBookingDone-{$id}'>
                                ✅ Konfirmasi Selesai
                            </a>
                            <span class='badge bg-secondary'>⏳ Menunggu Surat Peminjaman Selesai</span>";
                        $modals .= $confirmDone;
                        break;

                    case 'rejected_booking':
                        $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Booking Ulang</span>";
                        break;

                    case 'submission_full_payment':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                                    data-bs-toggle='modal' data-bs-target='#modalConfirmApproved-{$eventId}'>
                                                    ✅ Konfirmasi Surat
                                                </a>";
                        $modals .= $confirmApproved;
                        break;
                    case 'approved':
                        // $buttons .= "<span class='badge bg-success'>✔ Disetujui</span>";
                        // Tombol Batalkan (Selalu Tampil)
                        $buttons .= "<a class='btn btn-sm btn-outline-primary cursor-pointer' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#modalUploadSuratDisposisi-{$eventId}'>
                                        Upload Surat Disposisi
                                    </a>";
                        // Tambahkan modal ke dalam tombol aksi
                        $buttons .= $uploadSuratDisposisi;
                        break;
                    case 'rejected':
                        $buttons .= "<span class='badge bg-danger'>❌ Ditolak</span>";
                        break;

                    case 'cancelled':
                        $buttons .= "<span class='badge bg-warning'>⚠ Dibatalkan</span>";
                        break;
                }

                $buttons .= '</div>' . $modals;

                return $buttons;
            })
            ->rawColumns(['description', 'action'])
            ->make(true);
    }
    public function getDataAssetBookingEventsOrg($id)
    {
        $assetBookings = AssetBooking::where('event_id', $id)->get();
        $tableId = 'loanAssetEventTable';
        return DataTables::of($assetBookings)
            ->addIndexColumn()
            ->editColumn('datetime', function ($booking) {
                return
                    Carbon::parse($booking->usage_date_start)->translatedFormat('d F Y') . '<br>' .
                    Carbon::parse($booking->usage_date_start)->translatedFormat('H.i') . '-' .
                    Carbon::parse($booking->usage_date_end)->translatedFormat('H.i');
            })
            ->editColumn('location', function ($booking) {
                return $booking->asset->name;
            })
            ->editColumn('status', function ($booking) {
                $cancelledReason = $booking->reason ?? null;
                switch ($booking->status) {
                    case 'submission_booking':
                        $statusText = '⏳ Proses Pengajuan Booking';
                        $badgeClass = 'bg-secondary';
                        break;
                    case 'booked':
                        $statusText = '✅ Booking Disetujui';
                        $badgeClass = 'bg-primary';
                        break;
                    case 'submission_full_payment':
                        $statusText = '⏳ Pengajuan Surat Peminjaman';
                        $badgeClass = 'bg-secondary';
                        break;
                    case 'approved':
                        $statusText = '📦 Peminjaman Disetujui';
                        $badgeClass = 'bg-success';
                        break;
                    case 'rejected':
                        $statusText = '✖️ Peminjaman Ditolak';
                        $badgeClass = 'bg-danger';
                        break;
                    case 'rejected_full_payment':
                        $statusText = '✖️ Surat Peminjaman Ditolak';
                        $badgeClass = 'bg-danger';
                        break;
                    case 'cancelled':
                        $statusText = '❌ Peminjaman Dibatalkan';
                        $badgeClass = 'bg-dark';
                        break;
                }
                return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>' . ($cancelledReason ? '<br>' . $cancelledReason : '');
            })
            ->addColumn('action', function ($booking) use ($id, $tableId) {
                $assetBooking = $booking;
                $updateBookingModal = view('dashboardPage.events.modal.updateBookingAssetEvent', compact('booking'))->render();
                $cancelledBookingModal = view('dashboardPage.assets.asset-booking.modal.cancelled-booking', compact('assetBooking', 'tableId'))->render();

                // Tombol aksi berdasarkan status
                $buttons = '<div class="d-flex align-items-center gap-2">';
                $modals = '';
                // Tombol Perbarui (jika status bukan approved atau cancelled)
                if ($booking->status === 'submission_booking' || $booking->status === 'rejected') {
                    $buttons .= '
                        <a class="btn btn-sm btn-outline-success cursor-pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#modalUpdateBooking-' . $booking->id . '">
                            Perbarui
                        </a>';
                    $modals .=  $updateBookingModal;
                }
                // Tombol Batalkan (jika status bukan cancelled)
                if ($booking->status !== 'cancelled') {
                    $buttons .= '
                        <a class="btn btn-sm btn-outline-danger cursor-pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCancelBooking-' . $booking->id . '">
                            Batalkan
                        </a>';
                    $modals .=  $cancelledBookingModal;
                }
                // if ($booking->status === 'approved') {
                //     $buttons .= '
                //     <a href=""
                //     class="btn btn-dark text-sm btn-sm">
                //     Download Surat Disposisi
                // </a>';
                // }
                $buttons .= '</div>' . $modals;

                return $buttons;
            })
            ->rawColumns(['datetime', 'quota', 'status', 'action'])
            ->make(true);
    }

    public function updateAssetBooking(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:asset_bookings,asset_id',
            'event_date' => 'required',
            'event_time_start' => 'required',
            'event_time_end' => 'required',

        ], [
            'asset_id.required' => 'Tanggal Peminjaman wajib diisi.',
            'asset_id.exist' => 'Aset tidak ditemukan.',
            'event_date.required' => 'Tanggal Peminjaman wajib diisi.',
            'event_time_start.required' => 'Jam Mulai Peminjaman wajib diisi.',
            'event_time_end.required' => 'Jam Selesai Peminjaman wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $loanDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->event_date . ' ' . $request->event_time_start);
            $loanDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->event_date . ' ' . $request->event_time_end);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format tanggal/waktu tidak valid.'], 400);
        }

        // **Cek apakah ada booking yang bentrok dengan waktu yang dipilih**
        $conflict = AssetBooking::where('asset_id', $request->asset_id)
            ->where('id', '!=', $id)
            ->where(function ($query) {
                $query->where('status', 'NOT LIKE', 'submission%')
                    ->where('status', '!=', 'rejected_booking')
                    ->where('status', '!=', 'rejected')
                    ->where('status', '!=', 'cancelled');
            })
            ->where(function ($query) use ($loanDateStart, $loanDateEnd) {
                $query->where(function ($q) use ($loanDateStart, $loanDateEnd) {
                    $q->where('usage_date_start', '<', $loanDateEnd)
                        ->where('usage_date_end', '>', $loanDateStart);
                });
            })
            ->exists();


        if ($conflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.'
            ], 422);
        }
        DB::beginTransaction();
        try {
            // Gabungkan dengan waktu

            $assetBooking = AssetBooking::findOrFail($id);
            $assetBooking->update([
                'usage_date_start' => $loanDateStart,
                'usage_date_end' => $loanDateEnd,
            ]);
            if ($assetBooking->status === 'rejected') {
                $assetBooking->update([
                    'status' => 'submission_booking',
                    'reason' => null,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman Aset berhasil diperbarui!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui peminjaman aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function confirmAssetBookingEvent(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }

        $data = $request->input('bookings', []);

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data booking yang dikirim.'
            ], 422);
        }

        // 🔍 Validasi data masing-masing booking
        foreach ($data as $index => $booking) {
            $rules = [
                "bookings.$index.status" => 'required|in:approved,rejected',
            ];

            if (isset($booking['status']) && $booking['status'] === 'rejected') {
                $rules["bookings.$index.reason_rejected"] = 'required|string';
            }

            $validator = Validator::make($request->all(), $rules, [
                "bookings.$index.status.required" => "Status booking ke-" . ($index + 1) . " harus diisi.",
                "bookings.$index.status.in" => "Status booking ke-" . ($index + 1) . " tidak valid.",
                "bookings.$index.reason_rejected.required" => "Alasan penolakan booking ke-" . ($index + 1) . " wajib diisi.",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            $firstBooking = AssetBooking::findOrFail($data[0]['id']);
            $user = $firstBooking->user;
            $event_id = $firstBooking->event_id ?? [];
            $dataForNotification = [];

            foreach ($data as $booking) {
                $bookingModel = AssetBooking::find($booking['id']);

                if ($bookingModel) {
                    if ($booking['status'] === 'rejected') {
                        $bookingModel->status = 'rejected';
                        $bookingModel->reason = $booking['reason_rejected'] ?? null;
                    } else {
                        $bookingModel->status = 'booked';
                        $bookingModel->reason = null;
                    }
                    $bookingModel->save();

                    // 🔁 Tambahkan ke data notifikasi
                    $dataForNotification[] = [
                        'id' => $bookingModel->id,
                        'status' => $bookingModel->status,
                        'asset_name' => $bookingModel->asset->name ?? '-',
                        'usage_date' => Carbon::parse($bookingModel->usage_date_start)->translatedFormat('d F Y (H:i)')
                            . ' - ' .
                            Carbon::parse($bookingModel->usage_date_end)->translatedFormat('H:i'),
                        'reason_rejected' => $bookingModel->reason,
                        'event_name' => $bookingModel->event->title ?? '-',
                    ];
                }
            }

            Notification::send($user, new BookingAssetEventConfirmed($dataForNotification, $user, $event_id));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Booking aset berhasil dikonfirmasi!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengonfirmasi booking aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function cancelAssetBookingEvent(Request $request, $eventId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }

        $data = $request->input('bookings', []);

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data booking yang dikirim.'
            ], 422);
        }

        // 🔍 Validasi data masing-masing booking
        foreach ($data as $index => $booking) {
            $rules = [
                "bookings.$index.status" => 'required|in:approved,rejected',
            ];

            if (isset($booking['status']) && $booking['status'] === 'rejected') {
                $rules["bookings.$index.reason_cancelled"] = 'required|string';
            }

            $validator = Validator::make($request->all(), $rules, [
                "bookings.$index.status.required" => "Status booking ke-" . ($index + 1) . " harus diisi.",
                "bookings.$index.status.in" => "Status booking ke-" . ($index + 1) . " tidak valid.",
                "bookings.$index.reason_cancelled.required" => "Alasan pembatalan booking ke-" . ($index + 1) . " wajib diisi.",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            $firstBooking = AssetBooking::findOrFail($data[0]['id']);
            $user = $firstBooking->user;
            $event_id = $firstBooking->event_id;
            $dataForNotification = [];

            foreach ($data as $booking) {
                $bookingModel = AssetBooking::find($booking['id']);

                if ($bookingModel) {
                    if ($booking['status'] === 'rejected') {
                        $bookingModel->status = 'cancelled';
                        $bookingModel->reason = $booking['reason_cancelled'] ?? null;
                    }
                    $bookingModel->save();

                    // 🔁 Tambahkan ke data notifikasi
                    $dataForNotification[] = [
                        'id' => $bookingModel->id,
                        'status' => $bookingModel->status,
                        'asset_name' => $bookingModel->asset->name ?? '-',
                        'usage_date' => Carbon::parse($bookingModel->usage_date_start)->translatedFormat('d F Y (H:i)')
                            . ' - ' .
                            Carbon::parse($bookingModel->usage_date_end)->translatedFormat('H:i'),
                        'reason_cancelled' => $bookingModel->reason,
                        'event_name' => $bookingModel->event->title ?? '-',
                    ];
                }
            }

            Notification::send($user, new BookingAssetEventCancelled($dataForNotification, $user, $event_id));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Membatalkan Booking aset berhasil!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membatalkan booking aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function uploadDocumentAssetBooking(Request $request, $eventId)
    {
        // $assetBookings = AssetBooking::where('event_id', $eventId)
        //     ->where('status', '!=', 'cancelled')
        //     ->get();
        $assetJurusanBookings = AssetBooking::with('asset')->where('event_id', $eventId)
            ->whereHas('asset', function ($a) {
                $a->where('facility_scope', 'jurusan')->where('jurusan_id', auth()->user()->jurusan_id);
            })->where('status', '!=', 'cancelled')
            ->get();
        $assetUmumBookings = AssetBooking::with('asset')->where('event_id', $eventId)
            ->whereHas('asset', function ($a) {
                $a->where('facility_scope', 'umum');
            })->where('status', '!=', 'cancelled')->get();
        $event = Event::findOrFail($eventId);
        $assetDocument = AssetBookingDocument::where('event_id', $eventId)->where('document_type', 'Form Peminjaman')->first();
        DB::beginTransaction();
        try {
            if ($request->asset_jurusan === 'true') {
                if ($assetJurusanBookings) {
                    foreach ($assetJurusanBookings as $booking) {
                        $booking->status = 'submission_full_payment';
                        $booking->reason = null;
                        $booking->save();
                    }
                }
            } else {
                if ($assetUmumBookings) {
                    foreach ($assetUmumBookings as $booking) {
                        $booking->status = 'submission_full_payment';
                        $booking->reason = null;
                        $booking->save();
                    }
                }
            }

            if ($request->hasFile('loan_letter')) {

                $file = $request->file('loan_letter');

                // Hapus surat peminjaman lama jika ada
                if ($assetDocument && Storage::exists('public/' . $assetDocument)) {
                    Storage::delete('public/' . $assetDocument);
                }

                $extension = $file->getClientOriginalExtension();

                $userName = Str::slug(auth()->user()->organizer->shorten_name);

                $eventName = Str::words($event->title, 4, '');
                $fileName = $request->asset_jurusan === 'true'
                    ? "Surat Peminjaman Fasjur.$extension"
                    : "Surat Peminjaman Fasum.$extension";

                // Tentukan folder penyimpanan
                $filePath = "Booking Aset Event/{$userName}/{$eventName}/" . $fileName;

                // Simpan gambar ke dalam storage
                $file->storeAs("Booking Aset Event/{$userName}/{$eventName}", $fileName, 'public');

                if ($request->reupload) {
                    $assetDocument->document_path = $filePath;
                    $assetDocument->save();
                } else {
                    if ($request->asset_jurusan === 'true') {
                        foreach ($assetJurusanBookings as $booking) {
                            AssetBookingDocument::create([
                                'document_path' => $filePath,
                                'booking_id' => $booking->id,
                                'document_type' => 'Form Peminjaman',
                                'uploaded_by' => auth()->user()->id,
                            ]);
                        }
                    } else {
                        AssetBookingDocument::create([
                            'document_path' => $filePath,
                            'event_id' => $eventId,
                            'document_type' => 'Form Peminjaman',
                            'uploaded_by' => auth()->user()->id,
                        ]);
                    }
                }
            }
            $userSender = Auth::user();

            if ($request->asset_jurusan === 'true') {
                // Ambil semua user dengan role 'Admin Jurusan'
                $adminJurusanUsers = User::role('Admin Jurusan')->get();

                // Ambil jurusan_id dari aset pertama (pastikan datanya tidak null)
                $jurusanId = optional(optional($assetJurusanBookings->first())->asset)->jurusan_id;

                // Cari user dengan jurusan yang sesuai
                $userReceiver = $adminJurusanUsers->firstWhere('jurusan_id', $jurusanId);

                // Kirim notifikasi jika ada penerima
                if ($userReceiver) {
                    Notification::send($userReceiver, new BookingAssetEventUploadDoc($userSender));
                }
            } else {
                // Semua user dengan role 'Kaur RT'
                $userReceiver = User::role('Kaur RT')->get();

                // Kirim notifikasi ke semua
                Notification::send($userReceiver, new BookingAssetEventUploadDoc($userSender));
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Unggah Surat Peminjaman berhasil!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menggunggah surat peminjaman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function confirmDocument(Request $request, $eventId)
    {
        $assetJurusan = AssetBooking::with('asset')
            ->where('event_id', $eventId)
            ->where('status', '!=', 'cancelled')
            ->whereHas('asset', function ($a) {
                $a->where('facility_scope', 'jurusan')->where('jurusan_id',  auth()->user()->jurusan_id);
            })
            ->get();
        $assetUmum = AssetBooking::with('asset')
            ->where('event_id', $eventId)
            ->where('status', '!=', 'cancelled')
            ->whereHas('asset', function ($a) {
                $a->where('facility_scope', 'umum');
            })
            ->get();
        DB::beginTransaction();
        try {
            if ($request->actionConfirmDocument === 'approved') {
                $bookingsToApprove = $assetJurusan->isNotEmpty() ? $assetJurusan : $assetUmum;

                foreach ($bookingsToApprove as $booking) {
                    $booking->update([
                        'status' => 'approved',
                    ]);
                }
            } else {
                $bookingsToApprove = $assetJurusan->isNotEmpty() ? $assetJurusan : $assetUmum;

                foreach ($bookingsToApprove as $booking) {
                    $booking->update([
                        'status' => 'rejected_full_payment',
                        'reason' => $request->reason_rejected,
                    ]);
                }
            }
            $event_id = $bookingsToApprove->first()->event_id ?? '';
            $assetNames = $bookingsToApprove->pluck('asset.name')->toArray();
            $isApproved = $request->actionConfirmDocument === 'approved';
            $reason = $isApproved ? null : $request->reason_rejected;
            $userReceiver = optional($assetUmum->first())->user;
            $user_id = auth()->user()->id;
            Notification::send($userReceiver, new BookingAssetInternalConfirmDocument($user_id, $event_id, $isApproved, $assetNames, $reason));

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Konfirmasi surat peminjaman berhasil!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengonfirmasi surat peminjaman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function confirmDone(Request $request, $id)
    {

        if ($request->existEventId === 'exist') {
            $assetJurusan = AssetBooking::with('asset')
                ->where('event_id', $id)
                ->where('status', '!=', 'cancelled')
                ->whereHas('asset', function ($a) {
                    $a->where('facility_scope', 'jurusan')->where('jurusan_id',  auth()->user()->jurusan_id);
                })
                ->get();
            $assetUmum = AssetBooking::with('asset')
                ->where('event_id', $id)
                ->where('status', '!=', 'cancelled')
                ->whereHas('asset', function ($a) {
                    $a->where('facility_scope', 'umum');
                })
                ->get();
        }
        $assetBooking = collect();
        if ($request->existEventId === 'nothing') {
            $assetBooking = AssetBooking::findOrFail($id);
        }

        DB::beginTransaction();
        try {
            if ($request->existEventId === 'exist') {
                $bookingsToApprove = $assetJurusan->isNotEmpty() ? $assetJurusan : $assetUmum;

                foreach ($bookingsToApprove as $booking) {
                    $booking->update([
                        'status' => 'approved',
                    ]);
                }
                $userReceiver = $booking->user;
                $event_id = $booking->event_id ?? '';
                Notification::send($userReceiver, new BookingAssetConfirmDone($booking->asset->name, auth()->user(), $event_id));
            }
            if ($request->existEventId === 'nothing') {
                $assetBooking->update([
                    'status' => 'approved',
                ]);
                $userReceiver = $assetBooking->user;
                $event_id = $booking->event_id ?? '';

                Notification::send($userReceiver, new BookingAssetConfirmDone($assetBooking->asset->name, auth()->user(), $event_id));
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Konfirmasi peminjaman selesai berhasil!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengonfirmasi peminjaman selesai.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assetBookingManualInternal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_organizer' => 'required_without:user_not_organizer',
            'user_not_organizer' => 'required_if:user_organizer,other',

            'step_names' => 'required|array',
            'step_names.*' => 'required|string|max:255',

            'assets' => 'required|array',
            'assets.*' => 'required',

            'event_dates' => 'required|array',
            'event_dates.*' => 'required|date',

            'event_time_starts' => 'required|array',
            'event_time_starts.*' => 'required|date_format:H:i',

            'event_time_ends' => 'required|array',
            'event_time_ends.*' => 'required|date_format:H:i',

        ], [
            'user_organizer.required_without' => 'Pilih nama peminjam atau isi manual.',
            'user_not_organizer.required_if' => 'Silakan isi nama peminjam secara manual.',

            'step_names.required' => 'Nama tahapan event wajib diisi.',
            'step_names.*.required' => 'Setiap nama tahapan event wajib diisi.',
            'step_names.*.max' => 'Nama tahapan event tidak boleh lebih dari 255 karakter.',

            'assets.required' => 'Aset wajib dipilih.',
            'assets.*.required' => 'Setiap aset pada tahapan wajib dipilih.',

            'event_dates.required' => 'Tanggal pelaksanaan wajib diisi.',
            'event_dates.*.required' => 'Setiap tanggal pada tahapan wajib diisi.',
            'event_dates.*.date' => 'Format tanggal tidak valid.',

            'event_time_starts.required' => 'Jam mulai wajib diisi.',
            'event_time_starts.*.required' => 'Setiap jam mulai wajib diisi.',
            'event_time_starts.*.date_format' => 'Format jam mulai harus H:i (contoh: 14:30).',

            'event_time_ends.required' => 'Jam selesai wajib diisi.',
            'event_time_ends.*.required' => 'Setiap jam selesai wajib diisi.',
            'event_time_ends.*.date_format' => 'Format jam selesai harus H:i (contoh: 16:00).',

        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Ambil error pertama dari validasi
            notyf()->ripple(true)->error($firstError);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $organizer = Organizer::with('user')->findOrFail($request->user_organizer);
            $userId = optional($organizer->user)->id;

            foreach ($request->step_names as $index => $step_name) {
                $asset = $request->assets[$index];

                $status = $request->status[$index];

                $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->event_dates[$index] . ' ' . $request->event_time_starts[$index]);
                $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->event_dates[$index] . ' ' . $request->event_time_ends[$index]);


                $conflict = AssetBooking::where('asset_id', $asset)
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
                    notyf()->ripple(true)->error('Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.');
                    return redirect()->back();
                }



                $booking = AssetBooking::create([
                    'asset_id' => $asset,
                    'user_id' => $request->user_organizer !== 'other' ? $userId : NULL,
                    'external_user' => $request->user_organizer === 'other' ? $request->user_not_organizer : NULL,
                    'booking_number' => '',
                    'usage_date_start' => $usageDateStart,
                    'usage_date_end' => $usageDateEnd,
                    'usage_event_name' => $step_name,
                    'status' => $status,
                ]);

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
            }
            DB::commit();
            notyf()->ripple(true)->success('Menambahkan data booking berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->ripple(true)->error('Terjadi kesalahan saat menambahkan data booking.');
            return redirect()->back();
        }
    }

    public function getLoanForm(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $organizer = Organizer::with('user')->where('user_id', Auth::id())->first();

        $assetBookings = AssetBooking::with('asset', 'event')->where('event_id', $id)
            ->whereHas('asset', function ($query) {
                $query->where('type', 'building');
            })
            ->where('status', '!=', 'cancelled')
            ->get();

        $dpk = Stakeholder::where('position', 'DPK')->where('organizer_id', $organizer->id)
            ->where('is_active', true)->first();
        $presiden = Stakeholder::where('position', 'Presiden BEM')->where('is_active', true)->first();
        $kajur = Stakeholder::where('position', 'Ketua Jurusan')->where('is_active', true)->first();
        $wadir3 = Stakeholder::where('position', 'Wakil Direktur III')->where('is_active', true)->first();
        $team_members = TeamMember::where('organizer_id', $organizer->id)->get();
        $leader = TeamMember::findOrFail($request->leader);
        $letter_number = $request->letter_number;
        $event_leader = TeamMember::findOrFail($event->event_leader);
        return view('dashboardPage.assets.asset-booking-event.loan-form', compact('organizer', 'assetBookings', 'dpk', 'kajur', 'presiden', 'wadir3', 'letter_number', 'event_leader', 'leader', 'team_members'));
    }


    public function ExportReport(Request $request)
    {
        $year = $request->input('year');

        $yearStart = Carbon::parse("$year-01-01")->startOfDay();
        $yearEnd = Carbon::parse("$year-12-31")->endOfDay();

        $role = auth()->user()->getRoleNames()->first();
        $facility = $role === 'Admin Jurusan' ? auth()->user()->jurusan->kode_jurusan : 'Umum';

        return Excel::download(
            new InternalAssetBookingsExport($yearStart, $yearEnd),
            'Rekapan Booking Fasilitas ' . $facility . ' - ' . $year . '.xlsx'
        );
    }
}
