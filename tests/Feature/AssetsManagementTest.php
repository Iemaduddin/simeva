<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Asset;
use App\Models\Jurusan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetsManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_add_new_asset()
    {
        Storage::fake('public');
        $user = User::where('username', 'simevasuper')->first();
        $jurusan = Jurusan::where('kode_jurusan', 'TI')->first();

        $image1 = UploadedFile::fake()->image('asset1.jpg');
        $image2 = UploadedFile::fake()->image('asset2.png');

        $response = $this->actingAs($user)->post('/assets/store-asset', [
            'name' => 'Gedung Serbaguna A',
            'description' => 'Gedung ini digunakan untuk keperluan acara institusi.',
            'type' => 'building',
            'facility_scope' => 'umum',
            'jurusan_id' => $jurusan->id,
            'facility' => ['AC|Proyektor'],
            'available_at' => ['Senin|Rabu|Jumat'],
            'asset_images' => [$image1, $image2],
            'booking_type' => 'daily',
        ]);

        $response->assertRedirect();

        // $this->assertDatabaseHas('assets', [
        //     'name' => 'Gedung Serbaguna A',
        //     'type' => 'building',
        //     'facility_scope' => 'umum',
        //     'booking_type' => 'daily',
        // ]);
    }

    public function test_update_existing_asset()
    {
        Storage::fake('public');
        $user = User::where('username', 'simevasuper')->first();

        // Buat asset dummy terlebih dahulu
        $asset = Asset::create([
            'name' => 'Gedung Lama',
            'type' => 'building',
            'facility_scope' => 'umum',
            'facility' => 'AC|Kursi',
            'booking_type' => 'daily',
            'asset_images' => json_encode([]),
        ]);

        // Buat gambar baru untuk update
        $image1 = UploadedFile::fake()->image('updated1.jpg');
        $image2 = UploadedFile::fake()->image('updated2.jpg');

        // Kirim permintaan update
        $response = $this->actingAs($user)->put(route('update.asset', $asset->id), [
            'name' => 'Gedung Baru',
            'description' => 'Gedung yang diperbarui',
            'type' => 'building',
            'facility_scope' => 'umum',
            'facility' => ['AC', 'Proyektor', 'Kursi'],
            'available_at' => ['Selasa', 'Kamis'],
            'asset_images' => [$image1, $image2],
            'booking_type' => 'daily',
        ]);

        $response->assertRedirect();

        // Cek apakah data berubah di database
        // $this->assertDatabaseHas('assets', [
        //     'id' => $asset->id,
        //     'name' => 'Gedung Baru',
        //     'description' => 'Gedung yang diperbarui',
        // ]);
    }

    public function test_user_can_delete_asset()
    {
        $user = User::where('username', 'simevasuper')->first();

        $asset = Asset::create([
            'name' => 'Gedung yang Akan Dihapus',
            'type' => 'building',
            'facility_scope' => 'umum',
            'facility' => 'AC|Kursi',
            'booking_type' => 'daily',
            'asset_images' => json_encode([]),
        ]);

        $response = $this->actingAs($user)->delete(route('destroy.asset', $asset->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('assets', [
            'id' => $asset->id,
        ]);
    }
}
