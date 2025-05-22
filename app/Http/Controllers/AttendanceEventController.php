<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use App\Models\EventAttendance;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Exports\EventAttendanceParticipantsExport;

class AttendanceEventController extends Controller
{
    public function getDataMembers(Request $request)
    {
        $eventStepId = $request->event_step_id;
        $organizerId = Auth::user()->organizer->id;
        $team_members = TeamMember::with('attendances')
            ->where('organizer_id', $organizerId)->get();

        return DataTables::of($team_members)
            ->addIndexColumn()
            ->editColumn('position', content: function ($member) {
                return $member->level . '<br> (' . $member->position . ')';
            })
            ->editColumn('checkin', content: function ($member) use ($eventStepId) {
                $attendance  = EventAttendance::where('team_member_id', $member->id)
                    ->where('event_step_id', $eventStepId)
                    ->first();
                if ($attendance && $attendance->attendance_arrival) {
                    return
                        '
                    <p class="fw-bold text-success">
                    Hadir
                    </p> 
                    <p>
                    (' . Carbon::parse($attendance->attendance_arrival_time)->translatedFormat('d M Y, H.i.s') . ')' . '
                    </p>

                    ';
                } else {
                    return '
                    <form action="' . route('events.attendanceMember', ['memberId' => $member->id, 'checkType' => 'checkin']) . '" method="POST" data-table="eventTeamAttendance">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="event_step_id" value="' . $eventStepId . '">
                        <button type="submit" class="btn btn-sm btn-success-600">
                        Presensi Datang
                    </button>
                    </form>';
                }
            })
            ->editColumn('checkout', content: function ($member) use ($eventStepId) {

                $attendance  = EventAttendance::where('team_member_id', $member->id)
                    ->where('event_step_id', $eventStepId)
                    ->first();
                if ($attendance && $attendance->attendance_arrival) {
                    if ($attendance && $attendance->attendance_departure) {
                        return
                            '
                            <p class="fw-bold text-success">
                            Hadir
                            </p>
                            <p>
                            (' . Carbon::parse($attendance->attendance_departure_time)->translatedFormat('d M Y, H.i.s') . ')' . '
                            </p>

                            ';
                    } else {
                        return '
                            <form action="' . route('events.attendanceMember', ['memberId' => $member->id, 'checkType' => 'checkout']) . '" method="POST" data-table="eventTeamAttendance">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="event_step_id" value="' . $eventStepId . '">
                            <button type="submit" class="btn btn-sm btn-dark">
                            Presensi Pulang
                            </button>
                            </form>';
                    }
                } else {
                    return 'Belum Presensi Datang';
                }
            })
            ->rawColumns(['position', 'checkin', 'checkout'])
            ->make(true);
    }


    public function attendanceMember(Request $request, $memberId, $checkType)
    {
        try {
            $dateTimeNow = Carbon::now();
            if ($checkType === 'checkin') {
                EventAttendance::create([
                    'team_member_id' => $memberId,
                    'event_step_id' => $request->event_step_id,
                    'attendance_arrival' => true,
                    'attendance_arrival_time' => $dateTimeNow,
                ]);
            } else {
                $event_attendance = EventAttendance::where('team_member_id', $memberId)
                    ->where('event_step_id', $request->event_step_id)
                    ->first();
                $event_attendance->attendance_departure = true;
                $event_attendance->attendance_departure_time = $dateTimeNow;
                $event_attendance->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi ' . ($checkType === 'checkin' ? 'Kedatangan' : 'Kepulangan') . ' berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui presensi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function attendanceMemberAll($eventStepId, $checkType)
    {
        try {
            $organizerId = Auth::user()->organizer->id;
            $team_members = TeamMember::where('organizer_id', $organizerId)->get();
            $dateTimeNow = Carbon::now();

            if ($checkType === 'checkin') {
                foreach ($team_members as $member) {
                    EventAttendance::create([
                        'team_member_id' => $member->id,
                        'event_step_id' => $eventStepId,
                        'attendance_arrival' => true,
                        'attendance_arrival_time' => $dateTimeNow,
                    ]);
                }
            } else {
                $event_attendances = EventAttendance::where('event_step_id', $eventStepId)
                    ->where('attendance_departure', false)
                    ->get();
                foreach ($event_attendances  as $event_attendance) {
                    $event_attendance->attendance_departure = true;
                    $event_attendance->attendance_departure_time = $dateTimeNow;
                    $event_attendance->save();
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi ' . ($checkType === 'checkin' ? 'Kedatangan' : 'Kepulangan') . ' seluruh anggota berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui presensi seluruh anggota .',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getDataEventParticipants(Request $request)
    {
        $eventStepId = $request->event_step_id;
        $attendance_participants = EventAttendance::with('eventParticipant')
            ->where('event_participant_id', '!=', null)
            ->where('event_step_id', $eventStepId)
            ->get();

        return DataTables::of($attendance_participants)
            ->addIndexColumn()
            ->editColumn('ticket_code', function ($attendance) {
                return $attendance->eventParticipant->ticket_code ?? '-';
            })
            ->editColumn('name', function ($attendance) {
                return $attendance->eventParticipant->user->name ?? '-';
            })
            ->editColumn('category_user', function ($attendance) {
                $user = $attendance->eventParticipant->user;
                if ($user->category_user == 'Internal Kampus') {
                    $categoryUser = 'Mahasiswa J' . $user->jurusan->kode_jurusan;
                } else {
                    $categoryUser = 'Eksternal Kampus';
                }
                return $categoryUser;
            })
            ->editColumn('checkin', content: function ($attendance) use ($eventStepId) {

                if ($attendance && $attendance->attendance_arrival) {
                    return
                        '
                    <p class="fw-bold text-success">
                    Hadir
                    </p> 
                    <p>
                    (' . Carbon::parse($attendance->attendance_arrival_time)->translatedFormat('d M Y, H.i.s') . ')' . '
                    </p>

                    ';
                }
            })
            ->editColumn('checkout', content: function ($attendance) use ($eventStepId) {
                if ($attendance && $attendance->attendance_arrival) {
                    if ($attendance && $attendance->attendance_departure) {
                        return
                            '
                            <p class="fw-bold text-success">
                            Hadir
                            </p>
                            <p>
                            (' . Carbon::parse($attendance->attendance_departure_time)->translatedFormat('d M Y, H.i.s') . ')' . '
                            </p>

                            ';
                    }
                } else {
                    return 'Belum Presensi Datang';
                }
            })
            ->rawColumns(['category_user', 'checkin', 'checkout'])
            ->make(true);
    }
    public function attendanceParticipant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_code' => 'required',
            'event_step_id' => 'required',
            'attendance_type' => 'required|in:arrival,departure',
        ], [
            'ticket_code.required' => 'Kode tiket wajib diisi.',
            'event_step_id.required' => 'Tahapan Event wajib diisi.',
            'attendance_type.required' => 'Jenis Presensi wajib diisi.',
            'attendance_type.in' => 'Jenis Presensi harus arrival atau departure.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari peserta berdasarkan kode tiket
        $participant = EventParticipant::where('ticket_code', $request->ticket_code)->first();
        if (!$participant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Peserta tidak ditemukan.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari atau buat presensi untuk kombinasi peserta dan step
        $attendance = EventAttendance::firstOrNew([
            'event_participant_id' => $participant->id,
            'event_step_id' => $request->event_step_id,
        ]);

        if ($request->attendance_type === 'departure' && !$attendance->attendance_arrival) {
            return response()->json([
                'status' => 'error',
                'message' => 'Presensi gagal. Peserta belum presensi datang.',
                'errors' => $validator->errors()
            ], 422);
        }
        // Cek apakah sudah presensi
        if ($request->attendance_type === 'arrival' && $attendance->attendance_arrival) {
            return response()->json(['message' => 'Sudah presensi datang.'], 409);
        }

        if ($request->attendance_type === 'departure' && $attendance->attendance_departure) {
            return response()->json(['message' => 'Sudah presensi pulang.'], 409);
        }
        $dateNow = Carbon::now();
        try {
            if ($request->attendance_type === 'arrival') {
                $attendance->attendance_arrival = true;
                $attendance->attendance_arrival_time = $dateNow;
            } else {
                $attendance->attendance_departure = true;
                $attendance->attendance_departure_time = $dateNow;
            }

            $attendance->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi peserta berhasil!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat presensi peserta.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel($eventId, $category)
    {
        $eventName = Event::findOrFail($eventId)->value('title');
        $categoryPresensi = $category == 'member' ? 'Panitia' : 'Peserta';
        return Excel::download(new EventAttendanceParticipantsExport($eventId, $category), 'Rekapan Presensi ' . $categoryPresensi . '-' . $eventName . '.xlsx');
    }
}
