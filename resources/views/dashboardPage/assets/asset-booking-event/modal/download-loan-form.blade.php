<div class="modal fade" id="modalLoanForm" tabindex="-1" aria-labelledby="modalLoanFormLabel" aria-hidden="true">
    <div class="modal-dialog {{ count($assetBookings) > 2 ? 'modal-lg' : '' }} modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalLoanFormLabel">Data Undangan
                    Pembicara
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('getLoanForm', $event->id) }}" method="POST" target="_blank">
                    @csrf
                    <div class="row gy-4 ">
                        @foreach ($assetBookings->unique('asset_id') as $booking)
                            <div class="{{ count($assetBookings) > 2 ? 'col-6' : 'col-12' }}">
                                <label class="form-label">Nomor Surat Peminjaman {{ $booking->asset->name }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    name="letter_number[{{ $booking->asset_id }}]" placeholder="Nomor Surat" required>
                            </div>
                        @endforeach
                        <div class="col-12">
                            <label class="form-label">Pilih Pemimpin Organisasi <span
                                    class="text-danger">*</span></label>
                            <select name="leader" class="form-select" required>
                                @foreach ($leaders as $leader)
                                    <option value="{{ $leader->id }}">{{ $leader->name }} (NIM.{{ $leader->nim }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                        <button type="reset"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
