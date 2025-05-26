<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\EventSpeaker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventSpeakerManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_event_speaker_can_be_added()
    {
        $admin = User::where('username', 'simevasuper')->first();

        $speakerData = [
            'event_step_id' => '9ef840f9-7430-4985-98ae-9a7dc05d76b4',
            'name' => 'Prof.Dr.Eng ABCD, M.MT.',
            'role' => 'Pemateri',
        ];


        $response = $this->actingAs($admin)->post('/events/add-speaker', $speakerData);

        $response->assertStatus(201); // redirect after success

        $this->assertDatabaseHas('event_speakers', [
            'event_step_id' => '9ef840f9-7430-4985-98ae-9a7dc05d76b4',
            'name' => 'Prof.Dr.Eng ABCD, M.MT.',
            'role' => 'Pemateri',
        ]);
    }
    public function test_event_speaker_can_be_updated()
    {
        // Buat user dengan role Super Admin
        $admin = User::where('username', 'simevasuper')->first();
        $speaker = EventSpeaker::where('event_step_id', '9ef840f9-7430-4985-98ae-9a7dc05d76b4')->first();


        // Data update, gunakan username dan email dari user supaya valid
        $updateData = [
            'event_step_id' => '9ef840f9-7430-4985-98ae-9a7dc05d76b4',
            'name' => 'Prof.Dr.Eng DEF, M.MT.',
            'role' => 'Pemateri',
        ];

        // Login sebagai user yang sudah assign role
        $response = $this->actingAs($admin)
            ->put("/events/update-speaker/{$speaker->id}", $updateData);

        // Assert response berhasil
        $response->assertStatus(201);

        // Pastikan data di database sudah berubah
        $this->assertDatabaseHas('event_speakers', [
            'id' => $speaker->id,
            'name' => 'Prof.Dr.Eng DEF, M.MT.',
            'role' => 'Pemateri',
        ]);
    }



    public function test_event_speaker_can_be_deleted()
    {
        $speaker = EventSpeaker::where('event_step_id', '9ef840f9-7430-4985-98ae-9a7dc05d76b4')->first();


        $admin = User::where('username', 'simevasuper')->first();


        $response = $this->actingAs($admin)
            ->delete("/events/destroy-speaker/{$speaker->id}");

        $response->assertStatus(201);

        $this->assertDatabaseMissing('event_speakers', [
            'id' => $speaker->id,
        ]);
    }

    public function test_invitation_letter_event_speaker()
    {

        $speaker = EventSpeaker::where('event_step_id', '9ef840f9-7430-4985-98ae-9a7dc05d76b4')->first();

        $admin = User::where('username', 'simevasuper')->first();
        $postData = [
            'leader' => 7,
            'letter_number' => "XXX/HXXX/IX.2025",
        ];

        $response = $this->actingAs($admin)
            ->post("/events/invitation-speaker/{$speaker->id}", $postData);

        // Assert response berhasil
        $response->assertStatus(200);
    }
}
