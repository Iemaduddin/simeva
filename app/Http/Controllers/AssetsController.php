<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Jurusan;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use Illuminate\Support\Facades\DB;
use App\Models\AssetBookingDocument;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AssetsController extends Controller
{
    public function indexAssetFasum()
    {
        $jurusans = Jurusan::all();
        return view('dashboardPage.assets.index', ['kode_jurusan' => null, 'jurusans' => $jurusans]);
    }
    public function indexAssetFasjur($kode_jurusan)
    {
        $jurusans = Jurusan::all();

        return view('dashboardPage.assets.index', compact('kode_jurusan', 'jurusans'));
    }


    public function getDataAsset(Request $request, $kode_jurusan = null)
    {
        $jurusan = Jurusan::where('kode_jurusan', $kode_jurusan)->first();

        if ($kode_jurusan) {
            // Ambil data user dari database
            $assets = Asset::where('facility_scope', 'jurusan')->where('jurusan_id', $jurusan->id)->get();
        } else {
            $assets = Asset::where('facility_scope', 'umum')->get();
        }
        $tableId = $kode_jurusan ? 'assetFasilitasJurusan-' . $kode_jurusan . '-Table' : 'assetFasilitasUmumTable';

        return DataTables::of($assets)
            ->addIndexColumn()
            ->addColumn('action', function ($asset) use ($tableId) {
                $updateModal = view('dashboardPage.assets.modal.update-asset', compact('asset'))->render();
                return '<div class="d-flex gap-8">
                <a class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#modalUpdateAsset-' . $asset->id . '">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                ' . $updateModal . '
                
                <form action="' . route('destroy.asset', ['id' => $asset->id]) . '" method="POST" class="delete-form" data-table="' . $tableId . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
    public function addAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required|in:building,transportation',
            'facility' => 'required',
            'available_at' => 'required',
            'booking_type' => 'required|in:daily,annual',
            'asset_images' => 'required',
            'category_name' => 'required|array',
            'category_name.*' => 'required|string|max:255',
            'external_price' => 'required|array',
            'external_price.*' => 'required|numeric|min:0',
            'internal_price_percentage' => 'required|array',
            'internal_price_percentage.*' => 'required|numeric|min:0',
            'social_price_percentage' => 'required|array',
            'social_price_percentage.*' => 'required|numeric|min:0',

        ], [
            'name.required' => 'Nama Asset harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'type.required' => 'Tipe Aset harus diisi.',
            'type.in' => 'Tipe Aset harus berupa Bangunan atau Kendaraan.',
            'facility.required' => 'Fasilitas harus diisi.',
            'available_at.required' => 'Ketersediaan Aset untuk pihak Ekstern harus diisi.',
            'booking_type.required' => 'Tipe Sewa harus diisi.',
            'booking_type.in' => 'Tipe Sewa harus berupa Harian atau Tahunan.',
            'asset_images.required' => 'Gambar Aset harus diisi.',

            'category_name.array' => 'Jenis tarif harus dalam format yang benar.',
            'category_name.*.required' => 'Setiap jenis tarif harus diisi.',

            'external_price.required' => 'Tarif aset wajib diisi.',
            'external_price.array' => 'Tarif aset harus dalam format yang benar.',
            'external_price.*.required' => 'Setiap tarif aset harus diisi.',
            'external_price.*.numeric' => 'Tarif aset harus berupa angka.',
            'external_price.*.min' => 'Tarif aset tidak boleh kurang dari 0.',

            'internal_price_percentage.required' => 'Tarif internal (%) wajib diisi.',
            'internal_price_percentage.array' => 'Tarif internal (%) harus dalam format yang benar.',
            'internal_price_percentage.*.required' => 'Setiap tarif aset harus diisi.',
            'internal_price_percentage.*.numeric' => 'Tarif internal (%) harus berupa angka.',
            'internal_price_percentage.*.min' => 'Tarif internal (%) tidak boleh kurang dari 0.',

            'social_price_percentage.required' => 'Tarif sosial (%) wajib diisi.',
            'social_price_percentage.array' => 'Tarif sosial (%) harus dalam format yang benar.',
            'social_price_percentage.*.required' => 'Setiap tarif aset harus diisi.',
            'social_price_percentage.*.numeric' => 'Tarif sosial (%) harus berupa angka.',
            'social_price_percentage.*.min' => 'Tarif sosial (%) tidak boleh kurang dari 0.',
        ]);
        $images = [];
        if ($request->hasFile('asset_images')) {
            foreach ($request->file('asset_images') as $index => $image) {
                $validator = Validator::make(
                    ['asset_image' => $image], // Input yang divalidasi
                    [
                        'asset_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Batasan ukuran maksimal 2MB
                    ],
                    [
                        'asset_image.required' => 'Gambar aset wajib diunggah.',
                        'asset_image.image' => 'Gambar aset harus berupa file gambar.',
                        'asset_image.mimes' => 'Gambar aset harus memiliki format: .jpeg, .png, .jpg.',
                        'asset_image.max' => 'Ukuran gambar aset tidak boleh lebih dari 2MB.',
                    ]
                );
                $extension = $image->getClientOriginalExtension();
                $fileName = ($index + 1) . ".{$extension}";

                // Tentukan folder penyimpanan
                $filePath = "Gambar Asset/" . ($request->jurusan ? "Fasjur/{$request->jurusan}/" : "Fasum/" . $request->name);

                // Simpan gambar ke dalam storage
                $path = $image->storeAs($filePath, $fileName, 'public');

                // Simpan path gambar ke dalam array
                $images[] = $path;
            }
        }
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        $jurusan_id = Jurusan::where('kode_jurusan', $request->jurusan)->value('id');

        $available_at = implode(', ', $request->available_at);
        $facility = implode(', ', $request->facility);

        try {
            $asset = Asset::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'facility_scope' => $request->facility_scope,
                'jurusan_id' => $jurusan_id,
                'facility' => $facility,
                'available_at' => $available_at,
                'asset_images' => json_encode($images),
                'price' => $request->price,
                'booking_type' => $request->booking_type,
            ]);
            foreach ($request->category_name as $index => $category) {
                AssetCategory::create([
                    'asset_id' => $asset->id,
                    'category_name' => $category,
                    'external_price' => $request->external_price[$index],
                    'internal_price_percentage' => $request->internal_price_percentage[$index],
                    'social_price_percentage' => $request->social_price_percentage[$index],
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Aset berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateAsset(Request $request, $id)
    {
        // Ambil data user yang ingin diupdate
        $asset = Asset::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required|in:building,transportation',
            'facility' => 'required',
            'available_at' => 'required',
            'price' => 'required',
            'booking_type' => 'required|in:daily,annual',

        ], [
            'name.required' => 'Nama Asset harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'type.required' => 'Tipe Aset harus diisi.',
            'type.in' => 'Tipe Aset harus berupa Bangunan atau Kendaraan.',
            'facility.required' => 'Fasilitas harus diisi.',
            'available_at.required' => 'Ketersediaan Aset untuk pihak Ekstern harus diisi.',
            'price.required' => 'Harga sewa harus diisi.',
            'booking_type.required' => 'Tipe Sewa harus diisi.',
            'booking_type.in' => 'Tipe Sewa harus berupa Harian atau Tahunan.',
        ]);
        $images = [];
        if ($request->hasFile('asset_images')) {
            foreach ($request->file('asset_images') as $index => $image) {
                $validator = Validator::make(
                    ['asset_image' => $image], // Input yang divalidasi
                    [
                        'asset_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Batasan ukuran maksimal 2MB
                    ],
                    [
                        'asset_image.required' => 'Gambar aset wajib diunggah.',
                        'asset_image.image' => 'Gambar aset harus berupa file gambar.',
                        'asset_image.mimes' => 'Gambar aset harus memiliki format: .jpeg, .png, .jpg.',
                        'asset_image.max' => 'Ukuran gambar aset tidak boleh lebih dari 2MB.',
                    ]
                );
                $extension = $image->getClientOriginalExtension();
                $fileName = ($index + 1) . ".{$extension}";

                // Tentukan folder penyimpanan
                $filePath = "Gambar Asset/" . ($request->jurusan ? "Fasjur/{$request->jurusan}/" : "Fasum/" . $request->name);

                // Simpan gambar ke dalam storage
                $path = $image->storeAs($filePath, $fileName, 'public');

                // Simpan path gambar ke dalam array
                $images[] = $path;
                $asset->update(['asset_images => json_encode($images),']);
            }
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jurusan_id = Jurusan::where('kode_jurusan', $request->jurusan)->value('id');

        $available_at = implode(', ', $request->available_at);
        try {

            $asset->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'facility_scope' => $request->facility_scope,
                'jurusan_id' => $jurusan_id,
                'facility' => $request->facility,
                'available_at' => $available_at,
                'price' => $request->price,
                'booking_type' => $request->booking_type,
            ]);


            return response()->json([
                'status' => 'success',
                'message' => 'Update data aset berhasil!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyAsset($id)
    {
        try {
            // Ambil data aset
            Asset::destroy($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Hapus data aset berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus aset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Home Page
    public function indexAsetBmn()
    {
        $assets  = Asset::where('facility_scope', 'umum')->get();
        return view('homepage.aset', compact('assets'));
    }
    public function getDataAssetBmn($id)
    {
        $assetDetails  = Asset::where('facility_scope', 'umum')->where('id', $id)->first();
        return view('homepage.detail_aset', compact('assetDetails'));
    }

    public function getDataCategoryAssetBooking(Request $request, $id)
    {
        $categories = AssetCategory::where('asset_id', $id)->get(['id', 'category_name', 'external_price']);

        return response()->json([
            'data' => $categories
        ]);
    }
}
