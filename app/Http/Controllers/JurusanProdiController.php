<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class JurusanProdiController extends Controller
{
    public function indexJurusanProdi()
    {
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();
        return view('dashboardPage/jurusanprodi/index', compact('jurusans', 'prodis'));
    }

    public function getDataJurusan(Request $request)
    {
        // Ambil data jurusan dari database
        $jurusans = Jurusan::query();

        return DataTables::of($jurusans)
            ->addIndexColumn()
            ->editColumn('kode_jurusan', function ($jurusan) {
                // Tentukan warna background berdasarkan organizers_type
                $badgeClass = match ($jurusan->kode_jurusan) {
                    'TI' => 'bg-warning',
                    'TM' => 'bg-info-900',
                    'TE' => 'bg-warning-700',
                    'TS' => 'bg-brown',
                    'TK' => 'bg-success-800',
                    'AN' => 'bg-primary-900',
                    'AK' => 'bg-danger',
                    default => 'bg-dark',
                };

                // Kembalikan HTML dengan class yang sudah ditentukan
                return '<span class="badge text-sm fw-semibold ' . $badgeClass . ' px-20 py-9  radius-4 text-white">'
                    . ucfirst($jurusan->kode_jurusan) .
                    '</span>';
            })
            ->addColumn('action', function ($jurusan) {
                $updateModal = view('dashboardPage.jurusanprodi.modaljurusan.update-jurusan', compact('jurusan'))->render();
                return '<div class="d-flex gap-8">
                <a class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateJurusan-' . $jurusan->id . '">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                ' . $updateModal . '
                <form action="' . route('destroy.jurusan', ['id' => $jurusan->id]) . '" method="POST" class="delete-form" data-table="jurusanTable">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })

            ->rawColumns(['kode_jurusan', 'action'])
            ->make(true);
    }
    public function addJurusan(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:100',
            'kode_jurusan' => 'required|max:10|unique:jurusan,kode_jurusan',
        ], [
            'nama.required' => 'Nama Jurusan harus diisi.',
            'nama.max' => 'Nama Jurusan maksimal 100 karakter.',
            'kode_jurusan.required' => 'Kode Jurusan harus diisi.',
            'kode_jurusan.max' => 'Kode Jurusan maksimal 10 karakter.',
            'kode_jurusan.unique' => 'Kode Jurusan sudah ada, gunakan kode lain.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Simpan data jurusan
            Jurusan::create($request->only(['nama', 'kode_jurusan']));

            return response()->json([
                'status' => 'success',
                'message' => 'Jurusan berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan jurusan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateJurusan(Request $request, $id)
    {
        // Cari jurusan berdasarkan ID
        $jurusan = Jurusan::findOrFail($id);
        // Validasi input
        $rules = [
            'nama' => 'required|max:100',
        ];

        // Jika kode_jurusan diubah, baru tambahkan validasi unique
        if ($request->kode_jurusan !== $jurusan->kode_jurusan) {
            $rules['kode_jurusan'] = 'required|max:10|unique:jurusan,kode_jurusan';
        } else {
            $rules['kode_jurusan'] = 'required|max:10';
        }

        $validator = Validator::make($request->all(), $rules, [
            'nama.required' => 'Nama jurusan harus diisi.',
            'nama.max' => 'Nama jurusan maksimal 100 karakter.',
            'kode_jurusan.required' => 'Kode jurusan harus diisi.',
            'kode_jurusan.max' => 'Kode jurusan maksimal 10 karakter.',
            'kode_jurusan.unique' => 'Kode jurusan sudah ada, gunakan kode lain.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update data jurusan
            $jurusan->update($request->only(['nama', 'kode_jurusan']));

            return response()->json([
                'status' => 'success',
                'message' => 'Jurusan berhasil diperbarui!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui jurusan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroyJurusan($id)
    {
        try {
            // Cari jurusan berdasarkan ID
            $jurusan = Jurusan::findOrFail($id);

            // Hapus jurusan
            $jurusan->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Jurusan berhasil dihapus!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus jurusan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDataProdi(Request $request)
    {
        // Ambil data prodi dari database
        $prodis = Prodi::with('jurusan')
            ->get()
            ->groupBy('jurusan.kode_jurusan')->flatten();
        $jurusans = Jurusan::all();
        return DataTables::of($prodis)
            ->addIndexColumn()
            ->editColumn('jurusan_id', function ($prodi) {
                // Tentukan warna background berdasarkan organizers_type
                $badgeClass = match ($prodi->jurusan->kode_jurusan) {
                    'TI' => 'bg-warning',
                    'TM' => 'bg-info-900',
                    'TE' => 'bg-warning-700',
                    'TS' => 'bg-brown',
                    'TK' => 'bg-success-800',
                    'AN' => 'bg-primary-900',
                    'AK' => 'bg-danger',
                    default => 'bg-dark',
                };

                // Kembalikan HTML dengan class yang sudah ditentukan
                return '<span class="badge text-sm fw-semibold ' . $badgeClass . ' py-9  radius-4 text-white">'
                    . ucfirst($prodi->jurusan->kode_jurusan) .
                    '</span>';
            })
            ->addColumn('action', function ($prodi) use ($jurusans) {
                $updateModal = view('dashboardPage.jurusanprodi.modalprodi.update-prodi', compact('prodi', 'jurusans'))->render();
                return '<div class="d-flex gap-8">
                <a class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateProdi-' . $prodi->id . '">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                ' . $updateModal . '
                <form action="' . route('destroy.prodi', ['id' => $prodi->id]) . '" method="POST" class="delete-form" data-table="prodiTable">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })

            ->rawColumns(['jurusan_id', 'action'])
            ->make(true);
    }
    public function addProdi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_prodi' => 'required|max:100',
            'kode_prodi' => 'required|max:20|unique:prodi,kode_prodi',
            'jurusan_id' => 'required|exists:jurusan,id',
        ], [
            'nama_prodi.required' => 'Nama Program Studi harus diisi.',
            'kode_prodi.required' => 'Kode Program Studi harus diisi.',
            'kode_prodi.unique' => 'Kode Program Studi sudah digunakan.',
            'jurusan_id.required' => 'Jurusan harus dipilih.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buat data prodi baru
            Prodi::create([
                'nama_prodi' => $request->nama_prodi,
                'kode_prodi' => $request->kode_prodi,
                'jurusan_id' => $request->jurusan_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Program studi berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan program studi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateProdi(Request $request, $id)
    {     // Cari prodi berdasarkan ID
        $prodi = Prodi::findOrFail($id);
        $rules = [
            'nama_prodi' => 'required|max:100',
            'jurusan_id' => 'required|exists:jurusan,id',
        ];

        // Jika kode_prodi diubah, baru tambahkan validasi unique
        if ($request->kode_prodi !== $prodi->kode_prodi) {
            $rules['kode_prodi'] = 'required|max:10|unique:prodi,kode_prodi';
        } else {
            $rules['kode_prodi'] = 'required|max:10';
        }

        $validator = Validator::make($request->all(), $rules, [
            'nama.required' => 'Nama prodi harus diisi.',
            'nama.max' => 'Nama prodi maksimal 100 karakter.',
            'kode_prodi.required' => 'Kode prodi harus diisi.',
            'kode_prodi.max' => 'Kode prodi maksimal 10 karakter.',
            'kode_prodi.unique' => 'Kode prodi sudah ada, gunakan kode lain.',
            'jurusan_id.required' => 'Jurusan harus dipilih.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update data prodi
            $prodi->update($request->only(['nama_prodi', 'kode_prodi', 'jurusan_id']));

            return response()->json([
                'status' => 'success',
                'message' => 'Program studi berhasil diperbarui!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui program studi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyProdi($id)
    {
        try {
            // Cari prodi berdasarkan ID
            $prodi = Prodi::findOrFail($id);

            // Hapus prodi
            $prodi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Program studi berhasil dihapus!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus program studi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}