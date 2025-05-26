<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;
use App\Models\EventParticipant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventParticipantManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_add_event_participant()
    {
        // Buat event gratis
        $event = Event::where('is_free', true)->first();
        $admin = User::where('username', 'simevasuper')->first();

        // Data peserta eksternal
        $data = [
            'name' => 'John Doe 1',
            'username' => 'johndoe1participant',
            'email' => 'johndoe1participant@example.com',
            'phone_number' => '081234567890',
            'password' => 'password123',
            'province' => 14,
            'city' => 1404,
            'subdistrict' => 1404012,
            'village' => 1404012001,
            'address' => 'Jalan Gedongan',
        ];

        // Hit API
        $response = $this->actingAs($admin)->postJson(route('add.eventParticipant', $event->id), $data);

        // Assert sukses
        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Peserta berhasil ditambahkan!',
        ]);

        // Cek di database
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);

        $this->assertDatabaseHas('event_participants', [
            'event_id' => $event->id,
            'status' => 'registered',
        ]);
    }
    /** @test */
    public function test_can_update_event_participant_data()
    {
        $user = EventParticipant::whereHas('user', function ($query) {
            $query->where('email', 'johndoe1participant@example.com');
        })->first();
        $admin = User::where('username', 'simevasuper')->first();

        $updatedData = [
            'name' => 'Updated Participant Name 12',
            'email' => 'updatedparticipant15@example.com',
            'username' => 'johndoeparticipant15',
            'phone_number' => '081234567890',
            'password' => 'password123',
            'province' => 14,
            'city' => 1404,
            'subdistrict' => 1404012,
            'village' => 1404012001,
            'address' => 'Jalan Gedongan',
            'event_id' => $user->event->id,
        ];

        $response = $this->actingAs($admin)->putJson(route('update.eventParticipant', $user->id), $updatedData);

        // Asumsikan response 200 dan status sukses
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'id' => $user->user->id,
            'name' => 'Updated Participant Name 12',
            'email' => 'updatedparticipant15@example.com',
        ]);
    }
    public function test_can_block_an_event_participant()
    {
        // Buat user
        $user = User::factory()->create(['is_blocked' => false]);
        $admin = User::where('username', 'simevasuper')->first();

        // Panggil API block
        $response = $this->actingAs($admin)->putJson(route('block.eventParticipant', ['type' => 'block', 'id' => $user->id]));

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Blokir user berhasil!',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_blocked' => true,
        ]);
    }
    public function test_can_unblock_an_event_participant()
    {
        // Buat user yang sudah diblokir
        $user = User::factory()->create(['is_blocked' => true]);
        $admin = User::where('username', 'simevasuper')->first();

        // Panggil API unblock
        $response = $this->actingAs($admin)->putJson(route('block.eventParticipant', ['type' => 'unblock', 'id' => $user->id]));

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Membuka blokir user berhasil!',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_blocked' => false,
        ]);
    }
}
