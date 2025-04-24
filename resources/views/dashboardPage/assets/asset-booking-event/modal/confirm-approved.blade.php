<div class="modal fade" id="modalConfirmApproved-{{ $eventId }}" tabindex="-1"
    aria-labelledby="modalConfirmApprovedLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalConfirmApprovedLabel">Konfirmasi Surat Peminjaman
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="{{ $tableId }}"
                    action="{{ route('assetBookingEvent.confirmDocument', ['eventId' => $eventId]) }}" method="POST"
                    data-table="{{ $tableId }}">
                    @csrf
                    <div class="row">
                        @if ($documentPath)
                            <iframe src="{{ asset('storage/' . $documentPath) }}" frameborder="0" width="100%"
                                height="600px"></iframe>
                        @endif
                        <p class="fw-medium">Pilih Tindakan</p>
                        <div class="col-md-12">
                            <select class="form-select actionConfirmDocument" name="actionConfirmDocument" required>
                                <option value="approved" selected>✅ Setujui Surat Peminjaman</option>
                                <option value="rejected">❌ Tolak Setujui Surat Peminjaman</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 reason-reject d-none">
                        <p class="fw-medium">Masukkan Alasan Menolak Surat Peminjaman</p>
                        <div class="col-md-12">
                            <textarea class="form-control radius-8 bg-base" name="reason_rejected" placeholder="Masukkan Alasan Ditolak"></textarea>
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
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".reason-reject").forEach(element => {
            element.classList.add("d-none");
            const reasonField = element.querySelector("[name='reason_rejected']");
            if (reasonField) {
                reasonField.setAttribute("disabled", "true");
                reasonField.removeAttribute("required");
            }
        });
    });

    document.addEventListener("change", function(event) {
        if (event.target.classList.contains("actionConfirmDocument")) {
            const modal = event.target.closest(".modal");
            if (!modal) return;

            const reason = modal.querySelector(".reason-reject");
            const reasonField = modal.querySelector("[name='reason_rejected']");

            console.log(reason, reasonField);
            if (!reason || !reasonField) return;

            if (event.target.value === "approved") {
                console.log('bisa');
                reason.classList.add("d-none");

                reasonField.setAttribute("disabled", "true");
                reasonField.removeAttribute("required");
            } else {
                console.log('bisa');

                reason.classList.remove("d-none");
                reasonField.removeAttribute("disabled");
                reasonField.setAttribute("required", "true");

            }
        }
    });
</script>
