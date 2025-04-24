<div class="modal fade" id="modalAddManualBooking" tabindex="-1" aria-labelledby="modalAddManualBookingLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalAddManualBookingLabel">Tambah Booking
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assetBookingEvent.addManual') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                            <select class="form-select" name="user_organizer" required
                                onchange="const otherField = this.form.querySelector('[name=user_not_organizer]');
                                         const otherCol = otherField.closest('.col-md-6');
                                         const isOther = this.value === 'other';
                                         otherCol.classList.toggle('d-none', !isOther);
                                         otherField.disabled = !isOther;
                                         otherField.required = isOther;">
                                @foreach ($organizers as $orgId => $orgName)
                                    <option value="{{ $orgId }}">{{ $orgName }}</option>
                                @endforeach
                                <option value="other">Isi Manual</option>
                            </select>
                        </div>

                        <div class="col-md-6 d-none">
                            <label class="form-label">Nama Peminjam (Isi Manual) <span
                                    class="text-danger">*</span></label>

                            <input type="text" class="form-control" placeholder="Masukkan nama peminjam"
                                name="user_not_organizer" disabled>
                            <div class="invalid-feedback">
                                Nama Peminjam wajib diisi!
                            </div>
                        </div>
                    </div>

                    <div class="row px-3">
                        <div id="event-steps-container">
                            <div class="event-step">
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Masukkan nama event"
                                            name="step_names">
                                        <div class="invalid-feedback">
                                            Nama Tahapan Event wajib diisi!
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Aset <span class="text-danger">*</span></label>
                                        <select class="form-select" name="assets[]" required>
                                            @foreach ($assets as $assetId => $assetName)
                                                <option value="{{ $assetId }}">{{ $assetName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control date-execution-pickr"
                                            name="event_dates[]" required>
                                        <div class="invalid-feedback">
                                            Tanggal wajib diisi!
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"> Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control time-start-execution-pickr"
                                            name="event_time_starts[]" required>
                                        <div class="invalid-feedback">
                                            Jam Mulai wajib diisi!
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control time-end-execution-pickr"
                                            name="event_time_ends[]" required>
                                        <div class="invalid-feedback">
                                            Jam Selesai wajib diisi!
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kategori Penyewaan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="assets[]" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status[]" required>
                                            <option value="booked">Booking Disetujui</option>
                                            <option value="approved">Peminjaman Disetujui</option>
                                        </select>
                                    </div>
                                    <div class="text-start mt-3">
                                        <a href="#"
                                            class="btn btn-sm btn-danger-600 remove-event-step d-none">Hapus</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" id="add-event-step"
                                    class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill">
                                    <iconify-icon icon="zondicons:add-outline" class="menu-icon"></iconify-icon> Tambah
                                    Hari
                                </a>
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
                                Tambah
                            </button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>

<script>
    flatpickr(".date-execution-pickr", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enableTime: true,
        time_24hr: true,
    });

    flatpickr(".time-start-execution-pickr", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });

    flatpickr(".time-end-execution-pickr", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });
</script>
