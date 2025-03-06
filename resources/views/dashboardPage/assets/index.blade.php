@extends('layout.layout')
@section('title', 'Assets Management')
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
@php
    $title = 'Assets Management';
    $subTitle = 'Assets Management';
    $tableId = $kode_jurusan ? 'assetFasilitasJurusan-' . $kode_jurusan . '-Table' : 'assetFasilitasUmumTable';
@endphp

@section('content')
    <div id="asset-container" data-kode-jurusan="{{ $kode_jurusan }}"></div>
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0 align-content-center">Manajemen Aset Fasilitas {{ $kode_jurusan ?? 'Umum' }}</h5>
            <button class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalAddAsset">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Tambah Aset Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table basic-table bordered-table w-100  row-border sm-table" id="{{ $tableId }}">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th>Fasilitas</th>
                            <th>Tersedia</th>
                            <th>Tipe Sewa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('script')
    @include('dashboardPage.assets.modal.add-asset')

    <script src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const kodeJurusan = "{{ $kode_jurusan ?? '' }}"; // Jika null, dikosongkan
            const tableId = kodeJurusan ? `assetFasilitasJurusan-${kodeJurusan}-Table` : "assetFasilitasUmumTable";

            let assetTable = $(`#${tableId }`).DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: kodeJurusan ?
                        "{{ route('assets.getData', ':kodeJurusan') }}".replace(':kodeJurusan',
                            kodeJurusan) : "{{ route('assets.getData') }}",
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
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'facility',
                        name: 'facility'
                    },
                    {
                        data: 'available_at',
                        name: 'available_at'
                    },
                    {
                        data: 'booking_type',
                        name: 'booking_type'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                drawCallback: function() {
                    initializeChoices(); // Re-inisialisasi Choices setelah tabel di-refresh
                }
            });

            function initializeChoices() {
                document.querySelectorAll('.choices-multiple-remove-button').forEach(select => {
                    if (!select.dataset.choicesInitialized) {
                        new Choices(select, {
                            removeItemButton: true,
                            delimiter: ',',
                            shouldSort: false,
                        });
                        select.dataset.choicesInitialized = "true"; // Tandai sudah diinisialisasi
                    }
                });

                document.querySelectorAll('.choices-text-remove-button').forEach(input => {
                    if (!input.dataset.choicesInitialized) {
                        new Choices(input, {
                            delimiter: ',',
                            editItems: true,
                            removeItemButton: true,
                            placeholder: true,
                            placeholderValue: 'Masukkan fasilitas yang dimiliki',
                        });
                        input.dataset.choicesInitialized = "true"; // Tandai sudah diinisialisasi
                    }
                });
            }

            // Inisialisasi Choices setelah DOM siap
            initializeChoices();

            // Inisialisasi ulang Choices saat modal update muncul
            document.addEventListener('shown.bs.modal', function(event) {
                if (event.target.id.includes('modalUpdateAsset')) {
                    initializeChoices();
                }
            });
        });
    </script>
    @include('components.script-crud')

    <script>
        // =============================== Wizard Step Js Start ================================
        document.addEventListener("DOMContentLoaded", function() {
            const wizard = document.querySelector(".form-wizard");
            const form = document.querySelector("#addAssetForm");
            const fieldsets = form.querySelectorAll("fieldset");
            const nextBtns = form.querySelectorAll(".form-wizard-next-btn");
            const prevBtns = form.querySelectorAll(".form-wizard-previous-btn");
            const submitBtn = form.querySelector(".form-wizard-submit");
            const stepIndicators = document.querySelectorAll(".form-wizard-list__item");
            let currentStep = 0;

            function showStep(step) {
                fieldsets.forEach((fieldset, index) => {
                    fieldset.classList.toggle("show", index === step);
                });

                // Atur ulang tampilan button
                prevBtns.forEach(btn => btn.style.display = step > 0 ? "inline-block" : "none");
                submitBtn.style.display = step === fieldsets.length - 1 ? "inline-block" : "none";
                nextBtns.forEach(btn => btn.style.display = step < fieldsets.length - 1 ? "inline-block" : "none");

                // Update indikator langkah
                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.toggle("active", index === step);
                    if (index < step) {
                        indicator.classList.add("activated");
                    } else {
                        indicator.classList.remove("activated");
                    }
                });
            }

            function getLabelText(input) {
                const label = document.querySelector(`label[for="${input.id}"]`);
                if (label) {
                    // Ambil teks label tanpa elemen `<span>` di dalamnya
                    return label.childNodes[0].nodeValue.trim();
                }
                return "Field ini";
            }

            function validateStep(step) {
                let isValid = true;
                const inputs = fieldsets[step].querySelectorAll(
                    "input[required], select[required], textarea[required]");
                const fileInputs = fieldsets[step].querySelectorAll("input[type='file'][name='asset_images[]']");

                inputs.forEach(input => {
                    const fieldName = getLabelText(input);
                    let value = input.value.trim();

                    if (input.type === "number" && (value === "0")) {
                        isValid = false;
                        input.classList.add("border-danger");
                        showError(input, `${fieldName} harus lebih dari 0.`);
                    } else if (!value) {
                        isValid = false;
                        input.classList.add("border-danger");
                        showError(input, `${fieldName} harus diisi.`);
                    } else {
                        input.classList.remove("border-danger");
                        removeError(input);
                    }
                });



                // Validasi khusus untuk input file
                fileInputs.forEach(fileInput => {
                    const fieldName = getLabelText(fileInput);
                    if (fileInput.files.length === 0) {
                        isValid = false;
                        fileInput.closest(".upload-file-multiple").classList.add("border-danger");
                        showError(fileInput, 'Gambar harus diisi.');
                    } else {
                        fileInput.closest(".upload-file-multiple").classList.remove("border-danger");
                        removeError(fileInput);
                    }
                });

                return isValid;
            }

            function showError(input, message) {
                removeError(input);
                const errorDiv = document.createElement("div");
                errorDiv.className = "text-danger mt-1 text-sm";
                errorDiv.innerText = message;
                input.closest(".col-md-6")?.appendChild(errorDiv);
            }

            function removeError(input) {
                const error = input.closest(".col-md-6")?.querySelector(".text-danger");
                if (error) {
                    error.remove();
                }
            }

            nextBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    if (validateStep(currentStep)) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            prevBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    currentStep--;
                    showStep(currentStep);
                });
            });

            // submitBtn.addEventListener("click", function() {
            //     if (validateStep(currentStep)) {
            //         // form.submit();
            //     }
            // });

            // Hapus border merah saat user mulai mengisi
            form.querySelectorAll("input, select, textarea").forEach(input => {
                input.addEventListener("input", function() {
                    if (input.value.trim()) {
                        input.classList.remove("border-danger");
                        removeError(input);
                    }
                });

                input.addEventListener("change", function() {
                    if (input.value.trim()) {
                        input.classList.remove("border-danger");
                        removeError(input);
                    }
                });
            });

            // Tampilkan langkah pertama
            showStep(currentStep);
        });
        // =============================== Wizard Step Js End ================================
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const newUploadedImgsContainer = document.querySelector(".new-uploaded-imgs-container");
            console.log(newUploadedImgsContainer);
            document.addEventListener("change", function(e) {
                if (e.target.classList.contains("upload-file-input")) {
                    const files = e.target.files;
                    if (files.length === 0) return;

                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith("image/")) {
                            alert("Hanya file gambar yang diperbolehkan.");
                            return;
                        }

                        const objectUrl = URL.createObjectURL(file);

                        // Buat elemen wrapper untuk gambar baru
                        const imgContainer = document.createElement("div");
                        imgContainer.classList.add(
                            "uploaded-asset-img", "position-relative",
                            "h-120-px", "w-120-px",
                            "border", "radius-8", "overflow-hidden"
                        );

                        // Buat tombol hapus gambar
                        const removeButton = document.createElement("button");
                        removeButton.type = "button";
                        removeButton.classList.add(
                            "uploaded-img__remove", "position-absolute",
                            "top-0", "end-0", "z-1", "text-2xxl"
                        );
                        removeButton.innerHTML = `
                    <iconify-icon icon='radix-icons:cross-2' class='text-xl text-danger-600'></iconify-icon>
                `;

                        // Event untuk menghapus gambar
                        removeButton.onclick = () => {
                            URL.revokeObjectURL(objectUrl);
                            imgContainer.remove();
                        };

                        // Buat elemen img untuk preview gambar
                        const imagePreview = document.createElement("img");
                        imagePreview.classList.add("w-100", "h-100", "object-fit-cover");
                        imagePreview.src = objectUrl;

                        // Tambahkan elemen ke dalam wrapper
                        imgContainer.appendChild(removeButton);
                        imgContainer.appendChild(imagePreview);
                        newUploadedImgsContainer.appendChild(imgContainer);
                    });

                    e.target.value = ""; // Reset input agar bisa pilih file yang sama lagi
                }
            });

            // Event untuk menghapus gambar yang sudah ada (dari database)
            document.addEventListener("click", function(e) {
                if (e.target.closest(".uploaded-img__remove")) {
                    const button = e.target.closest(".uploaded-img__remove");
                    const imgContainer = button.closest(".uploaded-asset-img");
                    const imageName = button.getAttribute("data-image");

                    if (imageName) {
                        let removedImagesInput = document.querySelector("input[name='removed_images']");
                        if (!removedImagesInput) {
                            removedImagesInput = document.createElement("input");
                            removedImagesInput.type = "hidden";
                            removedImagesInput.name = "removed_images";
                            document.querySelector("form").appendChild(removedImagesInput);
                        }
                        removedImagesInput.value += `${imageName},`;
                    }

                    imgContainer.remove();
                }
            });
        });
    </script>
@endpush
