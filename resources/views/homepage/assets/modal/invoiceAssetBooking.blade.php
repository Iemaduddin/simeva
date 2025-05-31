<div id="invoice-container" class="invoice p-10 rounded shadow-sm border border-3">
    <div class="d-flex justify-content-between mb-4">
        <div>
            <h5 class="fw-bold mb-1">INVOICE PEMBAYARAN</h5>
            <small class="text-muted">No. Booking: {{ $assetBooking->booking_number }}</small><br>
        </div>
        <div class="text-end">
            <img src="{{ asset('assets/images/simeva-light.png') }}" alt="Logo" width="150">
            <p class="mb-0"><strong>POLITEKNIK NEGERI MALANG</strong></p>
            <small class="text-muted">Jl. Soekarno Hatta No 9
                Malang 65141
                0341-404424</small>
        </div>
    </div>

    <hr>

    <div class="row mb-4">
        <div class="col-6">
            <p class="fw-bold fs-5">Detail Peminjam:</p>
            <p class="mb-0">{{ $assetBooking->user->name ?? $assetBooking->external_user }}</p>
            <small class="text-muted">{{ $assetBooking->user->email ?? '-' }}</small><br>
            <small class="text-muted">{{ $assetBooking->user->phone_number ?? '-' }}</small><br>
        </div>
        <div class="col-6 text-end">
            Booking Aset: {{ $assetBooking->asset->name }}<br>
            <small class="text-muted">Tanggal:
                @if ($assetBooking->booking_type_annual)
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->translatedFormat('d F Y') }} -
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->translatedFormat('d F Y') }}
                @else
                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->translatedFormat('d F Y, H.i') }}
                    - {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->translatedFormat('d F Y, H.i') }}
                @endif
            </small>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Deskripsi</th>
                <th class="text-end">Amount (IDR)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $transactions = $assetBooking->transactions;
                $dpTransaction = $transactions->where('status', 'dp_paid')->first();
                $pendingTransaction = $transactions->where('status', 'pending')->first();
            @endphp

            @if ($assetBooking->payment_type === 'dp')

                @if ($dpTransaction)
                    <tr>
                        <td>Uang Muka</td>
                        <td class="text-end">Rp{{ number_format($dpTransaction->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ul>
                                <li>VA Number &nbsp;&nbsp;&nbsp; : {{ $dpTransaction->va_number ?? '-' }}</li>
                                <li>Account Name &nbsp;&nbsp;&nbsp; :
                                    {{ $assetBooking->user->name ?? $assetBooking->external_user }}</li>
                                <li>Status &nbsp;&nbsp;&nbsp; : <strong>Sudah Dibayar</strong></li>
                            </ul>
                        </td>
                    </tr>
                @elseif ($pendingTransaction)
                    {{-- Masih DP saja, belum bayar --}}
                    <tr>
                        <td>Uang Muka</td>
                        <td class="text-end">Rp{{ number_format($pendingTransaction->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ul>
                                <li>VA Number &nbsp;&nbsp;&nbsp; : {{ $pendingTransaction->va_number ?? '-' }}</li>
                                <li>Account Name &nbsp;&nbsp;&nbsp; :
                                    {{ $assetBooking->user->name ?? $assetBooking->external_user }}</li>
                                <li>VA Expiry Date &nbsp;&nbsp;&nbsp; :
                                    {{ \Carbon\Carbon::parse($pendingTransaction->va_expiry)->translatedFormat('d F Y') }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endif

                @if ($dpTransaction && $pendingTransaction)
                    <tr>
                        <td>Uang Pelunasan</td>
                        <td class="text-end">Rp{{ number_format($pendingTransaction->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ul>
                                <li>VA Number &nbsp;&nbsp;&nbsp; : {{ $pendingTransaction->va_number ?? '-' }}</li>
                                <li>Account Name &nbsp;&nbsp;&nbsp; :
                                    {{ $assetBooking->user->name ?? $assetBooking->external_user }}</li>
                                <li>VA Expiry Date &nbsp;&nbsp;&nbsp; :
                                    {{ \Carbon\Carbon::parse($pendingTransaction->va_expiry)->translatedFormat('d F Y') }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endif
            @elseif ($assetBooking->payment_type === 'lunas' && $pendingTransaction)
                <tr>
                    <td>Uang Pelunasan</td>
                    <td class="text-end">Rp{{ number_format($pendingTransaction->amount ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ul>
                            <li>VA Number &nbsp;&nbsp;&nbsp; : {{ $pendingTransaction->va_number ?? '-' }}</li>
                            <li>Account Name &nbsp;&nbsp;&nbsp; :
                                {{ $assetBooking->user->name ?? $assetBooking->external_user }}</li>
                            <li>VA Expiry Date &nbsp;&nbsp;&nbsp; :
                                {{ \Carbon\Carbon::parse($pendingTransaction->va_expiry)->translatedFormat('d F Y') }}
                            </li>
                        </ul>
                    </td>
                </tr>
            @endif

        </tbody>
        @php
            $total = 0;
            $amountDue = $pendingTransaction->amount ?? 0;
            if ($assetBooking->payment_type === 'dp') {
                if ($dpTransaction && $pendingTransaction) {
                    $total = ($dpTransaction->amount ?? 0) + ($pendingTransaction->amount ?? 0);
                } elseif ($dpTransaction) {
                    $total = $dpTransaction->amount ?? 0;
                } elseif ($pendingTransaction) {
                    $total = $pendingTransaction->amount ?? 0;
                }
            } elseif ($assetBooking->payment_type === 'lunas' && $pendingTransaction) {
                $total = $pendingTransaction->amount ?? 0;
            }
        @endphp

        <tfoot>
            <tr>
                <th>Tagihan yang harus dibayarkan</th>
                <th class="text-end">Rp{{ number_format($amountDue, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th>Total</th>
                <th class="text-end">Rp{{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>

    </table>

    <div class="mt-4">
        <p class="mb-1"><strong>Catatan:</strong></p>
        <ul class="small text-muted mb-3">
            <li>Harap lakukan pembayaran sebelum tanggal kadaluarsa VA.</li>
            <li>Pembayaran setelah masa berlaku VA akan dianggap tidak sah.</li>
            <li>Invoice ini hanya berlaku satu kali untuk satu transaksi booking.</li>
        </ul>
    </div>

    <div class="text-end">
        <small class="text-muted">Dicetak pada {{ now()->format('d F Y H:i') }}</small>
    </div>
</div>
{{-- Tombol cetak --}}
<div class="mt-12 mb-24 text-end">
    @if ($assetBooking->status !== 'approved_dp_payment' && $assetBooking->status !== 'approved_full_payment')
        <a href="{{ route('assetBooking.letter', $assetBooking->id) }}" target="_blank"
            class="btn btn-sm btn-outline-primary">
            <i class="ph ph-printer"></i> Berkas Booking
        </a>
    @endif
    <button onclick="printInvoice()" class="btn btn-sm btn-outline-primary">
        <i class="ph ph-printer"></i> Cetak Invoice
    </button>
</div>
<script>
    function printInvoice() {
        let printContents = document.getElementById('invoice-container').innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

    }
</script>
