<div class="modal fade" id="modalUploadSuratDisposisi-{{ $assetBooking->id ?? $eventId }}" tabindex="-1"
    aria-labelledby="modalUploadSuratDisposisiLabel" aria-hidden="true">
    @php
        if (isset($assetBooking)) {
            $suratDispo = \App\Models\AssetBookingDocument::where('booking_id', $assetBooking->id)
                ->where('document_type', 'Surat Disposisi')
                ->first();
        } else {
            $suratDispo = \App\Models\AssetBookingDocument::where('event_id', $eventId)
                ->where('document_type', 'Surat Disposisi')
                ->first();
        }
    @endphp
    <div class="modal-dialog {{ $suratDispo ? 'modal-lg' : '' }} modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalUploadSuratDisposisiLabel">Upload Surat Disposisi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="{{ $tableId }}"
                    action="{{ route('assetBooking.uploadSuratDisposisi', $assetBooking->id ?? $eventId) }}"
                    method="POST" data-table="{{ $tableId }}">
                    @csrf
                    <input type="hidden" name="type_id" value="{{ isset($eventId) ? 'event_id' : 'booking_id' }}">
                    <div class="row mb-12">

                        @if ($suratDispo)
                            <iframe
                                src="{{ asset('storage/' . $suratDispo->document_path) }}?v={{ $suratDispo->updated_at->timestamp }}"
                                width="100%" height="500px" style="border:1px solid #ccc;">
                            </iframe>
                        @endif
                        <div class="col-12 mt-12">
                            <label for="suratDisposisi" class="text-neutral-700 text-sm fw-bold mb-6 d-block">Surat
                                Disposisi</label>
                            <input id="suratDisposisi" class="form-control" type="file" accept=".pdf"
                                name="surat_disposisi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary  flex-center gap-8"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ajukan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
