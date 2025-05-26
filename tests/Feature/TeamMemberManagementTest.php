<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Organizer;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamMemberManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected $user;
    protected $organizer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user and organizer
        $this->user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();

        $this->organizer = $this->user->organizer;

        // Login as user
        $this->actingAs($this->user);
    }

    public function test_can_add_team_member()
    {
        $prodi = Prodi::where('jurusan_id', $this->user->jurusan_id)->first();

        $response = $this->postJson('/team_members/add-team-member', [
            'nim' => '222202020',
            'name' => 'John Doe',
            'prodi' => $prodi->id,
            'level' => 'SC',
            'position' => 'Ketua',
            'is_leader' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Anggota tim berhasil ditambahkan!',
            ]);

        $this->assertDatabaseHas('team_members', [
            'nim' => '222202020',
            'name' => 'John Doe',
            'level' => 'SC',
            'position' => 'Ketua',
            'organizer_id' => $this->organizer->id,
            'is_leader' => true,
        ]);
    }

    public function test_can_update_team_member()
    {
        $prodi = Prodi::where('jurusan_id', $this->user->jurusan_id)->first();

        $teamMember = TeamMember::where('nim', '222202020')->first();

        $response = $this->actingAs($this->user)->putJson("/team_members/update-team-member/{$teamMember->id}", [
            'nim' => '222202020',
            'name' => 'Jane Doe',
            'prodi' => $prodi->id,
            'level' => 'OC',
            'position' => 'Sekretaris',
            'is_leader' => false,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Anggota tim berhasil diperbarui!',
            ]);

        $this->assertDatabaseHas('team_members', [
            'id' => $teamMember->id,
            'nim' => '222202020',
            'name' => 'Jane Doe',
            'level' => 'OC',
            'position' => 'Sekretaris',
            'is_leader' => false,
        ]);
    }

    public function test_can_delete_team_member()
    {
        $teamMember = TeamMember::where('nim', '222202020')->first();



        $response = $this->actingAs($this->user)->deleteJson("/team_members/destroy-team-member/{$teamMember->id}");

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Hapus anggota tim berhasil!',
            ]);

        $this->assertDatabaseMissing('team_members', [
            'id' => $teamMember->id,
        ]);
    }
}
