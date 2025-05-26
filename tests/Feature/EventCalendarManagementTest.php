<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Calendar;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventCalendarManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_super_admin_can_add_all_day_event()
    {
        $user = User::where('username', 'simevasuper')->first();

        $this->actingAs($user);

        $data = [
            'all_day_true' => true,
            'event_date_start' => '2025-06-01',
            'event_date_end' => '2025-06-02',
            'title' => 'Event All Day',
            'is_public' => true,
        ];

        $response = $this->postJson('/calendar-event/add-event-calendar', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Event berhasil ditambahkan pada kalender!',
            ]);

        $this->assertDatabaseHas('calendars', [
            'title' => 'Event All Day',
            'all_day' => true,
            'is_public' => true,
        ]);
    }

    public function test_organizer_can_add_non_all_day_event()
    {
        $user = User::where('username', 'hmti_polinema')->first();

        $this->actingAs($user);

        $data = [
            'event_date' => '2025-06-10',
            'time_start' => '10:00',
            'time_end' => '12:00',
            'title' => 'Event Non All Day',
            'all_day' => false,
            'is_public' => true, // tapi Organizer tidak bisa set public, harus false di controller
        ];

        $response = $this->postJson('/calendar-event/add-event-calendar', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Event berhasil ditambahkan pada kalender!',
            ]);

        $this->assertDatabaseHas('calendars', [
            'title' => 'Event Non All Day',
            'all_day' => false,
            'is_public' => false, // harus false karena Organizer
            'organizer_id' => $user->organizer->id,
        ]);
    }

    public function test_validation_fails_when_required_fields_missing()
    {
        $user = User::where('username', 'simevasuper')->first();

        $this->actingAs($user);

        $data = [
            'all_day' => false,
        ];

        $response = $this->postJson('/calendar-event/add-event-calendar', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    // error validation harus ada sesuai rules
                    'event_date',
                    'time_start',
                    'time_end',
                    'title',
                ],
            ]);
    }
    public function test_update_event_calendar()
    {
        $user = User::where('username', 'simevasuper')->first();
        // Pastikan user punya role yg benar
        $this->actingAs($user);

        // Buat data event dummy dulu (gunakan model Calendar)
        $event = Calendar::create([
            'title' => 'Event Lama',
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'all_day' => false,
            'is_public' => true,
            'organizer_id' => null,
        ]);

        // Data update
        $updateData = [
            'event_date' => now()->toDateString(),
            'time_start' => '10:00',
            'time_end' => '12:00',
            'title' => 'Event Updated',
            'all_day' => false,
            'is_public' => true,
        ];

        $response = $this->putJson("/calendar-event/update-event-calendar/{$event->id}", $updateData);

        $response->assertStatus(201);


        // Cek di database, apakah data sudah berubah
        $this->assertDatabaseHas('calendars', [
            'id' => $event->id,
            'title' => 'Event Updated',
        ]);

        $event->delete();
    }
    public function test_delete_event_calendar()
    {
        $user = User::where('username', 'simevasuper')->first();

        $user->assignRole('Super Admin');  // Atau Organizer, sesuai kebutuhan
        $this->actingAs($user);

        // Buat event dummy dulu
        $event = Calendar::create([
            'title' => 'Event Lama To Delete',
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'all_day' => false,
            'is_public' => true,
            'organizer_id' => null,
        ]);
        $response = $this->deleteJson("/calendar-event/destroy-event-calendar/{$event->id}");

        $response->assertStatus(201);

        // Pastikan data event sudah hilang di database
        $this->assertDatabaseMissing('calendars', [
            'id' => $event->id,
        ]);
    }
}
