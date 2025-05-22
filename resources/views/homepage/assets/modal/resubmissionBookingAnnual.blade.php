<div id="modalResubmissionAssetBookingAnnual-{{ $assetBooking->id }}-{{ $status_booking }}" class="modal fade"
    tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-10">
            <div class="modal-header">
                <h5 id="modal-title" class="modal-title">Lengkapi data berikut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('asset.rebooking.tenant', $assetBooking->id) }}"
                id="assetBookingProfile-{{ $status_booking }}" data-table="assetBookingProfile-{{ $status_booking }}"
                class="needs-validation" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="booking_type_annual" value="annual">
                    <input type="hidden" name="asset_id" id="asset-id" value="{{ $assetBooking->asset->id }}">

                    <div class="row gy-4">
                        <div class="col-md-4">
                            <label for="usage_event_name" class="text-neutral-700 text-lg fw-medium mb-12">Keterangan
                                Penggunaan
                                <span class="text-danger-600">*</span> </label>
                            <input type="text"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                name="usage_event_name" value="{{ $assetBooking->usage_event_name }}"
                                placeholder="Masukkan Keterangan Penggunaan Aset" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="usage_date_annual" class="text-neutral-700 text-lg fw-medium mb-12">
                                Mulai Tanggal Sewa <span class="text-danger-600">*</span>
                            </label>
                            <input type="text"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                id="usage_date_annual" name="usage_date_start"
                                value="{{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('Y-m-d') }}"
                                placeholder="Pilih Tanggal" required>
                        </div>

                        <div class="col-md-4">
                            <label for="usage_event_name" class="text-neutral-700 text-lg fw-medium mb-12">Durasi Sewa
                                (Tahun)
                                <span class="text-danger-600">*</span> </label>
                            <input type="number"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                name="duration"
                                value="{{ (int) \Carbon\Carbon::parse($assetBooking->usage_date_start)->diffInYears(\Carbon\Carbon::parse($assetBooking->usage_date_end)) }}"
                                placeholder="Masukkan Durasi Sewa Aset" required>
                        </div>
                        <div class="col-md-4">
                            <label for="file_personal_identity" class="text-neutral-700 text-lg fw-medium mb-24">Scan
                                KTP
                                <span class="text-danger-600">*</span> </label>
                            <input type="file" accept=".pdf, .jpg, .jpeg, .png" name="file_personal_identity"
                                class="form-control border-transparent focus-border-main-600"
                                id="file_personal_identity" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-neutral-700 text-lg fw-medium mb-24">Jenis Pembayaran
                                <span class="text-danger-600">*</span> </label>
                            <div class="flex-align gap-24">
                                <div class="form-check common-check common-radio mb-0">
                                    <input class="form-check-input" type="radio" name="payment_type" id="DP"
                                        value="dp" {{ $assetBooking->payment_type === 'dp' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label fw-normal flex-grow-1" for="DP">DP
                                        (30%)</label>
                                </div>
                                <div class="form-check common-check common-radio mb-0">
                                    <input class="form-check-input" type="radio" name="payment_type" id="Lunas"
                                        value="lunas"
                                        {{ $assetBooking->payment_type === 'lunas' ? 'checked' : '' }}required>
                                    <label class="form-check-label fw-normal flex-grow-1" for="Lunas">Lunas</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-sm-12">
                            <label class="text-success-700 text-lg fw-bold mb-12">Info Tarif:</label>

                            <!-- Periode Sewa -->
                            <div class="row mb-12">
                                <div class="col-12">
                                    <p id="rental_period" class="text-neutral-700 text-md"> <strong>Periode
                                            : </strong> -</p>
                                </div>
                            </div>

                            <!-- Harga DP & Lunas -->
                            <div class="row justify-content-start">
                                <div class="col-sm-6">
                                    <h6 class="fw-bold">Pembayaran secara DP</h6>
                                    <p id="dp_price_annual">DP 30% : Rp0</p>
                                    <p id="remaining_price_annual">Pelunasan : Rp0</p>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="fw-bold">Pembayaran secara Lunas</h6>
                                    <input type="hidden" name="amount" id="amount_annual">
                                    <p id="full_price_annual">Pelunasan : Rp0</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary rounded-pill flex-center gap-8"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-main rounded-pill flex-center gap-8">
                        Ajukan
                        <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>

<script>
    $(document).ready(function() {
        flatpickr("#usage_date_annual", {
            dateFormat: "Y-m-d",
            minDate: "today",
        });
        let assetIdAnnual = $("#asset-id").val();
        let durationInput = $("input[name='duration']");
        let usageDateInput = $("#usage_date_annual");
        let externalPrice = 0; // Harga per tahun
        let startDate = null; // Tanggal mulai sewa

        function calculateAnnualPrice() {
            let duration = parseInt(durationInput.val()) || 1; // default 1 kalau kosong

            if (externalPrice > 0 && startDate) {
                let fullPrice = externalPrice * duration;
                let dpPrice = fullPrice * 0.3;
                let remainingPrice = fullPrice - dpPrice;
                $("#dp_price_annual").text(`DP 30% : Rp${dpPrice.toLocaleString()}`);
                $("#remaining_price_annual").text(`Pelunasan : Rp${remainingPrice.toLocaleString()}`);
                $("#full_price_annual").text(`Pelunasan : Rp${fullPrice.toLocaleString()}`);

                $('#amount_annual').val(fullPrice);

                // Hitung tanggal selesai (menambah tahun sesuai durasi)
                let endDate = new Date(startDate);
                endDate.setFullYear(endDate.getFullYear() + duration);

                // Format tanggal ke bahasa Indonesia
                let startDateFormatted = startDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                let endDateFormatted = endDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                // Tampilkan periode sewa
                $('#rental_period').html(
                    `<strong>Periode :</strong> ${startDateFormatted} - ${endDateFormatted}`);
            }
        }

        function loadFirstCategoryPrice() {
            $.ajax({
                url: "{{ route('asset-booking.getDataCategory', '') }}" + "/" + assetIdAnnual,
                type: "GET",
                success: function(response) {
                    if (response.data.length > 0) {
                        externalPrice = parseFloat(response.data[0].external_price) || 0;
                        calculateAnnualPrice(); // Hitung langsung setelah dapat harga
                    }
                }
            });
        }

        usageDateInput.change(function() {
            let selectedDate = new Date(usageDateInput.val());
            if (!isNaN(selectedDate)) {
                startDate = selectedDate;
                calculateAnnualPrice();
            }
        });

        durationInput.on('input', function() {
            calculateAnnualPrice();
        });

        let selectedDate = new Date(usageDateInput.val());
        if (!isNaN(selectedDate)) {
            startDate = selectedDate;
        }

        // Load harga saat halaman load
        loadFirstCategoryPrice();
    });
</script>
