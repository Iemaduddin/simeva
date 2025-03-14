<div class="modal fade" id="modalConfirmPaymentAndStatementLetter-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalConfirmPaymentAndStatementLetterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalConfirmPaymentAndStatementLetterLabel">Konfirmasi Bukti Pembayaran
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <p>👤 <strong>Nama Aset:</strong> {{ $assetBooking->asset->name }}</p>
                <p> <strong>Peminjam:</strong> {{ $assetBooking->user->name }}</p>
                <p>📅 <strong>Event:</strong> {{ $assetBooking->usage_event_name }}</p>
                <p>📌 <strong>Kategori Event:</strong> {{ $assetBooking->asset_category->category_name }}</p>
                <p>📆 <strong>Waktu Pakai:</strong>
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('d-M-Y H.i') }}
                    -
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('d-M-Y H.i') }}</p>
                <p>🔄 <strong>Pembayaran:</strong> <span>{{ $assetBooking->payment_type }}</span></p>
                <p>🔄 <strong>Harga Sewa:</strong> <span
                        class="text-warning fw-bold">{{ $assetBooking->total_amount }}</span></p>
                <p>🔄 <strong>Status:</strong> <span class="text-warning fw-bold">{{ $assetBooking->status }}</span></p>
                @php
                    // $proofOfPayment = $assetBooking->documents->where('document_type', 'Identitas Diri')->first();
                    $proofOfPayment = $assetBooking->transactions->where('status', 'pending')->first();
                @endphp
                @if ($assetBooking->status === 'submission_dp_payment' || $assetBooking->status === 'submission_full_payment')
                    <div class="row">
                        @if ($proofOfPayment)
                            <div class="col-md-4">
                                <label for="identity_personal"><strong>Bukti Pembayaran</strong></label><br>
                                <img id="identity_personal"
                                    src="{{ asset('storage/' . $proofOfPayment->proof_of_payment) }}?v={{ $proofOfPayment->updated_at->timestamp }}"
                                alt="Identity Personal Image" class="w-30" @else <p>Gambar tidak ditemukan
                                </p>
                            </div>
                        @endif
                        {{-- <img src="{{ asset('storage/' . $assetBooking->transactions->first()->proof_of_payment) }}"
                                alt="Identity Personal Image" width="100px" height="30px"> --}}

                        <div class="col-md-4">
                            <a href="#"> Form Peminjaman</a>
                        </div>
                        <div class="col-md-4">
                            <a href="#"> Surat Pernyataan</a>
                        </div>

                    </div>
                @else
                    {{-- <img src="{{ asset('storage/' . $assetBooking->transactions->first()->proof_of_payment) }}"
                alt="Identity Personal Image" width="100px" height="30px"> --}}
                @endif
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
                                value="{{ $assetBooking->loading_date_start ?? '' }}"
                                data-error-message="Tanggal dan waktu mulai loading harus diisi" required>
                        </div>
                        <div class="col-md-4">
                            <label for="loading_date_end">Tanggal Selesai Loading</label>
                            <input type="text" class="form-control flatpickr-datetime" name="loading_date_end"
                                id="loading_date_end" placeholder="Pilih Tanggal & Waktu"
                                value="{{ $assetBooking->loading_date_end ?? '' }}"
                                data-error-message="Tanggal dan waktu selesai loading harus diisi" required>
                        </div>
                        <div class="col-md-4">
                            <label for="unloading_date">Tanggal Unloading</label>
                            <input type="text" class="form-control flatpickr-datetime" name="unloading_date"
                                id="unloading_date" placeholder="Pilih Tanggal & Waktu"
                                value="{{ $assetBooking->unloading_date ?? '' }}"
                                data-error-message="Tanggal dan waktu unloading harus diisi" required>
                        </div>
                    </div>
                    @if ($assetBooking->status === 'submission_dp_payment')
                        <!-- Input VA (Hanya tampil jika "Setujui Booking" dipilih) -->
                        <div class="row mt-3 va-details">
                            <p class="fw-medium">Masukkan Nomor VA dan Expired VA</p>
                            <div class="col-md-6">
                                <label for="va_number">Nomor Virtual Account</label>
                                <input type="text" class="form-control" name="va_number" id="va_number"
                                    placeholder="Masukka Nomor VA" required>
                            </div>
                            <div class="col-md-6">
                                <label for="va_expiry">Expired Virtual Account</label>
                                <input type="date" class="form-control" name="va_expiry" id="va_expiry" required>
                            </div>
                        </div>
                    @endif
                    <div class="row mt-3 reason-reject d-none">
                        <p class="fw-medium">Masukkan Alasan Menolak Pembayaran</p>
                        <div class="col-md-12">
                            <textarea class="form-control radius-8 bg-base" name="reason_rejected" placeholder="Masukkan Alasan Ditolak"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer my-10">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalInvoicePreview-{{ $assetBooking->id }}">📄 Preview
                            Invoice</button>
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
        // Ambil usage dates dari data yang ada
        const usageDateStart = new Date('{{ $assetBooking->usage_date_start }}');
        const usageDateEnd = new Date('{{ $assetBooking->usage_date_end }}');

        let config = {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: "id",
            minuteIncrement: 1,
            allowInput: true,
            placeholder: "Pilih Tanggal & Waktu",
        };

        // Konfigurasi khusus berdasarkan jenis input
        switch (input.getAttribute('name')) {
            case 'loading_date_start':
                config = {
                    ...config,
                    minDate: new Date(), // Tidak boleh sebelum hari ini
                    maxDate: usageDateStart, // Tidak boleh melebihi usage_date_start
                    onChange: function(selectedDates, dateStr, instance) {
                        // Update min date untuk loading_date_end
                        const loadingEndPicker = document.querySelector('[name="loading_date_end"]')
                            ._flatpickr;
                        if (loadingEndPicker) {
                            loadingEndPicker.set('minDate', dateStr);
                        }
                        // validateDateTimeInputs();
                    }
                };
                break;

            case 'loading_date_end':
                config = {
                    ...config,
                    minDate: new Date(), // Akan diupdate oleh loading_date_start
                    maxDate: usageDateStart,
                    onChange: function(selectedDates, dateStr, instance) {
                        // validateDateTimeInputs();
                    }
                };
                break;

            case 'unloading_date':
                config = {
                    ...config,
                    minDate: usageDateEnd, // Hanya bisa setelah usage_date_end
                    onChange: function(selectedDates, dateStr, instance) {
                        // validateDateTimeInputs();
                    }
                };
                break;
        }

        flatpickr(input, config);
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

        document.querySelectorAll(".loading-date").forEach(element => {
            element.classList.remove(
                "d-none"); // Pastikan VA tetap muncul jika "Setujui" dipilih awalnya
            const loadingDateStart = element.querySelector("[name='loading_date_start']");
            const loadingDateEnd = element.querySelector("[name='loading_date_end']");
            const unloadingDate = element.querySelector("[name='unloading_date']");
            if (loadingDateStart && loadingDateEnd && unloadingDate) {
                loadingDateStart.removeAttribute("disabled");
                loadingDateStart.setAttribute("required", "true");
                loadingDateEnd.removeAttribute("disabled");
                loadingDateEnd.setAttribute("required", "true");
                unloadingDate.removeAttribute("disabled");
                unloadingDate.setAttribute("required", "true");
            }
        });
        document.querySelectorAll(".va-details").forEach(element => {
            element.classList.remove(
                "d-none"); // Pastikan VA tetap muncul jika "Setujui" dipilih awalnya
            const vaNumber = element.querySelector("[name='va_number']");
            const vaExpiry = element.querySelector("[name='va_expiry']");
            if (vaNumber && vaExpiry) {
                vaNumber.removeAttribute("disabled");
                vaNumber.setAttribute("required", "true");
                vaExpiry.removeAttribute("disabled");
                vaExpiry.setAttribute("required", "true");
            }
        });
    });

    document.addEventListener("change", function(event) {
        if (event.target.classList.contains("actionConfirmPayment")) {
            const modal = event.target.closest(".modal");
            if (!modal) return;

            const loadingDate = modal.querySelector(".loading-date");
            const vaDetail = modal.querySelector(".va-details");
            const reason = modal.querySelector(".reason-reject");

            const reasonField = modal.querySelector("[name='reason_rejected']");
            const loadingDateStart = modal.querySelector("[name='loading_date_start']");
            const loadingDateEnd = modal.querySelector("[name='loading_date_end']");
            const unloadingDate = modal.querySelector("[name='unloading_date']");
            const vaNumber = modal.querySelector("[name='va_number']");
            const vaExpiry = modal.querySelector("[name='va_expiry']");

            if (!loadingDate || !vaDetail || !reason || !reasonField || !loadingDateStart || !loadingDateEnd ||
                !unloadingDate) return;

            if (event.target.value === "approved") {
                reason.classList.add("d-none");
                loadingDate.classList.remove("d-none");
                vaDetail.classList.remove("d-none");

                reasonField.setAttribute("disabled", "true");
                reasonField.removeAttribute("required");

                loadingDateStart.removeAttribute("disabled");
                loadingDateStart.setAttribute("required", "true");
                loadingDateEnd.removeAttribute("disabled");
                loadingDateEnd.setAttribute("required", "true");
                unloadingDate.removeAttribute("disabled");
                unloadingDate.setAttribute("required", "true");

                vaNumber.removeAttribute("disabled");
                vaNumber.setAttribute("required", "true");
                vaExpiry.removeAttribute("disabled");
                vaExpiry.setAttribute("required", "true");
            } else {
                reason.classList.remove("d-none");
                loadingDate.classList.add("d-none");
                vaDetail.classList.add("d-none");

                reasonField.removeAttribute("disabled");
                reasonField.setAttribute("required", "true");

                loadingDateStart.setAttribute("disabled", "true");
                loadingDateStart.removeAttribute("required");
                loadingDateEnd.setAttribute("disabled", "true");
                loadingDateEnd.setAttribute("disabled", "true");
                unloadingDate.removeAttribute("required");
                vaNumber.removeAttribute("required");
                vaExpiry.removeAttribute("required");
            }
        }
    });
</script>
