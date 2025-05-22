<div class="modal fade" id="modalDataInvitationSpeaker-{{ $speaker->id }}" tabindex="-1"
    aria-labelledby="modalDataInvitationSpeakerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalDataInvitationSpeakerLabel">Data Undangan
                    Pembicara
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('invitation.speaker', $speaker->id) }}" method="POST" target="_blank">
                    @csrf
                    <div class="row gy-4 ">
                        <div class="col-12">

                            <label class="form-label">Nomor Surat Undangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="letter_number" placeholder="Nomor Surat"
                                required>
                        </div>
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
