<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Asset;
use App\Models\Event;
use App\Models\Jurusan;
use App\Models\Calendar;
use App\Models\EventStep;
use App\Models\Organizer;
use Illuminate\Support\Str;
use App\Models\AssetBooking;
use Illuminate\Http\Request;
use App\Models\AssetCategory;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function home()
    {
        $assets = collect();

        // Aset Umum
        $assetsUmum = Asset::where('facility_scope', 'umum')->limit(9)->get();
        $assets = $assets->merge($assetsUmum);

        // Aset per Jurusan (TI, AN, dll.)
        $jurusans = Jurusan::all();
        foreach ($jurusans as $jurusan) {
            $assetsJurusan = Asset::where('facility_scope', 'jurusan')
                ->where('jurusan_id', $jurusan->id)
                ->limit(9)
                ->get();

            $assets = $assets->merge($assetsJurusan);
        }


        $categories = ['all', 'Seminar', 'Kuliah Tamu', 'Pelatihan', 'Workshop', 'Kompetisi', 'Lainnya'];


        $eventsByCategory = [];

        foreach ($categories as $category) {
            $query = Event::where('status', 'published')->latest();
            $key = Str::slug($category, '_');
            if ($category !== 'all') {
                $query->where('event_category', $category);
            }

            $eventsByCategory[$key] = $query->take(12)->get();
        }

        $jurusans  = Jurusan::all();
        $logo_organizers = Organizer::pluck('logo');
        return view('homepage.home', compact('eventsByCategory', 'assets', 'jurusans', 'logo_organizers'));
    }

    public function event(Request $request)
    {
        $events = Event::with('organizers.user')->where('status', 'published')->get();
        $jurusans  = Jurusan::all();
        $order = ['LT', 'HMJ', 'UKM', 'Kampus', 'Jurusan',];

        $organizers = Organizer::with('user')
            ->get()
            ->sortBy(function ($organizer) use ($order) {
                return array_search($organizer->organizer_type, $order);
            })
            ->pluck('user.name', 'id');
        $allEvents = Event::with('organizers')->get();
        $query = Event::query();

        if ($request->filled('scope')) {
            $scope = $request->input('scope');

            if ($scope !== 'all') {
                if ($scope === 'Umum' || $scope === 'Internal Kampus') {
                    $query->where('scope', $scope);
                } else {
                    //  (Internal Jurusan)
                    $query->where('scope', 'Internal Jurusan')->whereHas('organizers.user', function ($q) use ($scope) {
                        $q->whereHas('jurusan', function ($subQuery) use ($scope) {
                            $subQuery->where('id', $scope);
                        });
                    });
                }
            }
        }


        if ($request->filled('organizer')) {
            $org = $request->input('organizer');
            if ($org !== 'all') {
                $query->where('organizer_id', $request->input('organizer'));
            }
        }

        if ($request->has('event_category')) {
            $selectedCategories = $request->input('event_category');
            if (!in_array('all', $selectedCategories)) {
                $query->whereIn('event_category', $selectedCategories);
            }
        }
        if ($request->has('is_free')) {
            $selectedCosts = $request->input('is_free');

            // Hapus nilai 'all' dari array
            $selectedCosts = array_filter($selectedCosts, function ($val) {
                return $val !== 'all';
            });

            if (!empty($selectedCosts)) {
                // Konversi string 'true'/'false' menjadi boolean asli
                $mappedCosts = array_map(function ($val) {
                    return filter_var($val, FILTER_VALIDATE_BOOLEAN);
                }, $selectedCosts);

                $query->whereIn('is_free', $mappedCosts);
            }
        }



        $events = $query->latest()->with(['organizers.user.jurusan'])->paginate(5);

        $from = ($events->currentPage() - 1) * $events->perPage() + 1;
        $to = $from + $events->count() - 1;  // Menggunakan count() dari paginator untuk menghitung jumlah di halaman ini
        $total = $events->total();  // Total semua entri
        $filtered = $events->count();  // Total data setelah filter diterapkan (jika ada)
        // Mengambil total filter, jika ada
        if ($request->ajax()) {
            $eventHtml = view('homepage.events.components.event-card', compact('events'))->render();
            $paginationHtml = view('homepage.events.components.pagination-button', compact('events'))->render();
            return response()->json([
                'eventHtml' => $eventHtml,
                'paginationHtml' => $paginationHtml,
                'from' => $events->firstItem(),
                'to' => $events->lastItem(),
                'total' => $events->total(), // total semua
                'filtered' => $events->total(),
            ]);
        }

        return view('homepage.events.event', compact('events', 'allEvents', 'jurusans', 'organizers', 'from', 'to', 'total', 'filtered'));
    }

    public function detail_event($id)
    {
        $event = Event::with(['prices', 'participants', 'steps.event_speaker'])->findOrFail($id);
        $categoryEvent = Event::where('id', $id)->value('event_category');


        $simillarEvents = Event::where('event_category', $categoryEvent)->where('id', '!=', $id)->where('status', 'published')->get();
        return view('homepage.events.detail_event', compact('event', 'simillarEvents'));
    }
    public function organizer()
    {
        $organizers = Organizer::all();

        return view('homepage.organizer', compact('organizers'));
    }
    public function detail_organizer($id)
    {
        $organizer = Organizer::findOrFail($id);
        $events = Event::where('organizer_id', $id)->where('status', 'published')->get();
        return view('homepage.detail_organizer', compact('organizer', 'events'));
    }

    public function indexAsetBmn(Request $request)
    {

        $jurusans  = Jurusan::all();

        $allAssets = Asset::with('jurusan')->get();
        $scope = $request->input('facility_scope', 'umum');

        $query = Asset::query();

        if ($request->has('booking_type')) {
            $selectedBooking = $request->input('booking_type');

            if (!empty($selectedBooking)) {

                $query->whereIn('booking_type', $selectedBooking);
            }
        }

        if ($scope === 'umum') {
            $query->where('facility_scope', $scope);
        } else {
            $query->where('facility_scope', 'jurusan')
                ->whereHas('jurusan', function ($q) use ($scope) {
                    $q->where('id', $scope);
                });
        }




        $assets = $query->latest()->with(['jurusan'])->paginate(5);

        $from = ($assets->currentPage() - 1) * $assets->perPage() + 1;
        $to = $from + $assets->count() - 1;  // Menggunakan count() dari paginator untuk menghitung jumlah di halaman ini
        $total = $assets->total();  // Total semua entri
        $filtered = $assets->count();  // Total data setelah filter diterapkan (jika ada)
        // Mengambil total filter, jika ada
        if ($request->ajax()) {
            $assetHtml = view('homepage.assets.components.asset-card', compact('assets'))->render();
            $paginationHtml = view('homepage.assets.components.pagination-button', compact('assets'))->render();
            return response()->json([
                'assetHtml' => $assetHtml,
                'paginationHtml' => $paginationHtml,
                'from' => $assets->firstItem(),
                'to' => $assets->lastItem(),
                'total' => $assets->total(), // total semua
                'filtered' => $assets->total(),
            ]);
        }



        return view('homepage.assets.aset', compact('assets', 'allAssets', 'jurusans', 'from', 'to', 'total', 'filtered'));
    }
    public function getDataAssetBmn($id)
    {
        $assetBookings = AssetBooking::where('asset_id', $id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')->get();
        $assetDetails  = Asset::where('id', $id)->first();
        return view('homepage.assets.detail_aset', compact('assetDetails', 'assetBookings'));
    }

    public function getDataCategoryAssetBooking($id)
    {
        $categories = AssetCategory::where('asset_id', $id)->get(['id', 'category_name', 'external_price']);

        return response()->json([
            'data' => $categories
        ]);
    }
    public function getDataCalendarAssetBooking($assetId)
    {
        $bookings = AssetBooking::with('user')
            ->where('asset_id', $assetId)
            ->whereIn('status', [
                'submission_booking',
                'submission_dp_payment',
                'submission_full_payment',
                'booked',
                'approved_dp_payment',
                'approved_full_payment'
            ])
            ->get()
            ->map(function ($booking) {
                // Set warna event berdasarkan status
                $eventColor = match (true) {
                    str_contains($booking->status, 'submission') => 'warning', // warning
                    str_contains($booking->status, 'booked') => 'info',    // primary
                    str_contains($booking->status, 'approved') => 'success',  // success
                    default => '#6c757d'                                      // secondary
                };
                $userIcon = $booking->user->category_user === 'Internal Kampus' ?
                    'ph-student' :
                    'ph-user-sound';
                return [
                    'id' => $booking->id,
                    'title' => $booking->usage_event_name,
                    'start' => Carbon::parse($booking->usage_date_start)->format('Y-m-d H:i:s'),
                    'end' => Carbon::parse($booking->usage_date_end)->format('Y-m-d H:i:s'),
                    // Warna untuk event
                    'className' => $eventColor,
                    'allDay' => false,
                    'icon' => $userIcon,
                    // Data tambahan
                    'loadingDate' => ($booking->loading_date_start && $booking->loading_date_end)
                        ? Carbon::parse($booking->loading_date_start)->translatedFormat('l, d M Y H:i') . ' - ' .
                        Carbon::parse($booking->loading_date_end)->format('H:i')
                        : '-',
                    'status' => $booking->status,
                    'user' => $booking->user->category_user === 'Internal Kampus' ? $booking->user->organizer->shorten_name . ' (Internal Kampus)' :  $booking->user->name . ' (Eksternal Kampus)',
                    'userCategory' => $booking->user->category_user,
                ];
            });


        return response()->json($bookings);
    }
    // public function getAnnualAssetBooking($assetId)
    // {
    //     $assetBookings = AssetBooking::where('asset_id', $assetId)
    //         ->where('status', '!=', 'cancelled')
    //         ->where('status', '!=', 'rejected')->get();

    //     $currentYear = now()->year;
    //     $data = [];

    //     for ($year = $currentYear; $year < $currentYear + 10; $year++) {
    //         $row = [
    //             'year' => $year,
    //             'jan' => '',
    //             'feb' => '',
    //             'mar' => '',
    //             'apr' => '',
    //             'may' => '',
    //             'jun' => '',
    //             'jul' => '',
    //             'aug' => '',
    //             'sep' => '',
    //             'oct' => '',
    //             'nov' => '',
    //             'dec' => '',
    //         ];

    //         for ($month = 1; $month <= 12; $month++) {
    //             $foundBooking = $assetBookings->first(function ($booking) use ($year, $month) {
    //                 $start = Carbon::parse($booking->usage_date_start);
    //                 $end = Carbon::parse($booking->usage_date_end);

    //                 return ($start->year <= $year && $end->year >= $year) &&
    //                     ($start->year < $year || $start->month <= $month) &&
    //                     ($end->year > $year || $end->month >= $month);
    //             });

    //             if ($foundBooking) {
    //                 $html = '<button class="btn btn-sm bg-color-blue" data-bs-toggle="modal" data-bs-target="#detail-booking-asset-annual">'
    //                     . $foundBooking->usage_event_name . '</button>';

    //                 $monthKey = strtolower(date('M', mktime(0, 0, 0, $month, 10))); // jan, feb, etc
    //                 $row[$monthKey] = $html;
    //             }
    //         }

    //         $data[] = $row;
    //     }

    //     return response()->json([
    //         'data' => $data
    //     ]);
    // }
    public function calender()
    {
        return view('homepage.calender');
    }

    // public function getDataCalendar(Request $request)
    // {

    //     $selectedCategories = $request->input('category', []);

    //     $calendarData = Calendar::with('event')
    //         ->where('is_public', true)
    //         ->get()
    //         ->map(function ($calendar) {
    //             return [
    //                 'id' => 'calendar_' . $calendar->id,
    //                 'title' => $calendar->title ?? ($calendar->event ? $calendar->event->name : 'No Title'),
    //                 'start' => Carbon::parse($calendar->start_date)->toISOString(),
    //                 'end' => Carbon::parse($calendar->end_date)->toISOString(),
    //                 'category' => 'Lainnya',
    //                 'allDay' => $calendar->all_day,
    //             ];
    //         })->toArray();


    //     $eventStepData = EventStep::with('event')
    //         ->whereHas('event', function ($query) use ($selectedCategories) {
    //             $query->where('is_publish', true);
    //             if (!in_array('All', $selectedCategories)) {
    //                 $query->whereIn('event_category', $selectedCategories);
    //             }
    //         })
    //         ->get()
    //         ->map(function ($step) {
    //             return [
    //                 'id' => 'eventstep_' . $step->id,
    //                 'title' => $step->event->title . ' - ' . $step->step_name,
    //                 'start' => Carbon::parse($step->event_date . ' ' . $step->event_time_start)->toISOString(),
    //                 'end' => Carbon::parse($step->event_date . ' ' . $step->event_time_end)->toISOString(),
    //                 'category' => $step->event->event_category,
    //                 'allDay' => false,
    //             ];
    //         })->toArray();

    //     $calendars = collect(array_merge($calendarData, $eventStepData));
    //     return response()->json($calendars);
    // }

    public function tutorial()
    {
        return view('homepage.tutorial');
    }
}
