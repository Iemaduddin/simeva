@extends('layout.layout')
@section('title', 'Users Management')
@php
    $title = 'Users Management';
    $subTitle = 'Users Management';
@endphp
@section('css')
    <link href="{{ URL::asset('assets/libs/choices.js/choices.js.min.css') }}" rel="stylesheet">
    <style>
        /* Styling untuk menyesuaikan dengan form-control Bootstrap */
        .choices {
            width: 100% !important;
            max-width: 100% !important;
        }

        .choices__inner {
            width: 100% !important;
            min-height: calc(1.5em + 0.75rem + 2px) !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: var(--bs-body-color) !important;
            background-color: #fff !important;
            background-clip: padding-box;
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            min-width: 0 !important;
            max-width: 100% !important;
        }

        .choices.is-focused .choices__inner {
            border-color: #86b7fe !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .choices__input {
            background-color: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            width: 100% !important;
            vertical-align: baseline !important;
            margin-bottom: 0 !important;
        }

        .choices__list--dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
            margin-top: 2px !important;
        }

        /* Untuk multiple select */
        .choices__list--multiple .choices__item {
            background-color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            border-radius: 0.25rem !important;
            color: white !important;
            margin: 2px !important;
        }
    </style>
@endsection
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
                            <th>Aksi</th>
                            <th>Nama</th>
                            <th>Singkatan</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nomor Handphone</th>
                            <th>Tipe Organizer</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('dashboardPage.users.organizer.modal.add-user')


@endsection
@push('script')
    <script src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js') }}"></script>

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
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
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
                ],
            });
        });
    </script>
    @include('components.script-crud')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const misionInput = new Choices('#mision', {
                removeItemButton: true, // Aktifkan tombol remove
                placeholderValue: 'Masukkan misi organizer...', // Placeholder untuk input
                maxItemCount: 10, // Maksimal jumlah input
                searchEnabled: false, // Matikan fitur pencarian
                delimiter: '|',
            });

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
