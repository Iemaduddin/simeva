<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Jurusan;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use Illuminate\Support\Facades\DB;
use App\Models\AssetBookingDocument;
use Illuminate\Support\Facades\Auth;
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

        if (Auth::user()->roles()->where('name', 'Admin Jurusan')->exists()) {
            if (optional(Auth::user()->jurusan)->kode_jurusan !== $kode_jurusan) {
                return abort(403, 'Unauthorized');
            }
        }
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
            ->editColumn('status', function ($asset) {
                $badges = '';
                if ($asset->status == 'OPEN') {
                    $badges = '<button class="btn btn-sm btn-success-700">' . strtoupper($asset->status) . '</button>';
                } else {
                    $badges = '<button class="btn btn-sm btn-danger -700">' . strtoupper($asset->status) . '</button>';
                }
                return $badges;
            })
            ->editColumn('facility', function ($asset) {
                $badges = '<div class="d-flex flex-wrap" style="gap: 4px;">';
                foreach (explode('|', $asset->facility) as $facility) {
                    $badges .= '<span class="badge bg-primary-600">' . e($facility) . '</span>';
                }
                $badges .= '</div>';
                return $badges;
            })
            ->editColumn('available_at', function ($asset) {
                $badges = '<div class="d-flex flex-wrap" style="gap: 4px;">';
                foreach (explode('|', $asset->available_at) as $available_at) {
                    $badges .= '<span class="badge bg-success-600">' . e($available_at) . '</span>';
                }
                $badges .= '</div>';
                return $badges;
            })
            ->addColumn('action', function ($asset) use ($kode_jurusan, $tableId) {
                $deleteModal = view('dashboardPage.assets.modal.confirm-delete', compact('asset'))->render();
                return '<div class="d-flex gap-8">
                <a href="' . route('update.assetPage', $asset->id) . '" class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </a>
                <button type="button"
                    class="w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#confirmDeleteAsset-' . $asset->id . '">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>

                ' . $deleteModal . '
                </div>';
            })

            ->rawColumns(['status', 'facility', 'available_at', 'action'])
            ->make(true);
    }

    public function addAssetPage($kode_jurusan = null)
    {

        if (Auth::user()->roles()->where('name', 'Admin Jurusan')->exists()) {
            if (optional(Auth::user()->jurusan)->kode_jurusan !== $kode_jurusan) {
                return abort(403, 'Unauthorized');
            }
        }
        return view('dashboardPage.assets.add-asset', compact('kode_jurusan'));
    }
    public function storeAsset(Request $request)
    {

        if ($request->jurusan) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|in:building,transportation',
                'facility' => 'required',
                'available_at' => 'required',
                'booking_type' => 'required|in:daily,annual',
                'asset_images' => 'required',
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
            ]);
        } else {
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
        }

        $images = [];
        if ($request->hasFile('asset_images')) {
            foreach ($request->file('asset_images') as $index => $image) {
                $validator = Validator::make(
                    ['asset_image' => $image], // Input yang divalidasi
                    [
                        'asset_image' => 'required|image|mimes:jpeg,png,jpg', // Batasan ukuran maksimal 2MB
                    ],
                    [
                        'asset_image.required' => 'Gambar aset wajib diunggah.',
                        'asset_image.image' => 'Gambar aset harus berupa file gambar.',
                        'asset_image.mimes' => 'Gambar aset harus memiliki format: .jpeg, .png, .jpg.',
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
            $firstError = $validator->errors()->first(); // Ambil error pertama dari validasi
            notyf()->ripple(true)->error($firstError);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jurusan_id = Jurusan::where('kode_jurusan', $request->jurusan)->value('id');
        $available_at = null;
        if ($request->available_at) {
            $available_at = implode('|', $request->available_at);
        }
        $facility = implode('|', $request->facility);
        DB::beginTransaction();
        try {
            $asset = Asset::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'facility_scope' => $request->facility_scope,
                'jurusan_id' => $jurusan_id,
                'facility' => $facility,
                'available_at' => $request->booking_type === 'daily' ? $available_at : null,
                'asset_images' => json_encode($images),
                'booking_type' => $request->booking_type,
            ]);
            if (!$request->jurusan) {
                foreach ($request->category_name as $index => $category) {
                    AssetCategory::create([
                        'asset_id' => $asset->id,
                        'category_name' => $category,
                        'external_price' => $request->external_price[$index],
                        'internal_price_percentage' => $request->internal_price_percentage[$index],
                        'social_price_percentage' => $request->social_price_percentage[$index],
                    ]);
                }
            }
            DB::commit();
            notyf()->ripple(true)->success('Aset berhasil ditambahkan!');
            if ($request->jurusan) {
                return redirect()->route('assets.fasjur', $request->jurusan);
            } else {
                return redirect()->route('assets.fasum');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->ripple(true)->error('Terjadi kesalahan saat menambahkan aset.');
            return redirect()->back();
        }
    }
    public function updateAsset(Request $request, $id)
    {
        // Ambil data user yang ingin diupdate
        $asset = Asset::findOrFail($id);
        if ($request->jurusan) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|in:building,transportation',
                'facility' => 'required',
                'available_at' => 'required',
                'booking_type' => 'required|in:daily,annual',
                // 'asset_images' => 'required',
            ], [
                'name.required' => 'Nama Asset harus diisi.',
                'description.required' => 'Deskripsi harus diisi.',
                'type.required' => 'Tipe Aset harus diisi.',
                'type.in' => 'Tipe Aset harus berupa Bangunan atau Kendaraan.',
                'facility.required' => 'Fasilitas harus diisi.',
                'available_at.required' => 'Ketersediaan Aset untuk pihak Ekstern harus diisi.',
                'booking_type.required' => 'Tipe Sewa harus diisi.',
                'booking_type.in' => 'Tipe Sewa harus berupa Harian atau Tahunan.',
                // 'asset_images.required' => 'Gambar Aset harus diisi.',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'type' => 'required|in:building,transportation',
                'facility' => 'required',
                'available_at' => [
                    function ($attribute, $value, $fail) use ($asset) {
                        if ($asset->booking_type === 'daily' && empty($value)) {
                            $fail('Ketersediaan Aset harus diisi untuk tipe sewa harian.');
                        }
                    }
                ],
                'booking_type' => 'required|in:daily,annual',
                'status' => 'required|in:OPEN,CLOSED',
                // 'asset_images' => 'required',
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
                'booking_type.required' => 'Tipe Sewa harus diisi.',
                'booking_type.in' => 'Tipe Sewa harus berupa Harian atau Tahunan.',
                'status.required' => 'Status Aset harus diisi.',
                'status.in' => 'Status Aset harus berupa OPEN atau CLOSED.',
                // 'asset_images.required' => 'Gambar Aset harus diisi.',

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
        }

        // Ambil data gambar lama
        $currentImages = $asset->asset_images ? json_decode($asset->asset_images, true) : [];

        // 1. Hapus gambar lama kalau ada yang dihapus
        if ($request->filled('removed_images')) {
            $removedImages = explode(',', $request->removed_images);

            foreach ($removedImages as $imagePath) {
                Storage::disk('public')->delete($imagePath);

                // Hapus dari array $currentImages
                $currentImages = array_filter($currentImages, function ($img) use ($imagePath) {
                    return $img !== $imagePath;
                });
            }
            // Reindex array
            $currentImages = array_values($currentImages);
        }

        // 2. Upload gambar baru
        if ($request->hasFile('asset_images')) {
            foreach ($request->file('asset_images') as $index => $image) {

                $extension = $image->getClientOriginalExtension();
                $fileName = (count($currentImages) + $index + 1) . ".{$extension}";

                $filePath = "Gambar Asset/" . ($request->jurusan ? "Fasjur/{$request->jurusan}/" : "Fasum/{$request->name}/");

                $storedPath = $image->storeAs($filePath, $fileName, 'public');

                $currentImages[] = $storedPath;
            }
        }

        if ($validator->fails()) {
            $firstError = $validator->errors()->first(); // Ambil error pertama dari validasi
            notyf()->ripple(true)->error($firstError);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $available_at = null;
        if ($request->available_at) {
            $available_at = implode('|', $request->available_at);
        }

        DB::beginTransaction();
        try {

            $asset->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'facility_scope' => $request->facility_scope,
                'facility' => $request->facility,
                'available_at' => $request->booking_type === 'daily' ? $available_at : null,
                'booking_type' => $request->booking_type,
                'asset_images' => json_encode($currentImages),
                'status' => $request->status,
            ]);

            if (!$request->jurusan) {
                $assetCategories = AssetCategory::where('asset_id', $asset->id)->get();
                $incomingCount = count($request->category_name);
                $existingCount = $assetCategories->count();

                // Update existing categories
                foreach ($assetCategories as $index => $category) {
                    if ($index < $incomingCount) {
                        $category->update([
                            'category_name' => $request->category_name[$index],
                            'external_price' => $request->external_price[$index],
                            'internal_price_percentage' => $request->internal_price_percentage[$index],
                            'social_price_percentage' => $request->social_price_percentage[$index],
                        ]);
                    } else {
                        // Hapus kategori lama yang tidak ada di inputan baru
                        $category->delete();
                    }
                }

                // Tambahkan sisa yang baru jika jumlah input lebih banyak dari yang ada di database
                for ($i = $existingCount; $i < $incomingCount; $i++) {
                    AssetCategory::create([
                        'asset_id' => $asset->id,
                        'category_name' => $request->category_name[$i],
                        'external_price' => $request->external_price[$i],
                        'internal_price_percentage' => $request->internal_price_percentage[$i],
                        'social_price_percentage' => $request->social_price_percentage[$i],
                    ]);
                }
            }

            DB::commit();
            notyf()->ripple(true)->success('Aset berhasil diperbarui!');
            if ($request->jurusan) {
                return redirect()->route('assets.fasjur', $request->jurusan);
            } else {
                return redirect()->route('assets.fasum');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            notyf()->ripple(true)->error('Terjadi kesalahan saat memperbarui aset.');
            return redirect()->back();
        }
    }
    public function updateAssetPage($id)
    {
        $kode_jurusan = null;
        if (Auth::user()->roles()->where('name', 'Admin Jurusan')->exists()) {
            $kode_jurusan = Auth::user()->jurusan->kode_jurusan;
            if (optional(Auth::user()->jurusan)->kode_jurusan !== $kode_jurusan) {
                return abort(403, 'Unauthorized');
            }
        }
        $asset = Asset::with('categories')->findOrFail($id);

        return view('dashboardPage.assets.update-asset', compact('asset', 'kode_jurusan'));
    }
    public function destroyAsset($id)
    {
        try {
            // Ambil data aset
            Asset::destroy($id);

            notyf()->ripple(true)->success('Menghapus data aset berhasil!');
            return redirect()->back();
        } catch (\Exception $e) {
            notyf()->ripple(true)->error('Terjadi kesalahan saat menghapus data aset.');
            return redirect()->back();
        }
    }
}