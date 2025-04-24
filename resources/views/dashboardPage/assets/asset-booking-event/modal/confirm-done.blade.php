@php
    $id = $assetBookingIdNoEvent === '' ? $eventId : $assetBookingIdNoEvent;
@endphp
<div class="modal fade" id="confirmAssetBookingDone-{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">

            <form action="{{ route('assetBookingEvent.confirmDone', ['id' => $id]) }}" method="POST"
                data-table="{{ $tableId }}">
                @csrf
                <div class="modal-body p-24 text-center">
                    <span class="mb-16 fs-1 line-height-1 text-success">
                        <iconify-icon icon="ci:check-all" class="menu-icon"></iconify-icon>
                    </span>
                    <input type="hidden" name="existEventId"
                        value="{{ $assetBookingIdNoEvent === '' ? 'exist' : 'nothing' }}">
                    <h6 class="text-lg fw-semibold text-primary-light mb-0">
                        Apakah Anda yakin ingin mengonfirmasi selesai asset booking ini?
                    </h6>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="w-50 btn btn-outline-neutral-900 text-md px-40 py-11 radius-8">
                            Batal
                        </button>
                        <button type="submit" id="confirmDeleteBtn"
                            class="w-50 btn btn-success-600 border border-danger text-md px-24 py-12 radius-8">
                            Selesai
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
