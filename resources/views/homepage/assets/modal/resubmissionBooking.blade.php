<div id="modalResubmissionAssetBooking-{{ $assetBooking->id }}-{{ $status_booking }}" class="modal fade" tabindex="-1"
    aria-labelledby="modal-title" aria-hidden="true">
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
                    <input type="hidden" name="asset_id" id="asset_id" value="{{ $assetBooking->asset->id }}">
                    <input type="hidden" id="available_days" value="{{ $assetBooking->asset->available_at }}">
                    <input type="hidden" id="status" value="{{ $assetBooking->asset->status }}">
                    <div class="row gy-4">
                        <div class="col-sm-4">
                            <label for="usage_date_display" class="text-neutral-700 text-lg fw-medium mb-12">
                                Tanggal dan Waktu Acara <span class="text-danger-600">*</span>
                            </label>
                            <input type="text"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                id="usage_date_display" placeholder="Pilih Tanggal" readonly>
                            <input type="hidden" id="usage_date"
                                value="{{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('Y-m-d') }}"
                                name="usage_date">
                        </div>

                        <div class="col-sm-4">
                            <label for="start_time" class="text-neutral-700 text-lg fw-medium mb-12">Waktu Mulai
                                Acara
                                <span class="text-danger-600">*</span> </label>
                            <input type="time"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                id="start_time" name="start_time"
                                value="{{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('H:i') }}"
                                placeholder="Masukkan Waktu Mulai Acara" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="end_time" class="text-neutral-700 text-lg fw-medium mb-12">Waktu Selesai
                                Acara
                                <span class="text-danger-600">*</span> </label>
                            <input type="time"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                id="end_time" name="end_time"
                                value="{{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('H:i') }}"
                                placeholder="Masukkan Waktu Selesai Acara" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="usage_event_name" class="text-neutral-700 text-lg fw-medium mb-12">Nama
                                Acara
                                <span class="text-danger-600">*</span> </label>
                            <input type="text"
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                id="usage_event_name" name="usage_event_name"
                                value="{{ $assetBooking->usage_event_name }}" placeholder="Masukkan nama acara"
                                required>
                        </div>
                        <div class="col-sm-4">
                            <label for="type_event" class="text-neutral-700 text-lg fw-medium mb-12">
                                Jenis Acara <span class="text-danger-600">*</span>
                            </label>
                            <select
                                class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600 form-select py-14"
                                id="type_event" name="type_event" required>
                                <option value="" selected hidden>Pilih Jenis Acara</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="file_personal_identity" class="text-neutral-700 text-lg fw-medium mb-24">Scan
                                KTP
                                <span class="text-danger-600">*</span> </label>
                            <input type="file" accept=".pdf, .jpg, .jpeg, .png" name="file_personal_identity"
                                class="form-control border-transparent focus-border-main-600"
                                id="file_personal_identity" required>
                        </div>
                        <div class="col-sm-4">
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
                                    <input class="form-check-input" type="radio" name="payment_type"
                                        id="Lunas" value="lunas"
                                        {{ $assetBooking->payment_type === 'lunas' ? 'checked' : '' }} required>
                                    <label class="form-check-label fw-normal flex-grow-1" for="Lunas">Lunas</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-sm-12">
                            <label class="text-success-700 text-lg fw-bold mb-12">Info Tarif: </label>
                            <div class="row justify-content-start">
                                <div class="col-sm-4">
                                    <h6>Pembayaran secara DP</h6>
                                    <p id="dp_price">DP 30% : Rp0</p>
                                    <p id="remaining_price">Pelunasan : Rp0</p>
                                </div>
                                <div class="col-sm-4">
                                    <h6>Pembayaran secara Lunas</h6>
                                    <input type="hidden" name="amount" id="amount">
                                    <p id="full_price">Pelunasan : Rp0</p>
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
<script>
    $(document).ready(function() {
        let assetId = $("#asset_id").val();
        let typeEvent = $("#type_event");
        let selectedCategoryId = "{{ $assetBooking->asset_category_id }}";
        // Fungsi untuk menghitung harga
        function calculatePrice() {
            let selectedOption = typeEvent.find(":selected");
            let selectedPrice = selectedOption.data("price"); // Default 1 jika kosong

            if (selectedPrice) {
                let fullPrice = parseFloat(selectedPrice);
                let dpPrice = fullPrice * 0.3;
                let remainingPrice = fullPrice - dpPrice;

                $("#dp_price").text(`DP 30% : Rp${dpPrice.toLocaleString()}`);
                $("#remaining_price").text(`Pelunasan : Rp${remainingPrice.toLocaleString()}`);
                $("#full_price").text(`Pelunasan : Rp${fullPrice.toLocaleString()}`);
                $('#amount').val(fullPrice);
            } else {
                $("#dp_price").text("DP 30% : Rp0");
                $("#remaining_price").text("Pelunasan : Rp0");
                $("#full_price").text("Pelunasan : Rp0");
            }
        }

        function loadCategories() {
            $.ajax({
                url: "{{ route('asset-booking.getDataCategory', '') }}" + "/" + assetId,
                type: "GET",
                success: function(response) {
                    typeEvent.empty();
                    typeEvent.append(
                        '<option value="" hidden>Pilih Jenis Acara</option>');

                    response.data.forEach(category => {
                        let isSelected = category.id == selectedCategoryId ?
                            "selected" :
                            "";
                        typeEvent.append(
                            `<option value="${category.id}" data-price="${category.external_price}" ${isSelected}>
                                ${category.category_name}
                            </option>`
                        );
                    });

                    // Hitung harga setelah kategori terpilih
                    calculatePrice();
                }
            });
        }

        loadCategories();

        // Event listener untuk perubahan jenis acara
        typeEvent.change(calculatePrice);

        let usageData = $('#usage_date').val(); // Format: 'YYYY-MM-DD'

        // Buat objek Date dari string
        let usageDate = new Date(usageData);

        // Format tanggal dengan locale 'id-ID'
        let formattedDisplayDate = usageDate.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

        $("#usage_date_display").val(formattedDisplayDate);
    });
</script>
