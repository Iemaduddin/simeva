<div class="modal fade" id="modalDetailAssetBooking-{{ $assetBooking->id }}" tabindex="-1"
    aria-labelledby="modalDetailAssetBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalDetailAssetBookingLabel">Rincian Booking Aset</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nama Peminjam</strong>
                        <p class="text-muted mb-0">{{ $assetBooking->user->name ?? $assetBooking->external_user }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Email Peminjam</strong>
                        <p class="text-muted mb-0">{{ $assetBooking->user->email ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nama Aset</strong>
                        <p class="text-muted mb-0">{{ $assetBooking->asset->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Kategori Aset</strong>
                        <p class="text-muted mb-0">{{ $assetBooking->asset_category->category_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tanggal Pemakaian</strong>
                        <p class="text-muted mb-0">
                            @if ($assetBooking->asset->booking_type === 'annual')
                                {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->translatedFormat('d F Y') }}
                                - {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->translatedFormat('d F Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->translatedFormat('d F Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Waktu Pemakaian</strong>
                        <p class="text-muted mb-0">
                            @if ($assetBooking->asset->booking_type === 'annual')
                                -
                            @else
                                {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('H:i') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    @if ($assetBooking->asset->booking_type === 'annual')
                        <div class="col-md-6">
                            <strong>Penggunaan</strong>
                            <p class="text-muted mb-0">{{ $assetBooking->usage_event_name ?? '-' }}</p>
                        </div>
                    @else
                        <div class="col-md-6">
                            <strong>Jenis Acara</strong>
                            <p class="text-muted mb-0">{{ $assetBooking->usage_event_name ?? '-' }}</p>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <strong>Dibuat Pada</strong>
                        <p class="text-muted mb-0">{{ $assetBooking->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
                @if ($assetBooking->reason)
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Alasan Penolakan</strong>
                            <p class="text-muted mb-0">{{ $assetBooking->reason }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
