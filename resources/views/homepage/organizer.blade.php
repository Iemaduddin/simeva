@extends('layout.landingPageLayout')

@section('title', 'Penyelenggara')
@section('content')
    <!-- ==================== Breadcrumb Start Here ==================== -->
    <section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
        <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt=""
            class="shape one animation-rotation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt=""
            class="shape two animation-scalation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt=""
            class="shape eight animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt=""
            class="shape six animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape nine animation-scalation">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="breadcrumb__wrapper">
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Penyelenggara</h1>
                        <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                            <li class="breadcrumb__item">
                                <a href="{{ route('home') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Home</a>
                            </li>
                            <li class="breadcrumb__item">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <a href="{{ route('organizer') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    Penyelenggara</a>
                            </li>
                            <li class="breadcrumb__item ">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600"> Daftar Penyelenggara </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container py-50">
        {{-- Jurusan --}}
        <div>
            <div class="flex-align gap-8 mb-16" data-aos="fade-down">
                <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                <h5 class="text-main-600 mb-0"> Jurusan</h5>
            </div>
            <div class="row">
                @foreach ($organizers_jurusan as $jurusan)
                    <div class="col-lg-4 col-sm-6 wow fadeInUp my-10" data-aos="fade-up" data-aos-duration="200">
                        <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                            <div class="course-item__content">
                                <div class="">
                                    <div
                                        class="instructor-item-two scale-hover-item social-hover d-flex justify-content-center align-items-center flex-column">
                                        <div class="instructor-item-two__thumb text-center rounded-circle aspect-ratio-1 p-12 border border-neutral-30 position-relative"
                                            style="width: 200px;height:200px">
                                            <div
                                                class="instructor-item-two__thumb-inner w-100 h-100 d-block bg-main-25 rounded-circle overflow-hidden position-relative">
                                                <img src="{{ asset($jurusan['image']) }}" alt="">
                                                <ul
                                                    class="social-list flex-center gap-12 d-flex position-absolute start-50 top-50 translate-middle">
                                                    <li class="social-list__item">
                                                        <a href="https://www.facebook.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-facebook-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.twitter.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600">
                                                            <i class="ph-bold ph-twitter-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.linkedin.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-instagram-logo"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="text-center mt-24">
                                            <h4 class="mb-12">
                                                <a href="instructor-details.html"
                                                    class="text-neutral-700 hover-text-main-600">{{ $jurusan['name'] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-20 border-dashed border-0">
                                        <span
                                            class="text-neutral-700 text-md fw-medium">{{ $jurusan['description'] }}</span>

                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0 justify-content-center">
                                        <a href="{{ route('detail_organizer') }}"
                                            class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                                            Selengkapnya
                                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        {{-- LT --}}
        <div>
            <div class="flex-align gap-8 mb-16 my-30" data-aos="fade-down">
                <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                <h5 class="text-main-600 mb-0"> Organisasi Kemahasiswaan Intra</h5>
            </div>
            <h5 class="ms-20 my-30 mb-0"> Lembaga Tinggi</h5>
            <div class="row">
                @foreach ($organizers_lt as $lt)
                    <div class="col-lg-4 col-sm-6 wow fadeInUp my-10" data-aos="fade-up" data-aos-duration="200">
                        <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                            <div class="course-item__content">
                                <div class="">
                                    <div
                                        class="instructor-item-two scale-hover-item social-hover d-flex justify-content-center align-items-center flex-column">
                                        <div class="instructor-item-two__thumb text-center rounded-circle aspect-ratio-1 p-12 border border-neutral-30 position-relative"
                                            style="width: 200px;height:200px">
                                            <div
                                                class="instructor-item-two__thumb-inner w-100 h-100 d-block bg-main-25 rounded-circle overflow-hidden position-relative">
                                                <img src="{{ asset($lt['image']) }}" alt="" class="cover-img">
                                                <ul
                                                    class="social-list flex-center gap-12 d-flex position-absolute start-50 top-50 translate-middle">
                                                    <li class="social-list__item">
                                                        <a href="https://www.facebook.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-facebook-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.twitter.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600">
                                                            <i class="ph-bold ph-twitter-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.linkedin.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-instagram-logo"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="text-center mt-24">
                                            <h4 class="mb-12">
                                                <a href="instructor-details.html"
                                                    class="text-neutral-700 hover-text-main-600">{{ $lt['name'] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-20 border-dashed border-0">
                                        <span class="text-neutral-700 text-md fw-medium">{{ $lt['description'] }}</span>

                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0 justify-content-center">
                                        <a href="{{ route('organizer') }}"
                                            class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                                            Selengkapnya
                                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        {{-- HMJ --}}
        <div>
            <h5 class="ms-20 my-30 mb-0">Himpunan Mahasiswa Jurusan</h5>
            <div class="row">
                @foreach ($organizers_hmj as $hmj)
                    <div class="col-lg-4 col-sm-6 wow fadeInUp my-10" data-aos="fade-up" data-aos-duration="200">
                        <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                            <div class="course-item__content">
                                <div class="">
                                    <div
                                        class="instructor-item-two scale-hover-item social-hover d-flex justify-content-center align-items-center flex-column">
                                        <div class="instructor-item-two__thumb text-center rounded-circle aspect-ratio-1 p-12 border border-neutral-30 position-relative"
                                            style="width: 200px;height:200px">
                                            <div
                                                class="instructor-item-two__thumb-inner w-100 h-100 d-block bg-main-25 rounded-circle overflow-hidden position-relative">
                                                <img src="{{ asset($hmj['image']) }}" alt="" class="cover-img">
                                                <ul
                                                    class="social-list flex-center gap-12 d-flex position-absolute start-50 top-50 translate-middle">
                                                    <li class="social-list__item">
                                                        <a href="https://www.facebook.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-facebook-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.twitter.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600">
                                                            <i class="ph-bold ph-twitter-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.linkedin.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-instagram-logo"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="text-center mt-24">
                                            <h4 class="mb-12">
                                                <a href="instructor-details.html"
                                                    class="text-neutral-700 hover-text-main-600">{{ $hmj['name'] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-20 border-dashed border-0">
                                        <span class="text-neutral-700 text-md fw-medium">{{ $hmj['description'] }}</span>

                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0 justify-content-center">
                                        <a href="{{ route('organizer') }}"
                                            class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                                            Selengkapnya
                                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        {{-- UKM --}}
        <div>
            <h5 class="ms-20 my-30 mb-0"> Unit Kegiatan Mahasiswa</h5>
            <div class="row">
                @foreach ($organizers_ukm as $ukm)
                    <div class="col-lg-4 col-sm-6 wow fadeInUp my-10" data-aos="fade-up" data-aos-duration="200">
                        <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                            <div class="course-item__content">
                                <div class="">
                                    <div
                                        class="instructor-item-two scale-hover-item social-hover d-flex justify-content-center align-items-center flex-column">
                                        <div class="instructor-item-two__thumb text-center rounded-circle aspect-ratio-1 p-12 border border-neutral-30 position-relative"
                                            style="width: 200px;height:200px">
                                            <div
                                                class="instructor-item-two__thumb-inner w-100 h-100 d-block bg-main-25 rounded-circle overflow-hidden position-relative">
                                                <img src="{{ asset($ukm['image']) }}" alt="" class="cover-img">
                                                <ul
                                                    class="social-list flex-center gap-12 d-flex position-absolute start-50 top-50 translate-middle">
                                                    <li class="social-list__item">
                                                        <a href="https://www.facebook.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-facebook-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.twitter.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600">
                                                            <i class="ph-bold ph-twitter-logo"></i></a>
                                                    </li>
                                                    <li class="social-list__item">
                                                        <a href="https://www.linkedin.com"
                                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                                class="ph-bold ph-instagram-logo"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="text-center mt-24">
                                            <h4 class="mb-12">
                                                <a href="instructor-details.html"
                                                    class="text-neutral-700 hover-text-main-600">{{ $ukm['name'] }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-20 border-dashed border-0">
                                        <span class="text-neutral-700 text-md fw-medium">{{ $ukm['description'] }}</span>

                                    </div>
                                    <div
                                        class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0 justify-content-center">
                                        <a href="{{ route('organizer') }}"
                                            class="btn btn-main rounded-pill flex-align gap-8" data-aos="fade-right">
                                            Selengkapnya
                                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- ==================== Breadcrumb End Here ==================== -->

@endsection
