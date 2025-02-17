<div class="modal fade" id="modalUpdateOrganizerUser-{{ $organizer->user->id }}" tabindex="-1"
    aria-labelledby="modalUpdateOrganizerUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalUpdateOrganizerUserLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="updateOrganizerUserForm-{{ $organizer->user->id }}"
                    action="{{ route('update.organizerUser', $organizer->user_id) }}" method="POST"
                    data-table="organizerUserTable">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Organizer<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control radius-8"
                                value="{{ $organizer->user->name }}" required>
                        </div>
                        <div class="col-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Singkatan Organizer
                                <span class="text-danger">*</span></label>
                            <input type="text" name="shorten_name" class="form-control radius-8"
                                value="{{ $organizer->shorten_name }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control radius-8 bg-base"
                                value="{{ $organizer->user->username }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control radius-8 bg-base"
                                value="{{ $organizer->user->email }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span
                                    class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control radius-8 bg-base" name="password"
                                    id="password-{{ $organizer->user->id }}" placeholder="Masukkan Password">
                                <span
                                    class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                    data-toggle="password-{{ $organizer->user->id }}"></span>
                            </div>
                        </div>
                        <div class="col-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe Organizer <span
                                    class="text-danger">*</span></label>
                            <select class="form-select bg-base form-select-sm w-100" name="organizer_type" required>
                                @php
                                    $org_type = ['Kampus', 'Jurusan', 'LT', 'HMJ', 'UKM'];
                                @endphp
                                <option value="" disabled selected>Pilih Tipe Organizer</option>
                                @foreach ($org_type as $org)
                                    <option value="{{ $org }}"
                                        {{ $organizer->organizer_type == $org ? 'selected' : '' }}>
                                        {{ $org }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Visi <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control radius-8 bg-base" name="vision" required>{{ $organizer->vision }}</textarea>
                        </div>

                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                Organizer <span class="text-danger">*</span></label>
                            <textarea class="form-control radius-8 bg-base" name="description" required>{{ $organizer->description }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Logo Organizer <span class="text-danger">*</span>
                            </label>
                            <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                <!-- Container Preview Gambar -->
                                <div
                                    class="uploaded-update-org-img position-relative h-80-px w-80-px border input-form-light radius-8
                                            overflow-hidden border-dashed bg-neutral-50 {{ $organizer->logo ? '' : 'd-none' }}">
                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        class="uploaded-update-org-img__remove position-absolute top-0 end-0 z-1
                                                text-2xxl line-height-1 me-8 mt-8 d-flex {{ $organizer->logo ? '' : 'd-none' }}">
                                        <iconify-icon icon="radix-icons:cross-2"
                                            class="text-xl text-danger-600"></iconify-icon>
                                    </button>
                                    <!-- Gambar Preview -->
                                    <img class="uploaded-update-org-img__preview w-80 h-80 object-fit-cover"
                                        src="{{ $organizer->logo ? asset('storage/' . $organizer->logo) . '?t=' . time() : '' }}"
                                        data-default-src="{{ $organizer->logo ? asset('storage/' . $organizer->logo) . '?t=' . time() : '' }}"
                                        alt="image" width="80px">
                                </div>
                                <!-- Input File -->
                                <label
                                    class="upload-file-update-org h-80-px w-200-px border input-form-light radius-8 overflow-hidden
                                                border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1">
                                    <iconify-icon icon="solar:camera-outline"
                                        class="text-xl text-secondary-light"></iconify-icon>
                                    <span class="fw-semibold text-secondary-light">Upload</span>
                                    <input type="file" name="logo" class="upload-file-update-org-input"
                                        accept=".jpg, .jpeg, .png" hidden>
                                </label>
                            </div>
                        </div>
                        <!-- Bagian Misi -->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="missions-container-update-org-{{ $organizer->id }}" class="row">
                                    @php
                                        $missions = explode(',', $organizer->mision); // Memecah misi menjadi array
                                    @endphp
                                    @foreach ($missions as $index => $mission)
                                        <div class="col-md-4 mb-20">
                                            <div class="mission-item-update-org position-relative mb-3">
                                                <textarea class="form-control radius-8 bg-base" name="missions[]" required>{{ trim($mission) }}</textarea>
                                                @if ($index > 0)
                                                    <a href="#"
                                                        class="text-danger fw-semibold d-inline-block mt-1 delete-mission-update-org">
                                                        - Hapus Misi
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="#" id="add-mission-update-org-{{ $organizer->id }}"
                                    class="text-success fw-semibold d-inline-block">+ Tambah Misi</a>
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
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ================== Password Show Hide Js Start ==========
    $(document).on("click", ".toggle-password", function() {
        $(this).toggleClass("ri-eye-off-line");

        // Ambil ID dari data-toggle tanpa #
        let inputId = $(this).attr("data-toggle");
        let input = $("#" + inputId); // Seleksi berdasarkan ID

        // Toggle type antara password dan text
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    // ========================= Password Show Hide Js End ===========================
</script>
