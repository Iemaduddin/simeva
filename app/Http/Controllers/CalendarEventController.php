<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Calendar;
use App\Models\EventStep;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarEventController extends Controller
{
    public function calendarEvent()
    {
        return view('dashboardPage.calendars.index');
    }

    public function getDataCalendarEvent(Request $request)
    {
        $selectedCategories = $request->input('category', []);

        $user = auth()->user();
        $query = Calendar::with('event');

        if (auth()->check() && $user->hasRole('Super Admin')) {
            // Super Admin hanya boleh lihat public + private buatan Super Admin
            $query->where(function ($q) {
                $q->where('is_public', true)
                    ->orWhere(function ($q2) {
                        $q2->where('is_public', false)
                            ->whereNull('organizer_id'); // event private buatan Super Admin
                    });
            });
        } elseif (auth()->check() && $user->hasRole('Organizer')) {
            $organizerId = $user->organizer->id ?? null;
            $query->where(function ($q) use ($organizerId) {
                $q->where('is_public', true)
                    ->orWhere(function ($q2) use ($organizerId) {
                        $q2->where('is_public', false)
                            ->where('organizer_id', $organizerId); // event private milik organizer
                    });
            });
        } else {
            // Role lain hanya lihat public
            $query->where('is_public', true);
        }

        $calendarData = $query->get()
            ->map(function ($calendar) {

                $start = Carbon::parse($calendar->start_date);
                $end = Carbon::parse($calendar->end_date);

                $diffInDays = $start->diffInDays($end);
                return array_merge(
                    [
                        'id' => 'calendar_' . $calendar->id,
                        'title' => $calendar->title ?? ($calendar->event ? $calendar->event->name : 'No Title'),
                        'start' => $calendar->start_date,
                        'created_by' => $calendar->organizer_id !== null ? $calendar->organizer->user->name : 'Admin',
                        'event_owner' => $calendar->organizer_id !== null ? $calendar->organizer->shorten_name : 'Admin',
                        'category' => 'Lainnya',
                        'allDay' => $calendar->all_day,
                        'is_public' => $calendar->is_public,
                    ],
                    $calendar->all_day && $diffInDays > 1 ? [
                        'end' => $calendar->end_date,
                    ] : []
                );
            })->toArray();

        if (!in_array('Lainnya', $selectedCategories) && !in_array('All', $selectedCategories)) {
            $calendarData = [];
        }

        $eventStepData = EventStep::with('event', 'event.organizers.user')
            ->whereHas('event', function ($query) use ($selectedCategories) {
                $query->where('is_publish', true);
                if (!in_array('All', $selectedCategories)) {
                    $query->whereIn('event_category', $selectedCategories);
                }
            })
            ->get()
            ->map(function ($step) {
                return [
                    'id' => 'eventstep_' . $step->id,
                    'title' => $step->event->steps->count() > 1
                        ? $step->event->title . ' - ' . $step->step_name
                        : $step->event->title,
                    'start' => Carbon::parse($step->event_date . ' ' . $step->event_time_start),
                    'end' => Carbon::parse($step->event_date . ' ' . $step->event_time_end),
                    'created_by' => $step->event->organizers->user->name,
                    // 'event_owner' => '',
                    'category' => $step->event->event_category,
                    'allDay' => false,
                ];
            })->toArray();
        $calendars = collect(array_merge($calendarData, $eventStepData));
        return response()->json($calendars);
    }

    public function addEventCalendar(Request $request)
    {
        if ($request->has('all_day_true')) {
            $validator = Validator::make($request->all(), [
                'event_date_start' => 'required|date',
                'event_date_end' => 'required|date',
                'title' => 'required|string|max:255',
            ], [
                'event_date_start.required' => 'Tanggal mulai acara wajib diisi.',
                'event_date_start.date' => 'Format tanggal mulai acara tidak valid.',
                'event_date_end.required' => 'Tanggal selesai acara wajib diisi.',
                'event_date_end.date' => 'Format tanggal akhir acara tidak valid.',
                'title.required' => 'Judul acara wajib diisi.',
                'title.string' => 'Judul acara harus berupa teks.',
                'title.max' => 'Judul acara tidak boleh lebih dari 255 karakter.',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'event_date' => 'required|date', // Pastikan tanggal valid
                'time_start' => 'required_if:all_day,false|date_format:H:i', // Pastikan waktu valid jika all_day tidak dicentang
                'time_end' => 'required_if:all_day,false|date_format:H:i', // Pastikan waktu valid jika all_day tidak dicentang
                'title' => 'required|string|max:255',
            ], [
                'event_date.required' => 'Tanggal acara wajib diisi.',
                'event_date.date' => 'Format tanggal acara tidak valid.',
                'time_start.required_if' => 'Waktu mulai wajib diisi jika acara tidak sepanjang hari.',
                'time_start.date_format' => 'Format waktu mulai harus mengikuti format HH:mm.',
                'time_end.required_if' => 'Waktu selesai wajib diisi jika acara tidak sepanjang hari.',
                'time_end.date_format' => 'Format waktu selesai harus mengikuti format HH:mm.',
                'title.required' => 'Judul acara wajib diisi.',
                'title.string' => 'Judul acara harus berupa teks.',
                'title.max' => 'Judul acara tidak boleh lebih dari 255 karakter.',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->has('all_day_true')) {
            $isPublic = filter_var($request->input('is_public', false), FILTER_VALIDATE_BOOLEAN);

            $startDate = Carbon::parse($request->input('event_date_start'))->setTime(0, 0);
            $endDate = Carbon::parse($request->input('event_date_end'));

            if ($startDate->toDateString() === $endDate->toDateString()) {
                // Jika tanggal sama → anggap event 1 hari → endDate di-set ke 00:00 besok harinya (eksklusif)
                $endDate = $endDate->addDay()->setTime(0, 0);
            } else {
                // Jika beda tanggal → anggap multi-day → endDate di-set ke 23:59 pada tanggal terakhir
                $endDate = $endDate->setTime(23, 59);
            }

            try {
                Calendar::create([
                    'title' => $request->input('title'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'all_day' => true,
                    'is_public' => auth()->user()->hasRole('Super Admin') ?  $isPublic : false,
                    'organizer_id' => auth()->user()->hasRole('Super Admin') ? null : auth()->user()->organizer->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Event berhasil ditambahkan pada kalender!',
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menambahkan event pada kalender.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            $startDate = Carbon::parse($request->input('event_date')); // Mengambil event_date yang dipilih oleh user
            $timeStart = $request->input('time_start'); // Waktu mulai jika all_day tidak dipilih
            $timeEnd = $request->input('time_end'); // Waktu selesai jika all_day tidak dipilih
            $allDay = filter_var($request->input('all_day', false), FILTER_VALIDATE_BOOLEAN);
            $isPublic = filter_var($request->input('is_public', false), FILTER_VALIDATE_BOOLEAN);

            if ($allDay) {
                // Jika all_day dicentang, atur start_date pada 00:00 dan end_date pada 00:00 hari berikutnya
                $startDate = $startDate->setTime(0, 0);
                $endDate = $startDate->copy()->addDay()->setTime(0, 0); // exclusive
            } else {
                // Jika tidak all_day, gabungkan dengan waktu yang dipilih user
                $startDate = $startDate->setTimeFromTimeString($timeStart);
                $endDate = $startDate->copy()->setTimeFromTimeString($timeEnd);
            }

            try {
                Calendar::create([
                    'title' => $request->input('title'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'all_day' => $allDay,
                    'is_public' => auth()->user()->hasRole('Super Admin') ?  $isPublic : false,
                    'organizer_id' => auth()->user()->hasRole('Super Admin') ? null : auth()->user()->organizer->id,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Event berhasil ditambahkan pada kalender!',
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menambahkan event pada kalender.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }
    public function updateEventCalendar(Request $request, $id)
    {
        if ($request->has('all_day_true')) {
            $validator = Validator::make($request->all(), [
                'event_date_start' => 'required|date',
                'event_date_end' => 'required|date',
                'title' => 'required|string|max:255',
            ], [
                'event_date_start.required' => 'Tanggal mulai acara wajib diisi.',
                'event_date_start.date' => 'Format tanggal mulai acara tidak valid.',
                'event_date_end.required' => 'Tanggal selesai acara wajib diisi.',
                'event_date_end.date' => 'Format tanggal akhir acara tidak valid.',
                'title.required' => 'Judul acara wajib diisi.',
                'title.string' => 'Judul acara harus berupa teks.',
                'title.max' => 'Judul acara tidak boleh lebih dari 255 karakter.',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'event_date' => 'required|date', // Pastikan tanggal valid
                'time_start' => 'required_unless:all_day,on|date_format:H:i',
                'time_end' => 'required_unless:all_day,on|date_format:H:i',
                'title' => 'required|string|max:255',
            ], [
                'event_date.required' => 'Tanggal acara wajib diisi.',
                'event_date.date' => 'Format tanggal acara tidak valid.',
                'time_start.required_unless' => 'Waktu mulai wajib diisi jika acara tidak sepanjang hari.',
                'time_start.date_format' => 'Format waktu mulai harus mengikuti format HH:mm.',
                'time_end.required_unless' => 'Waktu selesai wajib diisi jika acara tidak sepanjang hari.',
                'time_end.date_format' => 'Format waktu selesai harus mengikuti format HH:mm.',
                'title.required' => 'Judul acara wajib diisi.',
                'title.string' => 'Judul acara harus berupa teks.',
                'title.max' => 'Judul acara tidak boleh lebih dari 255 karakter.',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->has('all_day_true')) {
            $isPublic = filter_var($request->input('is_public', false), FILTER_VALIDATE_BOOLEAN);

            $startDate = Carbon::parse($request->input('event_date_start'))->setTime(0, 0);
            $endDate = Carbon::parse($request->input('event_date_end'));

            if ($startDate->toDateString() === $endDate->toDateString()) {
                // Jika tanggal sama → anggap event 1 hari → endDate di-set ke 00:00 besok harinya (eksklusif)
                $endDate = $endDate->addDay()->setTime(0, 0);
            } else {
                // Jika beda tanggal → anggap multi-day → endDate di-set ke 23:59 pada tanggal terakhir
                $endDate = $endDate->setTime(23, 59);
            }
            $calendar = Calendar::findOrFail($id);

            try {
                $calendar->update([
                    'title' => $request->input('title'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'is_public' => auth()->user()->hasRole('Super Admin') ?  $isPublic : false,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Event pada kalender berhasil diperbarui!',
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui event pada kalender.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            $startDate = Carbon::parse($request->input('event_date')); // Mengambil event_date yang dipilih oleh user
            $timeStart = $request->input('time_start'); // Waktu mulai jika all_day tidak dipilih
            $timeEnd = $request->input('time_end'); // Waktu selesai jika all_day tidak dipilih
            $allDay = filter_var($request->input('all_day', false), FILTER_VALIDATE_BOOLEAN);
            $isPublic = filter_var($request->input('is_public', false), FILTER_VALIDATE_BOOLEAN);

            if ($allDay) {
                // Jika all_day dicentang, atur start_date pada 00:00 dan end_date pada 00:00 hari berikutnya
                $startDate = $startDate->setTime(0, 0);
                $endDate = $startDate->copy()->addDay()->setTime(0, 0); // exclusive
            } else {
                // Jika tidak all_day, gabungkan dengan waktu yang dipilih user
                $startDate = $startDate->setTimeFromTimeString($timeStart);
                $endDate = $startDate->copy()->setTimeFromTimeString($timeEnd);
            }
            $calendar = Calendar::findOrFail($id);
            try {
                $calendar->update([
                    'title' => $request->input('title'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'all_day' => $allDay,
                    'is_public' => auth()->user()->hasRole('Super Admin') ?  $isPublic : false,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Event pada kalender berhasil diperbarui!',
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui event pada kalender.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }
    public function destroyEventCalendar($id)
    {
        $calendarEvent = Calendar::findOrFail($id);
        try {

            $calendarEvent->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Hapus event pada kalender berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus event pada kalender.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
