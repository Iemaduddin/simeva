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

    <!-- ========================== Filter Bus and Building Start =========================== -->
    <div class="container">
        <h3 class="my-40 text-center text-neutral-500">Cari Aset yang Anda Butuhkan</h3>
        <div class="row gy-4 justify-content-md-center">
            <div class="col-lg-2  m-40 bg-main-600 rounded-16 box-shadow-md wow fadeInUp" data-aos="fade-up"
                data-aos-duration="200">
                <div class="text-center mt-24">
                    <img src="{{ asset('assets/images/aset_bmn/bus_building.png') }}" style="width: 150px" alt="">
                    <h4 class="text-white">Semua Aset</h4>
                </div>
            </div>
            <div class="col-lg-2 m-40 bg-main-25 rounded-16 box-shadow-md wow fadeInUp" data-aos="fade-up"
                data-aos-duration="200">
                <div class="text-center mt-24">
                    <img src="{{ asset('assets/images/aset_bmn/building.png') }}" style="width: 150px" alt="">
                    <h4 class="text-main-600">Gedung</h4>
                </div>
            </div>
            <div class="col-lg-2 m-40 bg-main-25 rounded-16 box-shadow-md wow fadeInUp" data-aos="fade-up"
                data-aos-duration="200">
                <div class="text-center mt-24">
                    <img src="{{ asset('assets/images/aset_bmn/bus.png') }}" style="width: 150px" alt="">
                    <h4 class="text-main-600">Kendaraan</h4>
                </div>
            </div>
        </div>
        <div class="my-40">

            <h3 class="text-center text-neutral-500">Catatan:</h3>
            <h5 class="text-center text-neutral-500 fw-medium">Aset yang dapat dipinjam oleh pihak eksternal
                Polinema hanya Fasilitas Umum</h5>
        </div>
    </div>



    <!-- ========================== Filter Bus and Building End =========================== -->

    <!-- ================================== Explore Course Section Start =========================== -->
    <section class="explore-course pt-40  bg-main-25 position-relative z-1">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape six animation-scalation">

        <div class="container py-24">

            <div class="text-center">
                <div class="nav-tab-wrapper bg-white p-16 mb-40 d-inline-block" data-aos="zoom-out">
                    <ul class="nav nav-pills common-tab gap-16" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8 active"
                                id="pills-categories-tab" data-bs-toggle="pill" data-bs-target="#pills-categories"
                                type="button" role="tab" aria-controls="pills-categories" aria-selected="true">
                                <i class="text-xl d-flex ph-bold ph-squares-four"></i>
                                All Categories
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8 "
                                id="pills-fasum-tab" data-bs-toggle="pill" data-bs-target="#pills-fasum" type="button"
                                role="tab" aria-controls="pills-fasum" aria-selected="true">
                                <i class="text-xl d-flex ph-bold ph-squares-four"></i>
                                Fasilitas Umum
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8 "
                                id="pills-fasjur-tab" data-bs-toggle="pill" data-bs-target="#pills-fasjur"
                                type="button" role="tab" aria-controls="pills-fasjur" aria-selected="true">
                                <i class="text-xl d-flex ph-bold ph-squares-four"></i>
                                Fasilitas Jurusan
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-kategori" role="tabpanel"
                    aria-labelledby="pills-kategori-tab" tabindex="0">
                    <div class="row gy-4">
                        @foreach ($assets as $index => $asset)
                            <div class="col-lg-4 col-sm-6 wow fadeInUp" data-aos="fade-up" data-aos-duration="200">
                                <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                                    <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                                        <a href="{{ route('asset-bmn.getData', $asset->id) }}" class="w-100 h-100">
                                            @php
                                                $asset_images = json_decode($asset->asset_images, true);
                                            @endphp
                                            <img src="{{ asset('storage/' . $asset_images[0]) }}" alt="Asset Image"
                                                class="course-item__img rounded-12 cover-img transition-2">
                                        </a>
                                    </div>
                                    <div class="p-12">
                                        <div class="">
                                            <h4 class="mb-12">
                                                <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                                                    class="link text-line-2">{{ $asset->name }}</a>
                                            </h4>
                                            <span class="text-neutral-500 text-line-4">{{ $asset->description }}</span>
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
                                        </div>
                                        <div
                                            class="flex-between justify-content-center gap-8 pt-24 border-top border-neutral-50 mt-12 border-dashed border-0">
                                            <a href="{{ route('asset-bmn.getData', $asset->id) }}"
                                                class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                                                Cek Jadwal
                                                <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    </section>
    <!-- ================================== Explore Course Section End =========================== -->
@endsection
