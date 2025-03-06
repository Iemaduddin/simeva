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
                                <img src="assets/images/thumbs/instructor-details-thumb.png" alt=""
                                    class="cover-img">
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
                    </div>
                </div>
                <div class="col-lg-8 ps-xl-5">
                    <div class="ps-lg-5">
                        <h5 class="text-main-600 mb-0">Himpunan Mahasiswa Jurusan</h5>
                        <h3 class="my-16">Himpunan Mahasiswa Teknologi Informasi</h3>
                        <span class="text-neutral-700">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam,
                            necessitatibus temporibus, dolores earum consectetur accusantium mollitia voluptate animi eius
                            maxime aliquam nulla. Maxime, officia! Animi non quis laboriosam, labore aliquid temporibus ab
                            fuga harum assumenda provident alias dolore iure, porro est corporis illum eligendi illo
                            doloribus sit reiciendis laborum! Quaerat?</span>
                        <span class="d-block border border-neutral-30 my-20 border-dashed"></span>
                        <h4 class="mb-24">Visi Misi</h4>
                        <h5 class="text-main-600 mb-0 my-30">Misi</h5>
                        <p class="text-neutral-500 my-20">Offer brief biographies or profiles of each instructor. These may
                            include details about their careers, achievements, and interests.</p>

                        <h5 class="text-main-600 mb-0 my-30">Visi</h5>
                        <ul class="list-dotted d-flex flex-column gap-10 my-20">
                            <li>Foundations of Python: Understand the basics of Python programming, including syntax,
                                variables, and data types. Learn how to write, debug, and execute Python scripts.</li>
                            <li>Data Structures and Algorithms: Master Python's built-in data structures such as lists,
                                dictionaries, and sets. Implement algorithms for sorting, searching, and manipulating data
                                efficiently.</li>
                            <li>Object-Oriented Programming (OOP): Gain proficiency in OOP concepts like classes, objects,
                                inheritance, and polymorphism, which are crucial for developing complex and modular
                                programs.</li>
                            <li>File Handling and I/O Operations: Learn how to read from and write to files, manage file
                                directories, and handle exceptions for robust file operations.</li>
                            <li>Libraries and Frameworks: Explore essential Python libraries such as NumPy, Pandas,
                                Matplotlib, and Seaborn for data manipulation and visualization. Get an introduction to web
                                frameworks like Flask and Django.</li>
                            <li>Data Science and Machine Learning: Dive into data analysis and visualization. Use
                                Scikit-learn for building and evaluating machine learning models.</li>
                            <li>Project Development: Apply your skills in real-world scenarios with hands-on projects.
                                Develop a comprehensive capstone project that showcases your mastery of Python.</li>
                        </ul>
                    </div>
                </div>
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
                                                    class="w-32 h-32 object-fit-cover rounded-circle">
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
    <!-- ================================ Instructor Details Section End ==================================== -->
@endsection
