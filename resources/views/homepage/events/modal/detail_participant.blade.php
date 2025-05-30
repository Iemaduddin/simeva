<div class="modal fade" id="modalDetailParticipant-{{ $event->id }}" tabindex="-1"
    aria-labelledby="modalLabel{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('register.event', ['id' => $event->id]) }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel-{{ $event->id }}">Rincian Peserta Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">Informasi Peserta:</h6>
                <div class="row mb-10">
                    <div class="col-md-6">
                        <p><strong>Kode Tiket:</strong> {{ ucfirst($itemEvent->ticket_code) }}</p>
                        <p><strong>Nama:</strong> {{ $itemEvent->user->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> {{ $itemEvent->user->email ?? '-' }}</p>
                        <p><strong>No. HP:</strong> {{ $itemEvent->user->phone_number ?? '-' }}</p>
                    </div>
                </div>
                <hr>

                @php $steps = $itemEvent->event->steps; @endphp

                @if ($steps->count() === 1)
                    @php
                        $step = $steps->first();
                        $attendance = $step->attendances->where('event_participant_id', $itemEvent->id)->first();
                    @endphp
                    <h6 class="my-5">Presensi:</h6>

                    <div class="border border-3 rounded p-3">
                        <h6 class="mb-2">{{ ucfirst($step->step_name ?? $itemEvent->event->title) }}</h6>
                        <div class="row">
                            <div class="col-md-6">
                                @if ($attendance && $attendance->attendance_departure)
                                    <p class="mb-1"><strong>Presensi Datang:</strong></p>
                                    <p class="text-muted small">
                                        <strong>Hadir pada:</strong>
                                        {{ \Carbon\Carbon::parse($attendance->attendance_departure_time)->format('d M Y, H:i') }}
                                    </p>
                                @else
                                    <p class="text-muted mb-0">Belum melakukan presensi untuk step ini.</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if ($attendance && $attendance->attendance_departure)
                                    <p class="mb-1"><strong>Presensi Pulang:</strong></p>
                                    <p class="text-muted small">
                                        <strong>Hadir pada:</strong>
                                        {{ \Carbon\Carbon::parse($attendance->attendance_departure_time)->format('d M Y, H:i') }}
                                    </p>
                                @else
                                    <p class="text-muted mb-0">Belum melakukan presensi untuk step ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <h6 class="my-5">Daftar Presensi:</h6>
                    <div class="row">
                        @foreach ($steps as $step)
                            @php
                                $attendance = $step->attendances
                                    ->where('event_participant_id', $itemEvent->id)
                                    ->first();
                            @endphp

                            <div class="col-md-6 mb-10">
                                <div class="border border-3 rounded p-3 h-100">
                                    <h6 class="mb-2">{{ ucfirst($step->step_name ?? $itemEvent->event->title) }}</h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($attendance && $attendance->attendance_departure)
                                                <p class="mb-1"><strong>Presensi Datang:</strong></p>
                                                <p class="text-muted small">
                                                    <strong>Hadir pada:</strong>
                                                    {{ \Carbon\Carbon::parse($attendance->attendance_departure_time)->format('d M Y, H:i') }}
                                                </p>
                                            @else
                                                <p class="text-muted small mb-0">Belum melakukan presensi untuk step
                                                    ini.</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if ($attendance && $attendance->attendance_departure)
                                                <p class="mb-1"><strong>Presensi Pulang:</strong></p>
                                                <p class="text-muted small">
                                                    <strong>Hadir pada:</strong>
                                                    {{ \Carbon\Carbon::parse($attendance->attendance_departure_time)->format('d M Y, H:i') }}
                                                </p>
                                            @else
                                                <p class="text-muted small mb-0">Belum melakukan presensi untuk step
                                                    ini.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>




            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-main">Daftar</button>
            </div>
        </form>
    </div>
</div>
