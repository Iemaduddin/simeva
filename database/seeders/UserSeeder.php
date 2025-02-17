<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        ]);
        $kaur_rt = User::create([
            'name' => 'Santosa',
            'username' => 'kaur_rt',
            'email' => 'kaurrt.simeva@polinema.ac.id',
            'password' => Hash::make('simevakaurrt2025'),
        ]);
        $adminJurusanTI = User::create([
            'name' => 'Admin Jurusan TI',
            'username' => 'adminjurusanti',
            'email' => 'adminjurusanti.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusanti2025'),
        ]);
        $adminJurusanTE = User::create([
            'name' => 'Admin Jurusan TE',
            'username' => 'adminjurusante',
            'email' => 'adminjurusante.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusante2025'),
        ]);
        $adminJurusanTM = User::create([
            'name' => 'Admin Jurusan TM',
            'username' => 'adminjurusantm',
            'email' => 'adminjurusantm.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusantm2025'),
        ]);
        $adminJurusanTS = User::create([
            'name' => 'Admin Jurusan TS',
            'username' => 'adminjurusants',
            'email' => 'adminjurusants.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusants2025'),
        ]);
        $adminJurusanTK = User::create([
            'name' => 'Admin Jurusan TK',
            'username' => 'adminjurusantK',
            'email' => 'adminjurusantK.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusantK2025'),
        ]);
        $adminJurusanAN = User::create([
            'name' => 'Admin Jurusan AN',
            'username' => 'adminjurusanan',
            'email' => 'adminjurusanan.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusanan2025'),
        ]);
        $adminJurusanAK = User::create([
            'name' => 'Admin Jurusan AK',
            'username' => 'adminjurusanak',
            'email' => 'adminjurusanak.simeva@polinema.ac.id',
            'password' => Hash::make('simevaadminjurusanak2025'),
        ]);
        $uptPU = User::create([
            'name' => 'Admin UPT PU',
            'username' => 'upt_pu',
            'email' => 'uptpu.simeva@polinema.ac.id',
            'password' => Hash::make('simevauptpu2025'),
        ]);

        $superAdmin->assignRole('Super Admin');
        $kaur_rt->assignRole('Kaur RT');
        $adminJurusanTI->assignRole('Admin Jurusan');
        $adminJurusanTE->assignRole('Admin Jurusan');
        $adminJurusanTM->assignRole('Admin Jurusan');
        $adminJurusanTS->assignRole('Admin Jurusan');
        $adminJurusanTK->assignRole('Admin Jurusan');
        $adminJurusanAN->assignRole('Admin Jurusan');
        $adminJurusanAK->assignRole('Admin Jurusan');
        $uptPU->assignRole('UPT PU');
    }
}
