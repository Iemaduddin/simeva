<div class="modal fade" id="modalConfirmAssetBooking-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalConfirmAssetBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalConfirmAssetBookingLabel">Konfirmasi Booking Aset</h1>
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
                    $ktp = $assetBooking->documents->where('document_type', 'Identitas Diri')->first();
                @endphp
                @if ($ktp)
                    <label for="identity_personal"><strong>Identitas Diri</strong></label><br>
                    <img id="identity_personal"
                        src="{{ asset('storage/' . $ktp->document_path) }}?v={{ $ktp->updated_at->timestamp }}"
                    alt="Identity Personal Image" class="w-30" @else <p>Gambar tidak ditemukan
                    </p>
                @endif
                <hr>
                <form id="{{ $tableId }}" action="{{ route('assetBooking.confirm', $assetBooking->id) }}"
                    method="POST" data-table="{{ $tableId }}">
                    @csrf
                    <div class="row my-10">
                        <p class="fw-medium">Pilih Tindakan</p>
                        <div class="col-md-12">
                            <select class="form-control form-select actionConfirmBooking" name="actionBooking">
                                <option value="approved" selected>✅ Setujui Booking</option>
                                <option value="rejected">❌ Tolak Booking</option>
                            </select>
                        </div>
                    </div>

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
                    <div class="row mt-3 reason-reject d-none">
                        <p class="fw-medium">Masukkan Alasan Menolak Booking</p>
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
<!-- Modal Preview Invoice -->
{{-- <div class="modal fade" id="modalInvoicePreview-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalInvoicePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalInvoicePreviewLabel">Invoice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <h3 class="text-center">Invoice #{{ $assetBooking->id }}</h3>
                <p><strong>Nama Aset:</strong> {{ $assetBooking->asset->name }}</p>
                <p><strong>Peminjam:</strong> {{ $assetBooking->user->name }}</p>
                <p><strong>Event:</strong> {{ $assetBooking->usage_event_name }}</p>
                <p><strong>Kategori Event:</strong> {{ $assetBooking->asset_category->category_name }}</p>
                <p><strong>Waktu Pakai:</strong>
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('d-M-Y H.i') }} -
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('d-M-Y H.i') }}</p>
                <p><strong>Pembayaran:</strong> {{ $assetBooking->payment_type }}</p>
                <p><strong>Harga Sewa:</strong> <span
                        class="text-warning fw-bold">{{ $assetBooking->total_amount }}</span></p>
                <hr>
                <p class="text-center">Terima kasih telah menggunakan layanan kami!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div> --}}
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
        if (event.target.classList.contains("actionConfirmBooking")) {
            const modal = event.target.closest(".modal");
            if (!modal) return;

            const vaDetails = modal.querySelector(".va-details");
            const reason = modal.querySelector(".reason-reject");

            const reasonField = modal.querySelector("[name='reason_rejected']");
            const vaNumber = modal.querySelector("[name='va_number']");
            const vaExpiry = modal.querySelector("[name='va_expiry']");

            if (!vaDetails || !reason || !reasonField || !vaNumber || !vaExpiry) return;

            if (event.target.value === "approved") {
                reason.classList.add("d-none");
                vaDetails.classList.remove("d-none");

                reasonField.setAttribute("disabled", "true");
                reasonField.removeAttribute("required");

                vaNumber.removeAttribute("disabled");
                vaNumber.setAttribute("required", "true");
                vaExpiry.removeAttribute("disabled");
                vaExpiry.setAttribute("required", "true");
            } else {
                reason.classList.remove("d-none");
                vaDetails.classList.add("d-none");

                reasonField.removeAttribute("disabled");
                reasonField.setAttribute("required", "true");

                vaNumber.setAttribute("disabled", "true");
                vaNumber.removeAttribute("required");
                vaExpiry.setAttribute("disabled", "true");
                vaExpiry.removeAttribute("required");
            }
        }
    });
</script>
