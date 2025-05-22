<div class="modal fade" id="modalpayAndCompleteFile-{{ $assetBooking->id }}-{{ $status_booking }}" tabindex="-1"
    aria-labelledby="modalpayAndCompleteFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalpayAndCompleteFileLabel">
                    {{ $assetBooking->payment_type == 'dp' ? 'Bayar DP' : 'Bayar' }} dan Lengkapi Berkas</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                @include('homepage.assets.modal.invoiceAssetBooking')
                <form id="assetBookingProfile-{{ $status_booking }}"
                    action="{{ route('assetBooking.payAndCompleteFile', $assetBooking->id) }}" method="POST"
                    data-table="assetBookingProfile-{{ $status_booking }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="proofOfPayment" class="text-neutral-700 text-sm fw-bold mb-6 d-block">Bukti
                                Pembayaran (.jpg, .jpeg, .png)</label>
                            <input id="proofOfPayment"
                                class="form-control mb-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                type="file" accept=".png,.jpeg,.jpg" name="proof_of_payment">
                        </div>
                        <div class="col-sm-3">
                            <label for="statementLetter" class="text-neutral-700 text-sm fw-bold mb-6 d-block">Surat
                                Pernyataan (.pdf)</label>
                            <input id="statementLetter"
                                class="form-control mb-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                type="file" accept=".pdf" name="statement_letter">
                        </div>
                        <div class="col-sm-3">
                            <label for="loanForm" class="text-neutral-700 text-sm fw-bold mb-6 d-block">Formulir
                                Peminjaman (.pdf)</label>
                            <input id="loanForm"
                                class="form-control mb-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                type="file" accept=".pdf" name="loan_form">
                        </div>
                        @if ($assetBooking->asset->booking_type === 'annual')
                            <div class="col-sm-3">
                                <label for="loanForm" class="text-neutral-700 text-sm fw-bold mb-6 d-block">Surat
                                    Perjanjian Kontrak (.pdf)</label>
                                <input id="loanForm"
                                    class="form-control mb-20 rounded-pill border-transparent focus-border-main-600 d-block"
                                    type="file" accept=".pdf" name="contract_agreement_letter">
                            </div>
                        @endif

                        @if ($assetBooking->reason)
                            <div class="col-sm-12">
                                <label for="reason" class="text-neutral-700 text-sm fw-bold mb-6 d-block">Alasan
                                    Penolakan</label>
                                <div class="alert alert-danger mt-3">
                                    <strong>{{ $assetBooking->reason }}</strong>
                                </div>

                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ajukan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
