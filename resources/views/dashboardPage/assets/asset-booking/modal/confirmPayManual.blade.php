<div class="modal fade" id="confirmAssetBookingManual-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="confirmAssetBookingManualLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="confirmAssetBookingManualLabel">Konfirmasi Pembayaran
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                @include('homepage.assets.modal.invoiceAssetBooking')
                <form id="{{ $tableId }}" action="{{ route('confirmPaymentEksternal', $assetBooking->id) }}"
                    method="POST" data-table="{{ $tableId }}" enctype="multipart/form-data">
                    @csrf

                    @php
                        $amount = '';
                        $message = '';
                        $total = $assetBooking->total_amount;
                        $transaction = optional($assetBooking->transactions->first());
                        $status = $transaction->status;

                        if ($status == 'pending') {
                            $dp = ($total * 30) / 100;
                            $full = $total;

                            if ($assetBooking->payment_type == 'dp') {
                                $amount = $dp;
                                $message =
                                    'Pembayaran DP (30%): Rp ' .
                                    number_format($dp, 0, ',', '.') .
                                    '<br>' .
                                    'Pelunasan nanti (70%): Rp ' .
                                    number_format($total - $dp, 0, ',', '.');
                            } else {
                                $amount = $full;
                                $message = 'Pembayaran Lunas (100%): Rp ' . number_format($full, 0, ',', '.');
                            }
                        } elseif ($status == 'dp_paid') {
                            $dp = ($total * 30) / 100;
                            $sisa = $total - $dp;
                            $amount = $sisa;
                            $message =
                                'Sudah dibayar DP (30%): Rp ' .
                                number_format($dp, 0, ',', '.') .
                                '<br>' .
                                'Sisa pelunasan (70%): Rp ' .
                                number_format($sisa, 0, ',', '.');
                        } elseif ($status == 'full_paid') {
                            $message = 'Pembayaran sudah lunas. Total: Rp ' . number_format($total, 0, ',', '.');
                        }
                    @endphp

                    <p><strong>Informasi Pembayaran:</strong><br>{!! $message !!}</p>

                    @if ($assetBooking->status == 'booked' && optional($assetBooking->transactions->first())->status == 'pending')
                        <label class="form-label">Tipe Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select" name="payment_type" required>
                            <option value="">-- Pilih Pembayaran --</option>
                            <option value="dp">DP</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    @endif
                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Bukti Pembayaran<span
                            class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="proof_of_payment" accept=".jpg, .jpeg, .png, .pdf"
                        required>

                    <div class="modal-footer my-10">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success-600">Konfirmasi</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
