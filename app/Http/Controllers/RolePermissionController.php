<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    public function getDataPermission() {}
    public function assignPermissionToUser(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'array',
            'permissions*' => 'exists:permissions:name',
        ], [
            'name.required' => 'Nama Stakeholder harus diisi.',
            'permissions.*.exists' => 'Salah satu permission yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            $user->syncPermissions($request->permissions);
            return response()->json([
                'status' => 'success',
                'message' => 'Update permission berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan permission.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
