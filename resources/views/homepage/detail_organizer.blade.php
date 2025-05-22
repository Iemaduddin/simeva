@extends('layout.landingPageLayout')

@section('title', 'Rincian Penyelenggara')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Rincian Penyelenggara</h1>
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
                                <span class="text-main-two-600"> Rincian Penyelenggara </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================ Instructor Details Section Start ==================================== -->
    <section class="instructor-details pt-120 pb-56 position-relative z-1">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div
                        class="instructor-item-two scale-hover-item social-hover d-flex justify-content-center align-items-center flex-column">
                        <div
                            class="instructor-item-two__thumb text-center rounded-circle aspect-ratio-1 p-12 border border-neutral-30 position-relative w-100 h-100">
                            <div
                                class="instructor-item-two__thumb-inner w-100 h-100 d-block bg-main-25 rounded-circle overflow-hidden position-relative">
                                <img src="{{ $organizer->logo ? asset('storage/' . $organizer->logo) : asset('assets/images/banner.png') }}"
                                    alt="" class="cover-img">
                                <ul
                                    class="social-list flex-center gap-12 d-flex position-absolute start-50 top-50 translate-middle">
                                    <li class="social-list__item">
                                        <a href="{{ $organizer->link_media_social ? $organizer->link_media_social['instagram'] : '' }}"
                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                class="ph ph-instagram-logo"></i></a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="{{ $organizer->link_media_social ? $organizer->link_media_social['youtube'] : '' }}"
                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600">
                                            <i class="ph ph-youtube-logo"></i></a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="{{ $organizer->link_media_social ? $organizer->link_media_social['tiktok'] : '' }}"
                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600"><i
                                                class="ph ph-tiktok-logo"></i></a>
                                    </li>
                                    <li class="social-list__item">
                                        <a href="{{ $organizer->link_media_social ? $organizer->link_media_social['x'] : '' }}"
                                            class="flex-center border border-white text-white w-44 h-44 rounded-circle text-xl text-main-600 bg-white hover-bg-main-600 hover-text-white hover-border-main-600">
                                            <i class="ph ph-x-logo"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 ps-xl-5">
                    <div class="ps-lg-5">
                        @if ($organizer->organizer_type === 'Kampus' || $organizer->organizer_type === 'Jurusan')
                            <h5 class="text-main-600 mb-0">Politeknik Negeri Malang</h5>
                        @elseif ($organizer->organizer_type === 'LT')
                            <h5 class="text-main-600 mb-0">Lembaga Tinggi (LT - OKI)</h5>
                        @elseif ($organizer->organizer_type === 'HMJ')
                            <h5 class="text-main-600 mb-0">Himpunan Mahasiswa Jurusan (HMJ - OKI)</h5>
                        @elseif ($organizer->organizer_type === 'UKM')
                            <h5 class="text-main-600 mb-0">Unit Kegiatan Mahasiswa (UKM - OKI)</h5>
                        @endif
                        <h3 class="my-16">{{ $organizer->user->name }}</h3>
                        <span class="text-neutral-700">{{ $organizer->description }}</span>
                        <span class="d-block border border-neutral-30 my-20 border-dashed"></span>
                        <h4 class="mb-24">Visi Misi</h4>
                        <h5 class="text-main-600 mb-0 my-30">Visi</h5>
                        <p class="text-neutral-500 my-20">{{ $organizer->vision }}</p>
                        @php
                            $missions = explode('|', $organizer->mision);
                        @endphp
                        <h5 class="text-main-600 mb-0 my-30">Misi</h5>
                        <ul class="list-dotted d-flex flex-column gap-10 my-20">

                            @foreach ($missions as $mission)
                                <li>{{ $mission }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="flex-align gap-8 mb-16 my-30" data-aos="fade-down">
                <h4 class="text-dark mb-0"> Event yang dimiliki {{ $organizer->user->name }}</h4>
            </div>
            <div class="row gy-4">
                @include('homepage.events.components.event-card-vertical', [
                    'events' => $events,
                    'message' => 'Tidak ada event yang dipublish',
                ])
            </div>
        </div>
    </section>
    <!-- ================================ Instructor Details Section End ==================================== -->
@endsection
