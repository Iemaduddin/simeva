<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaUsersController extends Controller
{

    public function indexMahasiswa()
    {
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();
        return view('dashboardPage/users/mahasiswa/index', compact('jurusans', 'prodis'));
    }

    public function getDataMahasiswaUser(Request $request)
    {
        // Ambil kode jurusan dari request
        $kodeJurusan = $request->kode_jurusan;
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();

        // Filter mahasiswa berdasarkan kode jurusan
        $mahasiswas = Mahasiswa::with(['user.jurusan'])
            ->whereRelation('user.jurusan', 'kode_jurusan', $kodeJurusan)
            ->get();
        return DataTables::of($mahasiswas)
            ->addIndexColumn()
            ->editColumn('name', function ($mahasiswa) {
                $imageUrl = asset('storage/' . $mahasiswa->user->profile_picture) . '?t=' . time();
                $imageDefault = asset('assets/images/avatar');
                return '<div class="d-flex align-items-center ' . ($mahasiswa->user->is_blocked ? 'bg-danger-400' : '') . '">
                            <img src="' . ($mahasiswa->user->profile_picture ? $imageUrl : $imageDefault) . '" alt="" class="flex-shrink-0 me-12 radius-8" width="50px">
                            <h6 class="text-md mb-0 fw-medium flex-grow-1">' . $mahasiswa->user->name . '</h6>
                        </div>';
            })
            ->editColumn('username', fn($mahasiswa) => $mahasiswa->user->username)
            ->editColumn('email', fn($mahasiswa) => $mahasiswa->user->email)
            ->editColumn('phone_number', fn($mahasiswa) => $mahasiswa->user->phone_number)
            ->addColumn('action', function ($mahasiswa) use ($kodeJurusan, $jurusans, $prodis) {
                $updateModal = view('dashboardPage.users.mahasiswa.modal.update-user', compact('mahasiswa', 'jurusans', 'prodis', 'kodeJurusan'))->render();
                $html = '<div class="d-flex gap-8">';
                $html .= '<button class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#modalUpdateMahasiswaUser-' . $mahasiswa->user_id . '">
                <iconify-icon icon="lucide:edit" width="20"></iconify-icon>
            </button>';
                $html .= $updateModal;
                if ($mahasiswa->user->is_blocked) {
                    $html .= '
                        <form action="' . route('block.mahasiswaUser', ['type' => 'unblock', 'id' => $mahasiswa->user->id]) . '" method="POST" class="block-form" data-table="mahasiswaUserTable-' . $kodeJurusan . '">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="button" class="block-btn w-40-px h-40-px bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Unblock" data-action="unblock">
                                <iconify-icon icon="gg:unblock" width="25"></iconify-icon>
                            </button>
                        </form>';
                } else {
                    // Tombol Block
                    $html .= '
                        <form action="' . route('block.mahasiswaUser', ['type' => 'block', 'id' => $mahasiswa->user->id]) . '" method="POST" class="block-form" data-table="mahasiswaUserTable-' . $kodeJurusan . '">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button type="button" class="block-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Block" data-action="block">
                                <iconify-icon icon="ic:sharp-block" width="20"></iconify-icon>
                            </button>
                        </form>';
                }

                $html .= '<form action="' . route('destroy.mahasiswaUser', ['id' => $mahasiswa->user_id]) . '" method="POST" class="delete-form" data-table="mahasiswaUserTable-' . $kodeJurusan . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="button"
                        class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                    </button>
                    </form>';

                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function addMahasiswaUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone_number' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'jurusan' => 'required',
            'prodi' => 'required',
            'province' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'village' => 'required',
            'address' => 'required',
            'profile_picture' => 'image|mimes:jpeg,png,jpg',
        ], [
            'nim.required' => 'NIM Mahasiswa harus diisi.',
            'name.required' => 'Nama Mahasiswa harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'phone_number.required' => 'Nomor HP Mahasiswa harus diisi.',
            'phone_number.numeric' => 'Nomor HP Mahasiswa harus mengandung angka 0-9.',
            'tanggal_lahir.required' => 'Tanggal Lahir Mahasiswa harus diisi.',
            'tanggal_lahir.date' => 'Tanggal Lahir Mahasiswa harus berupa tanggal.',
            'jenis_kelamin.required' => 'Jenis Kelamin Mahasiswa harus diisi.',
            'jenis_kelamin.in' => 'Jenis Kelamin Mahasiswa harus Laki-laki/Perempuan.',
            'jurusan.required' => 'Jurusan Mahasiswa harus diisi.',
            'province.required' => 'Asal Provinsi Mahasiswa harus diisi.',
            'city.required' => 'Asal Kabupaten/Kota Mahasiswa harus diisi.',
            'subdistrict.required' => 'Asal Kecamatan Mahasiswa harus diisi.',
            'village.required' => 'Asal Kelurahan/Desa Mahasiswa harus diisi.',
            'address.required' => 'Alamat Lengkap Mahasiswa harus diisi.',
            'profile_picture.image' => 'Foto harus Mahasiswa harus berupa file gambar.',
            'profile_picture.mimes' => 'Foto harus Mahasiswa harus memiliki format: .jpeg, .png, .jpg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filePath = null;
            $jurusanName = Jurusan::where('id', $request->jurusan)->value('nama');
            $prodi = Prodi::where('id', $request->prodi)->value('nama_prodi');
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $extension = $file->getClientOriginalExtension();
                $fileName =  $request->nim . '_' . $request->name . '.' . $extension;
                $filePath = "Foto Profil/Mahasiswa/{$jurusanName}/{$prodi}/{$fileName}";
                $file->storeAs("Foto Profil/Mahasiswa/{$jurusanName}/{$prodi}", $fileName, 'public');
            }

            $newUser = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'category_user' => 'Internal Kampus',
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'province' => $request->province,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'village' => $request->village,
                'address' => $request->address,
                'jurusan_id' => $request->jurusan,
                'profile_picture' => $filePath,
            ]);
            $newUser->assignRole('Participant');


            Mahasiswa::create([
                'user_id' => $newUser->id,
                'prodi_id' => $request->prodi,
                'nim' => $request->nim,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Mahasiswa berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan mahasiswa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateMahasiswaUser(Request $request, $id)
    {
        // Ambil data user yang ingin diupdate
        $user = User::findOrFail($id);
        $rules = [
            'nim' => 'required',
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'jurusan' => 'required',
            'prodi' => 'required',
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
            'nim.required' => 'NIM Mahasiswa harus diisi.',
            'name.required' => 'Nama Mahasiswa harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'phone_number.required' => 'Nomor HP Mahasiswa harus diisi.',
            'phone_number.numeric' => 'Nomor HP Mahasiswa harus mengandung angka 0-9.',
            'tanggal_lahir.required' => 'Tanggal Lahir Mahasiswa harus diisi.',
            'tanggal_lahir.date' => 'Tanggal Lahir Mahasiswa harus berupa tanggal.',
            'jenis_kelamin.required' => 'Jenis Kelamin Mahasiswa harus diisi.',
            'jenis_kelamin.in' => 'Jenis Kelamin Mahasiswa harus Laki-laki/Perempuan.',
            'jurusan.required' => 'Jurusan Mahasiswa harus diisi.',
            'prodi.required' => 'Prodi Mahasiswa harus diisi.',
            'province.required' => 'Asal Provinsi Mahasiswa harus diisi.',
            'city.required' => 'Asal Kabupaten/Kota Mahasiswa harus diisi.',
            'subdistrict.required' => 'Asal Kecamatan Mahasiswa harus diisi.',
            'village.required' => 'Asal Kelurahan/Desa Mahasiswa harus diisi.',
            'address.required' => 'Alamat Lengkap Mahasiswa harus diisi.',
            'profile_picture.image' => 'Foto Mahasiswa harus berupa file gambar.',
            'profile_picture.mimes' => 'Foto  Mahasiswa harus memiliki format: .jpeg, .png, .jpg.',
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

            $mahasiswa = Mahasiswa::where('user_id', $id)->first();
            $jurusan = Jurusan::where('id', $request->jurusan)->value('nama');
            $prodi = Prodi::where('id', $request->prodi)->value('nama_prodi');
            $filePath = null;
            if ($request->hasFile('profile_picture')) {
                $request->validate([
                    'profile_picture' => 'image|mimes:jpeg,png,jpg',
                ]);
                $file = $request->file('profile_picture');

                // Hapus profile_picture lama jika ada
                if ($mahasiswa->user->profile_picture && Storage::exists('public/' . $mahasiswa->user->profile_picture)) {
                    Storage::delete('public/' . $mahasiswa->user->profile_picture);
                }

                // Simpan file baru
                $extension = $file->getClientOriginalExtension();
                $fileName =  $request->nim . '_' . $request->name . '.' . $extension;
                $filePath = "Foto Profil/Mahasiswa/{$jurusan}/{$prodi}/{$fileName}";
                $file->storeAs("Foto Profil/Mahasiswa/{$jurusan}/{$prodi}", $fileName, 'public');
                $user->update(['profile_picture' => $filePath]);
            }
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'category_user' => 'Internal Kampus',
                'jurusan_id' => $request->jurusan,
                'phone_number' => $request->phone_number,
                'province' => $request->province,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'village' => $request->village,
                'address' => $request->address,
            ]);
            $mahasiswa->update([
                'user_id' => $user->id,
                'prodi_id' => $request->prodi,
                'nim' => $request->nim,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Update data mahasiswa berhasil!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat update mahasiswa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyMahasiswaUser($id)
    {
        try {

            // Ambil data user dan hapus user
            Mahasiswa::where('user_id', $id)->delete();

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
    public function getProdiByJurusan(Request $request)
    {
        $kodeJurusan = $request->kode_jurusan;
        $prodis = Prodi::whereHas('jurusan', function ($query) use ($kodeJurusan) {
            $query->where('kode_jurusan', $kodeJurusan);
        })->get(['id', 'nama_prodi', 'kode_prodi']); // Ambil data yang diperlukan saja

        return response()->json($prodis);
    }

    public function blockMahasiswaUser($type, $id)
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
}