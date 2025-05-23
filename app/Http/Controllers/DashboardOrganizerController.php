<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Event;
use App\Models\Jurusan;
use App\Models\Organizer;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;
use App\Models\EventStep;

class DashboardOrganizerController extends Controller
{
    public function index($shorten_name)
    {
        if (Auth::user()->roles()->where('name', 'Organizer')->exists()) {
            if (optional(Auth::user()->organizer)->shorten_name !== $shorten_name) {
                return abort(403, 'Unauthorized');
            }
        }

        $today = Carbon::today();
        $upcomingEvents = Event::with('organizers')->where('organizer_id', Auth::user()->organizer->id)->withMax('steps', 'event_date')
            ->where('status', 'published')
            ->having('steps_max_event_date', '>=', $today)
            ->take(5)
            ->get();
        $organizerId = Auth::user()->organizer->id;
        $newParticipants = EventParticipant::whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId)
                ->where('status', 'published');
        })
            ->with('event', 'user')
            ->latest()
            ->take(5)
            ->get();

        $sevenDaysFromNow = Carbon::today()->addDays(7);

        $assetBookings = AssetBooking::where('user_id', auth()->user()->id)
            ->where('usage_date_start', '>=', $sevenDaysFromNow)
            ->get();

        $jurusanModel = Jurusan::all();

        return view(
            'dashboardPage.dashboard.organizer',
            compact('newParticipants', 'upcomingEvents', 'assetBookings', 'jurusanModel')
        );
    }

    public function getDataEventByYear($shorten_name, $year)
    {
        try {

            $total = EventStep::whereYear('event_date', $year)
                ->whereHas('event', function ($query) use ($shorten_name) {
                    $query->whereHas('organizers', function ($q) use ($shorten_name) {
                        $q->where('shorten_name', $shorten_name);
                    });
                })
                ->distinct('event_id')
                ->count('event_id');

            return response()->json([
                'total' => $total,
                'status' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEventChart(Request $request, $shorten_name)
    {
        $year = $request->input('year');

        $organizerId = Organizer::where('shorten_name', $shorten_name)->value('id');

        $events = Event::withMax('steps', 'event_date') // ambil langkah terakhir (max event_date)
            ->whereHas('steps', function ($query) use ($year) {
                $query->whereYear('event_date', $year);
            })
            ->whereHas('organizers', function ($query) use ($organizerId) {
                $query->where('organizer_id', $organizerId);
            })
            ->get();
        // Ambil bulan dari steps_max_event_date
        $eventCounts = array_fill(1, 12, 0);
        foreach ($events as $event) {
            $month = Carbon::parse($event->steps_max_event_date)->month;
            $eventCounts[$month]++;
        }

        $labels = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->translatedFormat('F');
        });

        return response()->json([
            'labels' => $labels,
            'data' => array_values($eventCounts),
        ]);
    }
}
