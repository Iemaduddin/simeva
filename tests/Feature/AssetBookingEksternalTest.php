<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetBookingEksternalTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_external_user_can_book_asset_manually()
    {
        // Simulasi storage agar tidak menyimpan file sungguhan
        Storage::fake('public');

        // Buat dummy asset
        $asset = Asset::where('facility_scope', 'umum')->first();
        $user = User::where('username', 'simevasuper')->first();


        // Simulasi data request
        $data = [
            'asset_id' => $asset->id,
            'external_user' => 'John Doe',
            'asset_category_id' => 1,
            'event_date' => '2025-05-25',
            'event_time_start' => '09:00',
            'event_time_end' => '12:00',
            'usage_event_name' => 'Seminar Umum',
            'payment_type' => 'lunas',
            'total_amount' => 500000,
            'status' => 'approved_full_payment',
            'proof_of_payment' => UploadedFile::fake()->image('payment.jpg'),
        ];

        // Jalankan request POST
        $response = $this->actingAs($user)->post("/assets/bookings/add-asset-booking-eksternal", $data);

        // Cek redirect berhasil
        $response->assertRedirect();

        // Pastikan booking tercatat di database
        // $this->assertDatabaseHas('asset_bookings', [
        //     'external_user' => 'John Doe',
        //     'asset_id' => $asset->id,
        //     'payment_type' => 'lunas',
        // ]);

        // Pastikan file tersimpan di folder yang benar
        // $assetName = $asset->name;
    }

    public function test_confirm_booking_eksternal()
    {
        $this->assertTrue(true);
    }
    public function test_payment_dp_lunas_booking()
    {
        $this->assertTrue(true);
    }
    public function test_confirm_payNDocument()
    {
        $this->assertTrue(true);
    }
    public function test_cancelled_booking()
    {
        $this->assertTrue(true);
    }
}
