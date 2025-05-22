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
                <form action="{{ route('assetBookingEksternal.addManual') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3 p-3">
                        <div class="col-md-3">
                            <label class="form-label">Nama Peminjam<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Masukkan nama peminjam"
                                name="external_user">
                            <div class="invalid-feedback">
                                Nama Peminjam wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nama Event<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Masukkan nama peminjam"
                                name="usage_event_name">
                            <div class="invalid-feedback">
                                Nama Peminjam wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Aset <span class="text-danger">*</span></label>
                            <select class="form-select" name="asset_id" id="assetSelect" required>
                                <option value="">-- Pilih Aset --</option>
                                @foreach ($assets as $assetId => $assetName)
                                    <option value="{{ $assetId }}">{{ $assetName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori Booking <span class="text-danger">*</span></label>
                            <select class="form-select" name="asset_category_id" id="categorySelect" required>
                                <option value="">-- Pilih Kategori --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori Tarif <span class="text-danger">*</span></label>
                            <select class="form-select" name="price_type" id="priceTypeSelect" required>
                                <option value="">-- Pilih Tarif --</option>
                                <option value="external">Eksternal</option>
                                <option value="internal">Internal</option>
                                <option value="social">Sosial</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="priceDisplay" readonly>
                            <input type="hidden" name="total_amount" id="priceValue">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="booked">Booking Disetujui</option>
                                <option value="approved_full_payment">Peminjaman Disetujui</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipe Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_type" required>
                                <option value="">-- Pilih Pembayaran --</option>
                                <option value="dp">DP</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" id="proof_label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" name="proof_of_payment" id="proof_of_payment"
                                accept=".jpg, .jpeg, .png, .pdf">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control date-pickr" name="event_date" required>
                            <div class="invalid-feedback">
                                Tanggal wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"> Jam Mulai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control time-pickr" name="event_time_start" required>
                            <div class="invalid-feedback">
                                Jam Mulai wajib diisi!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="text" class="form-control time-pickr" name="event_time_end" required>
                            <div class="invalid-feedback">
                                Jam Selesai wajib diisi!
                            </div>
                        </div>
                        <div class=" row gy-3 booking-date-time-wrapper">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Loading<span class="text-danger">*</span></label>
                                <input type="text" class="form-control date-pickr" name="loading_date" required>
                                <div class="invalid-feedback">Tanggal wajib diisi!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jam Mulai Loading <span class="text-danger">*</span></label>
                                <input type="text" class="form-control time-pickr" name="loading_time_start"
                                    required>
                                <div class="invalid-feedback">Jam Mulai wajib diisi!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jam Selesai Loading <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control time-pickr" name="loading_time_end"
                                    required>
                                <div class="invalid-feedback">Jam Selesai wajib diisi!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Unloading<span class="text-danger">*</span></label>
                                <input type="text" class="form-control date-pickr" name="unloading_date" required>
                                <div class="invalid-feedback">Tanggal wajib diisi!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jam Mulai Unloading <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control time-pickr" name="unloading_time_start"
                                    required>
                                <div class="invalid-feedback">Jam Mulai wajib diisi!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jam Selesai Unloading <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control time-pickr" name="unloading_time_end"
                                    required>
                                <div class="invalid-feedback">Jam Selesai wajib diisi!</div>
                            </div>
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
    flatpickr(".date-pickr", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enableTime: true,
        time_24hr: true,
    });

    flatpickr(".time-pickr", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });

    flatpickr(".time-pickr", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
    });
</script>
