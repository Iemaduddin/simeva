<div class="modal fade" id="modalUpdateMahasiswaUser-{{ $mahasiswa->user_id }}" tabindex="-1"
    aria-labelledby="modalUpdateMahasiswaUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalUpdateMahasiswaUserLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="updateMahasiswaUserForm-{{ $mahasiswa->user_id }}"
                    action="{{ route('update.mahasiswaUser', $mahasiswa->user_id) }}" method="POST"
                    data-table="mahasiswaUserTable-{{ $kodeJurusan }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">NIM<span
                                    class="text-danger">*</span></label>
                            <input type="number" name="nim" class="form-control radius-8"
                                placeholder="Masukkan NIM Mahasiswa" value="{{ $mahasiswa->nim }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Mahasiswa<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control radius-8"
                                placeholder="Masukkan Nama Mahasiswa"value="{{ $mahasiswa->user->name }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                    class="text-danger">*</span></label>
                            <input class="form-control radius-8 bg-base" type="text" name="username"
                                placeholder="Masukkan Username"value="{{ $mahasiswa->user->username }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                    class="text-danger">*</span></label>
                            <input class="form-control radius-8 bg-base" type="email" name="email"
                                placeholder="Masukkan Email" value="{{ $mahasiswa->user->email }}" required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span
                                    class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control radius-8 bg-base" name="password"
                                    id="password" placeholder="Masukkan Password">
                                <span
                                    class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                    data-toggle="#password"></span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                Handphone <span class="text-danger">*</span></label>
                            <input class="form-control radius-8 bg-base" type="number" name="phone_number"
                                placeholder="Masukkan No HP" value="{{ $mahasiswa->user->phone_number }}"required>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal Lahir</label>
                            <input class="form-control radius-8 bg-base" type="date" name="tanggal_lahir"
                                value="{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : '' }}"
                                required>
                        </div>

                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100" name="jenis_kelamin" required>
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki"
                                        {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="Perempuan"
                                        {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jurusan<span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100 select-jurusan" name="jurusan"
                                    required>
                                    <option value="" disabled selected>Pilih Jurusan</option>
                                    @foreach ($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}"
                                            data-kode-jurusan="{{ $jurusan->kode_jurusan }}"
                                            {{ $mahasiswa->user->jurusan_id == $jurusan->id ? 'selected' : '' }}>
                                            {{ $jurusan->nama }} ({{ $jurusan->kode_jurusan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Prodi <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100 select-prodi" name="prodi"
                                    required>
                                    <option value="" disabled selected>Pilih Prodi</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id }}"
                                            {{ $mahasiswa->prodi->id == $prodi->id ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Provinsi <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100 select-province"
                                    name="province" data-selected="{{ $mahasiswa->user->province }}" required>
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Kabupaten/Kota <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100 select-city" name="city"
                                    data-selected="{{ $mahasiswa->user->city }}" required>
                                    <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Kecamatan <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100 select-subdistrict"
                                    name="subdistrict" data-selected="{{ $mahasiswa->user->subdistrict }}" required>
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Kelurahan/Desa <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center flex-wrap gap-28">
                                <select class="form-select bg-base form-select-sm w-100 select-village" name="village"
                                    data-selected="{{ $mahasiswa->user->village }}" required>
                                    <option value="" disabled selected>Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat lengkap <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control radius-8 bg-base" name="address" placeholder="Masukkan Alamat Lengkap" required>{{ $mahasiswa->user->address }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Foto Mahasiswa <span class="text-danger">*</span>
                            </label>
                            <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                <!-- Container Preview Gambar -->
                                <div
                                    class="uploaded-update-org-img position-relative h-80-px w-80-px border input-form-light radius-8
                                            overflow-hidden border-dashed bg-neutral-50 {{ $mahasiswa->user->profile_picture ? '' : 'd-none' }}">
                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        class="uploaded-update-org-img__remove position-absolute top-0 end-0 z-1
                                                text-2xxl line-height-1 me-8 mt-8 d-flex {{ $mahasiswa->user->profile_picture ? '' : 'd-none' }}">
                                        <iconify-icon icon="radix-icons:cross-2"
                                            class="text-xl text-danger-600"></iconify-icon>
                                    </button>
                                    <!-- Gambar Preview -->
                                    <img class="uploaded-update-org-img__preview w-80 h-80 object-fit-cover"
                                        src="{{ $mahasiswa->user->profile_picture ? asset('storage/' . $mahasiswa->user->profile_picture) . '?t=' . time() : '' }}"
                                        data-default-src="{{ $mahasiswa->user->profile_picture ? asset('storage/' . $mahasiswa->user->profile_picture) . '?t=' . time() : '' }}"
                                        alt="image" width="80px">
                                </div>
                                <!-- Input File -->
                                <label
                                    class="upload-file-update-org h-80-px w-200-px border input-form-light radius-8 overflow-hidden
                                                border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1">
                                    <iconify-icon icon="solar:camera-outline"
                                        class="text-xl text-secondary-light"></iconify-icon>
                                    <span class="fw-semibold text-secondary-light">Upload</span>
                                    <input type="file" name="profile_picture" class="upload-file-update-org-input"
                                        accept=".jpg, .jpeg, .png" hidden>
                                </label>
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
                                Tambah
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
