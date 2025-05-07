<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;

use App\Models\Event;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EventParticipant;
use App\Models\EventTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use BaconQrCode\Renderer\ImageRenderer;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class EventParticipantController extends Controller
{
    public function getDataParticipants($id)
    {
        $participants = EventParticipant::with('user', 'event.steps', 'transaction')
            ->where('event_id', $id)
            ->get();

        $event_id = $id;
        $event = Event::findOrFail($event_id);
        return DataTables::of($participants)
            ->addIndexColumn()
            ->editColumn('user.name', function ($participant) {
                return '<div class="' . ($participant->user->is_blocked ? 'bg-danger-400' : '') . '">' . $participant->user->name . '</div>';
            })
            ->editColumn('category_user', function ($participant) {
                $user = $participant->user;
                if ($user->category_user == 'Internal Kampus') {
                    $categoryUser = 'Mahasiswa J' . $user->jurusan->kode_jurusan;
                } else {
                    $categoryUser = 'Eksternal Kampus';
                }
                return $categoryUser;
            })
            ->editColumn('status', function ($participant) {
                $badgeClass = '';
                $statusText = '';
                $reasonRejected = null;

                switch ($participant->status) {
                    case 'pending_approval':
                        $badgeClass = 'bg-warning-600';
                        $statusText = 'Perlu Diverifikasi';
                        break;
                    case 'registered':
                        $badgeClass = 'bg-primary-600';
                        $statusText = 'Terdaftar';
                        break;
                    case 'attended':
                        $badgeClass = 'bg-success-600';
                        $statusText = 'Hadir';
                        break;
                    case 'rejected':
                        $badgeClass = 'bg-danger-600';
                        $statusText = 'Ditolak';
                        $reasonRejected = $participant->reason;
                        break;
                }

                $html = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                if ($reasonRejected) {
                    $html .= '<p class="mt-2 text-danger small">' . e($reasonRejected) . '</p>';
                }

                return $html;
            })
            ->addColumn('action', function ($participant) use ($event, $event_id) {
                $confirmModal = view('dashboardPage.events.modal.confirmRegistration', compact('participant', 'event_id'))->render();
                $updateModal = view('dashboardPage.events.participants.update-participant', compact('participant', 'event', 'event_id'))->render();

                $html = '<div class="d-flex gap-8">';
                if ($participant->status === 'pending_approval') {
                    $html .= '
                    <a class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalConfirmRegistration-' . $participant->id . '">
                    <iconify-icon icon="ph:check-fat-fill" class="text-xl"></iconify-icon>
                    </a>';

                    $html .= $confirmModal;
                }
                if ($participant->status === 'registered') {
                    $html .= '
                    <a href="' . route('e-ticket.event', ['participant' => $participant->id]) . '" class="w-40-px h-40-px cursor-pointer bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    target="_blank">
                        <iconify-icon icon="iconamoon:ticket-light" class="text-xl"></iconify-icon>
                    </a>';
                }


                $html .= '
                <button class="w-40-px h-40-px bg-hover-success-200 bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateParticipant-' . $participant->user->id . '">
                    <iconify-icon icon="lucide:edit" width="20"></iconify-icon>
                </button>';
                $html .= $updateModal;
                // Tombol Unblock
                if ($participant->user->is_blocked) {
                    $html .= '
                        <form action="' . route('block.eventParticipant', ['type' => 'unblock', 'id' => $participant->user->id]) . '" method="POST" class="block-form" data-table="eventParticipantTable">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="button" class="block-btn w-40-px h-40-px bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Unblock" data-action="unblock">
                                <iconify-icon icon="gg:unblock" width="25"></iconify-icon>
                            </button>
                        </form>';
                } else {
                    // Tombol Block
                    $html .= '
                        <form action="' . route('block.eventParticipant', ['type' => 'block', 'id' => $participant->user->id]) . '" method="POST" class="block-form" data-table="eventParticipantTable">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="button" class="block-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Block" data-action="block">
                                <iconify-icon icon="ic:sharp-block" width="20"></iconify-icon>
                            </button>
                        </form>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['user.name', 'category_user', 'status', 'action'])
            ->make(true);
    }
    public function addEventParticipant(Request $request, $eventId)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone_number' => 'required|numeric',
            'province' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'village' => 'required',
            'address' => 'required',
        ], [
            'name.required' => 'Nama Stkeholder harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'phone_number.required' => 'Nomor HP Mahasiswa harus diisi.',
            'phone_number.numeric' => 'Nomor HP Mahasiswa harus mengandung angka 0-9.',
            'province.required' => 'Asal Provinsi Mahasiswa harus diisi.',
            'city.required' => 'Asal Kabupaten/Kota Mahasiswa harus diisi.',
            'subdistrict.required' => 'Asal Kecamatan Mahasiswa harus diisi.',
            'village.required' => 'Asal Kelurahan/Desa Mahasiswa harus diisi.',
            'address.required' => 'Alamat Lengkap Mahasiswa harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $event = Event::findOrFail($eventId);

        if ($event->remaining_quota >= $event->quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon maaf, kuota sudah penuh.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $newUser = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'category_user' => 'Eksternal Kampus',
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'province' => $request->province,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'village' => $request->village,
                'address' => $request->address,
            ]);
            $newUser->assignRole('Participant');



            $participant = EventParticipant::create([
                'event_id' => $eventId,
                'user_id' => $newUser->id,
                'ticket_code' => '',
                'status' => 'registered'
            ]);

            $clean_event_id = str_replace('-', '', $eventId);
            $clean_participant_id = str_replace('-', '', $participant->id);
            $uuid_event = substr($clean_event_id, (strlen($clean_event_id) - 8) / 2, 8);
            $uuid_participant = substr($clean_participant_id, (strlen($clean_participant_id) - 8) / 2, 8);
            $kode_ticket = strtoupper("{$uuid_event}{$uuid_participant}");

            $participant->ticket_code = $kode_ticket;
            $participant->save();

            $yearNow = Carbon::now()->year;

            if (!$event->is_free) {

                $eventPrice = $event->prices->firstWhere('scope', 'Eksternal Kampus') ??  $event->prices->firstWhere('scope', 'Umum');
                $total_amount = $eventPrice->price;

                $request->validate([
                    'proof_of_payment' => 'required|file|mimes:pdf,jpg,jpeg,png',
                ]);


                $part_event_name = implode(' ', array_slice(explode(' ', $event->title), 0, 3));
                $organizerName = $event->organizers->shorten_name;
                $file = $request->file('proof_of_payment');
                $extension = $file->getClientOriginalExtension();


                // Buat nama file
                $fileName =  $newUser->name . '.' . $extension;
                $path = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/' .  'Bukti Pembayaran',
                    $fileName,
                    'public'
                );
                EventTransaction::create([
                    'event_participant_id' => $participant->id,
                    'total_amount' => $total_amount,
                    'status' => 'paid',
                    'proof_of_payment' => $path,
                    'payment_date' => Carbon::now()
                ]);
            }


            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Peserta berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan peserta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateEventParticipant(Request $request, $id)
    {

        $participant = EventParticipant::findOrFail($id);
        $rules = [
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'province' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'village' => 'required',
            'address' => 'required',
        ];
        if ($request->username !== $participant->user->username) {
            $rules['username'] = 'required|unique:users,username';
        } else {
            $rules['username'] = 'required';
        }
        if ($request->email !== $participant->user->email) {
            $rules['email'] = 'required|email|unique:users,email';
        } else {
            $rules['email'] = 'required|email';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama Stkeholder harus diisi.',
            'phone_number.required' => 'Nomor HP Mahasiswa harus diisi.',
            'phone_number.numeric' => 'Nomor HP Mahasiswa harus mengandung angka 0-9.',
            'province.required' => 'Asal Provinsi Mahasiswa harus diisi.',
            'city.required' => 'Asal Kabupaten/Kota Mahasiswa harus diisi.',
            'subdistrict.required' => 'Asal Kecamatan Mahasiswa harus diisi.',
            'village.required' => 'Asal Kelurahan/Desa Mahasiswa harus diisi.',
            'address.required' => 'Alamat Lengkap Mahasiswa harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::findOrFail($participant->user->id);
        $event  = Event::findOrFail($request->event_id);
        DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'province' => $request->province,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'village' => $request->village,
                'address' => $request->address,
            ]);

            $yearNow = Carbon::now()->year;
            $event_transaction = EventTransaction::where('event_participant_id', $participant->id)->first();
            if (!$event->is_free) {
                if ($request->proof_of_payment) {

                    $request->validate([
                        'proof_of_payment' => 'file|mimes:pdf,jpg,jpeg,png',
                    ]);
                    if (Storage::exists('public/' . $event_transaction->proof_of_payment)) {
                        Storage::delete('public/' . $event_transaction->proof_of_payment);
                    }

                    $part_event_name = implode(' ', array_slice(explode(' ', $event->title), 0, 3));
                    $organizerName = $event->organizers->shorten_name;
                    $file = $request->file('proof_of_payment');
                    $extension = $file->getClientOriginalExtension();


                    // Buat nama file
                    $fileName =  $user->name . '.' . $extension;
                    $path = $file->storeAs(
                        'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/' .  'Bukti Pembayaran',
                        $fileName,
                        'public'
                    );
                    $event_transaction->update([
                        'proof_of_payment' => $path,
                        'payment_date' => Carbon::now()
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Peserta berhasil diperbarui!',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data peserta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyEventParticipant($id)
    {
        try {
            // Ambil data user yang ingin dihapus
            $user = User::findOrFail($id);
            // Hapus user
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Hapus data user berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function blockEventParticipant($type, $id)
    {
        try {
            // Ambil data user yang ingin dihapus
            $user = User::findOrFail($id);
            // Hapus user
            $user->update(['is_blocked' => $type == 'block' ? true : false]);
            return response()->json([
                'status' => 'success',
                'message' => $type == 'block' ? 'Blokir user berhasil!' :  'Membuka blokir user berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $type == 'block' ? 'Terjadi kesalahan saat memblokir user.' : 'Terjadi kesalahan saat membuka blokir user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function registerEvent(Request $request, $id)
    {
        if (!Auth::check()) {
            notyf()->ripple(true)->error('Anda harus login terlebih dahulu.');
            return redirect()->back();
        }
        if (!Auth::user()->hasRole('Participant')) {
            notyf()->ripple(true)->error('Anda harus login sebagai Participant.');
            return redirect()->back();
        }


        $event = Event::findOrFail($id);
        $user = Auth::user();


        if ($event->remaining_quota >= $event->quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon maaf, kuota sudah penuh.'
            ], 422);
        }
        // Cek apakah user sudah pernah mendaftar
        $alreadyRegistered = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRegistered) {
            notyf()->ripple(true)->warning('Anda sudah terdaftar di event ini');
            return redirect()->back();
        }
        $yearNow = Carbon::now()->year;
        DB::beginTransaction();
        try {
            $status = $event->is_free ? 'registered' : 'pending_approval';

            if ($status == 'registered') {
                $event->remaining_quota += 1;
            }
            $event->save();



            // Simpan ke tabel participant
            $participant = EventParticipant::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'ticket_code' => '',
                'status' => $status,
            ]);

            // Jika event berbayar, buat transaksi dan simpan bukti
            if (!$event->is_free) {
                $request->validate([
                    'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png',
                ]);


                $part_event_name = implode(' ', array_slice(explode(' ', $event->title), 0, 3));
                $organizerName = $event->organizers->shorten_name;
                $file = $request->file('proof_of_payment');
                $extension = $file->getClientOriginalExtension();


                // Buat nama file
                $fileName =  $user->name . '.' . $extension;
                $path = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/' .  'Bukti Pembayaran',
                    $fileName,
                    'public'
                );
                EventTransaction::create([
                    'event_participant_id' => $participant->id,
                    'total_amount' => $request->price,
                    'status' => 'pending',
                    'proof_of_payment' => $path,
                    'payment_date' => Carbon::now()
                ]);
            }
            DB::commit();

            notyf()->ripple(true)->success('Berhasil mendaftar event!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            notyf()->ripple(true)->error('Terjadi kesalahan saat mendaftar event. ' . $e);
            return redirect()->back();
        }
    }
    public function repeatRegisterEvent(Request $request, $id)
    {
        if (!Auth::check()) {
            notyf()->ripple(true)->error('Anda harus login terlebih dahulu.');
            return redirect()->back();
        }

        if (!Auth::user()->hasRole('Participant')) {
            notyf()->ripple(true)->error('Anda harus login sebagai Participant.');
            return redirect()->back();
        }


        $event = Event::findOrFail($id);
        $user = Auth::user();
        $yearNow = Carbon::now()->year;

        if ($event->remaining_quota >= $event->quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon maaf, kuota sudah penuh.'
            ], 422);
        }

        // Cek apakah user sudah pernah mendaftar
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        // Jika sudah pernah mendaftar dan statusnya rejected, izinkan upload ulang bukti
        if ($participant && $participant->status === 'rejected') {
            $request->validate([
                'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png',
            ]);

            DB::beginTransaction();
            try {
                if (Storage::disk('public')->exists($participant->transaction->first()->proof_of_payment)) {
                    Storage::disk('public')->delete($participant->transaction->first()->proof_of_payment);
                }

                $part_event_name = implode(' ', array_slice(explode(' ', $event->title), 0, 3));
                $organizerName = $event->organizers->shorten_name;
                $file = $request->file('proof_of_payment');
                $extension = $file->getClientOriginalExtension();

                $fileName = $user->name . '.' . $extension;
                $path = $file->storeAs(
                    'Event/' . $organizerName . '/' . $yearNow . '/' . $part_event_name . '/Bukti Pembayaran',
                    $fileName,
                    'public'
                );

                // Update status peserta jadi pending_approval kembali
                $participant->update([
                    'status' => 'pending_approval',
                    'reason' => null
                ]);

                // Update atau buat transaksi baru
                $participant->transaction()->update(
                    [
                        'proof_of_payment' => $path,
                        'payment_date' => Carbon::now(),
                    ]
                );

                DB::commit();
                notyf()->ripple(true)->success('Bukti pembayaran berhasil diperbarui!');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                notyf()->ripple(true)->error('Terjadi kesalahan saat memperbarui bukti pembayaran.');
                return redirect()->back();
            }
        }
    }
    public function confirmRegistration(Request $request, $id)
    {
        // Validasi input berdasarkan status
        $rules = ['statusRegistration' => 'required'];
        $messages = ['statusRegistration.required' => 'Tindakan Konfirmasi wajib diisi.'];

        if ($request->statusRegistration === 'rejected') {
            $rules['reason'] = 'required';
            $messages['reason.required'] = 'Alasan Penolakan wajib diisi.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        $event = Event::findOrFail($request->event_id);

        if ($event->remaining_quota >= $event->quota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mohon maaf, kuota sudah penuh.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Temukan peserta
            $eventParticipant = EventParticipant::findOrFail($id);

            // Update status berdasarkan input
            if ($request->statusRegistration === 'approved') {
                $eventParticipant->status = 'registered';
                $event->remaining_quota += 1;

                $clean_event_id = str_replace('-', '', $event->id);
                $clean_participant_id = str_replace('-', '', $eventParticipant->id);
                $uuid_event = substr($clean_event_id, (strlen($clean_event_id) - 8) / 2, 8);
                $uuid_participant = substr($clean_participant_id, (strlen($clean_participant_id) - 8) / 2, 8);
                $kode_ticket = strtoupper("{$uuid_event}{$uuid_participant}");

                $eventParticipant->ticket_code = $kode_ticket;
                $eventParticipant->save();
            } elseif ($request->statusRegistration === 'rejected') {
                $eventParticipant->status = 'rejected';
                $eventParticipant->reason = $request->reason;
            }

            $event->save();
            $eventParticipant->save();

            if ($request->statusRegistration === 'approved') {
                $eventParticipant->transaction()->update(
                    [
                        'status' => 'paid',
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Konfirmasi Pendaftaran berhasil!.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat konfirmasi pendaftaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function eTicket($id)
    {
        $participant = EventParticipant::with('user', 'event.steps', 'transaction')->findOrFail($id);

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd() // Gunakan SvgImageBackEnd yang tidak memerlukan Imagick
        );

        $writer = new Writer($renderer);
        $qrCode = base64_encode($writer->writeString($participant->ticket_code));
        $pdf = Pdf::loadView('components.e-ticket', compact('participant', 'qrCode'));

        return $pdf->stream("E-ticket {$participant->event->title}.pdf");
    }
}