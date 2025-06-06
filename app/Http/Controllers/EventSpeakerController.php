<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use BaconQrCode\Writer;
use App\Models\TeamMember;
use App\Models\EventSpeaker;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EventParticipant;
use BaconQrCode\Renderer\ImageRenderer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use App\Notifications\BookingAssetInternalCampus;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class EventSpeakerController extends Controller
{
    public function getDataSpeakers($id)
    {

        $speakers = EventSpeaker::with('event_step.event')
            ->whereHas('event_step', function ($query) use ($id) {
                $query->where('event_id', $id);
            })
            ->get();
        $event = Event::findOrFail($id);

        return DataTables::of($speakers)
            ->addIndexColumn()
            ->editColumn('event', function ($speaker) {
                $step = $speaker->event_step;
                if ($step->step_name != null) {
                    $eventName = $step->step_name;
                } else {
                    $eventName = $step->event->title;
                }

                $dateTimeEvent = Carbon::parse($step->event_date)->translatedFormat('d M Y') . ", " .
                    Carbon::parse($step->event_time_start)->translatedFormat('H.i') . " - " .
                    Carbon::parse($step->event_time_end)->translatedFormat('H.i') . ")";

                return $eventName . '<br>' . $dateTimeEvent;
            })
            ->addColumn('action', function ($speaker) use ($event): string {
                $organizer = $speaker->event_step->event->organizers ?? null;
                $leaders = TeamMember::where('organizer_id', $organizer->id)->where('is_leader', true)->get();

                $updateModal = view('dashboardPage.events.speaker.update-speaker', compact('event', 'speaker'))->render();
                $invitationLetterModal = view('dashboardPage.events.speaker.download-event-speaker', compact('event', 'speaker', 'leaders'))->render();

                return '<div class="d-flex gap-8">
                <button class="w-40-px h-40-px cursor-pointer bg-warning-focus text-warning-main rounded-circle d-inline-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#modalDataInvitationSpeaker-' . $speaker->id . '">
                <iconify-icon icon="mi:document-download" class="text-xl"></iconify-icon>
                </button>
                ' . $invitationLetterModal . '
                <button class="w-40-px h-40-px cursor-pointer bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                data-bs-toggle="modal" data-bs-target="#modalUpdateSpeaker-' . $speaker->id . '">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                </button>
                ' . $updateModal . '
                <form action="' . route('destroy.speaker', ['id' => $speaker->id]) . '" method="POST" class="delete-form" data-table="eventSpeakerTable">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="button"
                    class="delete-btn w-40-px h-40-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
                </form>
                </div>';
            })
            ->rawColumns(['event', 'action'])
            ->make(true);
    }

    public function invitationSpeaker(Request $request, $id)
    {
        $speaker = EventSpeaker::findOrFail($id);
        $organizer = $speaker->event_step->event->organizers ?? null;

        $leader = TeamMember::findOrFail($request->leader);
        $letter_number = $request->letter_number;
        $event_leader = TeamMember::findOrFail($speaker->event_step->event->event_leader);
        return view('dashboardPage.events.speaker.invitation-letter', compact('speaker', 'organizer', 'letter_number', 'leader', 'event_leader'));
    }

    public function addSpeaker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_step_id' => 'required',
            'name' => 'required',
            'role' => 'required',
        ], [
            'event_step_id.required' => 'Event harus dipilih.',
            'name.required' => 'Nama  harus diisi.',
            'role.required' => 'Peran harus dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }


        try {
            $role = $request->role === 'other' ? $request->other_role : $request->role;
            EventSpeaker::create([
                'event_step_id' => $request->event_step_id,
                'name' => $request->name,
                'role' => $role,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembicara berhasil ditambahkan!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan pembicara.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateSpeaker(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'event_step_id' => 'required',
            'name' => 'required',
            'role' => 'required',
        ], [
            'event_step_id.required' => 'Event harus dipilih.',
            'name.required' => 'Nama  harus diisi.',
            'role.required' => 'Peran harus dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        $event_speaker = EventSpeaker::findOrFail($id);

        try {
            $role = $request->role === 'other' ? $request->other_role : $request->role;
            $event_speaker->update([
                'event_step_id' => $request->event_step_id,
                'name' => $request->name,
                'role' => $role,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data Pembicara berhasil diperbarui!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data pembicara.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroySpeaker($id)
    {
        try {

            // Ambil data user dan hapus user
            $event_speaker = EventSpeaker::findOrFail($id);
            $event_speaker->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembicara berhasil dihapus!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus pembicara.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}