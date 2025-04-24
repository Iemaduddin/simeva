<div class="modal fade" id="modalUpdateSpeaker-{{ $speaker->id }}" tabindex="-1" aria-labelledby="modalUpdateSpeakerLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalUpdateSpeakerLabel">Tambah Pembicara
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('update.speaker', ['id' => $speaker->id]) }}" method="POST"
                    data-table="eventSpeakerTable">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Event<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="event_step_id" required>
                                @foreach ($event->steps as $step)
                                    @php
                                        if ($step->step_name != null) {
                                            $eventName = $step->step_name;
                                        } else {
                                            $eventName = $step->event->title;
                                        }

                                        $dateTimeEvent =
                                            '(' .
                                            \Carbon\Carbon::parse($step->event_date)->translatedFormat('d M Y') .
                                            ', ' .
                                            \Carbon\Carbon::parse($step->event_time_start)->translatedFormat('H.i') .
                                            ' - ' .
                                            \Carbon\Carbon::parse($step->event_time_end)->translatedFormat('H.i') .
                                            ')';
                                    @endphp
                                    <option value="{{ $step->id }}"
                                        {{ $step->id === $speaker->event_step_id ? 'selected' : '' }}>
                                        {{ $eventName }} {{ $dateTimeEvent }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Pembicara<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control radius-8"
                                value="{{ $speaker->name }}" placeholder="Masukkan Nama Pembicara" required>
                        </div>
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Peran<span
                                    class="text-danger">*</span></label>
                            <select class="form-select role-select" id="role-{{ $speaker->id }}" name="role"
                                required>
                                <option value="Keynote Speaker"
                                    {{ $speaker->role === 'Keynote Speaker' ? 'selected' : '' }}>Keynote Speaker
                                </option>
                                <option value="Pemateri" {{ $speaker->role === 'Pemateri' ? 'selected' : '' }}>Pemateri
                                    / Narasumber</option>
                                <option value="Moderator" {{ $speaker->role === 'Moderator' ? 'selected' : '' }}>
                                    Moderator</option>
                                <option value="Panelist" {{ $speaker->role === 'Panelist' ? 'selected' : '' }}>Panelist
                                </option>
                                <option value="MC" {{ $speaker->role === 'MC' ? 'selected' : '' }}>MC (Master of
                                    Ceremony)</option>
                                <option value="other"
                                    {{ !in_array($speaker->role, ['Keynote Speaker', 'Pemateri', 'Moderator', 'Panelist', 'MC']) ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>

                            <input type="text"
                                class="form-control mt-3 other-role-input {{ in_array($speaker->role, ['Keynote Speaker', 'Pemateri', 'Moderator', 'Panelist', 'MC']) ? 'd-none' : '' }}"
                                id="other_role-{{ $speaker->id }}" name="other_role"
                                placeholder="Masukkan peran lainnya"
                                value="{{ !in_array($speaker->role, ['Keynote Speaker', 'Pemateri', 'Moderator', 'Panelist', 'MC']) ? $speaker->role : '' }}"
                                {{ in_array($speaker->role, ['Keynote Speaker', 'Pemateri', 'Moderator', 'Panelist', 'MC']) ? 'disabled' : '' }}>
                        </div>
                        <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                            <button type="reset"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                                data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                                Perbarui
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleOtherRole(selectElement) {
        const speakerId = selectElement.id.split('role-')[1];
        const inputElement = document.getElementById('other_role-' + speakerId);
        if (!inputElement) return;
        if (selectElement.value === 'other') {
            inputElement.classList.remove('d-none');
            inputElement.removeAttribute('disabled');
            inputElement.setAttribute('required', true);
        } else {
            inputElement.classList.add('d-none');
            inputElement.setAttribute('disabled', true);
            inputElement.removeAttribute('required');
        }
    }

    // Event listener untuk semua role select
    document.querySelectorAll('.role-select').forEach(function(select) {
        toggleOtherRole(select); // Jalankan saat awal
        select.addEventListener('change', function() {
            toggleOtherRole(select);
        });
    });
</script>
