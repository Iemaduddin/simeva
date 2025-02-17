 <!-- Modal Add Uer -->
 <div class="modal fade" id="modalAddOrganizerUser" tabindex="-1" aria-labelledby="modalAddOrganizerUserLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content radius-16 bg-base">
             <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                 <h1 class="modal-title fs-5" id="modalAddOrganizerUserLabel">Tambah User</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body p-24">
                 <form id="addOrganizerUserForm" action="{{ route('add.organizerUser') }}" method="POST"
                     enctype="multipart/form-data" data-table="organizerUserTable">
                     @csrf
                     <div class="row">
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Organizer<span
                                     class="text-danger">*</span></label>
                             <input type="text" name="name" class="form-control radius-8"
                                 placeholder="Masukkan Nama Organizer" required>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Singkatan Organizer
                                 <span class="text-danger">*</span></label>
                             <input type="text" name="shorten_name" class="form-control radius-8"
                                 placeholder="Masukkan Singkatan Organizer" required>
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
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe Organizer <span
                                     class="text-danger">*</span></label>
                             <div class="d-flex align-items-center flex-wrap gap-28">
                                 @php
                                     $org_type = ['Kampus', 'Jurusan', 'LT', 'HMJ', 'UKM'];
                                 @endphp
                                 <select class="form-select bg-base form-select-sm w-100" name="organizer_type"
                                     required>
                                     <option value="" disabled selected>Pilih Tipe Organizer</option>
                                     @foreach ($org_type as $org)
                                         <option value="{{ $org }}">{{ $org }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <!-- Kolom kiri: Visi dan Misi -->
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Visi <span
                                     class="text-danger">*</span></label>
                             <textarea class="form-control radius-8 bg-base" name="vision" placeholder="Masukkan Visi" required></textarea>
                         </div>
                         <div class="col-md-4 mb-20">
                             <label class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                 Organizer <span class="text-danger">*</span></label>
                             <textarea class="form-control radius-8 bg-base" name="description" placeholder="Masukkan Deskripsi" required></textarea>
                         </div>
                         <!-- Kolom kanan: Deskripsi Organizer -->
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
                                     <input id="upload-file" type="file" name="logo" accept=".jpg, .jpeg, .png"
                                         hidden>
                                 </label>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-12 ">
                                 <div id="missions-container" class="row">
                                     <div class="col-md-4 mb-20">
                                         <!-- Bagian Misi -->
                                         <div class="mission-item position-relative mb-3">
                                             <label class="form-label fw-semibold text-primary-light text-sm mb-2">Misi
                                                 <span class="text-danger">*</span></label>
                                             <textarea class="form-control radius-8 bg-base" name="missions[]" placeholder="Masukkan Misi" required></textarea>
                                         </div>
                                     </div>
                                 </div>
                                 <!-- Tautan tambah misi -->
                                 <a href="#" id="add-mission" class="text-success fw-semibold d-inline-block">+
                                     Tambah Misi</a>
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
 <script>
     document.getElementById('add-mission').addEventListener('click', function(e) {
         e.preventDefault(); // Mencegah reload halaman
         const missionContainer = document.getElementById('missions-container');

         // Hitung jumlah misi saat ini
         const currentMissions = missionContainer.querySelectorAll('.mission-item').length;
         const newMissionNumber = currentMissions + 1;

         // Template HTML untuk misi baru
         const newMissionHTML = `
            <div class="col-md-4 mb-20">
                <div class="mission-item position-relative mb-3">
                    <label class="form-label fw-semibold text-primary-light text-sm mb-2">
                        Misi ${newMissionNumber} <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control radius-8 bg-base" name="missions[]" placeholder="Masukkan Misi" required></textarea>
                    <a href="#" class="text-danger fw-semibold d-inline-block mt-1 delete-mission">- Hapus Misi</a>
                </div>
            </div>
        `;

         // Tambahkan HTML ke container
         missionContainer.insertAdjacentHTML('beforeend', newMissionHTML);

         // Tambahkan event listener untuk tombol hapus
         addDeleteListeners();

         // Update label setelah penambahan
         updateMissionLabels();
     });

     // Fungsi untuk menambahkan event listener ke semua tautan hapus
     function addDeleteListeners() {
         const deleteLinks = document.querySelectorAll('.delete-mission');
         deleteLinks.forEach(link => {
             link.removeEventListener('click', deleteMission); // Pastikan tidak ada event listener ganda
             link.addEventListener('click', deleteMission);
         });
     }

     // Fungsi hapus misi
     function deleteMission(e) {
         e.preventDefault();
         const missionContainer = document.getElementById('missions-container');
         const missionItems = missionContainer.querySelectorAll('.mission-item');

         if (missionItems.length > 1) {
             e.target.closest('.col-md-4').remove();
             updateMissionLabels(); // Update label setelah penghapusan
         } else {
             alert('Minimal harus ada satu misi!');
         }
     }

     // Fungsi untuk update ulang label setelah penghapusan misi
     function updateMissionLabels() {
         const missions = document.querySelectorAll('#missions-container .mission-item');
         missions.forEach((mission, index) => {
             const label = mission.querySelector('label');
             if (missions.length === 1) {
                 label.innerHTML = 'Misi <span class="text-danger">*</span>';
             } else {
                 label.innerHTML = `Misi ${index + 1} <span class="text-danger">*</span>`;
             }
         });
     }

     // Pastikan event listener untuk tautan hapus ditambahkan saat halaman dimuat
     addDeleteListeners();
 </script>
