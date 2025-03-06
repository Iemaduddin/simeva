<div class="modal fade" id="modalpayAndCompleteFile-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalpayAndCompleteFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalpayAndCompleteFileLabel">
                    {{ $assetBooking->payment_type == 'dp' ? 'Bayar DP' : 'Bayar' }} dan Lengkapi Berkas</h1>
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
                <form id="assetBookingProfile"
                    action="{{ route('assetBooking.payAndCompleteFile', $assetBooking->id) }}" method="POST"
                    data-table="assetBookingProfile">
                    @csrf
                    <div class="row">
                        <a href="#" class="text-sm my-5">Download Surat Pernyataan dan Formulir Peminjaman</a>
                        <div class="{{ $assetBooking->asset->booking_type == 'daily' ? 'col-sm-4' : 'col-sm-6' }}">
                            <label for="proofOfPayment" class="text-neutral-700 text-sm fw-bold mb-12 d-block">Bukti
                                Pembayaran</label>
                            <input id="proofOfPayment"
                                class="form-control mt-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                type="file" accept=".pdf,.png,.jpeg,.jpg" name="proof_of_payment">
                        </div>
                        <div class="{{ $assetBooking->asset->booking_type == 'daily' ? 'col-sm-4' : 'col-sm-6' }}">
                            <label for="statementLetter" class="text-neutral-700 text-sm fw-bold mb-12 d-block">Surat
                                Pernyataan</label>
                            <input id="statementLetter"
                                class="form-control mt-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                type="file" accept=".pdf" name="statement_letter">
                        </div>
                        <div class="{{ $assetBooking->asset->booking_type == 'daily' ? 'col-sm-4' : 'col-sm-6' }}">
                            <label for="loanForm" class="text-neutral-700 text-sm fw-bold mb-12 d-block">Formulir
                                Peminjaman</label>
                            <input id="loanForm"
                                class="form-control mt-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                type="file" accept=".pdf" name="loan_form">
                        </div>
                        @if ($assetBooking->asset->booking_type === 'annual')
                            <div class="col-sm-6">
                                <label for="loanForm" class="text-neutral-700 text-sm fw-bold mb-12 d-block">Surat
                                    Perjanjian Kontrak</label>
                                <input id="loanForm"
                                    class="form-control mt-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                    type="file" accept=".pdf" name="contract_agreement_letter">
                            </div>
                        @endif
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
