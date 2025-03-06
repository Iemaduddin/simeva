<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Jurusan;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use App\Events\BookingConfirmed;
use App\Models\AssetTransaction;
use Illuminate\Support\Facades\DB;
use App\Notifications\BookingAsset;
use App\Models\AssetBookingDocument;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Notifications\BookingAssetConfirmed;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingAssetConfirmPayment;
use App\Notifications\BookingAssetPayAndCompleteFile;

class AssetBookingController extends Controller
{
    public function assetBookingFasum()
    {
        $jurusans = Jurusan::all();
        return view('dashboardPage.assets.asset-booking.index', ['kode_jurusan' => null, 'jurusans' => $jurusans]);
    }
    public function assetBookingFasjur($kode_jurusan)
    {
        $jurusans = Jurusan::all();

        return view('dashboardPage.assets.asset-booking.index', compact('kode_jurusan', 'jurusans'));
    }
    public function getDataAssetBooking(Request $request, $kode_jurusan = null)
    {
        $statusBooking = $request->status_booking;
        $jurusan = Jurusan::where('kode_jurusan', $kode_jurusan)->first();

        // Tentukan facility_scope berdasarkan keberadaan kode_jurusan
        $scope = $kode_jurusan ? 'jurusan' : 'umum';

        // Inisialisasi query utama
        $query = AssetBooking::with(['asset', 'user', 'asset_category'])
            ->whereHas('asset', function ($q) use ($scope, $jurusan) {
                $q->where('facility_scope', $scope);
                if ($scope === 'jurusan' && $jurusan) {
                    $q->where('jurusan_id', $jurusan->id);
                }
            });

        // Filter berdasarkan status_booking (kecuali 'all')
        if ($statusBooking === 'all') {
        } elseif (str_contains($statusBooking, 'submission')) {
            $query->whereIn('status', ['submission_booking', 'submission_payment', 'rejected_booking', 'rejected_payment']);
        } else {
            $query->where('status', $statusBooking);
        }

        // Eksekusi query
        $assetBookings = $query->get();

        $tableId = $kode_jurusan ? 'assetBookingFasilitasJurusan' . $kode_jurusan . '-Table-' . $statusBooking :
            "assetBookingFasilitasUmumTable-" . $statusBooking;

        return DataTables::of($assetBookings)
            ->addIndexColumn()
            ->addColumn('action', function ($assetBooking) use ($tableId) {
                // Render modal konfirmasi booking dan pembayaran
                $confirmBookingModal = view('dashboardPage.assets.asset-booking.modal.confirmBooking-asset', compact('assetBooking', 'tableId'))->render();
                $confirmPaymentAndStatementLetterModal = view('dashboardPage.assets.asset-booking.modal.confirmPaymentAndStatementLetter', compact('assetBooking', 'tableId'))->render();

                // Tombol aksi berdasarkan status
                $buttons = '<div class="d-flex gap-2">';
                $modals = ''; // Untuk menampung modal agar ditambahkan di luar kondisi

                switch ($assetBooking->status) {
                    case 'submission_booking':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmAssetBooking-{$assetBooking->id}'>
                                        ✅ Konfirmasi Booking
                                    </a>";
                        $modals .= $confirmBookingModal;
                        break;

                    case 'booked':
                        $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Pembayaran dan Upload Berkas</span>";
                        break;

                    case 'rejected_booking':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmAssetBooking-{$assetBooking->id}'>
                                        ✅ Konfirmasi Ulang Booking
                                    </a>";
                        $modals .= $confirmBookingModal;
                        break;

                    case 'submission_full_payment':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmPaymentAndStatementLetter-{$assetBooking->id}'>
                                        ✅ Konfirmasi Pembayaran Lunas dan Berkas
                                    </a>";
                        $modals .= $confirmPaymentAndStatementLetterModal;
                        break;
                    case 'submission_dp_payment':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmPaymentAndStatementLetter-{$assetBooking->id}'>
                                        ✅ Konfirmasi Pembayaran DP dan Berkas
                                    </a>";
                        $modals .= $confirmPaymentAndStatementLetterModal;
                        break;

                    case 'approved_full_payment':
                        $buttons .= "<span class='badge bg-success'>✔ Disetujui</span>";
                        break;
                    case 'approved_dp_payment':
                        $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Pelunasan Pembayaran</span>";
                        break;

                    case 'rejected_full_payment':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmPaymentAndStatementLetter-{$assetBooking->id}'>
                                        ✅ Konfirmasi Ulang Pembayaran dan Berkas
                                    </a>";
                        $modals .= $confirmPaymentAndStatementLetterModal;
                        break;
                    case 'rejected_dp_payment':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmPaymentAndStatementLetter-{$assetBooking->id}'>
                                        ✅ Konfirmasi Ulang Pembayaran dan Berkas
                                    </a>";
                        $modals .= $confirmPaymentAndStatementLetterModal;
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
            ->rawColumns(['action'])
            ->make(true);
    }
    // Tenant booking aset
    public function assetBooking(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:assets,id',
            'usage_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'usage_event_name' => 'required',
            'amount' => 'required',
            'file_personal_identity' => 'required|mimes:pdf,jpeg,png,jpg',
            'type_event' => 'required',
            'payment_type' => 'required|in:dp,lunas',


        ], [
            'asset_id.required' => 'Aset harus diisi.',
            'asset_id.exists' => 'Aset tidak ditemukan.',

            'usage_date.required' => 'Tanggal penggunaan harus diisi.',
            'usage_date.date_format' => 'Format tanggal dan waktu harus dalam format Y-m-d (contoh: 2025-02-20 14:30).',

            'start_time.required' => 'Waktu mulai harus diisi.',
            'start_time.date_format' => 'Format waktu mulai harus dalam format 24 jam (HH:mm).',

            'end_time.required' => 'Waktu selesai harus diisi.',
            'end_time.date_format' => 'Format waktu selesai harus dalam format 24 jam (HH:mm).',

            'amount.required' => 'Harga Sewa harus diisi.',
            'usage_event_name.required' => 'Tanggal loading harus diisi.',

            'file_personal_identity.required' => 'File KTP harus diunggah.',
            'file_personal_identity.mimes' => 'Format file yang diperbolehkan: PDF, JPEG, PNG, JPG.',

            'type_event.required' => 'Jenis acara harus dipilih.',

            'payment_type.required' => 'Jenis pembayaran harus dipilih.',
            'payment_type.in' => 'Jenis pembayaran harus berupa DP atau Lunas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date . ' ' . $request->start_time);
            $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date . ' ' . $request->end_time);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format tanggal/waktu tidak valid.'], 400);
        }
        $filePath = null;
        try {
            $result = DB::transaction(function () use ($request, $usageDateStart, $usageDateEnd, $filePath) {
                $user = auth()->user();
                $booking = AssetBooking::create([
                    'asset_id' => $request->asset_id,
                    'user_id' => auth()->user()->id,
                    'asset_category_id' => $request->type_event,
                    'usage_date_start' => $usageDateStart,
                    'usage_date_end' => $usageDateEnd,
                    'usage_event_name' => $request->usage_event_name,
                    'payment_type' => $request->payment_type,
                    'total_amount' => $request->amount,
                    'status' => 'submission_booking'
                ]);

                if ($request->hasFile('file_personal_identity')) {

                    $asset_name = Asset::where('id', $request->asset_id)->pluck('name')->first();
                    $file = $request->file('file_personal_identity');

                    $extension = $file->getClientOriginalExtension();
                    $fileName = auth()->user()->name . ".{$extension}";

                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/KTP/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/KTP/", $fileName, 'public');

                    AssetBookingDocument::create([
                        'document_path' => $filePath,
                        'booking_id' => $booking->id,
                        'document_type' => 'Identitas Diri',
                        'uploaded_by' => auth()->user()->id,
                    ]);
                }

                // Cari user berdasarkan role dari Spatie
                // $superAdmins = User::role('Super Admin')->get(); // Semua Super Admin
                $upd_pu = User::role('UPT PU')->get(); // Semua Tenant

                // Kirim notifikasi ke Super Admin & Tenant
                // Notification::send($superAdmins, new BookingAsset($booking, $user));
                Notification::send($upd_pu, new BookingAsset($booking, $user));
                return $booking;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Booking aset berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat booking aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function assetRebooking(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:assets,id',
            'usage_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'usage_event_name' => 'required',
            'amount' => 'required',
            'file_personal_identity' => 'required|mimes:pdf,jpeg,png,jpg',
            'type_event' => 'required',
            'payment_type' => 'required|in:dp,lunas',


        ], [
            'asset_id.required' => 'Aset harus diisi.',
            'asset_id.exists' => 'Aset tidak ditemukan.',

            'usage_date.required' => 'Tanggal penggunaan harus diisi.',
            'usage_date.date_format' => 'Format tanggal dan waktu harus dalam format Y-m-d (contoh: 2025-02-20 14:30).',

            'start_time.required' => 'Waktu mulai harus diisi.',
            'start_time.date_format' => 'Format waktu mulai harus dalam format 24 jam (HH:mm).',

            'end_time.required' => 'Waktu selesai harus diisi.',
            'end_time.date_format' => 'Format waktu selesai harus dalam format 24 jam (HH:mm).',

            'amount.required' => 'Harga Sewa harus diisi.',
            'usage_event_name.required' => 'Tanggal loading harus diisi.',

            'file_personal_identity.required' => 'File KTP harus diunggah.',
            'file_personal_identity.mimes' => 'Format file yang diperbolehkan: PDF, JPEG, PNG, JPG.',

            'type_event.required' => 'Jenis acara harus dipilih.',

            'payment_type.required' => 'Jenis pembayaran harus dipilih.',
            'payment_type.in' => 'Jenis pembayaran harus berupa DP atau Lunas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date . ' ' . $request->start_time);
            $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date . ' ' . $request->end_time);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format tanggal/waktu tidak valid.'], 400);
        }
        $filePath = null;
        try {
            $result = DB::transaction(function () use ($request, $id, $usageDateStart, $usageDateEnd, $filePath) {
                $user = auth()->user();
                $booking = AssetBooking::findOrFail($id);
                $booking->update([
                    'asset_id' => $request->asset_id,
                    'user_id' => auth()->user()->id,
                    'asset_category_id' => $request->type_event,
                    'usage_date_start' => $usageDateStart,
                    'usage_date_end' => $usageDateEnd,
                    'usage_event_name' => $request->usage_event_name,
                    'payment_type' => $request->payment_type,
                    'total_amount' => $request->amount,
                    'status' => 'submission_booking'
                ]);

                if ($request->hasFile('file_personal_identity')) {

                    $asset_name = Asset::where('id', $request->asset_id)->pluck('name')->first();
                    $file = $request->file('file_personal_identity');

                    $extension = $file->getClientOriginalExtension();
                    $fileName = auth()->user()->name . ".{$extension}";

                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/KTP/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/KTP/", $fileName, 'public');

                    $booking->documents()->firstOrFail()->update([
                        'document_path' => $filePath,
                        'booking_id' => $booking->id,
                        'document_type' => 'Identitas Diri',
                        'uploaded_by' => auth()->user()->id,
                    ]);
                }

                // Cari user berdasarkan role dari Spatie
                // $superAdmins = User::role('Super Admin')->get(); // Semua Super Admin
                $upt_pu = User::role('UPT PU')->get(); // Semua Tenant

                // Kirim notifikasi ke Super Admin & Tenant
                // Notification::send($superAdmins, new BookingAsset($booking, $user));
                Notification::send($upt_pu, new BookingAsset($booking, $user));
                return $booking;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Rebooking aset berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat rebooking aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // UPT PU confrim booking asset
    public function confirmBooking(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }
        if ($request->actionBooking === 'approved') {

            $validator = Validator::make($request->all(), [
                'va_number' => 'required',
                'va_expiry' => 'required',
            ], [
                'va_number.required' => 'Nomor VA harus diisi.',
                'va_expiry.required' => 'Expired Nomor VA harus diisi.',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'reason_rejected' => 'required',
            ], [
                'reason_rejected.required' => 'Alasan Penolakan harus diisi.',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {
            $paymentAmount = null;
            $vaNumber = null;
            $vaExpiredDate = null;

            $booking = AssetBooking::findOrFail($id);
            $user = User::findOrFail($booking->user_id);
            if ($request->actionBooking === 'approved') {
                $paymentAmount = ($booking->payment_type === 'dp') ? $booking->total_amount * 0.3 : $booking->total_amount;
                $vaNumber = $request->va_number;
                $vaExpiredDate = $request->va_expiry;

                $scope = $booking->asset->facility_scope == 'umum' ? 'FU' : 'FJ';
                $bookingDate = $booking->created_at->format('Y-m-d'); // Tanggal Booking
                $bookingSuffix = substr($booking->id, -2); // 4 karakter terakhir asset_id
                $assetSuffix = substr($booking->asset_id, -4); // 4 karakter terakhir asset_id
                $userSuffix = substr($booking->user_id, -4); // 4 karakter terakhir user_id
                $invoiceNo = "INV-{$scope}-{$bookingDate}-{$bookingSuffix}{$assetSuffix}-{$userSuffix}";

                // ✅ Ubah status menjadi "booked" 
                $booking->status = 'booked';

                AssetTransaction::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'invoice_number' => strToUpper($invoiceNo),
                    'amount' => $paymentAmount,
                    'va_number' => $vaNumber,
                    'va_expiry' => $vaExpiredDate,
                    'tax' => '10',
                    'status' => 'pending',
                ]);
            } else {
                // ✅ Ubah status menjadi "rejected" 
                $booking->reason_rejected = $request->reason_rejected;
                $booking->status = 'rejected_booking';
            }
            $confirmBooking = $request->actionBooking;

            $booking->save();
            // 🔥 Kirim Notifikasi ke User
            Notification::send($user, new BookingAssetConfirmed($booking, $confirmBooking, $user, $vaNumber, $paymentAmount, $vaExpiredDate));


            DB::commit(); // ✅ Semua sukses, simpan ke database

            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil dikonfirmasi',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function payAndCompleteFile(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }

        $rules = [
            'proof_of_payment' => 'required',
            'statement_letter' => 'required',
            'loan_form' => 'required',
        ];

        $messages = [
            'proof_of_payment.required' => 'Bukti Pembayaran harus diisi.',
            'statement_letter.required' => 'Surat Pernyataan harus diisi.',
            'loan_form.required' => 'Formulir Pernyataan harus diisi.',
        ];

        // Tambahkan validasi contract_agreement_letter jika ada
        if ($request->contract_agreement_letter) {
            $rules['contract_agreement_letter'] = 'required';
            $messages['contract_agreement_letter.required'] = 'Surat Perjanjian Kontrak harus diisi.';
        }

        // Lakukan validasi
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check validasi dan return response
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }


        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {
            $booking = AssetBooking::findOrFail($id);
            $user = User::findOrFail($booking->user_id);
            $transaction = AssetTransaction::where('booking_id', $id)->first();

            $documents = [
                'proof_of_payment' => 'Bukti Pembayaran',
                'statement_letter' => 'Surat Pernyataan',
                'load_form' => 'Form Peminjaman',
                'contract_agreement_letter' => 'Surat Perjanjian Kontrak',
            ];

            $asset_name = Asset::where('id', $booking->asset_id)->pluck('name')->first();
            foreach ($documents as $field => $documentName) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = auth()->user()->name . "-DP.{$extension}";

                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/{$documentName}/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/{$documentName}", $fileName, 'public');

                    if ($field === 'proof_of_payment') {
                        // Simpan ke transaksi
                        $transaction->update([
                            $field => $filePath
                        ]);
                    }

                    if ($field !== 'proof_of_payment') {
                        // Simpan ke AssetBookingDocument
                        AssetBookingDocument::create([
                            'document_path' => $filePath,
                            'booking_id' => $booking->id,
                            'document_name' => $documentName,
                            'uploaded_by' => auth()->user()->id,
                        ]);
                    }
                }
            }
            $booking->update([
                'status' => $booking->payment_type === 'dp' ? 'submission_dp_payment' : 'submission_full_payment'
            ]);
            $upt_pu = User::role('UPT PU')->get(); // Semua Tenant

            // 🔥 Kirim Notifikasi ke User
            Notification::send($upt_pu, new BookingAssetPayAndCompleteFile($booking,  $user));


            DB::commit(); // ✅ Semua sukses, simpan ke database

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan Bukti Pembayaran dan Berkas berhasil.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function confirmPayment(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }
        if ($request->actionConfirmPayment !== 'approved') {

            $validator = Validator::make($request->all(), [
                'reason_rejected' => 'required',
            ], [
                'reason_rejected.required' => 'Alasan Penolakan harus diisi.',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'loading_date_start' => [
                    'required',
                    'date_format:Y-m-d H:i',
                    'after_or_equal:today'
                ],
                'loading_date_end' => [
                    'required',
                    'date_format:Y-m-d H:i',
                    'after:loading_date_start'
                ],
                'unloading_date' => [
                    'required',
                    'date_format:Y-m-d H:i',
                    'after:loading_date_end'
                ],
            ], [
                'loading_date_start.required' => 'Tanggal dan waktu mulai loading harus diisi.',
                'loading_date_start.date_format' => 'Format tanggal dan waktu mulai loading tidak valid.',
                'loading_date_start.after_or_equal' => 'Tanggal mulai loading tidak boleh kurang dari hari ini.',

                'loading_date_end.required' => 'Tanggal dan waktu selesai loading harus diisi.',
                'loading_date_end.date_format' => 'Format tanggal dan waktu selesai loading tidak valid.',
                'loading_date_end.after' => 'Waktu selesai loading harus setelah waktu mulai loading.',

                'unloading_date.required' => 'Tanggal dan waktu unloading harus diisi.',
                'unloading_date.date_format' => 'Format tanggal dan waktu unloading tidak valid.',
                'unloading_date.after' => 'Waktu unloading harus setelah waktu selesai loading.',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {

            $booking = AssetBooking::findOrFail($id);
            $transaction = AssetTransaction::where('booking_id', $id)->first();
            $user = User::findOrFail($booking->user_id);

            if ($request->actionConfirmPayment === 'approved') {
                // ✅ Ubah status menjadi "booked" 
                $booking->status = $booking->payment_type === 'dp' ? 'Cannot use positional argument after named argument' : 'approved_full_payment';
                $transaction->status = $booking->payment_type === 'dp' ? 'dp_paid' : 'full_paid';
            } else {
                // ✅ Ubah status menjadi "rejected" 
                $booking->reason_rejected = $request->reason_rejected;
                $booking->status = $booking->booking_type === 'dp' ? 'rejected_dp_payment' : 'rejected_full_payment';
            }
            $confirmBooking = $request->actionBooking;

            $booking->save();
            $transaction->save();
            // 🔥 Kirim Notifikasi ke User
            Notification::send($user, new BookingAssetConfirmPayment($transaction->invoice_number, $booking, $confirmBooking, $user));


            DB::commit(); // ✅ Semua sukses, simpan ke database

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran dan Pengajuan Berkas berhasil dikonfirmasi',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    // Penanganan Pembayaran Pelunasan (Setelah DP)
    public function payInFull(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }


        $validator = Validator::make($request->all(), [
            'proof_of_payment' => 'required',
        ], [
            'proof_of_payment.required' => 'Bukti Pembayaran harus diisi.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {
            $booking = AssetBooking::findOrFail($id);
            $user = User::findOrFail($booking->user_id);
            $transaction = AssetTransaction::where('booking_id', $id)->first();


            $asset_name = Asset::where('id', $booking->asset_id)->pluck('name')->first();

            if ($request->hasFile('proof_of_payment')) {
                $file = $request->file('proof_of_payment');
                $extension = $file->getClientOriginalExtension();
                $fileName = auth()->user()->name . "-Full.{$extension}";

                // Tentukan folder penyimpanan
                $filePath = "Booking Aset BMN/{$asset_name}/Bukti Pembayaran/" . $fileName;

                // Simpan gambar ke dalam storage
                $file->storeAs("Booking Aset BMN/{$asset_name}/Bukti Pembayaran", $fileName, 'public');

                // Simpan ke transaksi
                $transaction->update([
                    'proof_of_payment' => $filePath
                ]);
            }

            $booking->update([
                'status' => 'submission_dp_payment'
            ]);
            $upt_pu = User::role('UPT PU')->get(); // Semua Tenant

            // 🔥 Kirim Notifikasi ke User
            Notification::send($upt_pu, new BookingAssetPayAndCompleteFile($booking,  $user));


            DB::commit(); // ✅ Semua sukses, simpan ke database

            return response()->json([
                'status' => 'success',
                'message' => 'Upload Bukti Pembayaran dan Berkas berhasil',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
