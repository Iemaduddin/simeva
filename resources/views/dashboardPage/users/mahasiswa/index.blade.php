@extends('layout.layout')
@section('title', 'Users Management')
@php
    $title = 'Users Management';
    $subTitle = 'Users Management';
@endphp
@section('content')
    <style>
        .bg-brown {
            background-color: #543A14;
        }
    </style>
    <div class="card p-0 overflow-hidden position-relative radius-12 h-100">
        <div class="card-body p-24 pt-10">
            <!-- Tab Navigation -->
            <ul class="nav nav-p bordered-tab border border-top-0 border-start-0 border-end-0 d-inline-flex nav-pills mb-16"
                id="pills-tab" role="tablist">
                @foreach ($jurusans as $index => $jurusan)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tab-mahasiswa px-16 py-10 {{ $index == 0 ? 'active' : '' }}"
                            id="pills-{{ $jurusan->kode_jurusan }}-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-{{ $jurusan->kode_jurusan }}" type="button" role="tab"
                            aria-controls="pills-{{ $jurusan->kode_jurusan }}"
                            aria-selected="{{ $index == 0 ? 'true' : 'false' }}"
                            data-kode-jurusan="{{ $jurusan->kode_jurusan }}">
                            {{ $jurusan->kode_jurusan }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="pills-tabContent">
                @foreach ($jurusans as $index => $jurusan)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                        id="pills-{{ $jurusan->kode_jurusan }}" role="tabpanel"
                        aria-labelledby="pills-{{ $jurusan->kode_jurusan }}-tab" tabindex="0">

                        <div class="flex-grow-1">
                            <div class="basic-data-table">
                                <div class="d-flex justify-content-between mb-20">
                                    <h5 class="card-title mb-0 align-content-center">Mahasiswa
                                        {{ $jurusan->nama }}</h5>
                                    <button id="btnTambahMahasiswa"
                                        class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#modalAddMahasiswaUser">
                                        <iconify-icon icon="ic:baseline-plus"
                                            class="icon text-xl line-height-1"></iconify-icon>
                                        Tambah Mahasiswa Baru
                                    </button>

                                </div>
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0 w-100 h-100 row-border order-column sm-table"
                                        id="mahasiswaUserTable-{{ $jurusan->kode_jurusan }}">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Aksi</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>No HP</th>
                                                <th>Tgl Lahir</th>
                                                <th>JK</th>
                                                {{-- <th>Status</th> --}}
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @include('dashboardPage.users.mahasiswa.modal.add-user')
            </div>
        </div>
    </div>
@endsection
@push('script')
    {{-- Set Jurusan --}}
    <script>
        $(document).ready(function() {
            let loadedTabs = {}; // Menyimpan tab yang sudah dimuat
            let selectedKodeJurusan = null;

            function loadTable(kodeJurusan) {
                let tableId = `#mahasiswaUserTable-${kodeJurusan}`;
                if (!loadedTabs[kodeJurusan]) {
                    $(tableId).DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('mahasiswaUsers.getData') }}",
                            type: 'GET',
                            data: {
                                kode_jurusan: kodeJurusan
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }, {
                                data: 'nim',
                                name: 'nim'
                            },
                            {
                                data: 'name',
                                name: 'name'
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
                                data: 'tanggal_lahir',
                                name: 'tanggal_lahir',
                                render: function(data, type, row) {
                                    if (!data) return '-';

                                    const tanggal = new Date(data);
                                    const options = {
                                        day: '2-digit',
                                        month: 'long',
                                        year: 'numeric'
                                    };
                                    return tanggal.toLocaleDateString('id-ID', options);
                                }
                            },
                            {
                                data: 'jenis_kelamin',
                                name: 'jenis_kelamin'
                            },
                        ]
                    });
                    loadedTabs[kodeJurusan] = true;
                }
            }

            function setSelectedJurusan(kodeJurusan) {
                let jurusanOption = $(`.select-jurusan option[data-kode-jurusan="${kodeJurusan}"]`);
                if (jurusanOption.length > 0) {
                    $('.select-jurusan').val(jurusanOption.val()).change();
                }
            }

            function setDataTableForm(kodeJurusan) {
                let form = document.getElementById("addMahasiswaUserForm");
                form.setAttribute("data-table", `mahasiswaUserTable-${kodeJurusan}`);
            }
            // Saat tab diklik
            $('.tab-mahasiswa').on('click', function() {
                let kodeJurusan = $(this).data('kode-jurusan');
                if (!kodeJurusan) return;

                selectedKodeJurusan = kodeJurusan;

                // Set dropdown jurusan agar sesuai dengan tab yang dipilih
                setSelectedJurusan(selectedKodeJurusan);

                loadTable(kodeJurusan);
            });

            // Saat tombol Tambah Mahasiswa diklik
            $('#btnTambahMahasiswa').on('click', function() {
                $('#modalAddMahasiswaUser').modal('show');

                if (selectedKodeJurusan) {
                    setSelectedJurusan(selectedKodeJurusan);
                    setDataTableForm(selectedKodeJurusan);
                }
            });

            // Muat tab pertama saat halaman terbuka
            let firstTab = $('.tab-mahasiswa.active');
            if (firstTab.length > 0) {
                let firstKodeJurusan = firstTab.data('kode-jurusan');
                selectedKodeJurusan = firstKodeJurusan;
                loadTable(firstKodeJurusan);
                setSelectedJurusan(firstKodeJurusan); // Pastikan jurusan pertama terpilih di dropdown
            }
        });
    </script>
    {{-- Set Prodi by Jurusan --}}
    <script>
        $(document).ready(function() {
            $(document).on('change', '.select-jurusan', function() {
                let selectedKodeJurusan = $(this).find(':selected').data('kode-jurusan');
                let form = $(this).closest('form'); // Dapatkan form terkait
                let prodiDropdown = form.find('.select-prodi');

                prodiDropdown.empty().append('<option value="" disabled selected>Pilih Prodi</option>');

                if (selectedKodeJurusan) {
                    $.ajax({
                        url: "{{ route('getProdiByJurusan') }}",
                        type: "GET",
                        data: {
                            kode_jurusan: selectedKodeJurusan
                        },
                        success: function(response) {
                            if (response.length > 0) {
                                $.each(response, function(index, prodi) {
                                    prodiDropdown.append(
                                        `<option value="${prodi.id}">${prodi.nama_prodi} (${prodi.kode_prodi})</option>`
                                    );
                                });
                            } else {
                                prodiDropdown.append(
                                    '<option value="" disabled>Tidak ada Prodi tersedia</option>'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Gagal mengambil data Prodi:", error);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let apiBaseUrl = "https://iemaduddin.github.io/api-wilayah-indonesia/api";
            let cachedProvinces = null;

            function loadDropdown(url, target, selected = null, callback = null) {
                target.empty().append('<option value="" disabled selected>Pilih</option>');
                $.get(url, function(data) {
                    data.forEach(item => {
                        target.append(
                            `<option value="${item.id}" ${item.id == selected ? 'selected' : ''}>${item.name}</option>`
                        );
                    });
                    if (callback) callback();
                });
            }

            function loadProvinces(target, selected = null) {
                if (cachedProvinces) {
                    target.empty().append('<option value="" disabled>Pilih Provinsi</option>');
                    cachedProvinces.forEach(item => {
                        target.append(`<option value="${item.id}">${item.name}</option>`);
                    });

                    // Set selected setelah dropdown diisi
                    if (selected) {
                        target.val(selected).trigger('change');
                    }
                } else {
                    $.get(`${apiBaseUrl}/provinces.json`, function(data) {
                        cachedProvinces = data;
                        loadProvinces(target, selected); // Load ulang dengan selected
                    });
                }
            }


            loadProvinces($('.select-province'));

            $(document).on('change', '.select-province', function() {
                let provinceId = $(this).val();
                let citySelect = $('.select-city');
                citySelect.empty().append(
                    '<option value="" disabled selected>Pilih Kabupaten/Kota</option>');
                $('.select-subdistrict, .select-village').empty().append(
                    '<option value="">Pilih Kabupaten/Kota</option>');
                if (provinceId) loadDropdown(`${apiBaseUrl}/regencies/${provinceId}.json`, citySelect);
            });

            $(document).on('change', '.select-city', function() {
                let cityId = $(this).val();
                let subdistrictSelect = $('.select-subdistrict');
                subdistrictSelect.empty().append(
                    '<option value="" disabled selected>Pilih Kecamatan</option>');
                $('.select-village').empty().append('<option value="">Pilih Kecamatan</option>');
                if (cityId) loadDropdown(`${apiBaseUrl}/districts/${cityId}.json`, subdistrictSelect);
            });

            $(document).on('change', '.select-subdistrict', function() {
                let subdistrictId = $(this).val();
                let villageSelect = $('.select-village');
                villageSelect.empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan/Desa</option>');
                if (subdistrictId) loadDropdown(`${apiBaseUrl}/villages/${subdistrictId}.json`,
                    villageSelect);
            });

            $('#modalAddMahasiswaUser').on('show.bs.modal', function() {
                $('.select-province, .select-city, .select-subdistrict, .select-village').empty().append(
                    '<option value="">Pilih</option>');
                loadProvinces($('.select-province'));
            });

            $(document).on('click', '[data-bs-target^="#modalUpdateMahasiswaUser-"]', function() {
                let targetModal = $($(this).data('bs-target'));
                let selectedProvince = targetModal.find('.select-province').data('selected');
                let selectedCity = targetModal.find('.select-city').data('selected');
                let selectedSubdistrict = targetModal.find('.select-subdistrict').data('selected');
                let selectedVillage = targetModal.find('.select-village').data('selected');

                loadProvinces(targetModal.find('.select-province'), selectedProvince);
                if (selectedProvince) {
                    loadDropdown(`${apiBaseUrl}/regencies/${selectedProvince}.json`, targetModal.find(
                        '.select-city'), selectedCity, function() {
                        if (selectedCity) {
                            loadDropdown(`${apiBaseUrl}/districts/${selectedCity}.json`, targetModal
                                .find('.select-subdistrict'), selectedSubdistrict,
                                function() {
                                    if (selectedSubdistrict) {
                                        loadDropdown(
                                            `${apiBaseUrl}/villages/${selectedSubdistrict}.json`,
                                            targetModal.find('.select-village'),
                                            selectedVillage);
                                    }
                                });
                        }
                    });
                }
            });
        });
    </script>
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
    @include('components.script-crud')
@endpush
