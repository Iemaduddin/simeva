<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Jurusan;
use Illuminate\Support\Str;
use App\Models\AssetCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets =
            [
                [
                    'name' => 'Graha Politeknik Negeri Malang',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],
                [
                    'name' => 'Aula Pertamina Politeknik Negeri Malang',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],
                [
                    'name' => 'Auditorium Gedung Teknik Sipil Lantai 8',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],
                [
                    'name' => 'Graha Theater',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Sabtu|Minggu|Senin|Selasa',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],
                [
                    'name' => 'Masjid Raya An-Nur Polinema',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],
                [
                    'name' => 'Wisma Polinema',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'annual',
                ],
                [
                    'name' => 'Kantin AX',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'annual',
                ],
                [
                    'name' => 'Kantin Tekkim',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'building',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'annual',
                ],
                [
                    'name' => 'Bus Polinema',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'transportation',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],
                [
                    'name' => 'Mobil Avanza',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                    'type' => 'transportation',
                    'facility_scope' => 'umum',
                    'facility' => "AC|Toilet|Parkir|dasdasdasdasd|dsadasffgfdgd",
                    'available_at' => 'Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu',
                    'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                    'booking_type' => 'daily',
                ],

            ];
        foreach ($assets as $asset) {
            $asset['id'] = Str::uuid();
            Asset::create($asset);

            $assetCategories = [
                [
                    'asset_id' => $asset['id'],
                    'category_name' => 'Resepsi Pernikahan',
                    'external_price' => 30000000,
                    'internal_price_percentage' => 75,
                    'social_price_percentage' => 50,
                ],
                [
                    'asset_id' => $asset['id'],
                    'category_name' => 'Seminar',
                    'external_price' => 40000000,
                    'internal_price_percentage' => 75,
                    'social_price_percentage' => 50,
                ],
            ];
            foreach ($assetCategories as $category) {
                AssetCategory::create($category);
            }
        }

        $jurusans = Jurusan::all();
        foreach ($jurusans as $index => $jurusan) {
            Asset::create([
                'id' => Str::uuid(),
                'name' => 'Ruang Teori ' . $index + 1,
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum id fugit reiciendis doloribus ducimus officia ad voluptatum quisquam, molestias quis aliquam. Vero molestiae quo tempore debitis ipsam odit nam praesentium, amet dicta tempora quas nihil sapiente inventore aut totam accusamus voluptatum rem. Quibusdam repellendus officiis necessitatibus! Minima aperiam magni velit?',
                'type' => 'building',
                'facility_scope' => 'jurusan',
                'jurusan_id' => $jurusan->id,
                'facility' => "AC,proyektor,dasdasdasdasd,dsadasffgfdgd",
                'available_at' => 'Sabtu|Minggu',
                'asset_images' => '["Gambar Asset\/Fasum\/Graha Polinema\/2.jpg","Gambar Asset\/Fasum\/Graha Polinema\/1.jpg","Gambar Asset\/Fasum\/Graha Polinema\/3.jpg","Gambar Asset\/Fasum\/Graha Polinema\/4.jpg"]',
                'booking_type' => 'daily',
            ]);
        }
    }
}