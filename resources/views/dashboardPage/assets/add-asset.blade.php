@extends('layout.layout')
@section('title', 'Asset Management')
@php
    $title = 'Asset Management';
    $subTitle = 'Asset Management';
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
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tambah Aset</h5>
                </div>
                <div class="card-body">
                    <form id="addAssetForm" action="{{ route('store.asset') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-3">
                            <input type="hidden" name="facility_scope" value="{{ $kode_jurusan ? 'jurusan' : 'umum' }}">
                            @if ($kode_jurusan)
                                <input type="hidden" name="jurusan" value="{{ $kode_jurusan }}">
                            @endif

                            <div class="col-md-3 mb-20">
                                <label for="assetNameInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                    Aset <span class="text-danger">*</span></label>
                                <input id="assetNameInput" type="text" name="name"
                                    class="form-control form-control-sm radius-8" placeholder="Masukkan Nama Aset" required>
                            </div>
                            <div class="col-md-3 mb-20">
                                <label for="AssetTypeInput" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Tipe Aset <span class="text-danger">*</span>
                                </label>
                                <select id="AssetTypeInput" class="form-select bg-base form-select-sm w-100" name="type"
                                    required>
                                    <option disabled>Pilih Tipe Aset</option>
                                    <option value="building">Bangunan</option>
                                    <option value="transportation">Kendaraan</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-20">
                                <label for="BookingTypeSelect"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Tipe Sewa <span class="text-danger">*</span>
                                </label>
                                <select id="BookingTypeSelect" class="form-select bg-base form-select-sm w-100"
                                    name="booking_type" required>
                                    <option disabled>Pilih Tipe Sewa</option>
                                    <option value="daily">Harian</option>
                                    <option value="annual">Tahunan</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-20" id="availableCol">
                                <label for="availableAtInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8 text-muted">
                                    Waktu Aset Tersedia (Pihak Ekstern)
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="availableAtInput"
                                    class="form-control form-control-sm choices-multiple-remove-button"
                                    name="available_at[]" placeholder="Masukkan waktu ketersediaan aset" multiple required>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jum'at">Jum'at</option>
                                    <option value="Sabtu" selected>Sabtu</option>
                                    <option value="Minggu" selected>Minggu</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-20">
                                <label for="descriptionAssetInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                    <span class="text-danger">*</span></label>
                                <textarea id="descriptionAssetInput" name="description" class="form-control" rows="4" cols="50"
                                    placeholder="Masukkan Deskripsi..." required></textarea>
                            </div>

                            <div class="col-md-4 mb-20">
                                <label for="facilityInput" class="fw-semibold text-primary-light text-sm mb-8 text-muted">
                                    Fasilitas
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-control form-control-sm radius-8 choices-text-remove-button"
                                    id="facilityInput" type="text" name="facility[]" style="overflow: hidden;"
                                    required />
                            </div>

                            <div class="col-md-4 mb-20">
                                <label for="upload-file-multiple"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                    Aset <span class="text-danger">*</span></label>

                                <div class="upload-image-wrapper d-flex align-items-center gap-3 flex-wrap">
                                    <div class="uploaded-imgs-container d-flex gap-3 flex-wrap"></div>

                                    <label
                                        class="upload-file-multiple h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                        for="upload-file-multiple">
                                        <iconify-icon icon="solar:camera-outline"
                                            class="text-xl text-secondary-light"></iconify-icon>
                                        <span class="fw-semibold text-secondary-light">Upload</span>
                                        <input id="upload-file-multiple" type="file" name="asset_images[]"
                                            accept=".jpg, .jpeg, .png" hidden multiple>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if (!$kode_jurusan)
                            <div id="asset-rates-container">
                                <div class="asset-rate">
                                    <div class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                                        <div class="col-md-5 mb-20">
                                            <label for="categoryNameInput"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis
                                                Tarif Aset
                                                <span class="text-danger">*</span>

                                            </label>
                                            <input id="categoryNameInput" type="text" name="category_name[]"
                                                class="form-control form-control-sm radius-8"
                                                placeholder="Masukkan Jenis Tarif Aset" required>
                                        </div>
                                        <div class="col-md-3 mb-20">
                                            <label for="externalPriceInput"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                (Eksternal)
                                                <span class="text-danger">*</span> </label>
                                            <input id="externalPriceInput" type="number" name="external_price[]"
                                                class="form-control form-control-sm radius-8" placeholder="Masukkan Tarif"
                                                required>
                                        </div>
                                        <div class="col-md-2 mb-20">
                                            <label for="internalPercentageInput"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                Internal (%)
                                                <span class="text-danger">*</span> </label>
                                            <input id="internalPercentageInput" type="number"
                                                name="internal_price_percentage[]"
                                                class="form-control form-control-sm radius-8" placeholder="Masukkan Tarif"
                                                value="75" required>
                                        </div>
                                        <div class="col-md-2 mb-20">
                                            <label for="socialPercentageInput"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                Sosial (%)
                                                <span class="text-danger">*</span> </label>

                                            <input id="socialPercentageInput" type="number"
                                                name="social_price_percentage[]"
                                                class="form-control form-control-sm radius-8" placeholder="Masukkan Tarif"
                                                value="50" required>
                                        </div>
                                        <div class="text-start mt-3">
                                            <a href="#" class="btn btn-sm btn-danger-600 remove-asset-rate"
                                                style="display: none;">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#" id="add-asset-rate"
                                    class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill">
                                    <iconify-icon icon="zondicons:add-outline" class="menu-icon"></iconify-icon>
                                    Tambah Jenis & Kelompok Tarif
                                </a>
                            </div>
                        @endif

                        <div class="card-footer bg-white py-3 mt-3">
                            <div class="text-end">
                                <button type="submit" class=" btn btn-primary px-32">Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const bookingTypeSelect = document.getElementById('BookingTypeSelect');
            const availableCol = document.getElementById('availableCol');
            const availableAtInput = document.getElementById('availableAtInput');

            bookingTypeSelect.addEventListener('change', function() {
                if (this.value === 'annual') {
                    availableCol.style.display = 'none'; // Sembunyikan kolom
                    availableAtInput.required = false; // Hapus required
                    availableAtInput.disabled = true; // Disabled input
                } else {
                    availableCol.style.display = 'block'; // Munculkan kolom
                    availableAtInput.required = true; // Set required
                    availableAtInput.disabled = false; // Enable input
                }
            });
        });
    </script>
    <script>
        function initializeChoices() {
            document.querySelectorAll('.choices-multiple-remove-button').forEach(select => {
                if (!select.dataset.choicesInitialized) {
                    new Choices(select, {
                        removeItemButton: true,
                        delimiter: '|',
                        shouldSort: false,
                    });
                    select.dataset.choicesInitialized = "true"; // Tandai sudah diinisialisasi
                }
            });

            document.querySelectorAll('.choices-text-remove-button').forEach(input => {
                if (!input.dataset.choicesInitialized) {
                    new Choices(input, {
                        delimiter: '|',
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
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const uploadedImgsContainer = document.querySelector(".uploaded-imgs-container");
            const uploadInput = document.getElementById("upload-file-multiple");

            uploadInput.addEventListener("change", function(e) {
                const files = e.target.files;
                if (files.length === 0) return;

                Array.from(files).forEach(file => {
                    if (!file.type.startsWith("image/")) {
                        alert("Hanya file gambar yang diperbolehkan.");
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);

                    const imgContainer = document.createElement("div");
                    imgContainer.classList.add(
                        "uploaded-asset-img", "position-relative",
                        "h-120-px", "w-120-px",
                        "border", "radius-8", "overflow-hidden"
                    );

                    const removeButton = document.createElement("button");
                    removeButton.type = "button";
                    removeButton.classList.add(
                        "uploaded-img__remove", "position-absolute",
                        "top-0", "end-0", "z-1", "text-2xxl"
                    );
                    removeButton.innerHTML = `
                <iconify-icon icon='radix-icons:cross-2' class='text-xl text-danger-600'></iconify-icon>
            `;

                    removeButton.onclick = () => {
                        URL.revokeObjectURL(objectUrl);
                        imgContainer.remove();
                    };

                    const imagePreview = document.createElement("img");
                    imagePreview.classList.add("w-100", "h-100", "object-fit-cover");
                    imagePreview.src = objectUrl;

                    imgContainer.appendChild(removeButton);
                    imgContainer.appendChild(imagePreview);
                    uploadedImgsContainer.appendChild(imgContainer);
                });

            });

            // Untuk hapus gambar dari database
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
    <script>
        const assetRatesContainer = document.getElementById('asset-rates-container');
        const assetRateButton = document.getElementById('add-asset-rate');

        // Tambah kategori harga
        assetRateButton.addEventListener('click', function(e) {
            e.preventDefault();
            const assetRateTemplate = `
            <div class="asset-rate">
                <div
                    class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                    <div class="col-md-5 mb-20">
                        <label for="categoryNameInput"
                            class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis
                            Tarif Aset
                            <span class="text-danger">*</span>
    
                        </label>
                        <input id="categoryNameInput" type="text" name="category_name[]"
                            class="form-control form-control-sm radius-8"
                            placeholder="Masukkan Jenis Tarif Aset" required>
                    </div>
                    <div class="col-md-3 mb-20">
                        <label for="externalPriceInput"
                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                            (Eksternal)
                            <span class="text-danger">*</span> </label>
                        <input id="externalPriceInput" type="number" name="external_price[]"
                            class="form-control form-control-sm radius-8"
                            placeholder="Masukkan Tarif" required>
                    </div>
                    <div class="col-md-2 mb-20">
                        <label for="internalPercentageInput"
                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                            Internal (%)
                            <span class="text-danger">*</span> </label>
                        <input id="internalPercentageInput" type="number"
                            name="internal_price_percentage[]"
                            class="form-control form-control-sm radius-8"
                            placeholder="Masukkan Tarif" value="75" required>
                    </div>
                    <div class="col-md-2 mb-20">
                        <label for="socialPercentageInput"
                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                            Sosial (%)
                            <span class="text-danger">*</span> </label>
    
                        <input id="socialPercentageInput" type="number"
                            name="social_price_percentage[]"
                            class="form-control form-control-sm radius-8"
                            placeholder="Masukkan Tarif" value="50" required>
                    </div>
                    <div class="text-start mt-3">
                        <a href="#" class="btn btn-sm btn-danger-600 remove-asset-rate"
                            style="display: none;">Hapus</a>
                    </div>
                </div>
            </div>
                `;

            const container = document.getElementById('asset-rates-container');
            container.insertAdjacentHTML('beforeend', assetRateTemplate);

            updateRemoveAssetRateButtons();
        });

        // Update tombol "Hapus" berdasarkan jumlah kategori harga
        function updateRemoveAssetRateButtons() {
            const removeButtons = assetRatesContainer.querySelectorAll('.remove-asset-rate');
            const assetRates = assetRatesContainer.querySelectorAll('.asset-rate');

            removeButtons.forEach((btn, index) => {
                btn.style.display = assetRates.length > 1 ? 'inline-block' : 'none';
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (assetRates.length > 1) {
                        btn.closest('.asset-rate').remove();
                        updateRemoveAssetRateButtons();
                    }
                });
            });
        }

        // Update tombol "Hapus" pada halaman load awal
        updateRemoveAssetRateButtons();
    </script>
    @include('components.script-button-loading-refresh')
@endpush
