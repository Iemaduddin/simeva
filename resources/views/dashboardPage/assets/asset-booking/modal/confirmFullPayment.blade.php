<div class="modal fade" id="modalConfirmFullPayment-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalConfirmFullPaymentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalConfirmFullPaymentLabel">Konfirmasi Bukti Pembayaran Pelunasan
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <p>👤 <strong>Nama Aset:</strong> {{ $assetBooking->asset->name }}</p>
                <p> <strong>Peminjam:</strong> {{ $assetBooking->user->name }}</p>
                <p>📅 <strong>Event:</strong> {{ $assetBooking->usage_event_name }}</p>
                <p>📌 <strong>Kategori Event:</strong> {{ $assetBooking->asset_category->category_name ?? '-' }}</p>
                <p>📆 <strong>Waktu Pakai:</strong>
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('d-M-Y H.i') }}
                    -
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('d-M-Y H.i') }}</p>
                <p>🔄 <strong>Pembayaran:</strong> <span>{{ $assetBooking->payment_type }}</span></p>
                <p>🔄 <strong>Harga Sewa:</strong> <span
                        class="text-warning fw-bold">{{ $assetBooking->total_amount }}</span></p>
                <p>🔄 <strong>Status:</strong> <span class="text-warning fw-bold">{{ $assetBooking->status }}</span></p>
                {{-- @php
                    $ktp = $assetBooking->documents->where('document_type', 'Identitas Diri')->first();
                @endphp --}}
                <div class="row">
                    <div class="col-md-4">
                        {{-- <img src="{{ asset('storage/' . $assetBooking->transactions->first()->proof_of_payment) }}"
                            alt="Identity Personal Image" width="100px" height="30px"> --}}

                    </div>
                    <div class="col-md-4">
                        <a href="#"> Form Peminjaman</a>
                    </div>
                    <div class="col-md-4">
                        <a href="#"> Surat Pernyataan</a>
                    </div>

                </div>
                <hr class="my-10">
                <form id="{{ $tableId }}" action="{{ route('assetBooking.confirmPayment', $assetBooking->id) }}"
                    method="POST" data-table="{{ $tableId }}">
                    @csrf
                    <div class="row">
                        <p class="fw-medium">Pilih Tindakan</p>
                        <div class="col-md-12">
                            <select class="form-control actionConfirmPayment" name="actionConfirmPayment" required>
                                <option value="approved" selected>✅ Setujui Pembayaran dan Berkas</option>
                                <option value="rejected">❌ Tolak Pembayaran dan Berkas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 loading-date">
                        <p class="fw-medium">Masukkan Tanggal Loading dan Unloading</p>
                        <div class="col-md-4">
                            <label for="loading_date_start">Tanggal Mulai Loading</label>
                            <input type="text" class="form-control flatpickr-datetime" name="loading_date_start"
                                id="loading_date_start" placeholder="Pilih Tanggal & Waktu"
                                data-error-message="Tanggal dan waktu mulai loading harus diisi" required>
                        </div>
                        <div class="col-md-4">
                            <label for="loading_date_end">Tanggal Selesai Loading</label>
                            <input type="text" class="form-control flatpickr-datetime" name="loading_date_end"
                                id="loading_date_end" placeholder="Pilih Tanggal & Waktu"
                                data-error-message="Tanggal dan waktu selesai loading harus diisi" required>
                        </div>
                        <div class="col-md-4">
                            <label for="unloading_date">Tanggal Unloading</label>
                            <input type="text" class="form-control flatpickr-datetime" name="unloading_date"
                                id="unloading_date" placeholder="Pilih Tanggal & Waktu"
                                data-error-message="Tanggal dan waktu unloading harus diisi" required>
                        </div>
                    </div>
                    <div class="row mt-3 reason-reject d-none">
                        <p class="fw-medium">Masukkan Alasan Menolak Pembayaran</p>
                        <div class="col-md-12">
                            <textarea class="form-control radius-8 bg-base" name="reason_rejected" placeholder="Masukkan Alasan Ditolak"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer my-10">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalInvoicePreview-{{ $assetBooking->id }}">📄 Preview Invoice</button>
                        <button type="submit" class="btn btn-success-600">✔️ Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    const datetimePickers = document.querySelectorAll('.flatpickr-datetime');
    datetimePickers.forEach(input => {
        flatpickr(input, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            minDate: "today",
            minuteIncrement: 1,
            allowInput: true,
            placeholder: "Pilih Tanggal & Waktu",
            onChange: function(selectedDates, dateStr, instance) {
                validateDateTimeInputs();
            }
        });
    });
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
        if (event.target.classList.contains("actionConfirmPayment")) {
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
