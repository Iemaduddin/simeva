<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'simevasuper',
            'email' => 'superadmin.simeva@polinema.ac.id',
            'password' => Hash::make('simevasuper2025'),
            'category_user' => 'Internal Kampus',
        ]);
        $kaur_rt = User::create([
            'name' => 'Santosa',
            'username' => 'kaur_rt',
            'email' => 'kaurrt.simeva@polinema.ac.id',
            'password' => Hash::make('simevakaurrt2025'),
            'category_user' => 'Internal Kampus',
        ]);


        $uptPU = User::create([
            'name' => 'Admin UPT PU',
            'username' => 'upt_pu',
            'email' => 'uptpu.simeva@polinema.ac.id',
            'password' => Hash::make('simevauptpu2025'),
            'category_user' => 'Internal Kampus',
        ]);
        $participantUser = User::create([
            'name' => 'Didin',
            'username' => 'didin',
            'email' => 'didin@email.com',
            'password' => Hash::make('password'),
            'category_user' => 'Eksternal Kampus',
        ]);
        $tenantUser = User::create([
            'name' => 'Tenant',
            'username' => 'tenant',
            'email' => 'tenant@email.com',
            'password' => Hash::make('password'),
            'category_user' => 'Eksternal Kampus',
        ]);

        $kodeJurusanList = ['TI', 'TS', 'TE', 'TM', 'TK', 'AK', 'AN'];

        foreach ($kodeJurusanList as $kode) {
            $jurusan = Jurusan::where('kode_jurusan', $kode)->first();

            $adminJurusan = User::create([
                'name' => 'Admin Jurusan ' . $kode,
                'username' => 'adminjurusan' . strtolower($kode),
                'email' => 'adminjurusan' . strtolower($kode) . '.simeva@polinema.ac.id',
                'password' => Hash::make('simevaadminjurusan' . strtolower($kode) . '2025'),
                'jurusan_id' => $jurusan->id, // Menggunakan null safe operator (PHP 8+)
            ]);
            $adminJurusan->assignRole('Admin Jurusan');
        }

        $superAdmin->assignRole('Super Admin');
        $kaur_rt->assignRole('Kaur RT');

        $uptPU->assignRole('UPT PU');
        $participantUser->assignRole('Participant');
        $tenantUser->assignRole('Tenant');
    }
}
