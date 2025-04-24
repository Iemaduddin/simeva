<div class="modal fade" id="modalAddSpeaker" tabindex="-1" aria-labelledby="modalAddSpeakerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalAddSpeakerLabel">Tambah Pembicara
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('add.speaker') }}" method="POST" data-table="eventSpeakerTable">
                    @csrf
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
                                    <option value="{{ $step->id }}">
                                        {{ $eventName }} {{ $dateTimeEvent }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Pembicara<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control radius-8"
                                placeholder="Masukkan Nama Pembicara" required>
                        </div>
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Peran<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="role" id="role" required>
                                <option value="Keynote Speaker">Keynote Speaker</option>
                                <option value="Pemateri">Pemateri / Narasumber</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Panelist">Panelist</option>
                                <option value="MC">MC (Master of Ceremony)</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <input type="text" class="form-control mt-3 d-none" id="other_role" name="other_role"
                                placeholder="Masukkan peran lainnya" disabled>
                        </div>
                        <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                            <button type="reset"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                                data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                                Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const roleSelect = document.getElementById('role');
    const otherRoleInput = document.getElementById('other_role');

    function toggleOtherRoleInput() {
        if (roleSelect.value === 'other') {
            otherRoleInput.classList.remove('d-none');
            otherRoleInput.removeAttribute('disabled');
            otherRoleInput.setAttribute('required', true);
        } else {
            otherRoleInput.classList.add('d-none');
            otherRoleInput.setAttribute('disabled', true);
            otherRoleInput.removeAttribute('required');
        }
    }

    // Panggil saat pertama kali load (jika ada value sebelumnya)
    toggleOtherRoleInput();

    // Pasang event listener
    roleSelect.addEventListener('change', toggleOtherRoleInput);
</script>
