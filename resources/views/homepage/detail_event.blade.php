@extends('layout.landingPageLayout')

@section('title', 'Rincian Event')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Rincian Event</h1>
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
                                <a href="{{ route('event') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    Event</a>
                            </li>
                            <li class="breadcrumb__item ">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600"> Rincian Event </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================== Course Details Section Start ============================== -->
    <section class="course-details py-120">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-8">
                    <!-- Details Content Start -->
                    <div class="course-details__content border border-neutral-30 rounded-12 bg-main-25 p-12">
                        <div class="rounded-12 overflow-hidden position-relative h-100">
                            <a href="{{ route('detail_event') }}">
                                <img src="assets/images/thumbs/course-details-img.png" alt="Course Image"
                                    class="rounded-12 cover-img transition-2">
                            </a>
                            <div
                                class="flex-align gap-8 bg-dark rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                <span class="text-lg fw-medium">Internal Jurusan</span>
                            </div>
                            <button type="button"
                                class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2">
                                <i class="ph ph-bookmark-simple"></i>
                            </button>
                        </div>
                        <div class="p-20">
                            <h2 class="mt-24 mb-24">Seminar Nasional Himpunan Mahasiswa Teknologi Informasi Tahun 2025</h2>
                            <p class="text-neutral-700">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Assumenda,
                                nesciunt quos quod rem in quaerat quae laborum hic obcaecati repellendus fugit facere maxime
                                exercitationem aut recusandae. Libero minima cum recusandae nam deserunt, pariatur corporis
                                nulla voluptatem harum officia? Totam debitis sequi ipsa alias repellendus ullam mollitia
                                incidunt fugit tempore voluptate?.</p>
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            <h3 class="mb-16">Narasumber</h3>
                            <ul class="list-dotted d-flex flex-column gap-15">
                                <li>Dr. Lorem ipsum, S.Kom., M.Kom.</li>
                                <li>Waluyo Gamblang, S.ST., M.Sc</li>
                            </ul>
                            <span class="d-block borderottom border-main-100 my-32"></span>
                            <h4 class="mb-16">Benefit</h4>
                            <ul class="list-dotted d-flex flex-column gap-15">
                                <li>Ilmu Bermanfaat</li>
                                <li>Seminar Kit</li>
                                <li>Relasi</li>
                                <li>Soft File Materi</li>
                                <li>E-Sertifikat</li>
                                <li>Doorprize</li>
                            </ul>
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            <h5 class="mb-16">Lokasi Event</h5>
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d527.5579684472881!2d112.61664396543006!3d-7.946824388343488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7882742cd7191f%3A0x56a5edb7ccb2769b!2sGraha%20Polinema!5e0!3m2!1sen!2sid!4v1737298921865!5m2!1sen!2sid"
                                width="800" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <p class="mt-10 text-center text-neutral-700">Graha Politeknik Negeri Malang</p>
                        </div>
                    </div>
                    <!-- Details Content End -->

                </div>
                <div class="col-xl-4">
                    <div class="course-details__sidebar border border-neutral-30 rounded-12 bg-white p-8">
                        <div class="border border-neutral-30 rounded-12 bg-main-25 p-24 bg-main-25">
                            <div class="d-flex justify-content-center mb-20">
                                <img src="{{ asset('assets/images/logo_organizers/hmti.png') }}" class="rounded-circle"
                                    alt="logo_organizers" style="width: 200px;height:200px">
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <h5 class="text-center">Himpunan Mahasiswa Teknologi Informasi</h5>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-note"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Pendaftaran</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">2 September 2025 - 15 September 2025 </p>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-timer"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Pelaksanaan</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">17 September 2025 (08.00-Selesai) </p>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                            class="ph-bold ph-map-pin-area"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Tempat</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">Graha Politeknik Negeri Malang </p>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                            class="ph-bold ph-user-circle"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Kuota</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">55/250 </p>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                            class="ph-bold ph-currency-circle-dollar"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Biaya</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">Rp50.000 </p>
                            </div>
                            <center>
                                <a href="{{ route('event') }}" class="btn btn-main rounded-pill flex-align gap-8"
                                    data-aos="fade-right">
                                    Daftar Sekarang
                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                </a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-align gap-8 mb-16 my-30" data-aos="fade-down">
                <h3 class="text-dark mb-0"> Event Serupa</h3>
            </div>
            <div class="row gy-4">
                @foreach ($events as $event)
                    <div class="col-lg-4 col-sm-6 wow fadeInUp" data-aos="fade-up" data-aos-duration="200">
                        <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                            <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                                <a href="{{ route('event') }}" class="w-100 h-100">
                                    <img src="{{ asset($event['image']) }}" alt="Course Image"
                                        class="course-item__img rounded-12 cover-img transition-2">
                                </a>
                                <div
                                    class="flex-align gap-8 {{ $event['category'] === 'internal_kampus' ? 'bg-main-600' : ($event['category'] === 'internal_jurusan' ? 'bg-warning' : 'bg-dark') }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                    <span class="text-lg fw-medium">{{ $event['category'] }}</span>
                                </div>
                                <button type="button"
                                    class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2">
                                    <i class="ph ph-bookmark-simple"></i>
                                </button>
                            </div>
                            <div class="course-item__content">
                                <div class="">
                                    <h4 class="mb-28">
                                        <a href="course-details.html" class="link text-line-2">{{ $event['title'] }}</a>
                                    </h4>
                                    <div class="flex-align gap-8">
                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                class="ph-bold ph-note"></i></span>
                                        <span class="text-neutral-700 text-lg fw-medium">Pendaftaran</span>
                                    </div>
                                    <span
                                        class="ms-30 my-5 btn btn-outline-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ $event['registration_period'] }}</span>
                                    <div class="flex-align gap-8">
                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                class="ph-bold ph-timer"></i></span>
                                        <span class="text-neutral-700 text-lg fw-medium">Pelaksanaan</span>
                                    </div>
                                    <span
                                        class="ms-30 my-5 btn btn-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ $event['event_period'] }}</span>

                                    <div class="flex-between gap-8 flex-wrap my-5">
                                        <div class="flex-align gap-4">
                                            <span class="text-neutral-700 text-2xl d-flex"><i
                                                    class="ph-bold ph-map-pin-area"></i></span>
                                            <span
                                                class="text-neutral-700 text-md fw-medium text-line-1">{{ $event['location'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-between gap-8 flex-wrap my-10">
                                        <div class="flex-align gap-4">
                                            <span class="text-neutral-700 text-2xl d-flex"><i
                                                    class="ph-bold ph-user-circle"></i></span>
                                            <span class="text-neutral-700 text-md fw-medium text-line-1">Kuota:
                                            </span><span>{{ $event['quota_left'] }}/{{ $event['quota'] }}</span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-8  d-flex align-items-center">
                                            <span class="text-neutral-700 text-2xl d-flex">
                                                <img src="{{ asset($event['organizer_logo']) }}" alt="Logo Organizers"
                                                    class="w-40 h-40 object-fit-cover">
                                            </span>
                                            <p class="text-neutral-700 text-md fw-medium text-line-1 ms-2">
                                                {{ $event['organizer'] }}
                                            </p>
                                        </div>
                                        <div class="col-4 text-center d-flex flex-column align-items-center">
                                            <span
                                                class="btn {{ $event['mode'] === 'Offline' ? 'btn-main' : ($event['mode'] === 'Online' ? 'btn-success' : 'btn-dark') }} rounded-10 px-10 py-10 text-white text-sm fw-medium">
                                                {{ $event['mode'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                    <h4 class="mb-0 text-main-two-600">Rp{{ $event['price'] }}</h4>
                                    <a href="apply-admission.html"
                                        class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold"
                                        tabindex="0">
                                        Daftar Sekarang
                                        <i class="ph ph-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- ============================== Course Details Section End ============================== -->
@endsection
