<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Event;
use App\Models\Prodi;
use App\Models\Organizer;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class TeamMemberController extends Controller
{
    public function teamMemberPage($shorten_name)
    {
        if (Auth::user()->roles()->where('name', 'Organizer')->exists()) {
            if (optional(Auth::user()->organizer)->shorten_name !== $shorten_name) {
                return abort(403, 'Unauthorized');
            }
        }
        $organizer = Auth::user()->organizer;

        if ($organizer->organizer_type == 'HMJ') {
            $prodis = Prodi::where('jurusan_id', $organizer->user->jurusan->id)->get();
        } else {
            $prodis = Prodi::all();
        }

        return view('dashboardPage.team-members.index', compact('shorten_name', 'prodis'));
    }
    public function getDataTeamMembers($shorten_name)
    {

        $organizer_id = Organizer::where('shorten_name', $shorten_name)->pluck('id')->first();
        $team_members = TeamMember::with('prodi')->where('organizer_id', $organizer_id)->get();
        $tableId = $shorten_name . '-TeamMembersTable';
        $organizer = Auth::user()->organizer;

        if ($organizer->organizer_type == 'HMJ') {
            $prodis = Prodi::where('jurusan_id', $organizer->user->jurusan->id)->get();
        } else {
            $prodis = Prodi::all();
        }

        return DataTables::of($team_members)
            ->addIndexColumn()
            ->editColumn('position', function ($team_member) {
                if ($team_member->is_leader) {
                    return $team_member->position . ' <br> <strong>(Pemimpin Organisasi)</strong>';
                } else {
                    return $team_member->position;
                }
            })
            ->addColumn('action', function ($team_member) use ($tableId, $prodis) {
                $updateModal = view('dashboardPage.team-members.modal.update-team', compact('team_member', 'tableId', 'prodis'))->render();
                return '<div class="d-flex gap-8">
                <a class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateTeamMember-' . $team_member->id . '">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                ' . $updateModal . '
                
                <form action="' . route('destroy.team-member', ['id' => $team_member->id]) . '" method="POST" class="delete-form" data-table="' . $tableId . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })
            ->rawColumns(['position', 'action'])
            ->make(true);
    }


    public function addTeamMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'name' => 'required',
            'prodi' => 'required',
            'level' => 'required|in:SC,OC',
            'position' => 'required',
        ], [
            'nim.required' => 'NIM harus diisi.',
            'name.required' => 'Nama harus diisi.',
            'prodi.required' => 'Prodi harus diisi.',
            'level.required' => 'Tingkatan Tim harus diisi.',
            'level.in' => 'Tingkatan Tim harus SC atau OC.',
            'position.required' => 'Posisi harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        $organizer_id = Organizer::where('user_id', Auth::id())->value('id');

        try {
            TeamMember::create([
                'nim' => $request->nim,
                'name' => $request->name,
                'prodi_id' => $request->prodi,
                'level' => $request->level,
                'position' => $request->position,
                'organizer_id' => $organizer_id,
                'is_leader' => $request->is_leader ? true : false,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Anggota tim berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan anggota tim.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateTeamMember(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'name' => 'required',
            'prodi' => 'required',
            'level' => 'required|in:SC,OC',
            'position' => 'required',
        ], [
            'nim.required' => 'NIM harus diisi.',
            'name.required' => 'Nama harus diisi.',
            'level.required' => 'Tingkatan Tim harus diisi.',
            'level.in' => 'Tingkatan Tim harus SC atau OC.',
            'position.required' => 'Posisi harus diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        $team_member = TeamMember::findOrFail($id);

        try {
            $team_member->update([
                'nim' => $request->nim,
                'name' => $request->name,
                'prodi_id' => $request->prodi,
                'level' => $request->level,
                'position' => $request->position,
                'is_leader' => $request->is_leader ? true : false,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Anggota tim berhasil diperbarui!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui anggota tim.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroyTeamMember($id)
    {
        try {

            // Ambil data user dan hapus user
            $team_member = TeamMember::findOrFail($id);
            $team_member->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Hapus anggota tim berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus anggota tim.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
