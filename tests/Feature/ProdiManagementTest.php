<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Prodi;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdiManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_prodi_can_be_added()
    {
        $jurusan = Jurusan::first();
        $admin = User::where('username', 'simevasuper')->first();

        $prodiData = [
            'nama_prodi' => 'Prodi ABC',
            'kode_prodi' => 'ABC',
            'jurusan_id' => $jurusan->id,
        ];

        // Kirim POST request ke route register (sesuaikan route-mu)
        $response = $this->actingAs($admin)->post('/jurusan-prodi/add-prodi', $prodiData);

        $response->assertStatus(201); // redirect after success

        $this->assertDatabaseHas('prodi', [
            'nama_prodi' => 'Prodi ABC',
            'kode_prodi' => 'ABC',
            'jurusan_id' => $jurusan->id,
        ]);
    }
    public function test_prodi_can_be_updated()
    {
        // Buat user dengan role Super Admin
        $admin = User::where('username', 'simevasuper')->first();
        $prodi = Prodi::where('kode_prodi', 'ABC')->first();


        // Data update, gunakan username dan email dari user supaya valid
        $updateData = [
            'nama_prodi' => 'Prodi DEF',
            'kode_prodi' => 'DEF',
            'jurusan_id' => $prodi->jurusan_id,
        ];

        // Login sebagai user yang sudah assign role
        $response = $this->actingAs($admin)
            ->put("/jurusan-prodi/update-prodi/{$prodi->id}", $updateData);

        // Assert response berhasil
        $response->assertStatus(200);

        // Pastikan data di database sudah berubah
        $this->assertDatabaseHas('prodi', [
            'id' => $prodi->id,
            'nama_prodi' => 'Prodi DEF',
        ]);
    }



    public function test_prodi_can_be_deleted()
    {
        $prodi = Prodi::where('kode_prodi', 'DEF')->first();



        $admin = User::where('username', 'simevasuper')->first();


        $response = $this->actingAs($admin)
            ->delete("/jurusan-prodi/destroy-prodi/{$prodi->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $prodi->id,
        ]);
    }
}
