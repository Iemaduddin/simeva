<?php

namespace Tests\Feature;

use App\Models\Organizer;
use Tests\TestCase;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventManagementTest extends TestCase
{
    /**
     * A basic feature test example.
     */


    public function test_event_can_be_created()
    {
        $organizer = Organizer::first();
        $event = Event::create([
            'id' => Str::uuid(),
            'organizer_id' => $organizer->id,
            'title' => 'Test Event',
            'theme' => 'test_event',
            'description' => 'Deskripsi acara',
            'scope' => 'Umum',
            'event_category' => 'Seminar',
            'event_leader' => 'John Doe',
            'status' => 'planned',
        ]);
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Test Event',
        ]);
    }

    public function test_event_can_be_updated()
    {
        $event = Event::where('theme', 'test_event')->first();

        $event->update([
            'title' => 'Updated Title',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Title',
            'status' => 'published',
        ]);
    }

    public function test_event_can_be_blocked()
    {
        $event = Event::where('theme', 'test_event')->first();



        $event->update([
            'status' => 'blocked',
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => 'blocked',
        ]);
    }

    public function test_event_can_be_deleted()
    {
        $event = Event::where('theme', 'test_event')->first();


        $event->delete();

        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }
}
