<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JurusanManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_jurusan_can_be_added()
    {
        $admin = User::where('username', 'simevasuper')->first();

        $jurusanData = [
            'nama' => 'Jurusan ABC',
            'kode_jurusan' => 'ABC',
        ];

        // Kirim POST request ke route register (sesuaikan route-mu)
        $response = $this->actingAs($admin)->post('/jurusan-prodi/add-jurusan', $jurusanData);

        $response->assertStatus(201); // redirect after success

        $this->assertDatabaseHas('jurusan', [
            'nama' => 'Jurusan ABC',
            'kode_jurusan' => 'ABC',
        ]);
    }
    public function test_jurusan_can_be_updated()
    {
        // Buat user dengan role Super Admin
        $admin = User::where('username', 'simevasuper')->first();
        $jurusan = Jurusan::where('kode_jurusan', 'ABC')->first();


        // Data update, gunakan username dan email dari user supaya valid
        $updateData = [
            'nama' => 'Jurusan DEF',
            'kode_jurusan' => 'DEF',
        ];

        // Login sebagai user yang sudah assign role
        $response = $this->actingAs($admin)
            ->put("/jurusan-prodi/update-jurusan/{$jurusan->id}", $updateData);

        // Assert response berhasil
        $response->assertStatus(200);

        // Pastikan data di database sudah berubah
        $this->assertDatabaseHas('jurusan', [
            'id' => $jurusan->id,
            'nama' => 'Jurusan DEF',
            'kode_jurusan' => 'DEF',
        ]);
    }



    public function test_jurusan_can_be_deleted()
    {
        $jurusan = Jurusan::where('kode_jurusan', 'DEF')->first();


        $admin = User::where('username', 'simevasuper')->first();


        $response = $this->actingAs($admin)
            ->delete("/jurusan-prodi/destroy-jurusan/{$jurusan->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $jurusan->id,
        ]);
    }
}
