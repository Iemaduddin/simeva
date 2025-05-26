<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_be_added()
    {
        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'phone_number' => '081234567890',
            'address' => 'Jakarta',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_type' => 'participant',
        ];

        // Kirim POST request ke route register (sesuaikan route-mu)
        $response = $this->post('/register', $userData);

        $response->assertStatus(302); // redirect after success

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'name' => 'John Doe',
        ]);
    }
    public function test_user_can_be_updated()
    {
        // Buat user dengan role Super Admin
        $admin = User::where('username', 'simevasuper')->first();
        $user = User::factory()->create();


        // Data update, gunakan username dan email dari user supaya valid
        $updateData = [
            'name' => 'Updated Name',
            'username' => $user->username,
            'email' => $user->email,
            'phone_number' => '089876543210',
            // 'address' => 'Bandung',
        ];

        // Login sebagai user yang sudah assign role
        $response = $this->actingAs($admin)
            ->put("/tenant-users/update-tenantuser/{$user->id}", $updateData);

        // Assert response berhasil
        // $response->assertStatus(200);

        // Pastikan data di database sudah berubah
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone_number' => '089876543210',
            // 'address' => 'Bandung',
        ]);
    }

    public function test_user_can_be_blocked_and_unblocked()
    {
        $admin = User::where('username', 'simevasuper')->first();


        $user = User::factory()->create(['is_blocked' => false]);

        // Block user
        $responseBlock = $this->actingAs($admin)
            ->put("/tenant-users/block-tenantuser/block/{$user->id}");

        $responseBlock->assertStatus(201); // 200 sesuai di controller

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_blocked' => true,
        ]);

        // Unblock user
        $responseUnblock = $this->actingAs($admin)
            ->put("/tenant-users/block-tenantuser/unblock/{$user->id}");

        $responseUnblock->assertStatus(201); // 200 sesuai di controller

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_blocked' => false,
        ]);
    }

    public function test_user_can_be_deleted()
    {
        $user = User::factory()->create();

        $admin = User::where('username', 'simevasuper')->first();


        $response = $this->actingAs($admin)
            ->delete("/tenant-users/destroy-tenantuser/{$user->id}");

        $response->assertStatus(201);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
