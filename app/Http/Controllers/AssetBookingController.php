<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Jurusan;
use App\Models\Organizer;
use Illuminate\Support\Str;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use App\Events\BookingConfirmed;
use App\Models\AssetTransaction;
use Illuminate\Support\Facades\DB;
use App\Notifications\BookingAsset;
use App\Models\AssetBookingDocument;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Notifications\BookingAssetCancelled;
use App\Notifications\BookingAssetConfirmed;
use Illuminate\Support\Facades\Notification;
use App\Exports\EksternalAssetBookingsExport;
use App\Notifications\BookingAssetConfirmPayment;
use App\Notifications\BookingAssetEventConfirmed;
use App\Notifications\BookingAssetDisposisiUploaded;
use App\Notifications\BookingAssetPayAndCompleteFile;

class AssetBookingController extends Controller
{
    public function assetBookingFasum()
    {
        $jurusans = Jurusan::all();
        $assets = Asset::where('facility_scope', 'umum')->pluck('name', 'id');
        $organizers = Organizer::with('user')->get()->pluck('user.name', 'id');
        return view('dashboardPage.assets.asset-booking.index', ['kode_jurusan' => null, 'jurusans' => $jurusans, 'organizers' => $organizers, 'assets' => $assets]);
    }

    public function assetBookingFasjur($kode_jurusan)
    {
        $jurusans = Jurusan::all();

        $assets = Asset::query();
        if (Auth::user()->hasRole('Admin Jurusan')) {
            $assets->where('facility_scope', 'jurusan')
                ->where('jurusan_id', Auth::user()->jurusan_id);
        } else {
            $assets->where('facility_scope', 'umum');
        }

        $assets = $assets->pluck('name', 'id');
        $organizers = Organizer::with('user')->get()->pluck('user.name', 'id');
        return view('dashboardPage.assets.asset-booking-event.index', ['kode_jurusan' => $kode_jurusan, 'jurusans' => $jurusans, 'assets' => $assets, 'organizers' => $organizers]);
    }

    // Untuk UPT PU (Peminjaman dari luar)
    public function getDataAssetBooking(Request $request, $kode_jurusan = null)
    {
        $statusBooking = $request->status_booking;
        $jurusan = Jurusan::where('kode_jurusan', $kode_jurusan)->first();

        // Tentukan facility_scope berdasarkan keberadaan kode_jurusan
        $scope = $kode_jurusan ? 'jurusan' : 'umum';

        // Inisialisasi query utama
        $query = AssetBooking::with(['asset', 'user', 'asset_category'])
            ->where(function ($q) {
                $q->whereNull('event_id')
                    ->orWhere('event_id', '');
            })
            ->whereHas('asset', function ($q) use ($scope, $jurusan) {
                $q->where('facility_scope', $scope);
                if ($scope === 'jurusan' && $jurusan) {
                    $q->where('jurusan_id', $jurusan->id);
                }
            });

        if (Auth::user()->hasRole('UPT PU')) {
            $query->whereNotNull('asset_category_id');
        }

        $statusCategories = [
            'submission_booking' => ['submission_booking'],
            'submission_payment' => ['submission_dp_payment', 'submission_full_payment'],
            'waiting_payment' => ['booked', 'approved_dp_payment', 'rejected_dp_payment', 'rejected_full_payment',],
            'approved' => ['approved_full_payment'],
            // 'done' => ['approved_full_payment'],
            'rejected' => ['rejected_booking',  'rejected'],
            'cancelled' => ['cancelled'],
        ];
        // Pastikan kategori yang diminta ada di daftar yang telah ditentukan
        if ($statusBooking !== 'all' && !array_key_exists($statusBooking, $statusCategories)) {
            return response()->json(['error' => 'Kategori tidak valid'], 400);
        }

        // Filter berdasarkan status_booking (kecuali 'all')
        if ($statusBooking !== 'all') {
            $query->whereIn('status', $statusCategories[$statusBooking]);

            if ($statusBooking === 'done') {
                $query->whereIn('status', $statusCategories[$statusBooking])
                    ->whereDate('usage_date_end', '<', now()); // Pastikan `usage_date_end` sudah lewat
            }
        }

        // Eksekusi query
        $assetBookings = $query->get();


        $tableId = $kode_jurusan
            ? "assetBookingFasilitasJurusan{$kode_jurusan}-Table-{$statusBooking}"
            : "assetBookingFasilitasUmumTable-{$statusBooking}";

        return DataTables::of($assetBookings)
            ->addIndexColumn()
            ->addColumn('action', function ($assetBooking) use ($tableId) {
                // Render modal konfirmasi booking dan pembayaran
                $confirmBookingModal = view('dashboardPage.assets.asset-booking.modal.confirmBooking-asset', compact('assetBooking', 'tableId'))->render();
                $confirmPaymentAndStatementLetterModal = view('dashboardPage.assets.asset-booking.modal.confirmPaymentAndStatementLetter', compact('assetBooking', 'tableId'))->render();
                $confirmFullPayment = view('dashboardPage.assets.asset-booking.modal.confirmFullPayment', compact('assetBooking', 'tableId'))->render();
                $confirmPayManual = view('dashboardPage.assets.asset-booking.modal.confirmPayManual', compact('assetBooking', 'tableId'))->render();
                $scriptCancelledBooking = view('dashboardPage.assets.asset-booking.modal.cancelled-booking', compact('assetBooking', 'tableId'))->render();
                $detailBooking = view('homepage.assets.modal.detailsAssetBooking', compact('assetBooking'))->render();
                $uploadSuratDisposisi = view('dashboardPage/assets.modal.uploadSuratDisposisi', compact('assetBooking', 'tableId'))->render();

                // Tombol aksi berdasarkan status
                $buttons = '<div class="d-flex align-items-center gap-2">';
                $modals = ''; // Untuk menampung modal agar ditambahkan di luar kondisi

                if ($assetBooking->status !== 'cancelled') {
                    // Tombol Batalkan (Selalu Tampil)
                    $buttons .= "<a class='btn btn-sm btn-outline-danger cursor-pointer' 
                        data-bs-toggle='modal' 
                        data-bs-target='#modalCancelBooking-{$assetBooking->id}'>
                        Batalkan
                    </a>";
                    // Tambahkan modal ke dalam tombol aksi
                    $buttons .= $scriptCancelledBooking;
                }
                $buttons .= "<a class='btn btn-sm btn-outline-warning cursor-pointer' data-bs-toggle='modal' data-bs-target='#modalDetailAssetBooking-{$assetBooking->id}'>Rincian</a>";
                $buttons .= $detailBooking;
                switch ($assetBooking->status) {
                    case 'submission_booking':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                                        data-bs-toggle='modal' data-bs-target='#modalConfirmAssetBooking-{$assetBooking->id}'>
                                        ✅ Konfirmasi Booking
                                    </a>";
                        $modals .= $confirmBookingModal;
                        break;

                    case 'booked':
                        $buttons .= "<a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                        data-bs-toggle='modal' data-bs-target='#confirmAssetBookingManual-{$assetBooking->id}'>
                        ✅ Konfirmasi Pembayaran
                    </a>";
                        $modals .= $confirmPayManual;
                        break;

                    case 'rejected_booking':
                        $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Booking Ulang</span>";
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
                        // $buttons .= "<span class='badge bg-success'>✔ Disetujui</span>";
                        $buttons .= "<a class='btn btn-sm btn-outline-primary cursor-pointer' 
                        data-bs-toggle='modal' 
                        data-bs-target='#modalUploadSuratDisposisi-{$assetBooking->id}'>
                        Upload Surat Disposisi
                    </a>";
                        // Tambahkan modal ke dalam tombol aksi
                        $buttons .= $uploadSuratDisposisi;
                        break;
                    case 'approved_dp_payment':
                        $buttons .= "
                        <a class='btn btn-outline-success cursor-pointer d-inline-flex align-items-center justify-content-center'
                        data-bs-toggle='modal' data-bs-target='#confirmAssetBookingManual-{$assetBooking->id}'>
                        ✅ Konfirmasi Pembayaran
                    </a>
                        <span class='badge bg-secondary'>⏳ Menunggu Pelunasan</span>";
                        $modals .= $confirmPayManual;
                        break;

                    case 'rejected_full_payment':
                        if ($assetBooking->payment_type === 'dp') {
                            $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Upload Ulang Bukti Pelunasan</span>";
                        } else {
                            $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Upload Ulang Bukti Pelunasan/Berkas</span>";
                        }
                        break;
                    case 'rejected_dp_payment':
                        $buttons .= "<span class='badge bg-secondary'>⏳ Menunggu Upload Ulang Pembayaran/Berkas</span>";
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
        if ($request->booking_type_annual) {
            $validator = Validator::make($request->all(), [
                'asset_id' => 'required|exists:assets,id',
                'usage_date_start' => 'required',
                'duration' => 'required|integer',
                'usage_event_name' => 'required',
                'amount' => 'required|numeric',
                'file_personal_identity' => 'required|mimes:pdf,jpeg,png,jpg',
                'payment_type' => 'required|in:dp,lunas',
            ], [
                'asset_id.required' => 'Aset harus diisi.',
                'asset_id.exists' => 'Aset tidak ditemukan.',

                'usage_date_start.required' => 'Tanggal penggunaan harus diisi.',

                'duration.required' => 'Durasi Sewa harus diisi.',
                'duration.integer' => 'Durasi Sewa harus berupa angka.',

                'amount.required' => 'Harga Sewa harus diisi.',
                'amount.numeric' => 'Harga Sewa harus berupa angka.',

                'usage_event_name.required' => 'Keterangan Penggunaan harus diisi.',

                'file_personal_identity.required' => 'File KTP harus diunggah.',
                'file_personal_identity.mimes' => 'Format file yang diperbolehkan: PDF, JPEG, PNG, JPG.',

                'payment_type.required' => 'Jenis pembayaran harus dipilih.',
                'payment_type.in' => 'Jenis pembayaran harus berupa DP atau Lunas.',
            ]);
        } else {

            $validator = Validator::make($request->all(), [
                'asset_id' => 'required|exists:assets,id',
                'usage_date' => 'required',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'usage_event_name' => 'required',
                'amount' => 'required|numeric',
                'file_personal_identity' => 'required|mimes:pdf,jpeg,png,jpg',
                'type_event' => 'required',
                'payment_type' => 'required|in:dp,lunas',
            ], [
                'asset_id.required' => 'Aset harus diisi.',
                'asset_id.exists' => 'Aset tidak ditemukan.',

                'usage_date.required' => 'Tanggal penggunaan harus diisi.',
                // 'usage_date.date_format' => 'Format tanggal harus dalam format Y-m-d (contoh: 2025-02-20).',

                'start_time.required' => 'Waktu mulai harus diisi.',
                'start_time.date_format' => 'Format waktu mulai harus dalam format 24 jam (HH:mm).',

                'end_time.required' => 'Waktu selesai harus diisi.',
                'end_time.date_format' => 'Format waktu selesai harus dalam format 24 jam (HH:mm).',
                'end_time.after' => 'Waktu selesai harus lebih besar dari waktu mulai.',

                'amount.required' => 'Harga Sewa harus diisi.',
                'amount.numeric' => 'Harga Sewa harus berupa angka.',

                'usage_event_name.required' => 'Nama acara harus diisi.',

                'file_personal_identity.required' => 'File KTP harus diunggah.',
                'file_personal_identity.mimes' => 'Format file yang diperbolehkan: PDF, JPEG, PNG, JPG.',

                'type_event.required' => 'Jenis acara harus dipilih.',

                'payment_type.required' => 'Jenis pembayaran harus dipilih.',
                'payment_type.in' => 'Jenis pembayaran harus berupa DP atau Lunas.',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->booking_type_annual) {
            $usageDateStartInput = $request->input('usage_date_start'); // formatnya '2025-04-18'
            $duration = (int) $request->input('duration'); // durasi dalam tahun

            // Pakai Carbon untuk manipulasi tanggal
            $usageDateStart = Carbon::parse($usageDateStartInput)->setTime(0, 1); // 00:01
            $usageDateEnd = (clone $usageDateStart)->addYears((int) $duration)->setTime(23, 59); // + durasi tahun, lalu mundur 1 menit (biar jamnya 23:59)

        } else {
            // Pastikan usage_date memiliki format yang benar
            $dates = explode(" to ", $request->usage_date);

            if (count($dates) === 2) {
                // Jika format "start to end"
                $usageDateStart = $dates[0]; // Tanggal mulai
                $usageDateEnd = $dates[1];   // Tanggal selesai
            } elseif (count($dates) === 1) {
                // Jika hanya 1 tanggal (peminjaman 1 hari)
                $usageDateStart = $dates[0];
                $usageDateEnd = $dates[0]; // Set tanggal selesai sama dengan tanggal mulai
            } else {
                return response()->json(['status' => 'error', 'message' => 'Format tanggal tidak valid.'], 400);
            }


            // Gabungkan dengan waktu
            try {
                $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $usageDateStart . ' ' . $request->start_time);
                $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $usageDateEnd . ' ' . $request->end_time);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Format tanggal/waktu tidak valid.'], 400);
            }
        }


        // **Cek apakah ada booking yang bentrok dengan waktu yang dipilih**
        $conflict = AssetBooking::where('asset_id', $request->asset_id)
            ->where(function ($query) {
                $query->where('status', '!=', 'rejected_booking')
                    ->where('status', '!=', 'rejected')
                    ->where('status', '!=', 'cancelled');
            })
            ->where(function ($query) use ($usageDateStart, $usageDateEnd) {
                $query->where(function ($q) use ($usageDateStart, $usageDateEnd) {
                    $q->where('usage_date_start', '<', $usageDateEnd)
                        ->where('usage_date_end', '>', $usageDateStart);
                });
            })->exists();
        if ($request->booking_type_annual) {
            if ($conflict) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aset ini sudah digunakan pada bulan dan tahun tersebut. Silakan pilih waktu lain.'
                ], 422);
            }
        } else {
            if ($conflict) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.'
                ], 422);
            }
        }

        $filePath = null;
        DB::beginTransaction();
        try {
            $categoryAssetAnnual = AssetCategory::where('asset_id', $request->asset_id)->first();
            $result = DB::transaction(function () use ($request, $usageDateStart, $usageDateEnd, $filePath, $categoryAssetAnnual) {
                $user = auth()->user();
                $booking = AssetBooking::create([
                    'asset_id' => $request->asset_id,
                    'user_id' => auth()->user()->id,
                    'booking_number' => '',
                    'asset_category_id' => $request->booking_type_annual ? $categoryAssetAnnual->id : $request->type_event,
                    'usage_date_start' => $usageDateStart,
                    'usage_date_end' => $usageDateEnd,
                    'usage_event_name' => $request->usage_event_name,
                    'payment_type' => $request->payment_type,
                    'total_amount' => $request->amount,
                    'status' => 'submission_booking'
                ]);

                // Pastikan relasi asset sudah dimuat
                $booking->load('asset');

                $scope = optional($booking->asset)->facility_scope == 'umum' ? 'FU' : 'FJ';
                $bookingDate = $booking->created_at->format('Ymd'); // Tanggal Booking

                // Ambil 4 karakter dari UUID dengan cara yang lebih aman
                $uuidPart1 = substr(str_replace('-', '', $booking->id), 0, 4);  // 4 karakter pertama tanpa "-"
                $uuidPart2 = substr(str_replace('-', '', $booking->id), -4); // 4 karakter terakhir tanpa "-"

                // Generate booking number
                $bookingNo = "{$scope}{$bookingDate}{$uuidPart1}{$uuidPart2}";

                // Update booking_number
                $booking->update(['booking_number' => strtoupper($bookingNo)]);
                if ($request->booking_type_annual) {
                    $booking->update([
                        'asset_category_id' => $booking->asset->categories->first()?->id,
                    ]);
                }
                if ($request->hasFile('file_personal_identity')) {

                    $asset_name = Asset::where('id', $request->asset_id)->pluck('name')->first();
                    $file = $request->file('file_personal_identity');

                    $extension = $file->getClientOriginalExtension();

                    $userName = Str::slug(auth()->user()->name);
                    $eventName = Str::slug($booking->usage_event_name); // Konversi ke format aman
                    $usageDate = date('Y-m-d', strtotime($booking->usage_date_start)); // Pastikan format tanggal benar


                    $fileName = "{$userName}-{$usageDate}-{$eventName}.{$extension}";

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
                $upt_pu = User::role('UPT PU')->get(); // Semua Tenant

                // Kirim notifikasi ke Super Admin & Tenant
                // Notification::send($superAdmins, new BookingAsset($booking, $user));
                Notification::send($upt_pu, new BookingAsset($booking, $user));
                return $booking;
            });
            DB::commit();
            if ($request->booking_type_annual) {
                notyf()->ripple(true)->success('Booking aset berhasil!');
                return redirect()->back();
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Booking aset berhasil!',
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
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

        if ($request->booking_type_annual) {
            $validator = Validator::make($request->all(), [
                'asset_id' => 'required|exists:assets,id',
                'usage_date_start' => 'required',
                'duration' => 'required|integer',
                'usage_event_name' => 'required',
                'amount' => 'required|numeric',
                'file_personal_identity' => 'mimes:pdf,jpeg,png,jpg',
                'payment_type' => 'required|in:dp,lunas',
            ], [
                'asset_id.required' => 'Aset harus diisi.',
                'asset_id.exists' => 'Aset tidak ditemukan.',

                'usage_date_start.required' => 'Tanggal penggunaan harus diisi.',

                'duration.required' => 'Durasi Sewa harus diisi.',
                'duration.integer' => 'Durasi Sewa harus berupa angka.',

                'amount.required' => 'Harga Sewa harus diisi.',
                'amount.numeric' => 'Harga Sewa harus berupa angka.',

                'usage_event_name.required' => 'Keterangan Penggunaan harus diisi.',

                // 'file_personal_identity.required' => 'File KTP harus diunggah.',
                'file_personal_identity.mimes' => 'Format file yang diperbolehkan: PDF, JPEG, PNG, JPG.',

                'payment_type.required' => 'Jenis pembayaran harus dipilih.',
                'payment_type.in' => 'Jenis pembayaran harus berupa DP atau Lunas.',
            ]);
        } else {
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
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }


        try {
            if ($request->booking_type_annual) {
                // Booking tahunan: gunakan tanggal request, dengan waktu full hari (00:00 - 23:59)
                $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date_start . ' 00:00');
                $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date_start . ' 23:59');
            } else {
                // Booking harian: gunakan tanggal dan waktu dari request
                $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date . ' ' . $request->start_time);
                $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->usage_date . ' ' . $request->end_time);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format tanggal/waktu tidak valid.'
            ], 400);
        }


        // **Cek apakah ada booking yang bentrok dengan waktu yang dipilih**
        $conflict = AssetBooking::where('asset_id', $request->asset_id)
            ->where(function ($query) {
                $query->where('status', '!=', 'rejected_booking')
                    ->where('status', '!=', 'rejected')
                    ->where('status', '!=', 'cancelled');
            })
            ->where(function ($query) use ($usageDateStart, $usageDateEnd) {
                $query->where(function ($q) use ($usageDateStart, $usageDateEnd) {
                    $q->where('usage_date_start', '<', $usageDateEnd)
                        ->where('usage_date_end', '>', $usageDateStart);
                });
            })
            ->where('id', '!==', $id)->exists();
        if ($request->booking_type_annual) {
            if ($conflict) {
                notyf()->ripple(true)->error('Aset ini sudah digunakan pada bulan dan tahun tersebut. Silakan pilih waktu lain.');
                return redirect()->back();
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'Aset ini sudah digunakan pada bulan dan tahun tersebut. Silakan pilih waktu lain.'
                // ], 422);
            }
        } else {
            if ($conflict) {
                notyf()->ripple(true)->error('Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.');
                return redirect()->back();
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'Aset ini sudah dipesan pada tanggal dan waktu tersebut. Silakan pilih waktu lain.'
                // ], 422);
            }
        }

        $filePath = null;
        try {
            $result = DB::transaction(function () use ($request, $id, $usageDateStart, $usageDateEnd, $filePath) {
                $user = auth()->user();
                $booking = AssetBooking::findOrFail($id);
                if ($request->booking_type_annual) {
                    $booking->update([
                        'usage_date_start' => $usageDateStart,
                        'usage_date_end' => $usageDateEnd,
                        'usage_event_name' => $request->usage_event_name,
                        'payment_type' => $request->payment_type,
                        'total_amount' => $request->amount,
                        'status' => 'submission_booking',
                        'reason' => NULL
                    ]);
                } else {
                    $booking->update([
                        'asset_category_id' => $request->type_event,
                        'usage_date_start' => $usageDateStart,
                        'usage_date_end' => $usageDateEnd,
                        'usage_event_name' => $request->usage_event_name,
                        'payment_type' => $request->payment_type,
                        'total_amount' => $request->amount,
                        'status' => 'submission_booking',
                        'reason' => NULL
                    ]);
                }

                if ($request->hasFile('file_personal_identity')) {

                    $asset_name = Asset::where('id', $request->asset_id)->pluck('name')->first();
                    $file = $request->file('file_personal_identity');

                    $extension = $file->getClientOriginalExtension();

                    $userName = Str::slug(auth()->user()->name);
                    $eventName = Str::slug($booking->usage_event_name); // Konversi ke format aman
                    $usageDate = date('Y-m-d', strtotime($booking->usage_date_start)); // Pastikan format tanggal benar


                    $fileName = "{$userName}-{$usageDate}-{$eventName}.{$extension}";

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
            notyf()->ripple(true)->success('Rebooking aset berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            notyf()->ripple(true)->error('Terjadi kesalahan saat rebooking aset.');
            return redirect()->back();
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Terjadi kesalahan saat rebooking aset.',
            //     'error' => $e->getMessage()
            // ], 500);
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

            $booking = AssetBooking::with(['asset'])->findOrFail($id);
            $user = User::findOrFail($booking->user_id);
            if ($request->actionBooking === 'approved') {
                $paymentAmount = ($booking->payment_type === 'dp') ? $booking->total_amount * 0.3 : $booking->total_amount;
                $vaNumber = $request->va_number;
                $vaExpiredDate = $request->va_expiry;



                // ✅ Ubah status menjadi "booked" 
                $booking->status = 'booked';
                AssetTransaction::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'amount' => $paymentAmount,
                    'va_number' => $vaNumber,
                    'va_expiry' => $vaExpiredDate,
                    'tax' => '10',
                    'status' => 'pending',
                ]);
            } else {
                // ✅ Ubah status menjadi "rejected" 
                $booking->reason = $request->reason_rejected;
                $booking->status = 'rejected_booking';
            }
            $confirmBooking = $request->actionBooking;

            $booking->save();
            // 🔥 Kirim Notifikasi ke User
            Notification::send($user, new BookingAssetConfirmed($booking, $confirmBooking, Auth::user(), $vaNumber, $paymentAmount, $vaExpiredDate));


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
                'loan_form' => 'Form Peminjaman',
                'contract_agreement_letter' => 'Surat Perjanjian Kontrak',
            ];

            $asset_name = Asset::where('id', $booking->asset_id)->pluck('name')->first();
            foreach ($documents as $field => $documentName) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->getClientOriginalExtension();
                    $userName = Str::slug(auth()->user()->name); // Konversi ke format aman
                    $eventName = Str::slug($booking->usage_event_name); // Konversi ke format aman
                    $usageDate = date('Y-m-d', strtotime($booking->usage_date_start)); // Pastikan format tanggal benar

                    if ($booking->payment_type === 'dp') {
                        $fileName = "{$userName}-{$usageDate}-{$eventName}-DP.{$extension}";
                    } else {
                        $fileName = "{$userName}-{$usageDate}-{$eventName}-Full.{$extension}";
                    }

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
                            'document_type' => $documentName,
                            'uploaded_by' => auth()->user()->id,
                        ]);
                    }
                }
            }
            $booking->update([
                'status' => $booking->payment_type === 'dp' ? 'submission_dp_payment' : 'submission_full_payment',
                'reason' => NULL
            ]);
            $upt_pu = User::role('UPT PU')->get(); // Semua Tenant

            // 🔥 Kirim Notifikasi ke User
            Notification::send($upt_pu, new BookingAssetPayAndCompleteFile($booking,  $user));


            DB::commit(); // ✅ Semua sukses, simpan ke database
            notyf()->ripple(true)->success('Pengajuan Bukti Pembayaran dan Berkas berhasil.');
            return redirect()->back();
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Pengajuan Bukti Pembayaran dan Berkas berhasil.',
            // ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            notyf()->ripple(true)->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            // ], 500);
        }
    }


    public function confirmPayment(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }

        $booking = AssetBooking::with(['asset'])->findOrFail($id);
        $validator = null;
        if ($request->actionConfirmPayment !== 'approved') {

            $validator = Validator::make($request->all(), [
                'reason_rejected' => 'required',
            ], [
                'reason_rejected.required' => 'Alasan Penolakan harus diisi.',
            ]);
        } else {
            if ($booking->payment_type == 'dp' && $booking->status == 'submission_dp_payment') {
                if ($booking->asset->booking_type == 'daily' && $booking->asset->type == 'building') {
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
                        'unloading_date_start' => [
                            'required',
                            'date_format:Y-m-d H:i',
                            // 'after:loading_date_end'
                        ],
                        'va_number' => [
                            'required',
                        ],
                        'va_expiry' => [
                            'required',
                        ],
                    ], [
                        'loading_date_start.required' => 'Tanggal dan waktu mulai loading harus diisi.',
                        'loading_date_start.date_format' => 'Format tanggal dan waktu mulai loading tidak valid.',
                        'loading_date_start.after_or_equal' => 'Tanggal mulai loading tidak boleh kurang dari hari ini.',

                        'loading_date_end.required' => 'Tanggal dan waktu selesai loading harus diisi.',
                        'loading_date_end.date_format' => 'Format tanggal dan waktu selesai loading tidak valid.',
                        'loading_date_end.after' => 'Waktu selesai loading harus setelah waktu mulai loading.',

                        'unloading_date_start.required' => 'Tanggal dan waktu unloading start harus diisi.',
                        'unloading start_date_start.date_format' => 'Format tanggal dan waktu unloading start tidak valid.',

                        'va_number.required' => 'Nomor Virtual Account harus diisi.',
                        'va_expiry.required' => 'Tanggal Kadaluarsa Nomor Virtual Account harus diisi.',
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'va_number' => [
                            'required',
                        ],
                        'va_expiry' => [
                            'required',
                        ],
                    ], [
                        'va_number.required' => 'Nomor Virtual Account harus diisi.',
                        'va_expiry.required' => 'Tanggal Kadaluarsa Nomor Virtual Account harus diisi.',
                    ]);
                }
            }
        }
        if ($validator && $validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {
            $user = User::findOrFail($booking->user_id);
            $successMessage = '';
            if ($request->actionConfirmPayment === 'approved') {
                $paymentAmount = $booking->total_amount * 0.7;
                $vaNumber = $request->va_number;
                $vaExpiredDate = $request->va_expiry;
                // Tipe pembayaran DP
                if ($booking->status === 'submission_dp_payment') {
                    $transaction = AssetTransaction::where('booking_id', $id)->where('status', 'pending')->first();
                    $transaction->status = 'dp_paid';

                    $booking->status = 'approved_dp_payment';

                    AssetTransaction::create([
                        'booking_id' => $booking->id,
                        'user_id' => $booking->user_id,
                        'amount' => $paymentAmount,
                        'va_number' => $vaNumber,
                        'va_expiry' => $vaExpiredDate,
                        'tax' => '10',
                        'status' => 'pending',
                    ]);

                    $successMessage = 'Pembayaran berhasil dikonfirmasi';
                    // Tipe pembayaran lunas
                } else {
                    $booking->status = 'approved_full_payment';
                    $transaction = AssetTransaction::where('booking_id', $id)->where('status', 'pending')->first();

                    $transaction->status = 'full_paid';

                    $successMessage = 'Pembayaran dan Pengajuan Berkas berhasil dikonfirmasi';
                }

                $transaction->save();

                $booking->update([
                    'reason' => '',
                    'loading_date_start' => $request->loading_date_start ?? null,
                    'loading_date_end' => $request->loading_date_end ?? null,
                    'unloading_date_start' => $request->unloading_date_start ?? null,
                    'unloading_date_end' => $request->unloading_date_end ?? null,
                ]);
            } else {
                // ✅ Ubah status menjadi "rejected" 
                $booking->reason = $request->reason_rejected;
                $booking->status = $booking->status === 'submission_full_payment' ? 'rejected_full_payment' : 'rejected_dp_payment';
                $successMessage = 'Pembayaran dan Pengajuan Berkas berhasil dikonfirmasi';
            }
            $confirmBooking = $request->actionConfirmPayment;

            $booking->save();
            // 🔥 Kirim Notifikasi ke User
            Notification::send($user, new BookingAssetConfirmPayment($booking->booking_number, $booking, $confirmBooking, Auth::user()));


            DB::commit(); // ✅ Semua sukses, simpan ke database

            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
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
            $transaction = AssetTransaction::where('booking_id', $id)->where('status', 'pending')->first();


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
                'status' => 'submission_full_payment',
                'reason' => NULL
            ]);
            $upt_pu = User::role('UPT PU')->get(); // Semua Tenant

            // 🔥 Kirim Notifikasi ke User
            Notification::send($upt_pu, new BookingAssetPayAndCompleteFile($booking,  $user));


            DB::commit(); // ✅ Semua sukses, simpan ke database

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Upload Bukti Pembayaran dan Berkas berhasil',
            // ]);
            notyf()->ripple(true)->success('Upload Bukti Pembayaran dan Berkas berhasil.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            // ], 500);
            notyf()->ripple(true)->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function cancelledBooking(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }


        $validator = Validator::make($request->all(), [
            'reason' => 'required',
        ], [
            'reason.required' => 'Alasan pembatalan harus diisi.',
        ]);

        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {
            $assetBooking = AssetBooking::findOrFail($id);


            $userbooking = null;
            if ($assetBooking->user_id) {
                $userbooking = User::findOrFail($assetBooking->user_id);
            }
            // Ambil user yang sedang login
            $user = Auth::user();
            $userRole = $user->getRoleNames()->first(); // Ambil nama role

            // Siapkan penerima notifikasi
            $userReceived = collect(); // Inisialisasi collection kosong
            if ($userbooking) {
                if ($userRole === 'Tenant') {
                    if ($assetBooking->facility_scope === 'jurusan') {
                        // Ambil admin jurusan yang sesuai dengan jurusan aset
                        $jurusan = Jurusan::findOrFail($assetBooking->asset->jurusan_id);
                        $userReceived = User::role('Admin Jurusan')
                            ->where('jurusan_id', $jurusan->id)
                            ->get();
                    } else {
                        // Jika bukan scope jurusan, kirim ke UPT PU
                        $userReceived = User::role('UPT PU')->get();
                    }
                } elseif ($userRole === 'Organizer') {
                    if ($assetBooking->facility_scope === 'jurusan') {
                        // Ambil admin jurusan yang sesuai dengan jurusan aset
                        $jurusan = Jurusan::findOrFail($assetBooking->asset->jurusan_id);
                        $userReceived = User::role('Admin Jurusan')
                            ->where('jurusan_id', $jurusan->id)
                            ->get();
                    } else {
                        // Jika bukan scope jurusan, kirim ke Kaur RT
                        $userReceived = User::role('Kaur RT')->get();
                    }
                } else {
                    // Jika yang membatalkan bukan Tenant dan Organizer, kirim notifikasi ke Tenant  atau Organizer yang bersangkutan
                    $userReceived = User::where('id', $assetBooking->user_id)->get();
                }
            }
            $assetBooking->reason = $request->reason;
            // Kirim notifikasi jika ada penerima
            if ($userReceived->isNotEmpty()) {
                Notification::send($userReceived, new BookingAssetCancelled($assetBooking, Auth::user(), $userbooking));
            }
            $assetBooking->status = 'cancelled';

            $assetBooking->save();

            DB::commit(); // ✅ Simpan perubahan jika berhasil
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Booking aset berhasil dibatalkan!',
            // ]);
            notyf()->ripple(true)->success('Booking aset berhasil dibatalkan!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            // ], 500);
            notyf()->ripple(true)->error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    public function cancelledAssetBookingEvent(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }


        $validator = Validator::make($request->all(), [
            'reason' => 'required',
        ], [
            'reason.required' => 'Alasan pembatalan harus diisi.',
        ]);

        DB::beginTransaction(); // ⏳ Mulai Transaksi
        try {
            $assetBooking = AssetBooking::findOrFail($id);


            // Ambil user yang sedang login
            $userbooking = User::findOrFail($assetBooking->user_id);

            $user = Auth::user();
            $userRole = $user->getRoleNames()->first(); // Ambil nama role

            // Siapkan penerima notifikasi
            $userReceived = collect(); // Inisialisasi collection kosong

            if ($userRole === 'Tenant') {
                if ($assetBooking->facility_scope === 'jurusan') {
                    // Ambil admin jurusan yang sesuai dengan jurusan aset
                    $jurusan = Jurusan::findOrFail($assetBooking->asset->jurusan_id);
                    $userReceived = User::role('Admin Jurusan')
                        ->where('jurusan_id', $jurusan->id)
                        ->get();
                } else {
                    // Jika bukan scope jurusan, kirim ke UPT PU
                    $userReceived = User::role('UPT PU')->get();
                }
            } else {
                // Jika yang membatalkan bukan Tenant, kirim notifikasi ke Tenant yang bersangkutan
                $userReceived = User::where('id', $assetBooking->user_id)->get();
            }
            $assetBooking->reason = $request->reason;
            // Kirim notifikasi jika ada penerima
            if ($userReceived->isNotEmpty()) {
                Notification::send($userReceived, new BookingAssetCancelled($assetBooking, Auth::user(), $userbooking));
            }
            $assetBooking->status = 'cancelled';

            $assetBooking->save();

            DB::commit(); // ✅ Simpan perubahan jika berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Booking aset berhasil dibatalkan!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDataAssetCategory($id)
    {
        $asset = Asset::with('categories')->findOrFail($id);

        $categories = $asset->categories;

        return response()->json($categories);
    }
    public function assetBookingManualEksternal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'external_user' => 'required',
            'usage_event_name' => 'required',
            'asset_id' => 'required',
            'asset_category_id' => 'required',
            'price_type' => 'required',
            'total_amount' => 'required',
            'event_date' => 'required|date',
            'event_time_start' => 'required|date_format:H:i',
            'event_time_end' => 'required|date_format:H:i',
            'status' => 'required|in:booked,approved_full_payment',
            'payment_type' => 'required',
            'loading_date' => 'required_if:status,approved_full_payment|date',
            'loading_time_start' => 'required_if:status,approved_full_payment|date_format:H:i',
            'loading_time_end' => 'required_if:status,approved_full_payment|date_format:H:i',
            'unloading_date' => 'required_if:status,approved_full_payment|date',
            'unloading_time_start' => 'required_if:status,approved_full_payment|date_format:H:i',
            'unloading_time_end' => 'required_if:status,approved_full_payment|date_format:H:i',
            'proof_of_payment' => 'required_if:status,approved_full_payment'

        ], [
            'external_user.required' => 'Nama peminjam harus diisi.',
            'usage_event_name.required' => 'Nama event harus diisi.',
            'asset_id.required' => 'Aset harus dipilih.',
            'asset_category_id.required' => 'Kategori booking harus dipilih.',
            'price_type.required' => 'Kategori Tarif harus diisi.',
            'total_amount.required' => 'Harga harus diisi.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status harus berupa booked atau approved_full_payment.',
            'payment_type.required' => 'Tipe pembayaran harus dipilih.',

            'event_date.required' => 'Tanggal Event harus diisi.',
            'event_date.date' => 'Format Tanggal Event salah.',
            'event_time_start.required' => 'Jam mulai event harus diisi.',
            'event_time_start.date_format' => 'Format jam mulai event harus H:i (contoh: 16:00).',
            'event_time_end.required' => 'Jam selesai event harus diisi.',
            'event_time_end.date_format' => 'Format jam selesai event harus H:i (contoh: 16:00).',

            'loading_date.required_if' => 'Tanggal Loading harus diisi.',
            'loading_date.date' => 'Format Tanggal Loading salah.',
            'loading_time_start.required_if' => 'Jam mulai loading harus diisi.',
            'loading_time_start.date_format' => 'Format jam mulai loading harus H:i (contoh: 16:00).',
            'loading_time_end.required_if' => 'Jam selesai loading harus diisi.',
            'loading_time_end.date_format' => 'Format jam selesai loading harus H:i (contoh: 16:00).',

            'unloading_date.required_if' => 'Tanggal Unloading harus diisi.',
            'unloading_date.date' => 'Format Tanggal Unloading salah.',
            'unloading_time_start.required_if' => 'Jam mulai unloading harus diisi.',
            'unloading_time_start.date_format' => 'Format jam mulai unloading harus H:i (contoh: 16:00).',
            'unloading_time_end.required_if' => 'Jam selesai unloading harus diisi.',
            'unloading_time_end.date_format' => 'Format jam selesai unloading harus H:i (contoh: 16:00).',
            'proof_of_payment.required_if' => "Bukti Pembayaran harus diisi, karena Anda memilih status peminjaman disetujui."

        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Ambil error pertama dari validasi
            notyf()->ripple(true)->error($firstError);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {


            $usageDateStart = $usageDateEnd = $loadingDateStart = $loadingDateEnd = $unloadingDateStart = $unloadingDateEnd = null;

            if ($request->filled(['event_date', 'event_time_start', 'event_time_end'])) {
                $usageDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->event_date . ' ' . $request->event_time_start);
                $usageDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->event_date . ' ' . $request->event_time_end);
            }

            if ($request->filled(['loading_date', 'loading_time_start', 'loading_time_end'])) {
                $loadingDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->loading_date . ' ' . $request->loading_time_start);
                $loadingDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->loading_date . ' ' . $request->loading_time_end);
            }

            if ($request->filled(['unloading_date', 'unloading_time_start', 'unloading_time_end'])) {
                $unloadingDateStart = Carbon::createFromFormat('Y-m-d H:i', $request->unloading_date . ' ' . $request->unloading_time_start);
                $unloadingDateEnd = Carbon::createFromFormat('Y-m-d H:i', $request->unloading_date . ' ' . $request->unloading_time_end);
            }

            $conflicts = AssetBooking::where('asset_id', $request->asset_id)
                ->where(function ($query) {
                    $query->where('status', 'NOT LIKE', 'submission%')
                        ->where('status', '!=', 'rejected_booking')
                        ->where('status', '!=', 'rejected')
                        ->where('status', '!=', 'cancelled');
                })
                ->where(function ($query) use (
                    $usageDateStart,
                    $usageDateEnd,
                    $loadingDateStart,
                    $loadingDateEnd,
                    $unloadingDateStart,
                    $unloadingDateEnd,
                ) {
                    if ($usageDateStart && $usageDateEnd) {
                        $query->orWhere(function ($q) use ($usageDateStart, $usageDateEnd) {
                            $q->where('usage_date_start', '<', $usageDateEnd)
                                ->where('usage_date_end', '>', $usageDateStart);
                        });
                    }

                    if ($loadingDateStart && $loadingDateEnd) {
                        $query->orWhere(function ($q) use ($loadingDateStart, $loadingDateEnd) {
                            $q->where('loading_date_start', '<', $loadingDateEnd)
                                ->where('loading_date_end', '>', $loadingDateStart);
                        });
                    }

                    if ($unloadingDateStart && $unloadingDateEnd) {
                        $query->orWhere(function ($q) use ($unloadingDateStart, $unloadingDateEnd) {
                            $q->where('unloading_date_start', '<', $unloadingDateEnd)
                                ->where('unloading_date_end', '>', $unloadingDateStart);
                        });
                    }
                })->get();


            if ($conflicts->isNotEmpty()) {
                $conflictMessages = [];

                foreach ($conflicts as $conflict) {
                    if (
                        $conflict->usage_date_start < $usageDateEnd &&
                        $conflict->usage_date_end > $usageDateStart
                    ) {
                        $conflictMessages[] = 'penggunaan';
                    }

                    if (
                        $conflict->loading_date_start < $loadingDateEnd &&
                        $conflict->loading_date_end > $loadingDateStart
                    ) {
                        $conflictMessages[] = 'loading';
                    }

                    if (
                        $conflict->unloading_date_start < $unloadingDateEnd &&
                        $conflict->unloading_date_end > $unloadingDateStart
                    ) {
                        $conflictMessages[] = 'unloading';
                    }
                }

                // Unikkan dan gabungkan
                $conflictMessages = array_unique($conflictMessages);
                $conflictText = implode(', ', $conflictMessages);

                notyf()->ripple(true)->error("Aset ini sudah dipesan di waktu $conflictText. Silakan ubah tanggalnya.");
                return redirect()->back()->withInput();
            }

            $booking = AssetBooking::create([
                'asset_id' => $request->asset_id,
                'external_user' => $request->external_user,
                'booking_number' => '',
                'asset_category_id' => $request->asset_category_id,
                'usage_date_start' => $usageDateStart,
                'usage_date_end' => $usageDateEnd,
                'loading_date_start' => $loadingDateStart ?? null,
                'loading_date_end' => $loadingDateEnd ?? null,
                'unloading_date_start' => $unloadingDateStart ?? null,
                'unloading_date_end' => $unloadingDateEnd ?? null,
                'usage_event_name' => $request->usage_event_name,
                'payment_type' => $request->payment_type,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
            ]);


            $scope = optional($booking->asset)->facility_scope == 'umum' ? 'FU' : 'FJ';
            $bookingDate = $booking->created_at->format('Ymd'); // Tanggal Booking

            // Ambil 4 karakter dari UUID dengan cara yang lebih aman
            $uuidPart1 = substr(str_replace('-', '', $booking->id), 0, 4);  // 4 karakter pertama tanpa "-"
            $uuidPart2 = substr(str_replace('-', '', $booking->id), -4); // 4 karakter terakhir tanpa "-"

            // Generate booking number
            $bookingNo = "{$scope}{$bookingDate}{$uuidPart1}{$uuidPart2}";

            // Update booking_number
            $booking->update(['booking_number' => strtoupper($bookingNo)]);

            $amount = '';

            if ($booking->payment_type == 'dp' && $booking->status == 'booked') {
                $amount = $request->total_amount * 30 / 100;
            } elseif ($booking->payment_type == 'dp' && $booking->status == 'approved_dp_payment') {
                $amount = $request->total_amount * 70 / 100;
            } elseif ($booking->payment_type == 'lunas') {
                $amount = $request->total_amount;
            }


            $asset_name = Asset::where('id', $booking->asset_id)->pluck('name')->first();
            if ($booking->status == 'booked' && $request->payment_type == 'dp') {
                if ($request->hasFile('proof_of_payment')) {

                    $file = $request->file('proof_of_payment');
                    $extension = $file->getClientOriginalExtension();

                    $fileName = $booking->external_user . "-DP.{$extension}";
                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/Bukti Pembayaran/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/Bukti Pembayaran", $fileName, 'public');
                    // Simpan ke transaksi
                    AssetTransaction::create(
                        [
                            'booking_id' => $booking->id,
                            'amount' => $amount,
                            'proof_of_payment' => $filePath,
                            'status' => 'dp_paid',
                        ]
                    );
                } else {
                    AssetTransaction::create(
                        [
                            'booking_id' => $booking->id,
                            'amount' => $amount,
                            'status' => 'pending',
                        ]
                    );
                }
            } elseif ($booking->status == 'approved_full_payment' && $request->payment_type == 'lunas') {
                if ($request->hasFile('proof_of_payment')) {

                    $file = $request->file('proof_of_payment');
                    $extension = $file->getClientOriginalExtension();

                    $fileName = $booking->external_user . "-Full.{$extension}";
                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/Bukti Pembayaran/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/Bukti Pembayaran", $fileName, 'public');
                    // Simpan ke transaksi
                    AssetTransaction::create(
                        [
                            'booking_id' => $booking->id,
                            'amount' => $amount,
                            'proof_of_payment' => $filePath,
                            'status' => 'full_paid',
                        ]
                    );
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

    public function confirmPaymentEksternal(Request $request, $id)
    {

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

            $asset_name = Asset::where('id', $booking->asset_id)->pluck('name')->first();
            $transaction =  AssetTransaction::where('booking_id', $booking->id)->first();

            if ($request->payment_type == 'dp' && $transaction->status == 'pending') {
                if ($request->hasFile('proof_of_payment')) {
                    $file = $request->file('proof_of_payment');
                    $extension = $file->getClientOriginalExtension();

                    $fileName = $booking->external_user . "-DP.{$extension}";
                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/Bukti Pembayaran/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/Bukti Pembayaran", $fileName, 'public');
                    // Simpan ke transaksi
                    $transaction->update([
                        'proof_of_payment' => $filePath,
                        'status' => 'dp_paid'
                    ]);
                }
                $booking->update([
                    'status' => 'approved_dp_payment'
                ]);
            } elseif ($request->payment_type == 'lunas' && $transaction->status == 'pending') {

                if ($request->hasFile('proof_of_payment')) {
                    $file = $request->file('proof_of_payment');
                    $extension = $file->getClientOriginalExtension();

                    $fileName = $booking->external_user . "-Full.{$extension}";
                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/Bukti Pembayaran/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/Bukti Pembayaran", $fileName, 'public');
                    // Simpan ke transaksi
                    $transaction->update([
                        'proof_of_payment' => $filePath,
                        'amount' => $booking->total_amount,
                        'status' => 'full_paid'
                    ]);
                }
                $booking->update([
                    'status' => 'approved_full_payment'
                ]);
            }

            if (!$request->payment_type && $transaction->status == 'dp_paid') {

                $totalAmount = $booking->total_amount * 70 / 100;
                if ($request->hasFile('proof_of_payment')) {
                    $file = $request->file('proof_of_payment');
                    $extension = $file->getClientOriginalExtension();

                    $fileName = $booking->external_user . "-Full.{$extension}";
                    // Tentukan folder penyimpanan
                    $filePath = "Booking Aset BMN/{$asset_name}/Bukti Pembayaran/" . $fileName;

                    // Simpan gambar ke dalam storage
                    $file->storeAs("Booking Aset BMN/{$asset_name}/Bukti Pembayaran", $fileName, 'public');
                    // Simpan ke transaksi
                    AssetTransaction::create([
                        'booking_id' => $booking->id,
                        'amount' => $totalAmount,
                        'proof_of_payment' => $filePath,
                        'status' => 'full_paid'
                    ]);
                }
                $booking->update([
                    'status' => 'approved_full_payment'
                ]);
            }


            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Upload Bukti Pembayaran dan Konfirmasi berhasil',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function uploadSuratDisposisi(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile('surat_disposisi')) {

                $file = $request->file('surat_disposisi');
                $extension = $file->getClientOriginalExtension();
                if ($request->type_id === 'event_id') {
                    $booking = AssetBooking::with('event', 'user')->where('event_id', $id)->first();
                    $fileName = $booking->user->organizer->shorten_name . ".{$extension}";
                    $eventId = $booking->event->id;
                } else {
                    $booking = AssetBooking::findOrFail($id);
                    $fileName = $booking->user->name ?? $booking->user->external_user . ".{$extension}";
                }

                $asset_name = $booking->asset->name;
                // Tentukan folder penyimpanan
                $filePath = "Booking Aset BMN/{$asset_name}/Surat Disposisi/" . $fileName;

                // Hapus file lama jika ada
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                // Simpan gambar ke dalam storage
                $file->storeAs("Booking Aset BMN/{$asset_name}/Surat Disposisi", $fileName, 'public');
                // Simpan ke transaksi
                AssetBookingDocument::create([
                    'booking_id' => $request->type_id === 'event_id' ?  null : $booking->id,
                    'event_id' => $request->type_id === 'event_id' ? $eventId : null,
                    'document_path' => $filePath,
                    'document_type' => 'Surat Disposisi',
                    'uploaded_by' => Auth::user()->id
                ]);
            }
            $userReceiver = $booking->user;

            // Nama peminjam
            $event_id = $booking->event_id ?? '';
            // Kirim notifikasi
            Notification::send($userReceiver, new BookingAssetDisposisiUploaded(Auth::user(), $asset_name, $event_id));

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Upload Surat Disposisi berhasil',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Jika ada error, batalkan semua perubahan
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ExportReport(Request $request)
    {
        $year = $request->input('year');

        $yearStart = Carbon::parse("$year-01-01")->startOfDay();
        $yearEnd = Carbon::parse("$year-12-31")->endOfDay();

        return Excel::download(
            new EksternalAssetBookingsExport($yearStart, $yearEnd),
            'Rekapan Booking Eksternal ' . ' - ' . $year . '.xlsx'
        );
    }

    public function bookingLetter($id)
    {
        $assetBooking = AssetBooking::findOrFail($id);

        return view('homepage/assets/booking_letter', compact('assetBooking'));
    }
}
