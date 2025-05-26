<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\EventStep;
use App\Models\Organizer;
use App\Models\TeamMember;
use App\Models\EventAttendance;
use App\Models\EventParticipant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceEventParticipantTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_checkin_attendance_for_single_member()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();

        $teamMember = TeamMember::findOrFail(76);
        $eventStep = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');

        $this->actingAs($user)
            ->postJson(route('events.attendanceMember', [
                'memberId' => $teamMember->id,
                'checkType' => 'checkin'
            ]), [
                'event_step_id' => $eventStep->id,
            ])
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('event_attendance', [
            'team_member_id' => $teamMember->id,
            'event_step_id' => $eventStep->id,
            'attendance_arrival' => true,
        ]);
    }

    public function test_checkout_attendance_for_single_member()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();

        $teamMember = TeamMember::findOrFail(76);

        $eventStep = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');


        $this->actingAs($user)
            ->postJson(route('events.attendanceMember', [
                'memberId' => $teamMember->id,
                'checkType' => 'checkout'
            ]), [
                'event_step_id' => $eventStep->id,
            ])
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);
        $this->assertDatabaseHas('event_attendance', [
            'team_member_id' => $teamMember->id,
            'event_step_id' => $eventStep->id,
            'attendance_departure' => true,
        ]);
    }

    public function test_checkin_attendance_for_all_members()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();

        $members = TeamMember::where('organizer_id', $user->organizer->id)->get();

        $eventStep = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');
        foreach ($members as $member) {
            EventAttendance::create([
                'team_member_id' => $member->id,
                'event_step_id' => $eventStep->id,
                'attendance_arrival' => true,
                'attendance_departure' => false,
            ]);
        }

        $this->actingAs($user)
            ->postJson(route('events.attendanceMemberAll', [
                'eventStepId' => $eventStep->id,
                'checkType' => 'checkin'
            ]))
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);

        foreach ($members as $member) {
            $this->assertDatabaseHas('event_attendance', [
                'team_member_id' => $member->id,
                'event_step_id' => $eventStep->id,
                'attendance_arrival' => true,
            ]);
        }
    }

    public function test_checkout_attendance_for_all_members()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();

        $members = TeamMember::where('organizer_id', $user->organizer->id)->get();



        $eventStep = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');


        $this->actingAs($user)
            ->postJson(route('events.attendanceMemberAll', [
                'eventStepId' => $eventStep->id,
                'checkType' => 'checkout'
            ]))
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);


        foreach ($members as $member) {
            $this->assertDatabaseHas('event_attendance', [
                'team_member_id' => $member->id,
                'event_step_id' => $eventStep->id,
                'attendance_departure' => true,
            ]);
        }
    }
    public function test_checkin_attendance_for_participant()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();
        $step = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');

        $participant = EventParticipant::where('ticket_code', '4803864B470D9DA8')->first();

        $response = $this->actingAs($user)->postJson(route('events.attendanceParticipant'), [
            'ticket_code' => '4803864B470D9DA8',
            'event_step_id' => $step->id,
            'attendance_type' => 'arrival'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Presensi peserta berhasil!',
            ]);

        $this->assertDatabaseHas('event_attendance', [
            'event_participant_id' => $participant->id,
            'event_step_id' => $step->id,
            'attendance_arrival' => true,
        ]);
    }

    public function test_checkout_attendance_for_participant()
    {

        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();
        $step = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');

        $participant = EventParticipant::where('ticket_code', '4803864B470D9DA8')->first();

        EventAttendance::create([
            'event_participant_id' => $participant->id,
            'event_step_id' => $step->id,
            'attendance_arrival' => true,
            'attendance_arrival_time' => now(),
        ]);

        $response = $this->actingAs($user)->postJson(route('events.attendanceParticipant'), [
            'ticket_code' => '4803864B470D9DA8',
            'event_step_id' => $step->id,
            'attendance_type' => 'departure'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Presensi peserta berhasil!',
            ]);

        $this->assertDatabaseHas('event_attendance', [
            'event_participant_id' => $participant->id,
            'event_step_id' => $step->id,
            'attendance_departure' => true,
        ]);
    }

    public function test_checkout_attendance_for_participant_whithout_arrival_attendance()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();
        $step = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');

        $participant = EventParticipant::where('ticket_code', '4803864B4413AC51')->first();

        $response = $this->actingAs($user)->postJson(route('events.attendanceParticipant'), [
            'ticket_code' => '4803864B4413AC51',
            'event_step_id' => $step->id,
            'attendance_type' => 'departure'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Presensi gagal. Peserta belum presensi datang.',
            ]);
    }

    public function test_checkout_attendance_for_participant_with_invalid_code()
    {
        $user = User::whereHas('organizer', function ($query) {
            $query->where('shorten_name', 'HMTI');
        })->first();
        $step = EventStep::findOrFail('9e8dc39a-91dd-491f-bc3d-02d3d7d2ee81');


        $response = $this->actingAs($user)->postJson(route('events.attendanceParticipant'), [
            'ticket_code' => 'INVALIDCODE',
            'event_step_id' => $step->id,
            'attendance_type' => 'arrival'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Peserta tidak ditemukan.',
            ]);
    }
}
