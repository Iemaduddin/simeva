<div class="modal fade" id="modalConfirmRegistration-{{ $participant->id }}" tabindex="-1"
    aria-labelledby="modalConfirmRegistrationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalConfirmRegistrationLabel">Konfirmasi Registrasi
                    Peserta
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('events.confirmRegistration', ['id' => $participant->id]) }}" method="POST"
                    data-table="eventParticipantTable">
                    @csrf
                    <div class="row gy-4 ">

                        <input type="hidden" name="event_id" value="{{ $event_id }}">
                        @php
                            $proof = $participant->transaction->proof_of_payment ?? null;
                        @endphp

                        @if ($proof)
                            <center>
                                <label class="form-label">Bukti Pembayaran</label>
                                <div class="mb-5">
                                    <img src="{{ asset('storage/' . $proof) }}" width="300px">
                                </div>
                            </center>
                        @endif

                        <label class="form-label">Pilih Tindakan <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <select name="statusRegistration" class="form-select status-select"
                                data-id="{{ $participant->id }}" required>
                                <option value="approved">✔️ Approve</option>
                                <option value="rejected">✖️ Reject</option>
                            </select>
                        </div>

                        <div class="col-md-12 reason-reject mt-3 d-none" id="reason-{{ $participant->id }}">
                            <textarea class="form-control reason-textarea" name="reason" placeholder="Masukkan alasan penolakan" rows="2"
                                disabled></textarea>
                        </div>

                    </div>
                    <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                        <button type="reset"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('status-select')) {
            const id = e.target.dataset.id;
            const reasonBox = document.getElementById(`reason-${id}`);
            const textarea = reasonBox?.querySelector('textarea');

            if (e.target.value === 'rejected') {
                reasonBox?.classList.remove('d-none');
                textarea?.removeAttribute('disabled');
                textarea?.setAttribute('required', true);
            } else {
                reasonBox?.classList.add('d-none');
                textarea?.setAttribute('disabled', true);
                textarea?.removeAttribute('required');

                if (textarea) textarea.value = '';
            }
        }
    });
</script>
