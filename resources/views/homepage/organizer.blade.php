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
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Beranda</a>
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
        <div class="row">
            @php
                $label = [];
            @endphp

            @foreach ($organizers as $organizer)
                @php
                    $logoPath = $organizer['logo'];
                    $logo = $logoPath ? asset('storage/' . $logoPath) : asset($logoPath);
                @endphp
                @if ($organizer->organizer_type === 'Kampus' && !in_array('Kampus', $label))
                    <div class="flex-align gap-8 mb-16" data-aos="fade-down">
                        <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                        <h5 class="text-main-600 mb-0"> Kampus </h5>
                    </div>
                    @php $label[] = 'Kampus'; @endphp
                @endif

                @if ($organizer->organizer_type === 'Jurusan' && !in_array('Jurusan', $label))
                    <div class="flex-align gap-8 mb-16" data-aos="fade-down">
                        <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                        <h5 class="text-main-600 mb-0"> Jurusan </h5>
                    </div>
                    @php $label[] = 'Jurusan'; @endphp
                @endif

                @if (in_array($organizer->organizer_type, ['LT', 'HMJ', 'UKM']) && !in_array('OKI', $label))
                    <div class="flex-align gap-8 mb-16" data-aos="fade-down">
                        <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                        <h5 class="text-main-600 mb-0"> Organisasi Kemahasiswaan Intra </h5>
                    </div>
                    @php $label[] = 'OKI'; @endphp
                @endif

                @if ($organizer->organizer_type === 'LT' && !in_array('LT', $label))
                    <h5 class="ms-20 my-30 mb-0">Lembaga Tinggi</h5>
                    @php $label[] = 'LT'; @endphp
                @endif

                @if ($organizer->organizer_type === 'HMJ' && !in_array('HMJ', $label))
                    <h5 class="ms-20 my-30 mb-0">Himpunan Mahasiswa Jurusan</h5>
                    @php $label[] = 'HMJ'; @endphp
                @endif

                @if ($organizer->organizer_type === 'UKM' && !in_array('UKM', $label))
                    <h5 class="ms-20 my-30 mb-0">Unit Kemahasiswaan Intra</h5>
                    @php $label[] = 'UKM'; @endphp
                @endif


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
                                            <img src="{{ $logo }}" alt="">
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
                                                class="text-neutral-700 hover-text-main-600">{{ $organizer->user->name }}</a>
                                        </h4>
                                    </div>
                                </div>
                                <div
                                    class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-20 border-dashed border-0">
                                    <span class="text-neutral-700 text-md fw-medium">{{ $organizer['description'] }}</span>

                                </div>
                                <div
                                    class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0 justify-content-center">
                                    <a href="{{ route('detail_organizer', $organizer->id) }}"
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
@endsection
