<div class="modal fade" id="modalpayInFull-{{ $assetBooking->id }}-{{ $status_booking }}" tabindex="-1"
    aria-labelledby="modalpayInFullLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalpayInFullLabel">Bayar Pelunasan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                Harusnya INVOICE
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
                <p>🔄 <strong>Status:</strong> <span class="text-warning fw-bold">{{ $assetBooking->status }}</span>
                </p>
                <hr class="my-10">
                <form id="assetBookingProfile" action="{{ route('assetBooking.payInFull', $assetBooking->id) }}"
                    method="POST" data-table="assetBookingProfile-{{ $status_booking }}">
                    @csrf
                    <div class="row">
                        <label for="proofOfPayment" class="text-neutral-700 text-sm fw-bold mb-12 d-block">Bukti
                            Pembayaran</label>
                        <input id="proofOfPayment"
                            class="form-control mt-20 rounded-pill border-transparent focus-border-main-600 d-block"
                            type="file" accept=".png,.jpeg,.jpg" name="proof_of_payment">
                    </div>
                    <div class="modal-footer mt-10">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalInvoicePreview-{{ $assetBooking->id }}">📄 Preview Invoice</button>
                        <button type="submit" class="btn btn-success">✔️ Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
