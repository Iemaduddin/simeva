<div class="modal fade" id="modalUploadDocument-{{ $event->id }}" tabindex="-1"
    aria-labelledby="modalUploadDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalUploadDocumentLabel">Unggah Surat Peminjaman
                    (Selesai)
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assetBookingEvent.uploadDocument', ['eventId' => $event->id]) }}" method="POST"
                    data-table="loanAssetEventTable" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="asset_jurusan" id="assetJurusanInput-{{ $event->id }}"
                            value="">

                        @if ($documentPath)
                            <iframe src="{{ asset('storage/' . $documentPath) }}" frameborder="0" width="100%"
                                height="600px"></iframe>
                            <input type="hidden" name="reupload" value="reupload">
                        @endif
                        <div class="col-md-12">
                            <label class="form-label">Surat Peminjaman (.pdf)</label>
                            <input type="file" accept=".pdf" class="form-control" name="loan_letter">
                        </div>
                        @php
                            $booking = $event->bookings->first();
                        @endphp
                        @if (optional($booking)->status === 'rejected_full_payment')
                            <div class="col-md-12 mt-3">
                                <p class="fw-bold">Alasan Penolakan:</p>
                                <div class="border-1 p-10 radius-10">{{ $booking->reason }} </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                        <button type="reset"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Unggah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
