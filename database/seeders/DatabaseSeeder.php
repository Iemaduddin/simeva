<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Stakeholder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            JurusanProdiSeeder::class,
            UserSeeder::class,
            AssetSeeder::class,
            TeamMemberSeeder::class,
            MahasiswaSeeder::class,
            StakeholderSeeder::class,
        ]);
    }
}