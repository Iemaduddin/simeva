<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile($id)
    {
        if ($id !== auth()->id()) {
            abort(403, 'Unauthorized access'); // Kembalikan error jika bukan pemilik profil
        }
        $myAsset = AssetBooking::where('user_id', $id)->with('asset', 'user', 'asset_category')->get();

        $user = User::findOrFail($id);
        return view('homepage.profile', compact('user', 'myAsset'));
    }

    public function updateProfile(Request $request, $id)
    {
        // Ambil data user yang ingin diupdate
        $user = User::findOrFail($id);
        $rules = [
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'province' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'village' => 'required',
            'address' => 'required',
            'profile_picture' => 'image|mimes:jpeg,png,jpg',
        ];

        if ($request->username !== $user->username) {
            $rules['username'] = 'required|unique:users,username';
        } else {
            $rules['username'] = 'required';
        }
        if ($request->email !== $user->email) {
            $rules['email'] = 'required|email|unique:users,email';
        } else {
            $rules['email'] = 'required|email';
        }
        // Validasi input
        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'phone_number.required' => 'Nomor HP harus diisi.',
            'phone_number.numeric' => 'Nomor HP harus mengandung angka 0-9.',
            'province.required' => 'Asal Provinsi harus diisi.',
            'city.required' => 'Asal Kabupaten/Kota harus diisi.',
            'subdistrict.required' => 'Asal Kecamatan harus diisi.',
            'village.required' => 'Asal Kelurahan/Desa harus diisi.',
            'address.required' => 'Alamat Lengkap harus diisi.',
            'profile_picture.image' => 'Foto harus harus berupa file gambar.',
            'profile_picture.mimes' => 'Foto harus harus memiliki format: .jpeg, .png, .jpg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        // Validasi input

        try {

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }


            $filePathTenant = null;
            $filePathParticipant = null;
            if ($request->hasFile('profile_picture')) {

                $file = $request->file('profile_picture');

                // Hapus profile_picture lama jika ada
                if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                    Storage::delete('public/' . $user->profile_picture);
                }

                // Simpan file baru
                $extension = $file->getClientOriginalExtension();
                $fileName =  $request->nim . '_' . $request->name . '.' . $extension;
                $filePathTenant = "Foto Profil/Tenant/{$fileName}";
                $filePathParticipant = "Foto Profil/Participant/{$fileName}";
                if ($user->hasRole('Tenant')) {
                    $file->storeAs("Foto Profil/Tenant/", $fileName, 'public');
                    $user->update(['profile_picture' => $filePathTenant]);
                } elseif ($user->hasRole('Participant')) {
                    $file->storeAs("Foto Profil/Participant/", $fileName, 'public');
                    $user->update(['profile_picture' => $filePathParticipant]);
                }
            }
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'category_user' => 'eksternal_kampus',
                'phone_number' => $request->phone_number,
                'province' => $request->province,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'village' => $request->village,
                'address' => $request->address,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Memperbarui Data Profil berhasil!',
                'profile_picture' => $user->profile_picture,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data profil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myAssetBooking($id)
    {
        if ($id !== auth()->id()) {
            abort(403, 'Unauthorized access'); // Kembalikan error jika bukan pemilik profil
        }
        $myAsset = AssetBooking::where('user_id', $id)->with('asset', 'user', 'asset_category')->get();

        $user = User::findOrFail($id);
        return view('homepage.assets.my_asset_booking', compact('user', 'myAsset'));
    }

    public function getDataMyAssetBooking(Request $request, $id)
    {
        $status_booking = $request->status_booking;
        // Definisi kategori status berdasarkan array grouping
        $statusCategories = [
            'waiting_booking' => ['submission_booking'],
            'waiting_payment' => ['submission_dp_payment', 'submission_full_payment'],
            'payment' => ['booked', 'approved_dp_payment'],
            'approved' => ['approved_full_payment'],
            'done' => ['approved_full_payment'],
            'rejected' => ['rejected_booking', 'rejected_dp_payment', 'rejected_full_payment', 'rejected'],
            'cancelled' => ['cancelled'],
        ];

        // Pastikan kategori yang diminta ada di daftar yang telah ditentukan
        if (!array_key_exists($status_booking, $statusCategories)) {
            return response()->json(['error' => 'Kategori tidak valid'], 400);
        }

        // Ambil data berdasarkan status dalam kategori
        $query = AssetBooking::with('asset')
            ->whereIn('status', $statusCategories[$status_booking])->get();

        return DataTables::of($query)
            ->addColumn('nama_aset', function ($assetBooking) {
                $assetImages = json_decode($assetBooking->asset->asset_images, true);
                $imageUrl = isset($assetImages[0]) ? asset('storage/' . $assetImages[0]) : '';

                return '
                <div class="d-flex align-items-center gap-24">
                    <div class="w-60 h-60 border border-neutral-40 rounded-8 d-flex justify-content-center align-items-center bg-white">
                        <img src="' . $imageUrl . '" alt="" width="50">
                    </div>
                    <div>
                        <h6 class="text-md mb-12">' . optional($assetBooking->asset)->name . '</h6>
                        <div class="d-flex align-items-center gap-16">
                            <div class="d-flex align-items-center gap-4">
                                <span class="text-xs text-neutral-500">Event:</span>
                                <span class="p-5 border border-neutral-40 bg-white rounded-4 text-sm text-neutral-500">
                                    ' . $assetBooking->usage_event_name . '
                                </span>
                            </div>
                        </div>
                    </div>
                </div>';
            })
            ->addColumn('waktu_pemakaian', function ($assetBooking) {
                return \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('d-M-Y H.i') . ' -<br>' .
                    \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('d-M-Y H.i');
            })
            ->addColumn('aksi', function ($assetBooking) use ($status_booking) {
                $buttons = '<div class="d-flex gap-8">';
                $scriptCancelledBooking = view('homepage.assets.modal.cancelled-booking', compact('assetBooking', 'status_booking'))->render();

                if ($assetBooking->status !== 'cancelled') {
                    // Tombol Batalkan (Selalu Tampil)
                    $buttons .= "<a class='btn btn-sm btn-outline-danger cursor-pointer' 
                        data-bs-toggle='modal' 
                        data-bs-target='#modalCancelBooking-{$assetBooking->id}-{$status_booking}'>
                        Batalkan
                    </a>";
                    // Tambahkan modal ke dalam tombol aksi
                    $buttons .= $scriptCancelledBooking;
                }
                $buttons .= "<a class='btn btn-sm btn-outline-warning cursor-pointer' data-bs-toggle='modal' data-bs-target='#modalResubmissionAssetBooking-{$assetBooking->id}-{$status_booking}'>Rincian</a>";

                // Render modal konfirmasi booking dan pembayaran
                $resubmissionBookingModal = view('homepage.assets.modal.resubmissionBooking', compact('assetBooking', 'status_booking'))->render();
                $payAndCompleteFileModal = view('homepage.assets.modal.payAndCompleteFile', compact('assetBooking', 'status_booking'))->render();
                $payInFullModal = view('homepage.assets.modal.payInFull', compact('assetBooking', 'status_booking'))->render();

                // Tombol aksi berdasarkan status
                $modals = '';
                switch ($assetBooking->status) {
                    case 'rejected_booking':
                        $buttons .= "<a class='btn btn-sm btn-outline-main' data-bs-toggle='modal' data-bs-target='#modalResubmissionAssetBooking-{$assetBooking->id}-{$status_booking}'>🔄 Ajukan Ulang Booking</a>";
                        $modals .= $resubmissionBookingModal;
                        break;
                    case 'booked':
                        $buttons .= "<a class='btn btn-sm btn-outline-main' data-bs-toggle='modal' data-bs-target='#modalpayAndCompleteFile-{$assetBooking->id}-{$status_booking}'>Bayar " . ($assetBooking->payment_type === 'dp' ? 'DP' : '') . " dan Lengkapi Berkas</a>";
                        $modals .= $payAndCompleteFileModal;
                        break;
                    case 'rejected_dp_payment':
                        $buttons .= "<a class='btn btn-sm btn-outline-main' data-bs-toggle='modal' data-bs-target='#modalpayAndCompleteFile-{$assetBooking->id}-{$status_booking}'>📤 Upload Ulang Bukti Pembayaran dan Berkas</a>";
                        $modals .= $payAndCompleteFileModal;
                        break;
                    case 'rejected_full_payment':
                        $buttons .= "<a class='btn btn-sm btn-outline-main' data-bs-toggle='modal' data-bs-target='#modalpayInFull-{$assetBooking->id}-{$status_booking}'>📤 Upload Ulang Bukti Pembayaran dan Berkas</a>";
                        $modals .= $payInFullModal;
                        break;
                    case 'approved_dp_payment':
                        $buttons .= "<a class='btn btn-sm btn-outline-main' data-bs-toggle='modal' data-bs-target='#modalpayInFull-{$assetBooking->id}-{$status_booking}'>Bayar Pelunasan</a>";
                        $modals .= $payInFullModal;
                        break;
                    case 'approved_full_payment':
                        $buttons .= "<a class='btn btn-sm btn-outline-main' data-bs-toggle='modal' data-bs-target='#modalConfirmAssetBooking-{$assetBooking->id}-{$status_booking}'>⬇️ Surat Disposisi</a>";
                        $modals .= '';
                        break;
                }
                $buttons .= '</div>' . $modals;
                return $buttons;
            })
            ->rawColumns(['nama_aset', 'waktu_pemakaian', 'aksi'])
            ->make(true);
    }
}
