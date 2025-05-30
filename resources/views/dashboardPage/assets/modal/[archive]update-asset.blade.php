<div class="modal fade" id="modalUpdateAsset-{{ $asset->id }}" tabindex="-1" aria-labelledby="modalUpdateAssetLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalUpdateAssetLabel">Edit Aset</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                @php
                    $tableId = $asset->jurusan_id
                        ? 'assetFasilitasJurusan-' . $asset->jurusan->kode_jurusan . '-Table'
                        : 'assetFasilitasUmumTable';
                @endphp
                <form id="updateAssetForm" action="{{ route('update.asset', $asset->id) }}" method="POST"
                    enctype="multipart/form-data" data-table="{{ $tableId }}">
                    @csrf
                    @method('PUT')
                    @if (!$kode_jurusan)
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
                                <div class="row">
                                    <input type="hidden" name="facility_scope"
                                        value="{{ $asset->jurusan ? 'jurusan' : 'umum' }}">
                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                            Aset<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control radius-8"
                                            placeholder="Masukkan Nama Aset" value="{{ $asset->name }}" required>
                                    </div>
                                    <div class="col-md-3 mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe
                                            Aset<span class="text-danger">*</span></label>
                                        <select class="form-select bg-base form-select-sm w-100" name="type"
                                            required>
                                            <option disabled>Pilih Tipe Aset</option>
                                            <option value="building" {{ $asset->type == 'building' ? 'selected' : '' }}>
                                                Bangunan
                                            </option>
                                            <option value="transportation"
                                                {{ $asset->type == 'transportation' ? 'selected' : '' }}>
                                                Kendaraan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe
                                            Sewa<span class="text-danger">*</span></label>
                                        <select class="form-select bg-base form-select-sm w-100" name="booking_type"
                                            required>
                                            <option disabled>Pilih Tipe Sewa</option>
                                            <option value="daily"
                                                {{ $asset->booking_type == 'daily' ? 'selected' : '' }}>
                                                Harian
                                            </option>
                                            <option value="annual"
                                                {{ $asset->booking_type == 'annual' ? 'selected' : '' }}>
                                                Tahunan
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label for="descriptionAssetInput"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                            <span class="text-danger">*</span></label>
                                        <textarea id="descriptionAssetInput" name="description" class="form-control" rows="4" cols="50"
                                            placeholder="Masukkan Deskripsi..." required>{{ $asset->description }}</textarea>
                                    </div>
                                    @if (isset($asset->jurusan_id))
                                        <div class="col-md-6 mb-20">
                                            <label
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Jurusan<span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="text"
                                                value="Fasilitas {{ $asset->jurusan->kode_jurusan }}" disabled>
                                            <input type="hidden" name="jurusan" value="{{ $asset->kode_jurusan }}">
                                        </div>
                                    @endif
                                    @if ($asset->booking_type === 'daily')
                                        <div class="col-md-6 mb-20">
                                            <label for="availableAtInput"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8 text-muted">
                                                Waktu Aset Tersedia (Pihak Ekstern)
                                                <span class="text-danger">*</span>
                                            </label>
                                            @php
                                                $availableAtArray = $asset->available_at
                                                    ? explode('|', $asset->available_at)
                                                    : [];
                                            @endphp

                                            <select id="availableAtInput"
                                                class="form-control form-control-sm choices-multiple-remove-button"
                                                name="available_at[]" placeholder="Masukkan waktu ketersediaan aset"
                                                multiple required>
                                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu', 'Minggu'] as $day)
                                                    <option value="{{ $day }}"
                                                        {{ in_array($day, $availableAtArray) ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    @endif
                                    <div class="col-md-6 mb-20">
                                        <label class="fw-semibold text-primary-light text-sm mb-8 text-muted">
                                            Fasilitas
                                            <span class="text-danger">*</span>
                                        </label>
                                        @php
                                            $facilityValue = $asset->facility
                                                ? implode('|', explode('|', $asset->facility))
                                                : '';
                                        @endphp
                                        <input class="form-control radius-8 choices-text-remove-button" type="text"
                                            name="facility" value="{{ $facilityValue }}" style="overflow: hidden;" />
                                    </div>

                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                            Aset</label>
                                        <div class="upload-image-wrapper d-flex align-items-center gap-3 flex-wrap">

                                            <!-- Gambar yang sudah ada di database -->
                                            <div class="uploaded-imgs-container d-flex gap-3 flex-wrap">
                                                @foreach (json_decode($asset->asset_images, true) as $image)
                                                    <div
                                                        class="uploaded-asset-img position-relative h-120-px w-120-px border radius-8 overflow-hidden">
                                                        <img src="{{ asset('storage/' . $image) }}"
                                                            class="w-100 h-100 object-fit-cover">
                                                        <button type="button"
                                                            class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl"
                                                            data-image="{{ $image }}">
                                                            <iconify-icon icon='radix-icons:cross-2'
                                                                class='text-xl text-danger-600'></iconify-icon>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Gambar baru yang diunggah (bukan dari DB) -->
                                            <div class="new-uploaded-imgs-container d-flex gap-3 flex-wrap"></div>

                                            <!-- Tombol Upload -->
                                            <label
                                                class="upload-file-multiple h-120-px w-120-px border input-form-light radius-8 overflow-hidden 
                                    border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1">
                                                <iconify-icon icon="solar:camera-outline"
                                                    class="text-xl text-secondary-light"></iconify-icon>
                                                <span class="fw-semibold text-secondary-light">Upload</span>
                                                <input type="file" name="asset_images[]" class="upload-file-input"
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
                                <div class="row gy-3">
                                    <div id="event-prices-container">
                                        @foreach ($asset->categories as $itemRate)
                                            <div class="event-price">
                                                <input type="hidden" name="rate_ids[]" value="{{ $itemRate->id }}">
                                                <div
                                                    class="row gy-3 mt-3 border-3 border-dashed border-primary p-3 mb-3 rounded">
                                                    <div class="col-md-5 mb-20">
                                                        <label for="categoryNameInput"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Jenis
                                                            Tarif Aset
                                                            <span class="text-danger">*</span>

                                                        </label>
                                                        <input id="categoryNameInput" type="text"
                                                            name="category_name[]"
                                                            class="form-control form-control-sm radius-8"
                                                            value="{{ $itemRate->category_name }}"
                                                            placeholder="Masukkan Jenis Tarif Aset" required>
                                                    </div>
                                                    <div class="col-md-2 mb-20">
                                                        <label for="externalPriceInput"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                            Aset (Eksternal)
                                                            <span class="text-danger">*</span>

                                                            <input id="externalPriceInput" type="number"
                                                                name="external_price[]"
                                                                class="form-control form-control-sm radius-8"
                                                                value="{{ $itemRate->external_price }}"
                                                                placeholder="Masukkan Tarif" required>
                                                    </div>
                                                    <div class="col-md-2 mb-20">
                                                        <label for="internalPercentageInput"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                            Aset Internal (%)
                                                            <span class="text-danger">*</span>

                                                            <input id="internalPercentageInput" type="number"
                                                                name="internal_price_percentage[]"
                                                                class="form-control form-control-sm radius-8"
                                                                placeholder="Masukkan Tarif"
                                                                value="{{ $itemRate->internal_price_percentage }}"
                                                                required>
                                                    </div>
                                                    <div class="col-md-2 mb-20">
                                                        <label for="socialPercentageInput"
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tarif
                                                            Aset Sosial (%)
                                                            <span class="text-danger">*</span>
                                                            <input id="socialPercentageInput" type="number"
                                                                name="social_price_percentage[]"
                                                                class="form-control form-control-sm radius-8"
                                                                placeholder="Masukkan Tarif"
                                                                value="{{ $itemRate->social_price_percentage }}"required>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="#" id="add-event-price"
                                            class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2 rounded-pill">
                                            <iconify-icon icon="zondicons:add-outline"
                                                class="menu-icon"></iconify-icon>
                                            Tambah Kategori Harga
                                        </a>
                                    </div>

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
                        <div class="row">
                            <input type="hidden" name="facility_scope"
                                value="{{ $asset->jurusan ? 'jurusan' : 'umum' }}">
                            <div class="col-md-6 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Aset<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control radius-8"
                                    placeholder="Masukkan Nama Aset" value="{{ $asset->name }}" required>
                            </div>
                            <div class="col-md-3 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe Aset<span
                                        class="text-danger">*</span></label>
                                <select class="form-select bg-base form-select-sm w-100" name="type" required>
                                    <option disabled>Pilih Tipe Aset</option>
                                    <option value="building" {{ $asset->type == 'building' ? 'selected' : '' }}>
                                        Bangunan
                                    </option>
                                    <option value="transportation"
                                        {{ $asset->type == 'transportation' ? 'selected' : '' }}>
                                        Kendaraan</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe Sewa<span
                                        class="text-danger">*</span></label>
                                <select class="form-select bg-base form-select-sm w-100" name="booking_type" required>
                                    <option disabled>Pilih Tipe Sewa</option>
                                    <option value="daily" {{ $asset->booking_type == 'daily' ? 'selected' : '' }}>
                                        Harian
                                    </option>
                                    <option value="annual" {{ $asset->booking_type == 'annual' ? 'selected' : '' }}>
                                        Tahunan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-20">
                                <label for="descriptionAssetInput"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi
                                    <span class="text-danger">*</span></label>
                                <textarea id="descriptionAssetInput" name="description" class="form-control" rows="4" cols="50"
                                    placeholder="Masukkan Deskripsi..." required>{{ $asset->description }}</textarea>
                            </div>

                            @if ($asset->booking_type === 'daily')
                                <div class="col-md-6 mb-20">
                                    <label for="availableAtInput"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8 text-muted">
                                        Waktu Aset Tersedia (Pihak Ekstern)
                                        <span class="text-danger">*</span>
                                    </label>
                                    @php
                                        $availableAtArray = $asset->available_at
                                            ? explode('|', $asset->available_at)
                                            : [];
                                    @endphp

                                    <select id="availableAtInput"
                                        class="form-control form-control-sm choices-multiple-remove-button"
                                        name="available_at[]" placeholder="Masukkan waktu ketersediaan aset" multiple
                                        required>
                                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu', 'Minggu'] as $day)
                                            <option value="{{ $day }}"
                                                {{ in_array($day, $availableAtArray) ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6 mb-20">
                                <label class="fw-semibold text-primary-light text-sm mb-8 text-muted">
                                    Fasilitas
                                    <span class="text-danger">*</span>
                                </label>
                                @php
                                    $facilityValue = $asset->facility
                                        ? implode('|', explode('|', $asset->facility))
                                        : '';
                                @endphp
                                <input class="form-control radius-8 choices-text-remove-button" type="text"
                                    name="facility" value="{{ $facilityValue }}" style="overflow: hidden;" />
                            </div>

                            <div class="col-md-6 mb-20">
                                <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                    Aset</label>
                                <div class="upload-image-wrapper d-flex align-items-center gap-3 flex-wrap">

                                    <!-- Gambar yang sudah ada di database -->
                                    <div class="uploaded-imgs-container d-flex gap-3 flex-wrap">
                                        @foreach (json_decode($asset->asset_images, true) as $image)
                                            <div
                                                class="uploaded-asset-img position-relative h-120-px w-120-px border radius-8 overflow-hidden">
                                                <img src="{{ asset('storage/' . $image) }}"
                                                    class="w-100 h-100 object-fit-cover">
                                                <button type="button"
                                                    class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl"
                                                    data-image="{{ $image }}">
                                                    <iconify-icon icon='radix-icons:cross-2'
                                                        class='text-xl text-danger-600'></iconify-icon>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Gambar baru yang diunggah (bukan dari DB) -->
                                    <div class="new-uploaded-imgs-container d-flex gap-3 flex-wrap"></div>

                                    <!-- Tombol Upload -->
                                    <label
                                        class="upload-file-multiple h-120-px w-120-px border input-form-light radius-8 overflow-hidden 
                            border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1">
                                        <iconify-icon icon="solar:camera-outline"
                                            class="text-xl text-secondary-light"></iconify-icon>
                                        <span class="fw-semibold text-secondary-light">Upload</span>
                                        <input type="file" name="asset_images[]" class="upload-file-input"
                                            accept=".jpg, .jpeg, .png" hidden multiple>
                                    </label>
                                </div>
                            </div>


                            <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                                <button type="reset"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">Update</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
