<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Event;
use App\Models\Jurusan;
use App\Models\EventStep;
use App\Models\Organizer;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardSuperAdminController extends Controller
{
    public function index()
    {

        if (!Auth::user()->roles()->where('name', 'Super Admin')->exists()) {

            return abort(403, 'Unauthorized');
        }
        $totalUser = User::count();

        $sevenDaysAgo = Carbon::now()->subDays(7);
        $newUsers = User::where('created_at', '>=', $sevenDaysAgo)
            ->latest('created_at')
            ->take(10)
            ->get();

        $today = Carbon::today();
        $onGoingEvents = Event::with('organizers')->withMax('steps', 'event_date')
            ->where('status', 'published')
            ->having('steps_max_event_date', '>=', $today)
            ->take(10)
            ->get();

        // Asset Booking
        $query = AssetBooking::with(['asset', 'user', 'asset_category'])
            ->where(function ($q) {
                $q->whereNull('event_id');
            })
            ->whereHas('asset', function ($q) {
                $q->where('facility_scope', 'umum');
            });

        $query->whereNotNull('asset_category_id');
        $assetBookings = $query->take(value: 5)->get();

        // Kode Jurusan
        $jurusans = Jurusan::pluck('kode_jurusan');

        $organizers = Organizer::with('user')->get()->groupBy('organizer_type');


        return view(
            'dashboardPage.dashboard.super-admin',
            compact('totalUser', 'newUsers', 'onGoingEvents', 'assetBookings', 'jurusans', 'organizers')
        );
    }

    public function getTotalEventByYear($year)
    {
        try {

            $total = EventStep::whereYear('event_date', $year)
                ->whereHas('event', function ($q) {
                    $q->where('status', 'published');
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
    public function getTotalAssetByType($type)
    {
        try {


            if ($type === 'umum') {
                $total = Asset::where('facility_scope', 'umum')->count();
            } else {
                // Ambil dari relasi jurusans, dengan kode jurusan tertentu
                $total = Asset::where('facility_scope', 'jurusan')
                    ->whereHas('jurusan', function ($query) use ($type) {
                        $query->where('kode_jurusan', $type);
                    })
                    ->count();
            }
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
    public function getDataStatusAssetBookingChart()
    {
        $statusCounts = AssetBooking::selectRaw('
            SUM(CASE WHEN status = "booked" THEN 1 ELSE 0 END) as booked_count,
            SUM(CASE WHEN status NOT IN ("booked","approved", "rejected", "cancelled") THEN 1 ELSE 0 END) as submission_count,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
            SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count
        ')
            ->first();
        return response()->json([
            'labels' => ['Booking Disetujui', 'Pengajuan', 'Disetujui', 'Ditolak', 'Dibatalkan'],
            'colors' => ['#487fff', '#ff9f29', '#16a34a', '#dc2626', '#000000'],
            'statusTotal' => [
                $statusCounts->booked_count ?? 0,
                $statusCounts->submission_count ?? 0,
                $statusCounts->approved_count ?? 0,
                $statusCounts->rejected_count ?? 0,
                $statusCounts->cancelled_count ?? 0
            ]
        ]);
    }
    public function getDataEventChart(Request $request)
    {
        $yearNow = Carbon::now()->year;
        $organizerId = $request->input('organizer_id');

        $events = Event::withMax('steps', 'event_date') // ambil langkah terakhir (max event_date)
            ->whereHas('steps', function ($query) use ($yearNow) {
                $query->whereYear('event_date', $yearNow);
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
