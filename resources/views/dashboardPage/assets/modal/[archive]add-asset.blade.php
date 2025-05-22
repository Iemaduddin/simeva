<div class="modal fade" id="modalAddAsset" tabindex="-1" aria-labelledby="modalAddAssetLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalAddAssetLabel">Tambah Aset</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form id="addAssetForm" action="{{ route('add.asset') }}" method="POST" enctype="multipart/form-data"
                    data-table="{{ $tableId }}">
                    @csrf
                    @if (!$kode_jurusan)
                        <!-- Form Wizard Start -->
                        <div class="form-wizard">
                            <div class="form-wizard-header overflow-x-auto scroll-sm pb-8 mb-32">
                                <ul class="list-unstyled form-wizard-list">
                                    <li class="form-wizard-list__item active">
                                        <div class="form-wizard-list__line">
                                            <span class="count">1</span>
                                        </div>
                                        <span class="text text-xs fw-semibold">Data Aset </span>
                                    </li>
                                    <li class="form-wizard-list__item">
                                        <div class="form-wizard-list__line">
                                            <span class="count">2</span>
                                        </div>
                                        <span class="text text-xs fw-semibold">Data Penyewaan Aset</span>
                                    </li>
                                </ul>
                            </div>

                            <fieldset class="wizard-fieldset show">
                                <h6 class="text-md text-neutral-500">Informasi Detail Aset</h6>
                                <div class="row gy-3">
                                    <input type="hidden" name="facility_scope"
                                        value="{{ $kode_jurusan ? 'jurusan' : 'umum' }}">
                                    <div class="col-md-6 mb-20">
                                        <label for="assetNameInput"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                            Aset <span class="text-danger">*</span></label>
                                        <input id="assetNameInput" type="text" name="name"
                                            class="form-control form-control-sm radius-8"
                                            placeholder="Masukkan Nama Aset" required>
                                    </div>
                                    <div class="col-md-3 mb-20">
                                        <label for="AssetTypeInput"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Tipe Aset <span class="text-danger">*</span>
                                        </label>
                                        <select id="AssetTypeInput" class="form-select bg-base form-select-sm w-100"
                                            name="type" required>
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
                                    <div class="col-md-6 mb-20">
                                        <label for="descriptionAssetInput"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                            <span class="text-danger">*</span></label>
                                        <textarea id="descriptionAssetInput" name="description" class="form-control" rows="4" cols="50"
                                            placeholder="Masukkan Deskripsi..." required></textarea>
                                    </div>
                                    <div class="col-md-6 mb-20" id="availableCol">
                                        <label for="availableAtInput"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8 text-muted">
                                            Waktu Aset Tersedia (Pihak Ekstern)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="availableAtInput"
                                            class="form-control form-control-sm choices-multiple-remove-button"
                                            name="available_at[]" placeholder="Masukkan waktu ketersediaan aset"
                                            multiple required>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jum'at">Jum'at</option>
                                            <option value="Sabtu" selected>Sabtu</option>
                                            <option value="Minggu" selected>Minggu</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label for="facilityInput"
                                            class="fw-semibold text-primary-light text-sm mb-8 text-muted">
                                            Fasilitas
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control form-control-sm radius-8 choices-text-remove-button"
                                            id="facilityInput" type="text" name="facility[]"
                                            style="overflow: hidden;" required />
                                    </div>

                                    <div class="col-md-6 mb-20">
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
                                    <div class="form-group text-end">
                                        <button type="button"
                                            class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="wizard-fieldset">
                                <h6 class="text-md text-neutral-500">Informasi Detail Penyewaan Aset</h6>
                                <div id="asset-rates-container">
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
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="#" id="add-asset-rate"
                                        class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill">
                                        <iconify-icon icon="zondicons:add-outline" class="menu-icon"></iconify-icon>
                                        Tambah Jenis & Kelompok Tarif
                                    </a>
                                </div>
                                <div
                                    class="modal-footer form-group d-flex align-items-center justify-content-end gap-8">
                                    <button type="button"
                                        class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                                    <button type="submit"
                                        class="form-wizard-submit btn btn-primary-600 px-32">Tambah</button>
                                </div>
                            </fieldset>
                        </div>
                    @else
                        <div class="row gy-3">
                            <input type="hidden" name="facility_scope"
                                value="{{ $kode_jurusan ? 'jurusan' : 'umum' }}">
                            @if ($kode_jurusan)
                                <input type="hidden" name="jurusan" value="{{ $kode_jurusan }}">
                            @endif
                            <div class="col-md-6 mb-20">
                                <label for="assetNameInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                    Aset<span class="text-danger">*</span></label>
                                <input id="assetNameInput" type="text" name="name"
                                    class="form-control form-control-sm radius-8" placeholder="Masukkan Nama Aset"
                                    required>
                            </div>
                            <div class="col-md-3 mb-20">
                                <label for="AssetTypeInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Tipe Aset <span class="text-danger">*</span>
                                </label>
                                <select id="AssetTypeInput" class="form-select bg-base form-select-sm w-100"
                                    name="type" required>
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
                            <div class="col-md-6 mb-20">
                                <label for="descriptionAssetInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                    <span class="text-danger">*</span></label>
                                <textarea id="descriptionAssetInput" name="description" class="form-control" rows="4" cols="50"
                                    placeholder="Masukkan Deskripsi..." required></textarea>
                            </div>
                            <div class="col-md-6 mb-20">
                                <label for="availableAtInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8 text-muted">
                                    Waktu Aset Tersedia (Pihak Ekstern)
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="availableAtInput"
                                    class="form-control form-control-sm choices-multiple-remove-button"
                                    name="available_at[]" placeholder="Masukkan waktu ketersediaan aset" multiple
                                    required>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jum'at">Jum'at</option>
                                    <option value="Sabtu" selected>Sabtu</option>
                                    <option value="Minggu" selected>Minggu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-20">
                                <label for="facilityInput"
                                    class="fw-semibold text-primary-light text-sm mb-8 text-muted">
                                    Fasilitas
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-control form-control-sm radius-8 choices-text-remove-button"
                                    id="facilityInput" type="text" name="facility[]" style="overflow: hidden;"
                                    required />
                            </div>

                            <div class="col-md-6 mb-20">
                                <label for="upload-file-multiple"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                    Aset</label>

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
                            <div class="modal-footer form-group d-flex align-items-center justify-content-end gap-8">
                                <button type="button" class="btn btn-neutral-500 border-neutral-100 px-32"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary-600 px-32">Tambah</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
            <!-- Form Wizard End -->
        </div>
    </div>
</div>
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
<script>
    // ================================================ Upload Multiple image js Start here ================================================
    // Get the form element that contains the file input
    const assetForm = document.querySelector('form'); // Adjust the selector if needed
    const fileInputMultiple = document.getElementById("upload-file-multiple");
    const uploadedImgsContainer = document.querySelector(".uploaded-imgs-container");

    // Function to reset the image upload
    function resetImageUpload() {
        // Clear the file input
        if (fileInputMultiple) {
            fileInputMultiple.value = '';
        }

        // Clear all preview images
        if (uploadedImgsContainer) {
            uploadedImgsContainer.innerHTML = '';
        }
    }

    // Handle file selection
    fileInputMultiple.addEventListener("change", (e) => {
        const files = e.target.files;
        Array.from(files).forEach(file => {
            const src = URL.createObjectURL(file);
            const imgContainer = document.createElement("div");
            imgContainer.classList.add("position-relative", "h-120-px", "w-120-px", "border",
                "input-form-light", "radius-8", "overflow-hidden", "border-dashed", "bg-neutral-50");

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("uploaded-img__remove", "position-absolute", "top-0", "end-0",
                "z-1", "text-2xxl", "line-height-1", "me-8", "mt-8", "d-flex");
            removeButton.innerHTML =
                "<iconify-icon icon='radix-icons:cross-2' class='text-xl text-danger-600'></iconify-icon>";

            const imagePreview = document.createElement("img");
            imagePreview.classList.add("w-100", "h-100", "object-fit-cover");
            imagePreview.src = src;

            imgContainer.appendChild(removeButton);
            imgContainer.appendChild(imagePreview);
            uploadedImgsContainer.appendChild(imgContainer);

            removeButton.addEventListener("click", () => {
                URL.revokeObjectURL(src);
                imgContainer.remove();
            });
        });
    });

    // Add form submit handler
    if (assetForm) {
        assetForm.addEventListener('submit', async (e) => {
            // Your existing form submission logic here

            // After successful submission
            resetImageUpload();
        });
    }

    // Add event listener for modal hidden event if you're using Bootstrap modal
    document.addEventListener('hidden.bs.modal', function(event) {
        resetImageUpload();
    });

    // Reset when clicking close/cancel button (if applicable)
    const modalCloseButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', resetImageUpload);
    });
    // ================================================ Upload Multiple image js End here  ================================================
</script>
<script>
    $(document).ready(function() {
        let bookingType = $('#BookingTypeSelect');
        bookingType.change(function() {
            let bookingTypeSelected = $(this).val(); // Ambil nilai terbaru setiap kali berubah

            if (bookingTypeSelected === 'annual') {
                $('#col-available').prop('hidden', true);
                $('#availableAtInput').prop('hidden', true).prop('disabled', true);
            } else {
                $('#availableAtInput').prop('hidden', false).prop('disabled', false);
                $('#col-available').prop('hidden', false);
            }
        });

    });
</script>
