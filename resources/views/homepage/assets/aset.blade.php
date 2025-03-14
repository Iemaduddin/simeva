@extends('layout.landingPageLayout')

@section('title', 'Aset BMN')
@section('content')
    <!-- ========================= Banner Section Start =============================== -->
    <section class="banner py-80 position-relative overflow-hidden">

        <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt="" class="shape one animation-rotation">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape two animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt="" class="shape three animation-walking">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt="" class="shape five animation-walking">

        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-xl-6    ">
                    <div class="banner-content pe-md-4">
                        <h2 class="text-main-600 wow bounceInLeft">Penjadwalan dan Pemanfaatan</h2>
                        <h3 class="wow bounceInLeft">Aset Barang Milik Negara Polinema
                        </h3>
                        <p class="text-neutral-500 wow bounceInUp">Sistem Informasi Manajemen Event dan Aset
                            (SIMEVA) dirancang untuk membantu Anda memantau ketersediaan aset Barang Milik Negara yang dapat
                            dipinjam atau disewa sesuai kebutuhan event atau agenda Anda. Jelajahi lebih lanjut untuk
                            mendapatkan informasi selengkapnya.</p>
                        <div class="wow bounceInLeft">
                            <p class="mt-40">
                                Unit yang terkait atas Pemanfaatan Aset BMN :
                            </p>
                            <ul class="list-dotted d-flex flex-column gap-10 my-20">
                                <li>UPT. Pengelola Usaha</li>
                                <li>Ur. Rumah Tangga</li>
                                <li>UPT. Perawatan & Perbaikan</li>
                                <li>Pengelola Auditorium AH Lt. 1</li>
                                <li>Pengelola Masjid</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="container">
                        <div class="choose-us__thumbs position-relative">
                            <div class="text-end" data-aos="zoom-out">
                                <div class="d-sm-inline-block d-block position-relative">
                                    <img src="{{ asset('assets/images/aset_bmn/graha_polinema.jpg') }}"
                                        style="width: 1000px;height:600px" alt=""
                                        class="choose-us__img object-fit-cover rounded-12" data-tilt data-tilt-max="16"
                                        data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                                    <span
                                        class="shadow-main-two flex-center bg-main-two-600 rounded-circle position-absolute inset-block-start-0 inset-inline-start-0 mt-40 ms--40 animation-upDown"
                                        style="width: 150px; height: 150px;">
                                        <h3 class="text-white text-center pt-20 wow bounceInLeft">20+<br> Aset</h3>
                                    </span>
                                </div>
                            </div>
                            <div class="animation-video w-75" data-aos="zoom-in">
                                <img src="{{ asset('assets/images/aset_bmn/bus_polinema.jpg') }}" alt=""
                                    class="border border-white border-3 object-fit-cover rounded-circle"
                                    style="width:350px; height:350px" data-tilt>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
    </section>
    <!-- ========================= Banner SEction End =============================== -->
    <section class="course-list-view py-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="sidebar rounded-12 bg-main-25 p-32 border border-neutral-30">
                        <form id="filter-form">
                            <div class="flex-between mb-24">
                                <h4 class="mb-0">Filter</h4>
                                <button type="button"
                                    class="sidebar-close text-xl text-neutral-500 d-lg-none hover-text-main-600">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            </div>

                            <div class="position-relative">
                                <input type="text" id="search-input" class="common-input pe-48 rounded-pill"
                                    placeholder="Search...">
                                <button type="submit"
                                    class="text-neutral-500 text-xl d-flex position-absolute top-50 translate-middle-y inset-inline-end-0 me-24 hover-text-main-600">
                                    <i class="ph-bold ph-magnifying-glass"></i>
                                </button>
                            </div>
                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>

                            <h6 class="text-lg mb-24 fw-medium">Lingkup Event</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="facility_scope" value="all"
                                            id="all-scope" checked>
                                        <label class="form-check-label fw-normal flex-grow-1" for="all-scope">Semua
                                            Lingkup</label>
                                    </div>
                                    <span class="text-neutral-500">{{ count($assets) }}</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="facility_scope" value="umum"
                                            id="umum-scope">
                                        <label class="form-check-label fw-normal flex-grow-1" for="umum-scope">Umum</label>
                                    </div>
                                    <span
                                        class="text-neutral-500">{{ $assets->where('facility_scope', 'umum')->count() }}</span>
                                </div>
                                @foreach ($jurusans as $jurusan)
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="jurusan"
                                                value="{{ $jurusan->id }}" id="jurusan-{{ $jurusan->id }}">
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="jurusan-{{ $jurusan->id }}">{{ $jurusan->nama }}</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $assets->where('jurusan_id', $jurusan->id)->count() }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>
                            <button type="reset"
                                class="btn btn-outline-main rounded-pill flex-center gap-16 fw-semibold w-100">
                                <i class="ph-bold ph-arrow-clockwise d-flex text-lg"></i>
                                Reset Filters
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 ps-40">
                    <div class="course-list-wrapper">
                        <div class="flex-between gap-16 flex-wrap mb-40">
                            <span class="text-neutral-500">Showing <span id="result-count">0</span> of
                                {{ count($assets) }} Results</span>
                            <div class="flex-align gap-16">
                                <div class="flex-align gap-8">
                                    <span class="text-neutral-500 flex-shrink-0">Sort By :</span>
                                    <select id="sort-by"
                                        class="form-select ps-20 pe-28 py-8 fw-medium rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                                        <option value="newest">Newest</option>
                                        <option value="trending">Trending</option>
                                        <option value="popular">Popular</option>
                                    </select>
                                </div>
                                <button type="button"
                                    class="list-bar-btn text-xl w-40 h-40 bg-main-600 text-white rounded-8 flex-center d-lg-none">
                                    <i class="ph-bold ph-funnel"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row gy-4" id="asset-list">
                            @foreach ($assets as $asset)
                                <div class="col-12 course-item" data-type="{{ $asset->type }}"
                                    data-facility="{{ $asset->facility_scope }}"
                                    data-jurusan="{{ $asset->jurusan_id }}">
                                    <div
                                        class="row course-item bg-main-25 rounded-16 p-20 border border-neutral-30 list-view">
                                        <div class="col-lg-5 ps-5 rounded-12 overflow-hidden position-relative h-100">
                                            <a href="{{ route('asset-bmn.getData', $asset->id) }}">
                                                <img src="assets/images/thumbs/course-img1.png" alt="Course Image"
                                                    class="rounded-12 cover-img transition-2"
                                                    style="width:350px; height: 400px">
                                            </a>
                                            <div
                                                class="flex-align gap-8 {{ $asset->facility_scope === 'umum' ? 'bg-main-600' : 'bg-warning-600' }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                                <span
                                                    class="text-lg fw-medium">{{ strToUpper($asset->facility_scope) }}</span>
                                            </div>
                                            <button type="button"
                                                class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2">
                                                <i class="ph ph-bookmark-simple"></i>
                                            </button>
                                        </div>
                                        <div class="col-lg-7 ps-20 ">
                                            <div class="">
                                                <h4 class="mb-12">
                                                    <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                                                        class="link text-line-2">{{ $asset->name }}</a>
                                                </h4>
                                                <span
                                                    class="text-neutral-500 text-line-4">{{ $asset->description }}</span>
                                                <h5 class="mt-12">Fasilitas</h5>
                                                @php
                                                    $facilityList = explode(',', $asset->facility);
                                                @endphp
                                                @foreach ($facilityList as $asset_facility)
                                                    <div class="flex-align gap-8">
                                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                                class="ph-bold ph-check text-main-600"></i></span>
                                                        <span
                                                            class="text-neutral-700 text-md ">{{ $asset_facility }}</span>
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

                    </div>
                    <ul class="pagination mt-40 flex-align gap-12 flex-wrap justify-content-center">
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#"><i class="ph-bold ph-caret-left"></i></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#">...</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link text-neutral-700 fw-semibold w-40 h-40 bg-main-25 rounded-circle hover-bg-main-600 border-neutral-30 hover-border-main-600 hover-text-white flex-center p-0"
                                href="#"><i class="ph-bold ph-caret-right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            function filterAssets() {
                let selectedScopes = [];
                $('input[name="facility_scope"]:checked').each(function() {
                    selectedScopes.push($(this).val());
                });

                let selectedJurusan = [];
                $('input[name="jurusan"]:checked').each(function() {
                    selectedJurusan.push($(this).val());
                });

                let searchQuery = $('#search-input').val().toLowerCase();

                let visibleCount = 0;

                $('.course-item').hide().filter(function() {
                    let assetType = $(this).data('type');
                    let assetFacility = $(this).data('facility');
                    let assetJurusan = $(this).data('jurusan');
                    let assetName = $(this).find('.link').text().toLowerCase();

                    let scopeMatch = (selectedScopes.includes('all') || selectedScopes.includes(
                        assetFacility));
                    let jurusanMatch = (selectedJurusan.length === 0 || selectedJurusan.includes(
                        assetJurusan.toString()));
                    let searchMatch = (searchQuery === '' || assetName.includes(searchQuery));

                    let isVisible = scopeMatch && jurusanMatch && searchMatch;
                    if (isVisible) visibleCount++;
                    return isVisible;
                }).show();

                $('#result-count').text(visibleCount);
            }

            $('#filter-form input').change(function() {
                filterAssets();
            });

            $('#search-input').on('input', function() {
                filterAssets();
            });

            $('#filter-form').on('reset', function() {
                setTimeout(filterAssets, 0);
            });

            // Default: Tampilkan semua aset
            filterAssets();
        });
    </script>
@endpush
