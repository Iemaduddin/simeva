 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddMahasiswaUser" tabindex="-1" aria-labelledby="modalAddMahasiswaUserLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddMahasiswaUserLabel">Tambah User</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addMahasiswaUserForm" action="{{ route('add.mahasiswaUser') }}" method="POST"
                     enctype="multipart/form-data" data-table="mahasiswaUserTable-{{ $jurusan->kode_jurusan }}">
                     @csrf
                     <div class="row">
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">NIM<span
                                     class="text-danger">*</span></label>
                             <input type="number" name="nim" class="form-control radius-8"
                                 placeholder="Masukkan NIM Mahasiswa" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Mahasiswa<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 placeholder="Masukkan Nama Mahasiswa" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Username <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="text" name="username"
                                 placeholder="Masukkan Username" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                     class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="email" name="email"
                                 placeholder="Masukkan Email" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span
                                     class="text-danger">*</span></label>
                             <div class="position-relative">
                                 <input type="password" class="form-control radius-8 bg-base" name="password"
                                     id="password" placeholder="Masukkan Password" required>
                                 <span
                                     class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                     data-toggle="#password"></span>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                 Handphone <span class="text-danger">*</span></label>
                             <input class="form-control radius-8 bg-base" type="number" name="phone_number"
                                 placeholder="Masukkan No HP" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal Lahir</label>
                             <input class="form-control radius-8 bg-base" type="date" name="tanggal_lahir" required>
                         </div>

                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Kelamin <span
                                     class="text-danger">*</span></label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100" name="jenis_kelamin" required>
                                     <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                     <option value="Laki-laki">Laki-laki</option>
                                     <option value="Perempuan">Perempuan</option>
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
                                             data-kode-jurusan="{{ $jurusan->kode_jurusan }}">
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
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Provinsi <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-province"
                                     name="province" required>
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
                                     required>
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
                                     name="subdistrict"required>
                                     <option value="" disabled selected>Pilih Kecamatan</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                 Kelurahan/Desa <span class="text-danger">*</span>
                             </label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 <select class="form-select bg-base form-select-sm w-100 select-village"
                                     name="village" required>
                                     <option value="" disabled selected>Pilih Kelurahan/Desa</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-4 ">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat lengkap <span
                                     class="text-danger">*</span></label>
                             <textarea class="form-control radius-8 bg-base" name="address" placeholder="Masukkan Alamat Lengkap" required></textarea>
                         </div>
                         <div class="col-md-4">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Logo
                                 Organizer</label>
                             <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                 <div
                                     class="uploaded-img d-none position-relative h-80-px w-80-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                     <button type="button"
                                         class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                         <iconify-icon icon="radix-icons:cross-2"
                                             class="text-xl text-danger-600"></iconify-icon>
                                     </button>
                                     <img id="uploaded-img__preview" class="w-80 h-80 object-fit-cover"
                                         src="{{ asset('assets/images/user.png') }}" alt="image">
                                 </div>

                                 <label
                                     class="upload-file h-80-px w-200-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                     for="upload-file">
                                     <iconify-icon icon="solar:camera-outline"
                                         class="text-xl text-secondary-light"></iconify-icon>
                                     <span class="fw-semibold text-secondary-light">Upload</span>
                                     <input id="upload-file" type="file" name="logo"
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
     const fileInput = document.getElementById("upload-file");
     const imagePreview = document.getElementById("uploaded-img__preview");
     const uploadedImgContainer = document.querySelector(".uploaded-img");
     const removeButton = document.querySelector(".uploaded-img__remove");

     fileInput.addEventListener("change", (e) => {
         if (e.target.files.length) {
             const src = URL.createObjectURL(e.target.files[0]);
             imagePreview.src = src;
             uploadedImgContainer.classList.remove("d-none");
         }
     });
     removeButton.addEventListener("click", () => {
         imagePreview.src = "";
         uploadedImgContainer.classList.add("d-none");
         fileInput.value = "";
     });
 </script>
