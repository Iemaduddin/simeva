@extends('layout.layout')
@section('title', 'Users Management')
@php
    $title = 'Users Management';
    $subTitle = 'Users Management';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0 align-content-center">Organizer User</h5>
            <button class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalAddOrganizerUser">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Tambah User Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table"
                    id="organizerUserTable">
                    <thead>
                        <tr>

                            <th>No</th>
                            <th>Nama</th>
                            <th>Singkatan</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nomor Handphone</th>
                            <th>Tipe Organizer</th>
                            <th>Deskripsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('dashboardPage.users.organizer.modal.add-user')


@endsection
@push('script')
    <script>
        $(document).ready(function() {
            let organizerTable = $('#organizerUserTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('organizerUsers.getData') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex', // Data dari addIndexColumn di server-side
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    }, {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'shorten_name',
                        name: 'shorten_name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'organizer_type',
                        name: 'organizer_type'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });
    </script>
    @include('components.script-crud')
    <script>
        document.addEventListener("DOMContentLoaded", function() {


            // Event delegation untuk menangani upload file
            document.addEventListener("change", function(e) {
                if (e.target.classList.contains("upload-file-update-org-input")) {

                    const input = e.target;
                    const file = input.files[0];

                    if (file) {
                        const objectUrl = URL.createObjectURL(file);

                        // Cari elemen terkait dalam wrapper
                        const wrapper = input.closest(".upload-image-wrapper");
                        if (!wrapper) return;

                        const imagePreview = wrapper.querySelector(".uploaded-update-org-img__preview");
                        const imageContainer = wrapper.querySelector(".uploaded-update-org-img");
                        const removeButton = wrapper.querySelector(".uploaded-update-org-img__remove");

                        if (imagePreview) {
                            // Update preview gambar
                            imagePreview.src = objectUrl;
                            imagePreview.onload = () => URL.revokeObjectURL(
                                objectUrl); // Hapus URL blob setelah gambar dimuat
                            imagePreview.onerror = () => console.error("Gagal memuat gambar");

                            // Tampilkan container dan tombol hapus
                            imageContainer.classList.remove("d-none");
                            removeButton.classList.remove("d-none");
                        }
                    }
                }
            });

            // Event delegation untuk menghapus gambar
            document.addEventListener("click", function(e) {
                if (e.target.closest(".uploaded-update-org-img__remove")) {
                    const button = e.target.closest(".uploaded-update-org-img__remove");

                    // Cari elemen terkait dalam wrapper
                    const wrapper = button.closest(".upload-image-wrapper");
                    if (!wrapper) return;

                    const imagePreview = wrapper.querySelector(".uploaded-update-org-img__preview");
                    const imageContainer = wrapper.querySelector(".uploaded-update-org-img");
                    const fileInput = wrapper.querySelector(".upload-file-update-org-input");

                    if (imagePreview) {
                        imagePreview.src = ""; // Hapus gambar
                        imagePreview.removeAttribute("src"); // Pastikan tidak ada gambar
                    }

                    // Sembunyikan kontainer gambar dan tombol hapus
                    imageContainer.classList.add("d-none");
                    button.classList.add("d-none");

                    if (fileInput) {
                        fileInput.value = ""; // Reset input file
                    }
                }
            });

            // **Saat modal dibuka ulang, pastikan logo default tetap muncul**
            document.querySelectorAll(".upload-image-wrapper").forEach((wrapper) => {
                const imagePreview = wrapper.querySelector(".uploaded-update-org-img__preview");
                const imageContainer = wrapper.querySelector(".uploaded-update-org-img");
                const removeButton = wrapper.querySelector(".uploaded-update-org-img__remove");

                if (imagePreview && !imagePreview.src.trim()) {
                    imageContainer.classList.add("d-none");
                    removeButton.classList.add("d-none");
                }
            });
        });
    </script>
    <script>
        // Ambil semua tombol 'Tambah Misi' berdasarkan ID dinamis
        document.querySelectorAll('[id^="add-mission-update-org-"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Ambil ID dari tombol untuk menemukan container terkait
                const orgUpdateId = this.id.replace('add-mission-update-org-', '');
                const missionContainerUpdateOrg = document.getElementById(
                    `missions-container-update-org-${orgUpdateId}`);

                // Hitung jumlah misi saat ini untuk modal ini saja
                const currentMissions = missionContainerUpdateOrg.querySelectorAll(
                    '.mission-item-update-org').length;
                const newMissionId =
                    `mission-${orgUpdateId}-${Date.now()}`; // ID unik untuk textarea dan label

                // Tambahkan misi baru dengan label dan textarea
                const newMissionUpdate = `
               <div class="col-md-4 mb-20">
                   <div class="mission-item-update-org position-relative mb-3">
                       <label for="${newMissionId}" class="form-label fw-semibold text-primary-light text-sm mb-2">
                           Misi ${currentMissions + 1} <span class="text-danger">*</span>
                       </label>
                       <textarea id="${newMissionId}" class="form-control radius-8 bg-base" name="missions[]" placeholder="Masukkan Misi" required></textarea>
                       <a href="#" class="text-danger fw-semibold d-inline-block mt-1 delete-mission-update-org">- Hapus Misi</a>
                   </div>
               </div>
            `;

                missionContainerUpdateOrg.insertAdjacentHTML('beforeend', newMissionUpdate);

                // Tambahkan event listener untuk tombol hapus dan update label di modal yang sesuai
                addDeleteMisionUpdateListeners(orgUpdateId);
                updateMissionLabelsOrgUpdate(orgUpdateId);
            });
        });

        // Fungsi untuk menambahkan event listener hapus di modal yang sesuai
        function addDeleteMisionUpdateListeners(orgUpdateId) {
            const missionContainerUpdateOrg = document.getElementById(`missions-container-update-org-${orgUpdateId}`);
            missionContainerUpdateOrg.querySelectorAll('.delete-mission-update-org').forEach(link => {
                link.removeEventListener('click', deleteMission);
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const missionItems = missionContainerUpdateOrg.querySelectorAll(
                        '.mission-item-update-org');

                    if (missionItems.length > 1) {
                        e.target.closest('.col-md-4').remove();
                        updateMissionLabelsOrgUpdate(orgUpdateId); // Update label setelah penghapusan
                    } else {
                        alert('Minimal harus ada satu misi!');
                    }
                });
            });
        }

        // Fungsi untuk memperbarui label misi hanya di modal yang sesuai
        function updateMissionLabelsOrgUpdate(orgUpdateId) {
            const missionContainerUpdateOrg = document.getElementById(`missions-container-update-org-${orgUpdateId}`);
            const missions = missionContainerUpdateOrg.querySelectorAll('.mission-item-update-org');

            missions.forEach((mission, index) => {
                let label = mission.querySelector('label');
                let textarea = mission.querySelector('textarea');

                if (!label) {
                    label = document.createElement('label');
                    label.className = 'form-label fw-semibold text-primary-light text-sm mb-2';
                    mission.prepend(label);
                }

                const newMissionId = `mission-${orgUpdateId}-${index + 1}`;
                label.setAttribute('for', newMissionId);
                textarea.id = newMissionId;
                textarea.setAttribute('name', 'missions[]'); // Pastikan name="missions[]" selalu ada
                label.innerHTML = `Misi ${index + 1} <span class="text-danger">*</span>`;
            });
        }

        // Jalankan fungsi untuk semua modal saat halaman dimuat
        document.querySelectorAll('[id^="missions-container-update-org-"]').forEach(container => {
            const orgUpdateId = container.id.replace('missions-container-update-org-', '');
            addDeleteMisionUpdateListeners(orgUpdateId);
            updateMissionLabelsOrgUpdate(orgUpdateId);
        });
    </script>
    <script>
        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
        // ========================= Password Show Hide Js End ===========================
    </script>
@endpush
