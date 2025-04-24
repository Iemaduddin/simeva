<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StakeholderUsersController extends Controller
{

    public function indexStakeholder()
    {
        $users = User::all();
        $roles = Role::whereNotIn('name', ['super admin', 'participant', 'tenant', 'organizer'])->get();

        return view('dashboardPage/users/stakeholder/index', compact('users', 'roles'));
    }

    public function getDataStakeholderUser(Request $request)
    {
        // Ambil data user dari database
        $users = User::query();
        $roles = Role::whereNotIn('name', ['participant', 'tenant', 'organizer'])->get();
        $permissions = Permission::all();
        return DataTables::of($users,)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                return '<div class="d-flex align-items-center">
                            <img src="' . asset('assets/images/user-list/user-list1.png') . '" alt="" class="flex-shrink-0 me-12 radius-8">
                            <h6 class="text-md mb-0 fw-medium flex-grow-1">' . $user->name . '</h6>
                        </div>';
            })
            ->addColumn('role', function ($user) {
                $roleName = $user->getRoleNames()->first();
                $badgeClass = match ($roleName) {
                    'Super Admin'   => 'bg-primary-900',
                    'Kaur RT'       => 'bg-success-900',
                    'Admin Jurusan'      => 'bg-warning-600',
                    'UPT PU'      => 'bg-info-900',
                    default    => 'bg-dark', // Default jika tidak cocok dengan yang lain
                };
                return '<span class="badge text-sm fw-semibold ' . $badgeClass . ' px-20 py-9 w-100 radius-4 text-white">' . $user->getRoleNames()->implode(', ') . '</span>';
            })
            ->addColumn('action', function ($user) use ($roles, $permissions) {
                $updateModal = view('dashboardPage.users.stakeholder.modal.update-user', compact('user', 'roles'))->render();
                $assignPermissionModal = view('dashboardPage.users.modalAssignPermission', compact('user', 'roles', 'permissions'))->render();
                return '<div class="d-flex gap-8">
                <button class="w-40-px h-40-px bg-hover-warning-200 bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center tooltip-trigger"
                    data-bs-toggle="modal" data-bs-target="#assignPermissionModal-' . $user->id . '" 
                    title="Atur Permission">
                    <iconify-icon icon="solar:key-square-2-outline" width="20"></iconify-icon>
                </button>
                ' . $assignPermissionModal .

                    '<div class="d-flex gap-8">
                <button class="w-40-px h-40-px bg-hover-success-200 bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateStakeholderUser-' . $user->id . '">
                    <iconify-icon icon="lucide:edit" width="20"></iconify-icon>
                </button>
                ' . $updateModal . '
                ' . (!$user->getRoleNames()->contains('Super Admin') ? '
                <form action="' . route('destroy.stakeholderUser', ['id' => $user->id]) . '" method="POST" class="delete-form" data-table="stakeholderUserTable">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line" width="20"></iconify-icon>
                </button>
                </form>' : '') . '
                </div>';
            })

            ->rawColumns(['name', 'role', 'action'])
            ->make(true);
    }
    public function addStakeholderUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required'
        ], [
            'name.required' => 'Nama Stkeholder harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password harus diisi.',
            'role.required' => 'Role harus diisi.',
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
                'password' => Hash::make($request->password)
            ]);
            $newUser->assignRole($request->role);
            return response()->json([
                'status' => 'success',
                'message' => 'Stakeholder berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan stakeholder.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateStakeholderUser(Request $request, $id)
    {
        // Ambil data user yang ingin diupdate
        $user = User::findOrFail($id);
        $rules = [
            'name' => 'required',
            'role' => 'required'
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
            'name.required' => 'Nama Stkeholder harus diisi.',
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'role.required' => 'Role harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update data user
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;

            // Jika password diisi, maka hash dan update password
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Sync role user dengan role yang dipilih
            $user->syncRoles($request->role);

            // Simpan perubahan
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Update data stakeholder berhasil!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyStakeholderUser($id)
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
}
