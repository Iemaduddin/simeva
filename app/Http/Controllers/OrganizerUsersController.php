<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrganizerUsersController extends Controller
{

    public function indexOrganizer()
    {
        return view('dashboardPage/users/organizer/index');
    }

    public function getDataOrganizerUser(Request $request)
    {
        // Ambil data user dari database
        $organizers = Organizer::with('user')
            ->orderByRaw("FIELD(organizer_type, 'Kampus', 'Jurusan', 'LT', 'HMJ', 'UKM')")
            ->get();

        return DataTables::of($organizers)
            ->addIndexColumn()
            ->editColumn('name', function ($organizer) {
                $imageUrl = asset('storage/' . $organizer->logo) . '?t=' . time(); // Tambahkan timestamp
                return '<div class="d-flex align-items-center">
                            <img src="' . $imageUrl . '" alt="" class="flex-shrink-0 me-12 radius-8" width="50px">
                            <h6 class="text-md mb-0 fw-medium flex-grow-1">' . $organizer->user->name . '</h6>
                        </div>';
            })

            ->editColumn('username', function ($organizer) {
                return $organizer->user->username;
            })
            ->editColumn('email', function ($organizer) {
                return $organizer->user->email;
            })
            ->editColumn('phone_number', function ($organizer) {
                return $organizer->user->phone_number;
            })
            ->addColumn('organizer_type', function ($organizer) {
                // Tentukan warna background berdasarkan organizers_type
                $badgeClass = match ($organizer->organizer_type) {
                    'Kampus'   => 'bg-primary',
                    'LT'       => 'bg-success',
                    'HMJ'      => 'bg-danger',
                    'UKM'      => 'bg-info',
                    'Jurusan'  => 'bg-warning',
                    default    => 'bg-dark', // Default jika tidak cocok dengan yang lain
                };

                // Kembalikan HTML dengan class yang sudah ditentukan
                return '<span class="badge text-sm fw-semibold ' . $badgeClass . ' px-20 py-9 w-100 radius-4 text-white">'
                    . ucfirst($organizer->organizer_type) .
                    '</span>';
            })

            ->addColumn('action', function ($organizer) {
                $updateModal = view('dashboardPage.users.organizer.modal.update-user', compact('organizer'))->render();
                return '<div class="d-flex gap-8">
                <a class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateOrganizerUser-' . $organizer->user->id . '">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                ' . $updateModal . '
                
                <form action="' . route('destroy.organizerUser', ['id' => $organizer->user_id]) . '" method="POST" class="delete-form" data-table="organizerUserTable">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })

            ->rawColumns(['name', 'organizer_type', 'action'])
            ->make(true);
    }
    public function addOrganizerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'organizer_type' => 'required|in:Kampus,Jurusan,LT,HMJ,UKM',
            'shorten_name' => 'required',
            'vision' => 'required',
            'missions' => 'required|array',
            'missions.*' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg'
        ], [
            'name.required' => 'Nama Organizer harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'organizer_type.required' => 'Tipe Organizer harus diisi.',
            'shorten_name.required' => 'Singkatan Organizer harus diisi.',
            'vision.required' => 'Visi harus diisi.',
            'missions.required' => 'Misi harus diisi.',
            'missions.array' => 'Misi harus bertipe array.',
            'missions.*.required' => 'Misi harus diisi.',
            'logo.required' => 'Logo harus diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus memiliki format: .jpeg, .png, .jpg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $newUser = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'category_user' => 'internal_kampus',
                'password' => Hash::make($request->password)
            ]);
            $newUser->assignRole('Organizer');
            $missions = implode(', ', $request->missions);
            $filePath = null;

            if ($request->hasFile('logo')) {
                $request->validate([
                    'logo' => 'image|mimes:jpeg,png,jpg',
                ]);
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'Logo-' . $request->shorten_name . '.' . $extension;
                $filePath = "Logo Organizer/" . $fileName;
                $file->storeAs('Logo Organizer', $fileName, 'public');
            }
            Organizer::create([
                'user_id' => $newUser->id,
                'shorten_name' => $request->shorten_name,
                'vision' => $request->vision,
                'mision' => $missions,
                'description' => $request->description,
                'organizer_type' => $request->organizer_type,
                'logo' => $filePath
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateOrganizerUser(Request $request, $id)
    {
        // Ambil data user yang ingin diupdate
        $user = User::findOrFail($id);
        $rules = [
            'name' => 'required',
            'organizer_type' => 'required|in:Kampus,Jurusan,LT,HMJ,UKM',
            'shorten_name' => 'required',
            'vision' => 'required',
            'missions' => 'required|array',
            'missions.*' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg'
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
            'name.required' => 'Nama Organizer harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'organizer_type.required' => 'Tipe Organizer harus diisi.',
            'shorten_name.required' => 'Singkatan Organizer harus diisi.',
            'vision.required' => 'Visi harus diisi.',
            'missions.required' => 'Misi harus diisi.',
            'missions.array' => 'Misi harus bertipe array.',
            'missions.*.required' => 'Misi harus diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus memiliki format: .jpeg, .png, .jpg',
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

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $missions = implode(', ', $request->missions);
            $organizer = Organizer::where('user_id', $id)->first();
            $filePath = null;
            if ($request->hasFile('logo')) {
                $request->validate([
                    'logo' => 'image|mimes:jpeg,png,jpg',
                ]);
                $file = $request->file('logo');

                // Hapus logo lama jika ada
                if ($organizer->logo && Storage::exists('public/' . $organizer->logo)) {
                    Storage::delete('public/' . $organizer->logo);
                }

                // Simpan file baru
                $extension = $file->getClientOriginalExtension();
                $fileName = 'Logo-' . $organizer->shorten_name . '.' . $extension;
                $filePath = "Logo Organizer/" . $fileName;
                $file->storeAs('Logo Organizer', $fileName, 'public');
                $organizer->update(['logo' => $filePath]);
            }
            $organizer->update([
                'shorten_name' => $request->shorten_name,
                'vision' => $request->vision,
                'mision' => $missions,
                'description' => $request->description,
                'organizer_type' => $request->organizer_type,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Update data user berhasil!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyOrganizerUser($id)
    {
        try {

            // Ambil data user dan hapus user
            Organizer::where('user_id', $id)->delete();

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
}
