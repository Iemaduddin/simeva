<div class="modal fade" id="modalAddAsset" tabindex="-1" aria-labelledby="modalAddAssetLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalAddAssetLabel">Tambah Aset</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">

                <!-- Form Wizard Start -->
                <div class="form-wizard">
                    <form id="addAssetForm" action="{{ route('add.asset') }}" method="POST"
                        enctype="multipart/form-data" data-table="{{ $tableId }}">
                        @csrf
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
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Aset<span
                                            class="text-danger">*</span></label>
                                    <input id="assetNameInput" type="text" name="name"
                                        class="form-control form-control-sm radius-8" placeholder="Masukkan Nama Aset"
                                        required>
                                </div>
                                <div class="col-md-6 mb-20">
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
                                <div class="col-md-6 mb-20">
                                    <label for="descriptionAssetInput"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                        <span class="text-danger">*</span></label>
                                    <textarea id="descriptionAssetInput" name="description" class="form-control" rows="4" cols="50"
                                        placeholder="Masukkan Deskripsi..." required></textarea>
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
                                <div class="form-group text-end">
                                    <button type="button"
                                        class="form-wizard-next-btn btn btn-primary-600 px-32">Next</button>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="wizard-fieldset">
                            <h6 class="text-md text-neutral-500">Informasai Detail Penyewaan Aset</h6>
                            <div class="row gy-3">
                                @if ($kode_jurusan)
                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Aset
                                            Jurusan<span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center flex-wrap gap-28">
                                            <input class="form-control form-control-sm" type="text"
                                                value="Fasilitas {{ $kode_jurusan }}" disabled>
                                            <input type="hidden" name="jurusan" value="{{ $kode_jurusan }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-20">
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
                                <hr>
                                <div class="col-md-12">
                                    <div id="tarif-container">
                                        <div class="tarif-row row align-items-end">
                                            <div class="col-md-5 mb-20">
                                                <label for="categoryNameInput"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis
                                                    Tarif Aset
                                                    <span class="text-danger">*</span></label>
                                                <input id="categoryNameInput" type="text" name="category_name[]"
                                                    class="form-control form-control-sm radius-8"
                                                    placeholder="Masukkan Jenis Tarif Aset" required>
                                            </div>
                                            <div class="col-md-2 mb-20">
                                                <label for="externalPriceInput"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                    Aset (Eksternal) <span class="text-danger">*</span></label>
                                                <input id="externalPriceInput" type="number" name="external_price[]"
                                                    class="form-control form-control-sm radius-8"
                                                    placeholder="Masukkan Tarif" required>
                                            </div>
                                            <div class="col-md-2 mb-20">
                                                <label for="internalPercentageInput"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                    Aset Internal (%)<span class="text-danger">*</span></label>
                                                <input id="internalPercentageInput" type="number"
                                                    name="internal_price_percentage[]"
                                                    class="form-control form-control-sm radius-8"
                                                    placeholder="Masukkan Tarif" value="75" required>
                                            </div>
                                            <div class="col-md-2 mb-20">
                                                <label for="socialPercentageInput"
                                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                    Aset Sosial (%)<span class="text-danger">*</span></label>
                                                <input id="socialPercentageInput" type="number"
                                                    name="social_price_percentage[]"
                                                    class="form-control form-control-sm radius-8"
                                                    placeholder="Masukkan Tarif" value="50" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Tambah -->
                                    <a href="#" id="add-tarif"
                                        class="text-success fw-semibold d-inline-block">+ Tambah Jenis & Kelompok
                                        Tarif</a>
                                </div>

                            </div>
                            <div class="modal-footer form-group d-flex align-items-center justify-content-end gap-8">
                                <button type="button"
                                    class="form-wizard-previous-btn btn btn-neutral-500 border-neutral-100 px-32">Back</button>
                                <button type="submit"
                                    class="form-wizard-submit btn btn-primary-600 px-32">Tambah</button>
                            </div>
                </div>
                </fieldset>
                </form>
            </div>
            <!-- Form Wizard End -->
        </div>
    </div>
</div>

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
    document.getElementById("add-tarif").addEventListener("click", function(event) {
        event.preventDefault();

        let container = document.getElementById("tarif-container");
        let newRow = document.createElement("div");
        newRow.classList.add("tarif-row", "row", "align-items-end");

        newRow.innerHTML = `
        <div class="row align-items-end">
        <div class="col-md-5 mb-20">
            <label for="categoryNameInput" class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis Tarif Aset
                <span class="text-danger">*</span></label>
            <input id="categoryNameInput" type="text" name="category_name[]"
                class="form-control form-control-sm radius-8" placeholder="Masukkan Jenis Tarif Aset" required>
        </div>
        <div class="col-md-2 mb-20">
            <label for="externalPriceInput"
                class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif Aset (Eksternal) <span class="text-danger">*</span></label>
            <input id="externalPriceInput" type="number" name="external_price[]"
                class="form-control form-control-sm radius-8" placeholder="Masukkan Tarif" required>
        </div>
        <div class="col-md-2 mb-20">
            <label for="internalPercentageInput"
                class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif Aset Internal (%)<span
                class="text-danger">*</span></label>
            <input id="internalPercentageInput" type="number" name="internal_price_percentage[]" class="form-control form-control-sm radius-8"
                    placeholder="Masukkan Tarif" value="75" required>
        </div>
        <div class="col-md-2 mb-20">
            <label for="socialPercentageInput"
                class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif Aset Sosial (%)<span class="text-danger">*</span></label>
            <input id="socialPercentageInput" type="number"  name="social_price_percentage[]"
                class="form-control form-control-sm radius-8" placeholder="Masukkan Tarif" value="50" required>
        </div>
            <div class="col-md-1 mb-20">
                <button type="button" class="btn btn-danger-600 btn-sm ms-3 remove-tarif">X</button>
        </div>
        </div>
        `;

        container.appendChild(newRow);
    });

    // Event Listener untuk Menghapus Baris
    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-tarif")) {
            event.target.closest(".tarif-row").remove();
        }
    });
</script>
