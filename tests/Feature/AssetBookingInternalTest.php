<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Event;
use App\Models\Asset;
use App\Models\AssetBooking;
use App\Models\AssetBookingDocument;
use App\Models\Organizer;
use App\Models\Jurusan;

class AssetBookingInternalTest extends TestCase
{

    // public function test_user_can_upload_asset_document_for_internal_campus()
    // {
    //     Storage::fake('public');

    //     // Setup data
    //     $jurusan = Jurusan::first();
    //     $organizer = Organizer::where('shorten_name', 'HMTI')->first();
    //     $user = User::whereHas('organizer', function ($query) {
    //         $query->where('shorten_name', 'HMTI');
    //     })->first();

    //     $event = Event::where('organizer_id', $organizer->id)->first();
    //     $asset = Asset::where('facility_scope', 'umum')->first();
    //     $booking = AssetBooking::create([
    //         'asset_id' => $asset->id,
    //         'user_id' => $user->id,
    //         'booking_number' => 'BOOK-' . Str::random(6),
    //         'event_id' => $event->id,
    //         'usage_date_start' => now()->addDays(1),
    //         'usage_date_end' => now()->addDays(2),
    //         'usage_event_name' => 'Simulasi Event Booking Aset',
    //         'status' => 'submission_booking',
    //     ]);


    //     // Simulasi file upload
    //     $file = UploadedFile::fake()->create('loan_letter.pdf', 100, 'application/pdf');

    //     $this->actingAs($user)
    //         ->postJson(route('assetBookingEvent.uploadDocument', $event->id), [
    //             'asset_jurusan' => 'false',
    //             'loan_letter' => $file,
    //         ])
    //         ->assertStatus(201)
    //         ->assertJson([
    //             'status' => 'success',
    //             'message' => 'Unggah Surat Peminjaman berhasil!',
    //         ]);

    //     // Cek status booking berubah
    //     $this->assertDatabaseHas('asset_bookings', [
    //         'id' => $booking->id,
    //         'status' => 'submission_full_payment',
    //     ]);

    //     $booking->delete();
    //     // Cek file disimpan
    //     // Storage::disk('public')->assertExists('Booking Aset Event/hmti/TestEventExampleName/Surat Peminjaman Fasjur.pdf');


    //     // Cek AssetBookingDocument tercatat
    //     // $this->assertDatabaseHas('asset_booking_documents', [
    //     //     'booking_id' => $booking->id,
    //     //     'document_type' => 'Form Peminjaman',
    //     // ]);
    // }
    // public function test_confirm_document_loan_internal()
    // {
    //     // Gunakan fake storage jika dibutuhkan
    //     Storage::fake('public');

    //     // Setup data user & auth
    //     $jurusan = Jurusan::first();
    //     $organizer = Organizer::where('shorten_name', 'HMTI')->first();
    //     $user = User::where('username', 'simevasuper')->first();

    //     $event = Event::where('organizer_id', $organizer->id)->first();
    //     $asset = Asset::where('facility_scope', 'umum')->first();
    //     $booking = AssetBooking::create([
    //         'asset_id' => $asset->id,
    //         'user_id' => $user->id,
    //         'booking_number' => 'BOOK-' . Str::random(6),
    //         'event_id' => $event->id,
    //         'usage_date_start' => now()->addDays(1),
    //         'usage_date_end' => now()->addDays(2),
    //         'usage_event_name' => 'Simulasi Event Booking Aset',
    //         'status' => 'submission_full_payment',
    //     ]);

    //     // 🔄 Test status menjadi approved
    //     $this->actingAs($user)
    //         ->postJson(route('assetBookingEvent.confirmDocument', $event->id), [
    //             'actionConfirmDocument' => 'approved',
    //         ])
    //         ->assertStatus(201)
    //         ->assertJson([
    //             'status' => 'success',
    //             'message' => 'Konfirmasi surat peminjaman berhasil!',
    //         ]);

    //     $this->assertDatabaseHas('asset_bookings', [
    //         'id' => $booking->id,
    //         'status' => 'approved',
    //     ]);

    //     // Reset status dan coba rejected
    //     $booking->update(['status' => 'submission_full_payment']);

    //     // 🔄 Test status menjadi rejected_full_payment
    //     $this->actingAs($user)
    //         ->postJson(route('assetBookingEvent.confirmDocument', $event->id), [
    //             'actionConfirmDocument' => 'rejected',
    //             'reason_rejected' => 'Berkas tidak valid',
    //         ])
    //         ->assertStatus(201)
    //         ->assertJson([
    //             'status' => 'success',
    //             'message' => 'Konfirmasi surat peminjaman berhasil!',
    //         ]);

    //     $this->assertDatabaseHas('asset_bookings', [
    //         'id' => $booking->id,
    //         'status' => 'rejected_full_payment',
    //         'reason' => 'Berkas tidak valid',
    //     ]);
    //     $booking->delete();
    // }

    // public function test_user_can_download_surat_disposisi()
    // {
    //     // Ambil data booking dan dokumen
    //     $booking = AssetBooking::findOrFail('9ef8abe7-44cc-4aad-b797-cdd26227857e');
    //     $document = AssetBookingDocument::where('booking_id', $booking->id)
    //         ->where('document_type', 'Surat Disposisi')
    //         ->firstOrFail();

    //     // Path relatif dari storage/app/public
    //     $storedPath = $document->document_path;

    //     // URL yang dihasilkan
    //     $expectedUrl = asset('storage/' . $storedPath) . '?v=' . $document->updated_at->timestamp;


    //     // Assert file benar-benar tersimpan di storage disk 'public'
    //     $this->assertTrue(
    //         Storage::disk('public')->exists($storedPath),
    //         "File tidak ditemukan di storage: {$storedPath}"
    //     );

    //     // Assert URL download mengandung path dan timestamp
    //     $this->assertStringContainsString('storage/' . $storedPath, $expectedUrl);
    //     $this->assertStringContainsString('?v=', $expectedUrl);
    // }

    public function test_user_can_add_manual_asset_booking_internal()
    {

        Storage::fake('public');

        $user = User::where('username', 'simevasuper')->first();
        $organizer = Organizer::where('shorten_name', 'HMTI')->first();

        $asset = Asset::where('facility_scope', 'umum')->first();

        $date = now()->addDays(1)->format('Y-m-d');
        $timeStart = '08:00';
        $timeEnd = '10:00';

        $payload = [
            'user_organizer' => $organizer->id,
            'step_names' => ['Test Event'],
            'assets' => [$asset->id],
            'status' => ['approved'],
            'event_dates' => [$date],
            'event_time_starts' => [$timeStart],
            'event_time_ends' => [$timeEnd],
        ];

        $response = $this->actingAs($user)->post(route('assetBookingEvent.addManual'), $payload);

        $response->assertRedirect();

        $this->assertDatabaseHas('asset_bookings', [
            'asset_id' => $asset->id,
            'usage_event_name' => 'Test Event',
            'status' => 'approved',
        ]);
    }
    public function test_confirm_asset_booking()
    {


        $user = User::where('username', 'simevasuper')->first();
        $organizer = Organizer::where('shorten_name', 'HMTI')->first();

        $asset = Asset::where('facility_scope', 'umum')->first();

        $event = Event::where('organizer_id', $organizer->id)->first();
        // Buat booking dummy
        $booking = AssetBooking::where('user_id', $organizer->user->id)->latest()->first();


        $payload = [
            'bookings' => [
                [
                    'id' => $booking->id,
                    'status' => 'approved'
                ]
            ]
        ];

        $response = $this->actingAs($user)->postJson("/events/confirm-asset-booking-event/{$event->id}", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Booking aset berhasil dikonfirmasi!',
        ]);

        $this->assertDatabaseHas('asset_bookings', [
            'id' => $booking->id,
            'status' => 'booked',
            'reason' => null,
        ]);
    }
    public function test_confirm_done_asset_booking()
    {
        $user = User::where('username', 'simevasuper')->first();
        $organizer = Organizer::where('shorten_name', 'HMTI')->first();

        $asset = Asset::where('facility_scope', 'umum')->first();

        $booking = AssetBooking::where('user_id', $organizer->user->id)->latest()->first();



        $payload = [
            'existEventId' => 'nothing',
        ];

        $response = $this->actingAs($user)
            ->postJson("/events/confirm-done-asset-booking-event/{$booking->id}", $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Konfirmasi peminjaman selesai berhasil!',
        ]);

        $this->assertDatabaseHas('asset_bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);
    }
}
