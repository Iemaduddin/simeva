<div class="row gy-4" id="asset-results">
    @foreach ($assets as $asset)
        <div class="col-12 course-item" data-type="{{ $asset->type }}" data-facility="{{ $asset->facility_scope }}"
            data-jurusan="{{ $asset->jurusan_id }}">
            <div class="row course-item bg-main-25 rounded-16 p-20 border border-neutral-30 list-view">
                <div class="col-md-5 ps-5 d-flex rounded-12 overflow-hidden position-relative h-100">
                    @php
                        $images = json_decode($asset->asset_images);
                        $firstImage = $images[0] ?? null;
                    @endphp

                    <a href="{{ route('asset-bmn.getData', $asset->id) }}">
                        <img src="{{ 'storage/' . $firstImage }}" alt="Course Image"
                            class="rounded-12 cover-img transition-2" style="width:100%; height: 400px">
                    </a>
                    <div
                        class="{{ $asset->facility_scope === 'umum' ? 'bg-main-600' : 'bg-warning-600' }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                        <span class="text-lg fw-medium">{{ strToUpper($asset->facility_scope) }}</span>
                    </div>
                    <button type="button"
                        class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2"
                        data-asset-id="{{ $asset->id }}">
                        <i class="ph ph-bookmark-simple"></i>
                    </button>
                </div>
                <div class="col-md-7 ps-20 ">
                    <div class="">
                        <h4 class="my-12">
                            <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                                class="link text-line-2">{{ $asset->name }}</a>
                        </h4>
                        <span class="text-neutral-500 text-line-2">{{ $asset->description }}</span>
                        <h5 class="mt-12">Fasilitas</h5>
                        @php
                            $facilityList = explode(',', $asset->facility);
                        @endphp
                        @foreach ($facilityList as $asset_facility)
                            <div class="flex-align gap-8">
                                <span class="text-neutral-700 text-2xl d-flex"><i
                                        class="ph-bold ph-check text-main-600"></i></span>
                                <span class="text-neutral-700 text-md ">{{ $asset_facility }}</span>
                            </div>
                        @endforeach
                        <div class="flex-align gap-8 mt-20">
                            <span
                                class="badge {{ $asset->status === 'OPEN' ? 'badge-success' : 'badge-danger' }}rounded-10 px-10 py-10 bg-success text-white text-sm fw-bold ">
                                {{ $asset->status }}
                            </span>
                            <div class="row">
                                <div class="col-12 fw-bold">Tersedia:</div>
                                <div class="col-12">{{ $asset->available_at }}</div>
                            </div>
                        </div>
                        <hr>
                        <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                            class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold"
                            tabindex="0">
                            Cek Jadwal
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
