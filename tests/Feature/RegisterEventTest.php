<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterEventTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_register_event()
    {
        $user = User::where('username', 'mahasiswa_3_D4-TI')->first();

        $event = Event::findOrFail('9ef810be-b8e1-4e12-96e3-59a3a7835c89');


        $response = $this->actingAs($user)->post("/events/register/{$event->id}", [
            'proof_of_payment' => UploadedFile::fake()->image('bukti.jpg'),
            'price' => 50000,
        ]);


        $response->assertStatus(302); // redirect after success

        $this->assertDatabaseHas('event_participants', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'pending_approval',
        ]);

        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        $participant->status = 'rejected';
        $participant->save();
    }
    public function test_repeat_register_event()
    {
        $user = User::where('username', 'mahasiswa_3_D4-TI')->first();

        $event = Event::findOrFail('9ef810be-b8e1-4e12-96e3-59a3a7835c89');

        $response = $this->actingAs($user)->post("/events/repeat-register/{$event->id}", [
            'proof_of_payment' => UploadedFile::fake()->image('bukti.jpg'),
            'price' => 50000,
        ]);



        $response->assertStatus(302); // redirect after success

        $this->assertDatabaseHas('event_participants', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'pending_approval',
        ]);
    }
    public function test_confirm_register_event()
    {
        $admin = User::where('username', 'simevasuper')->first();
        $user = User::where('username', 'mahasiswa_3_D4-TI')->first();

        $event = Event::findOrFail('9ef810be-b8e1-4e12-96e3-59a3a7835c89');
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)->where('status', 'pending_approval')
            ->first();

        $postData = [
            'statusRegistration' => 'approved',
            'event_id' => $event->id,
        ];
        $response = $this->actingAs($admin)->post("/events/confirm-registration-participants/{$participant->id}", $postData);


        $response->assertStatus(201); // redirect after success

        $this->assertDatabaseHas('event_participants', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
        ]);
    }

    public function test_download_eticket_event()
    {

        $user = User::where('username', 'mahasiswa_3_D4-TI')->first();

        $event = Event::findOrFail('9ef810be-b8e1-4e12-96e3-59a3a7835c89');
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)->where('status', 'registered')
            ->first();

        $response = $this->actingAs($user)->get("/events/e-ticket/{$participant->id}");

        $response->assertStatus(200); // redirect after success

    }
}
