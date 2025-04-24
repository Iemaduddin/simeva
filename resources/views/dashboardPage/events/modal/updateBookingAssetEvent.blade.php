<div class="modal fade" id="modalUpdateBooking-{{ $booking->id }}" tabindex="-1" aria-labelledby="modalUpdateBookingLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalUpdateBookingLabel">Perbarui Peminjaman Aset
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assetBookingEvent.update', ['id' => $booking->id]) }}" method="POST"
                    data-table="loanAssetEventTable">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Nama Aset</label>
                            <input type="text" class="form-control" disabled value="{{ $booking->asset->name }}">
                            <input type="hidden" class="form-control" name="asset_id" value="{{ $booking->asset->id }}"
                                required>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label">Tanggal Peminjaman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control date-loan-pickr" name="event_date"
                                value="{{ \Carbon\Carbon::parse($booking->usage_date_start)->translatedFormat('Y-m-d') }}"
                                required>
                            <div class="invalid-feedback">
                                Tanggal Peminjaman wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label"> Jam Mulai Peminjaman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control time-loan-pickr" name="event_time_start"
                                value="{{ \Carbon\Carbon::parse($booking->usage_date_start)->translatedFormat('H:i') }}"
                                required>
                            <div class="invalid-feedback">
                                Jam Mulai Peminjaman wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label">Jam Selesai Peminjaman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control time-loan-pickr" name="event_time_end"
                                value="{{ \Carbon\Carbon::parse($booking->usage_date_end)->translatedFormat('H:i') }}"required>
                            <div class="invalid-feedback">
                                Jam Selesai Peminjaman wajib diisi!
                            </div>
                        </div>
                        @if ($booking->status === 'rejected')
                            <div class="col-md-12 mt-3">
                                <p class="fw-bold">Alasan Penolakan:</p>
                                <div class="border-1 p-10 radius-10">{{ $booking->reason }} </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                        <button type="reset"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Ubah
                        </button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>
<script>
    flatpickr(".date-loan-pickr", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enableTime: false,
    });
    flatpickr(".time-loan-pickr", {
        noCalendar: true,
        dateFormat: "H:i",
        enableTime: true,
        time_24hr: true,
    });
</script>
