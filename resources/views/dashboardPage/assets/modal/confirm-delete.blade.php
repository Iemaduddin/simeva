<div class="modal fade" id="confirmDeleteAsset-{{ $asset->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <form action="{{ route('destroy.asset', ['id' => $asset->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-24 text-center">
                    <span class="mb-16 fs-1 line-height-1 text-danger">
                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                    </span>
                    <h6 class="text-lg fw-semibold text-primary-light mb-0">
                        Apakah Anda yakin ingin menghapus data ini?
                    </h6>
                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" data-bs-dismiss="modal"
                            class="w-50 btn btn-outline-neutral-900 text-md px-40 py-11 radius-8">
                            Batal
                        </button>
                        <button type="submit" id="confirmDeleteBtn"
                            class="w-50 btn btn-danger border border-danger text-md px-24 py-12 radius-8">
                            Hapus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
