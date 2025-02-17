<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat role
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $organizer = Role::firstOrCreate(['name' => 'Organizer']);
        $kaur_rt = Role::firstOrCreate(['name' => 'Kaur RT']);
        $admin_jurusan = Role::firstOrCreate(['name' => 'Admin Jurusan']);
        $upt_pu = Role::firstOrCreate(['name' => 'UPT PU']);
        $participant = Role::firstOrCreate(['name' => 'Participant']);
        $tenant = Role::firstOrCreate(['name' => 'Tenant']);

        // Daftar permissions
        $permissions = [
            // Manage Users
            'users.manage_all' => [$superAdmin],

            // Manage Events
            'events.manage_all' => [$superAdmin],
            'events.manage_own' => [$organizer],
            'events.register' => [$participant],
            'events.feedback' => [$participant],
            'participants.manage' => [$organizer],

            // Manage Assets
            'assets.manage_all' => [$superAdmin],
            'assets_fasum.manage' => [$kaur_rt],
            'assets_fasjur.manage' => [$admin_jurusan],
            'assets.request_booking' => [$tenant],
            'assets_fasum.approve_request_booking' => [$upt_pu, $superAdmin],
            'assets_fasjur.approve_request_booking' => [$admin_jurusan, $superAdmin],

            // Manage Schedule
            'schedules.manage_all' => [$superAdmin],
            'schedules.manage_own' => [$organizer],
        ];

        foreach ($permissions as $permission => $roles) {
            // Buat permission jika belum ada
            $perm = Permission::firstOrCreate(['name' => $permission]);

            // Berikan permission ke setiap role yang sesuai
            foreach ($roles as $role) {
                $role->givePermissionTo($perm);
            }
        }
    }
}
