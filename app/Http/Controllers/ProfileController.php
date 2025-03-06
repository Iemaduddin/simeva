<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
}
