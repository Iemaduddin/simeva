<div class="modal fade" id="modalCancelBooking-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalCancelBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalCancelBookingLabel">Konfirmasi Pembatalan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assetBooking.cancelled', ['id' => $assetBooking->id]) }}" method="POST"
                data-table="{{ $tableId }}">
                @csrf
                <div class="modal-body">
                    <p class="fw-bold">Apakah Anda yakin ingin membatalkan booking ini?</p>

                    @if (
                        $assetBooking->status === 'submission_dp_payment' ||
                            $assetBooking->status === 'submission_full_payment' ||
                            $assetBooking->status === 'approved_dp_payment' ||
                            $assetBooking->status === 'approved_full_payment')
                        <p class="text-danger">Anda harus mengembalikan uang tenant sesuai dengan kesepakatan.</p>
                    @endif

                    <!-- Input Alasan Pembatalan -->
                    <div class="mb-3">
                        <label for="reason-{{ $assetBooking->id }}" class="form-label">Alasan Pembatalan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason-{{ $assetBooking->id }}" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-danger-600">
                        Ya, Batalkan
                    </button>
            </form>
        </div>
    </div>
</div>
</div>
