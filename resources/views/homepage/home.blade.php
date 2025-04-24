@extends('layout.landingPageLayout')

@section('title', 'Beranda')
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
                <div class="col-xl-6">
                    <div class="banner-content pe-md-4">
                        <div class="flex-align gap-8 mb-16" data-aos="fade-down">
                            <span class="w-8 h-8 bg-main-600 rounded-circle"></span>
                            <h5 class="text-main-600 mb-0">SIMEVA – Solusi Cerdas untuk Manajemen Event & Aset</h5>
                        </div>

                        <h1 class="display3 mb-24 wow bounceInLeft">Selamat Datang!</h1>
                        <h3 class="mb-24 wow bounceInLeft"> Nikmati Kemudahan Mengikuti
                            <span class="text-main-two-600 wow bounceInRight" data-wow-duration="2s" data-wow-delay=".5s">
                                Event </span> <span> & Peminjaman </span>
                            <span class="text-main-600 wow bounceInLeft" data-wow-duration="2s" data-wow-delay=".5s">Aset
                                Kampus</span>
                        </h3>

                        <p class="text-neutral-500 text-line-5 wow bounceInUp">
                            <strong>SIMEVA</strong> adalah solusi inovatif untuk manajemen event dan aset di
                            <strong>Polinema</strong>.
                            Temukan dan ikuti berbagai event menarik, serta manfaatkan fasilitas peminjaman aset yang
                            tersedia
                            untuk pihak internal maupun eksternal kampus. Kelola semua kebutuhan acara dan aset dalam satu
                            sistem
                            yang praktis, efisien, dan terorganisir.
                        </p>

                        <div class="buttons-wrapper flex-align flex-wrap gap-24 mt-40">
                            <a href="{{ route('event') }}" class="btn btn-main rounded-pill flex-align gap-8"
                                data-aos="fade-right">
                                Cari Event
                                <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                            </a>
                            <a href="{{ route('aset-bmn') }}" class="btn btn-outline-main rounded-pill flex-align gap-8"
                                data-aos="fade-left">
                                Lihat Aset
                                <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="banner-thumb position-relative">
                        <img src="{{ asset('assets/images/event_home_graphic.png') }}" alt=""
                            class="banner-thumb__img rounded-12 wow bounceIn" data-wow-duration="3s" data-wow-delay=".5s"
                            data-tilt data-tilt-max="12" data-tilt-speed="500" data-tilt-perspective="5000"
                            data-tilt-full-page-listening data-tilt-scale="1.02">

                        <img src="{{ asset('assets/images/shapes/curve-arrow.png') }}" alt=""
                            class="curve-arrow position-absolute">

                        <div class="banner-box one px-24 py-12 rounded-12 bg-white fw-medium box-shadow-lg d-inline-block"
                            data-aos="fade-down">
                            <span class="text-main-600">36k+</span> Enrolled Students
                            <div class="enrolled-students mt-12">
                                <img src="{{ asset('assets/images/thumbs/enroll-student-img1.png') }}" alt=""
                                    class="w-48 h-48 rounded-circle object-fit-cover transition-2">
                                <img src="{{ asset('assets/images/thumbs/enroll-student-img2.png') }}" alt=""
                                    class="w-48 h-48 rounded-circle object-fit-cover transition-2">
                                <img src="{{ asset('assets/images/thumbs/enroll-student-img3.png') }}" alt=""
                                    class="w-48 h-48 rounded-circle object-fit-cover transition-2">
                                <img src="{{ asset('assets/images/thumbs/enroll-student-img4.png') }}" alt=""
                                    class="w-48 h-48 rounded-circle object-fit-cover transition-2">
                                <img src="{{ asset('assets/images/thumbs/enroll-student-img5.png') }}" alt=""
                                    class="w-48 h-48 rounded-circle object-fit-cover transition-2">
                                <img src="{{ asset('assets/images/thumbs/enroll-student-img6.png') }}" alt=""
                                    class="w-48 h-48 rounded-circle object-fit-cover transition-2">
                            </div>
                        </div>
                        <div class="banner-box two px-24 py-12 rounded-12 bg-white fw-medium box-shadow-lg flex-align d-inline-flex gap-16"
                            data-aos="fade-up">
                            <span
                                class="banner-box__icon flex-shrink-0 w-48 h-48 bg-purple-400 text-white text-2xl flex-center rounded-circle"><i
                                    class="ph ph-watch"></i></span>
                            <div>
                                <h6 class="mb-4">20% OFF</h6>
                                <span class="">For All Courses</span>
                            </div>
                        </div>
                        <div class="banner-box three px-24 py-12 rounded-12 bg-white fw-medium box-shadow-lg flex-align d-inline-flex gap-16"
                            data-aos="fade-left">
                            <span
                                class="banner-box__icon flex-shrink-0 w-48 h-48 bg-main-50 text-main-600 text-2xl flex-center rounded-circle"><i
                                    class="ph ph-whatsapp-logo"></i></span>
                            <div>
                                <span class="">Hubungi Kami</span>
                                <a href="tel:(704)555-0127"
                                    class="mt-8 fw-medium text-xl d-block text-main-600 hover-text-main-500">082331440024</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= Banner SEction End =============================== -->

    <!-- ========================== Brand Section Start =========================== -->
    <div class="brand wow fadeInUpBig my-60" data-wow-duration="1s" data-wow-delay=".5s">
        <div class="container container--lg">
            <div class="brand-box py-80 px-16 bg-main-25 border border-neutral-30 rounded-16">
                <h5 class="mb-40 text-center text-neutral-500">Penyelenggara Event di Politeknik Negeri Malang</h5>
                <div class="container">
                    <div class="brand-slider">
                        @foreach ($logo_organizers as $logo)
                            <div class="brand-slider__item px-24">
                                <img src="{{ asset($logo) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ========================== Brand Section End =========================== -->

    <!-- ================================== Explore Course Section Start =========================== -->
    <section class="explore-course pt-96 pb-40 bg-main-25 position-relative z-1">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape six animation-scalation">

        <div class="container">
            <div class="section-heading text-center style-flex gap-24">
                <div class="section-heading__inner text-start">
                    <h2 class="mb-0 wow bounceIn">Cari dan Ikuti Event Menarik di Politeknik Negeri Malang</h2>
                </div>
                <div class="section-heading__content">
                    <p class="section-heading__desc text-start mt-0 text-line-2 wow bounceInUp">Temukan event yang
                        sesuai
                        dengan minat Anda dan bergabunglah untuk pengalaman yang luar biasa.</p>
                    <a href="{{ route('event') }}"
                        class="item-hover__text flex-align gap-8 text-main-600 mt-24 hover-text-decoration-underline transition-1"
                        tabindex="0">
                        Lihat Semua Event
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="nav-tab-wrapper bg-white p-16 mb-40" data-aos="zoom-out">
                @php
                    $tabItems = [
                        'all' => ['icon' => 'ph-squares-four', 'label' => 'Semua Kategori'],
                        'seminar' => ['icon' => 'ph-magic-wand', 'label' => 'Seminar'],
                        'kuliah_tamu' => ['icon' => 'ph-code', 'label' => 'Kuliah Tamu'],
                        'pelatihan' => ['icon' => 'ph-code', 'label' => 'Pelatihan'],
                        'workshop' => ['icon' => 'ph-graduation-cap', 'label' => 'Workshop'],
                        'kompetisi' => ['icon' => 'ph-chart-pie-slice', 'label' => 'Kompetisi'],
                        'lainnya' => ['icon' => 'ph-chart-pie-slice', 'label' => 'Lainnya'],
                    ];
                @endphp
                <ul class="nav nav-pills common-tab gap-16" id="pills-tab" role="tablist">
                    @foreach ($tabItems as $key => $item)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8 @if ($loop->first) active @endif"
                                id="pills-{{ $key }}-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-{{ $key }}" type="button" role="tab"
                                aria-controls="pills-{{ $key }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="text-xl d-flex ph-bold {{ $item['icon'] }}"></i>
                                {{ $item['label'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-content" id="pills-tabContent">
                @foreach ($tabItems as $key => $item)
                    <div class="tab-pane fade @if ($loop->first) show active @endif"
                        id="pills-{{ $key }}" role="tabpanel" aria-labelledby="pills-{{ $key }}-tab"
                        tabindex="0">
                        <div class="row gy-4">
                            @forelse ($eventsByCategory[$key] as $event)
                                @php
                                    $organizer = $event->organizers;
                                    $user = $organizer->user;
                                    $jurusan = $user->jurusan;
                                    $userLogin = Auth::user();
                                    $userCategory = optional($userLogin)->category_user;
                                    $userJurusanId = $userLogin->jurusan_id ?? null;
                                    $eventScope = $event->scope;
                                    $organizerJurusanId = $organizer->user->jurusan_id ?? null;

                                    $showEvent = false;
                                    if (
                                        $eventScope === 'Internal Jurusan' &&
                                        $userJurusanId &&
                                        $userJurusanId == $organizerJurusanId
                                    ) {
                                        // Jika scope dan category_user cocok
                                        $showEvent = true;
                                    } elseif ($eventScope === 'Umum') {
                                        // Semua boleh ikut event umum
                                        $showEvent = true;
                                    } elseif ($eventScope === $userCategory) {
                                        // Untuk internal jurusan, cocokkan jurusan
                                        $showEvent = true;
                                    }
                                @endphp
                                @auth
                                    @continue(!$showEvent)
                                @endauth
                                <div class="col-lg-4 col-sm-6">
                                    <div class="course-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                                        <div class="course-item__thumb rounded-12 overflow-hidden position-relative">
                                            <a class="w-100 h-100" href="{{ route('detail_event', $event->id) }}">
                                                <img src="{{ asset('storage/' . $event->pamphlet_path) }}"
                                                    alt="Pamflet Event"
                                                    class="course-item__img rounded-12 cover-img transition-2">
                                            </a>
                                            @php
                                                if ($event->scope === 'Internal Organisasi') {
                                                    $statusText = 'Internal ' . $event->organizers->shorten_name;
                                                    $badgeClass = 'btn-secondary';
                                                } elseif (
                                                    $event->scope === 'Internal Jurusan' &&
                                                    $event->organizers->organizer_type === 'Jurusan'
                                                ) {
                                                    $statusText = 'Internal ' . $event->organizers->shorten_name;
                                                    $badgeClass = 'btn-warning';
                                                } elseif (
                                                    $event->scope === 'Internal Jurusan' &&
                                                    $event->organizers->organizer_type === 'HMJ'
                                                ) {
                                                    $statusText = 'Internal J' . $jurusan->kode_jurusan;
                                                    $badgeClass = 'btn-warning';
                                                } elseif ($event->scope === 'Internal Kampus') {
                                                    $statusText = 'Internal Kampus';
                                                    $badgeClass = 'btn-main';
                                                } elseif ($event->scope === 'Umum') {
                                                    $statusText = 'Umum';
                                                    $badgeClass = 'btn-dark';
                                                }
                                            @endphp
                                            <div
                                                class="flex-align gap-8 btn {{ $badgeClass }}  rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                                <span class="text-lg fw-medium">{{ $statusText }}</span>
                                            </div>
                                            @if (auth()->check() && auth()->user()->hasRole('Participant'))
                                                <button type="button"
                                                    class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2"
                                                    data-event-id="{{ $event->id }}">
                                                    <i class="ph ph-bookmark-simple"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="course-item__content">
                                            <div class="">
                                                <h4 class="mb-28">
                                                    <a class="link text-line-2">{{ $event->title }}</a>
                                                </h4>
                                                <div class="flex-align gap-8">
                                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                                            class="ph-bold ph-note"></i></span>
                                                    <span class="text-neutral-700 text-lg fw-medium">Pendaftaran</span>
                                                </div>
                                                <span
                                                    class="ms-30 my-5 btn btn-outline-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ \Carbon\Carbon::parse($event->registration_date_start)->translatedFormat('d F Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($event->registration_date_end)->translatedFormat('d F Y') }}</span>
                                                <div class="flex-align gap-8">
                                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                                            class="ph-bold ph-timer"></i></span>
                                                    <span class="text-neutral-700 text-lg fw-medium">Pelaksanaan</span>
                                                </div>
                                                @php
                                                    $dates = $event->steps->pluck('event_date')->sort(); // sort ascending

                                                    if ($dates->count() > 1) {
                                                        $firstDate = \Carbon\Carbon::parse($dates->first());
                                                        $lastDate = \Carbon\Carbon::parse($dates->last());

                                                        // Jika bulan sama
                                                        if ($firstDate->format('F') === $lastDate->format('F')) {
                                                            $displayDate =
                                                                $firstDate->format('d') .
                                                                ' - ' .
                                                                $lastDate->format('d') .
                                                                ' ' .
                                                                $lastDate->translatedFormat('F Y');
                                                        } else {
                                                            $displayDate =
                                                                $firstDate->translatedFormat('d M') .
                                                                ' - ' .
                                                                $lastDate->translatedFormat('d M Y');
                                                        }
                                                    } elseif ($dates->count() === 1) {
                                                        $displayDate = \Carbon\Carbon::parse(
                                                            $dates->first(),
                                                        )->translatedFormat('d F Y');
                                                    } else {
                                                        $displayDate = '-';
                                                    }
                                                @endphp
                                                <span
                                                    class="ms-30 my-5 btn btn-main rounded-pill px-10 py-10 text-white text-sm fw-medium">{{ $displayDate }}</span>
                                                @php

                                                    $allLocations = [];

                                                    foreach ($event->steps as $step) {
                                                        foreach (json_decode($step->location ?? '[]', true) as $loc) {
                                                            if ($loc['type'] === 'offline') {
                                                                // Cek apakah location adalah UUID
                                                                if (isset($loc['location'])) {
                                                                    $assetName = \App\Models\Asset::find(
                                                                        $loc['location'],
                                                                    )?->name;
                                                                    if ($assetName) {
                                                                        $allLocations[] = $assetName;
                                                                    }
                                                                } else {
                                                                    if (isset($loc['location'])) {
                                                                        $allLocations[] = $loc['location'];
                                                                    }
                                                                }
                                                            } elseif ($loc['type'] === 'online') {
                                                                if (isset($loc['location'])) {
                                                                    $allLocations[] = $loc['location'];
                                                                }
                                                            } elseif ($loc['type'] === 'hybrid') {
                                                                // Offline bagian
                                                                if (isset($loc['location_offline'])) {
                                                                    if (
                                                                        \Ramsey\Uuid\Uuid::isValid(
                                                                            $loc['location_offline'],
                                                                        )
                                                                    ) {
                                                                        $assetName = \App\Models\Asset::find(
                                                                            $loc['location_offline'],
                                                                        )?->name;
                                                                        if ($assetName) {
                                                                            $allLocations[] = $assetName;
                                                                        }
                                                                    } else {
                                                                        $allLocations[] = $loc['location_offline'];
                                                                    }
                                                                }

                                                                // Online bagian
                                                                if (isset($loc['location_online'])) {
                                                                    $allLocations[] = $loc['location_online'];
                                                                }
                                                            }
                                                        }
                                                    }

                                                    // Buang duplikat & gabung dengan koma
                                                    $locationString = implode(', ', array_unique($allLocations));
                                                @endphp
                                                <div class="flex-between gap-8 flex-wrap my-5">
                                                    <div class="flex-align gap-4">
                                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                                class="ph-bold ph-map-pin-area"></i></span>
                                                        <span
                                                            class="text-neutral-700 text-md fw-medium text-line-1">{{ $locationString }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-between gap-8 flex-wrap my-10">
                                                    <div class="flex-align gap-4">
                                                        <span class="text-neutral-700 text-2xl d-flex"><i
                                                                class="ph-bold ph-user-circle"></i></span>
                                                        <span class="text-neutral-700 text-md fw-medium text-line-1">Kuota:
                                                        </span><span>{{ $event->remaining_quota }}/{{ $event->quota }}</span>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center">
                                                    <div class="col-8  d-flex align-items-center">
                                                        <span class="text-neutral-700 text-2xl d-flex">
                                                            <img src="{{ asset($event->organizers->logo) }}"
                                                                alt="Logo Organizers" class="w-40 h-40 object-fit-cover">
                                                        </span>
                                                        <p class="text-neutral-700 text-md fw-medium text-line-1 ms-2">
                                                            {{ $event->organizers->shorten_name }}
                                                        </p>
                                                    </div>
                                                    @php
                                                        // Ambil semua execution_system dari step yang ada
                                                        $executionSystems = $event->steps
                                                            ->pluck('execution_system')
                                                            ->unique();

                                                        if ($executionSystems->count() === 1) {
                                                            // Semua step sama jenis pelaksanaannya
                                                            $executionSystemDisplay = ucfirst(
                                                                $executionSystems->first(),
                                                            ); // Offline, Online, Hybrid
                                                        } else {
                                                            // Ada campuran jenis
                                                            $executionSystemDisplay = 'Hybrid';
                                                        }

                                                        if ($executionSystemDisplay === 'Offline') {
                                                            $executionBadgeClass = 'btn-main';
                                                        } elseif ($executionSystemDisplay === 'Online') {
                                                            $executionBadgeClass = 'btn-success';
                                                        } else {
                                                            $executionBadgeClass = 'btn-dark';
                                                        }
                                                    @endphp
                                                    <div class="col-4 text-center d-flex flex-column align-items-center">
                                                        <span
                                                            class="btn {{ $executionBadgeClass }} rounded-10 px-10 py-10 text-white text-sm fw-medium">
                                                            {{ $executionSystemDisplay }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="flex-between gap-8 pt-24 border-top border-neutral-50 mt-28 border-dashed border-0">
                                                @php
                                                    $lowestPrice = $event->prices->pluck('price')->filter()->min();
                                                @endphp
                                                <h4
                                                    class="mb-0 {{ $lowestPrice && $lowestPrice != 0 ? 'text-success-600' : 'text-main-two-600' }}">
                                                    {{ $lowestPrice && $lowestPrice != 0 ? 'Rp' . number_format($lowestPrice, 0, ',', '.') : 'Gratis' }}
                                                </h4>
                                                <a href="{{ route('detail_event', $event->id) }}"
                                                    class="flex-align gap-8 text-main-600 hover-text-decoration-underline transition-1 fw-semibold"
                                                    tabindex="0">
                                                    Daftar Sekarang
                                                    <i class="ph ph-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <p class="text-muted">Belum ada event di kategori ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="container my-52 text-center">
                <a href="{{ route('event') }}"
                    class="btn btn-outline-main rounded-pill px-20 py-15 text-white text-lg fw-medium "
                    data-aos="zoom-out">Lebih Banyak lagi</a>
            </div>
        </div>
    </section>
    <!-- ================================== Explore Course Section End =========================== -->

    <section class="explore-course pt-40 position-relative z-1">

        <!-- ========================== Filter Transportation and Building Start =========================== -->
        <div class="container">
            <h3 class="text-center text-neutral-500">Cari Aset yang Anda Butuhkan</h3>
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-2 m-40 bg-main-600 rounded-16 box-shadow-md cursor-pointer filter-asset"
                    data-type="all" data-active="bus_building_active.png" data-non-active="bus_building_non-active.png">
                    <div class="text-center mt-24">
                        <img src="{{ asset('assets/images/aset_bmn/bus_building_active.png') }}" style="width: 200px"
                            alt="">
                        <h4 class="text-white">Semua Aset</h4>
                    </div>
                </div>
                <div class="col-lg-2 m-40 bg-main-25 rounded-16 box-shadow-md cursor-pointer filter-asset"
                    data-type="building" data-active="building_active.png" data-non-active="building_non-active.png">
                    <div class="text-center mt-24">
                        <img src="{{ asset('assets/images/aset_bmn/building_non-active.png') }}" style="width: 200px"
                            alt="">
                        <h4 class="text-main-600">Gedung</h4>
                    </div>
                </div>
                <div class="col-lg-2 m-40 bg-main-25 rounded-16 box-shadow-md cursor-pointer filter-asset"
                    data-type="transportation" data-active="bus_active.png" data-non-active="bus_non-active.png">
                    <div class="text-center mt-24">
                        <img src="{{ asset('assets/images/aset_bmn/bus_non-active.png') }}" style="width: 200px"
                            alt="">
                        <h4 class="text-main-600">Kendaraan</h4>
                    </div>
                </div>
            </div>
            @if (Auth::check() && Auth::user()->category_user === 'Internal Kampus')
                <div class="flex-between gap-16 flex-wrap mb-20 px-20">
                    <span class="text-center text-neutral-500 fw-medium">
                        <strong>Catatan:</strong> Aset yang dapat dipinjam oleh pihak eksternal Polinema hanya Fasilitas
                        Umum
                    </span>
                    <div class="flex-align gap-8">
                        <span class="text-neutral-500 flex-shrink-0">Filter Kategori Aset :</span>
                        <select id="facility-filter"
                            class="form-select ps-20 pe-28 py-8 fw-semibold rounded-pill bg-main-25 border border-neutral-30 text-neutral-700">
                            <option value="umum">Fasilitas Umum</option>
                            <optgroup label="Fasilitas Jurusan">
                                @foreach ($jurusans as $jurusan)
                                    <option value="jurusan_{{ $jurusan->id }}">{{ $jurusan->nama }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
            @endif

        </div>
        <!-- ========================== Filter Transportation and Building End =========================== -->

        <!-- ================================== Asset Content Start =========================== -->
        <section class="pt-40 bg-main-25  ">
            <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt=""
                class="shape six animation-scalation">


            <section class="container pt-10 pb-24">
                <div class="row gy-4">
                    @foreach ($assets as $index => $asset)
                        <div class="col-lg-4 col-sm-6 assets-item" data-type="{{ $asset->type }}"
                            data-facility="{{ $asset->facility_scope }}" data-jurusan-id="{{ $asset->jurusan_id }}"
                            data-is-public="{{ $asset->facility_scope === 'umum' ? 'true' : 'false' }}">
                            <div class="asset-item bg-white rounded-16 p-12 h-100 box-shadow-md">
                                <div class="asset-item__thumb rounded-12 overflow-hidden position-relative">
                                    <a href="{{ route('asset-bmn.getData', $asset->id) }}" class="w-100 h-100">
                                        @php
                                            $asset_images = json_decode($asset->asset_images, true);
                                        @endphp
                                        <img src="{{ asset('storage/' . $asset_images[0]) }}" alt="Asset Image"
                                            class="asset-item__img rounded-12 cover-img transition-2">
                                    </a>
                                    <div
                                        class="{{ $asset->facility_scope === 'umum' ? 'bg-main-600' : 'bg-warning-600' }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
                                        <span class="text-lg fw-medium">Fasilitas
                                            {{ strToUpper($asset->facility_scope) }}</span>
                                    </div>
                                    @if (auth()->check() && auth()->user()->hasRole('Tenant'))
                                        <button type="button"
                                            class="wishlist-btn w-48 h-48 bg-white text-main-two-600 flex-center position-absolute inset-block-start-0 inset-inline-end-0 mt-20 me-20 z-1 text-2xl rounded-circle transition-2"
                                            data-asset-id="{{ $asset->id }}">
                                            <i class="ph ph-bookmark-simple"></i>
                                        </button>
                                    @endif
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
                    <div class="container my-52 text-center">
                        <a href="{{ route('aset-bmn') }}"
                            class="btn btn-outline-main rounded-pill px-20 py-15 text-white text-lg fw-medium "
                            data-aos="zoom-out">Lebih Banyak lagi</a>
                    </div>
                </div>
            </section>
            <!-- ================================== Asset Content End =========================== -->
        </section>
    </section>
@endsection
@push('script')
    <script>
        let isUserLoggedIn = @json(Auth::check());
        let userCategory = @json(Auth::check() ? Auth::user()->category_user : 'public');
    </script>

    <script>
        $(document).ready(function() {
            function filterAssets() {
                let selectedType = $('.filter-asset.bg-main-600').data('type');
                let selectedFacility = $('#facility-filter').val();

                $('.assets-item').hide().filter(function() {
                    let assetType = $(this).data('type');
                    let assetFacility = $(this).data('facility');
                    let assetJurusanId = $(this).data('jurusan-id');
                    let isPublicAsset = assetFacility === 'umum';

                    // Logic for different user states
                    if (!isUserLoggedIn) {
                        // When not logged in, only show public assets
                        return isPublicAsset && (selectedType === 'all' || assetType === selectedType);
                    } else if (userCategory === 'Internal Kampus') {
                        // Internal campus users can see all assets with full filtering
                        let typeMatch = (selectedType === 'all' || assetType === selectedType);
                        let facilityMatch;

                        if (selectedFacility === 'umum') {
                            facilityMatch = assetFacility === 'umum';
                        } else if (selectedFacility.startsWith('jurusan_')) {
                            // Extract jurusan ID from the selected value
                            let selectedJurusanId = selectedFacility.split('_')[1];
                            facilityMatch = assetFacility === 'jurusan' && assetJurusanId ===
                                selectedJurusanId;
                        }

                        return typeMatch && facilityMatch;
                    } else {
                        // Other logged-in users - show only public assets
                        return isPublicAsset && (selectedType === 'all' || assetType === selectedType);
                    }
                }).show();
            }

            // Handle filter button clicks
            $('.filter-asset').click(function() {
                $('.filter-asset').each(function() {
                    $(this).removeClass('bg-main-600').addClass('bg-main-25');
                    let nonActiveImg = $(this).data('non-active');
                    $(this).find('img').attr('src', '{{ asset('assets/images/aset_bmn/') }}/' +
                        nonActiveImg);
                    $(this).find('h4').removeClass('text-white').addClass('text-main-600');
                });

                $(this).removeClass('bg-main-25').addClass('bg-main-600');
                let activeImg = $(this).data('active');
                $(this).find('img').attr('src', '{{ asset('assets/images/aset_bmn/') }}/' + activeImg);
                $(this).find('h4').removeClass('text-main-600').addClass('text-white');

                filterAssets();
            });

            // Handle facility filter changes
            $('#facility-filter').change(function() {
                filterAssets();
            });

            // Initial filter application
            filterAssets();
        });
    </script>
    @if (auth()->check() && auth()->user()->hasRole('Tenant'))
        <script>
            document.addEventListener("DOMContentLoaded", async function() {
                const buttons = document.querySelectorAll(".wishlist-btn");
                const assetIds = Array.from(buttons).map(button => button.dataset.assetId);

                try {
                    const response = await fetch("{{ route('saved.item.check') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            itemType: 'asset',
                            asset_ids: assetIds
                        })
                    });

                    const savedAssets = await response.json();

                    buttons.forEach(button => {
                        if (savedAssets.includes(button.dataset.assetId)) {
                            button.classList.add("bg-main-two-600", "text-white");
                            button.classList.remove("bg-white", "text-main-two-600");
                        } else {
                            button.classList.add("bg-white", "text-main-two-600");
                            button.classList.remove("bg-main-two-600", "text-white");
                        }
                    });

                } catch (error) {
                    console.error("Error fetching saved:", error);
                }

                buttons.forEach(button => {
                    button.addEventListener("click", async function() {
                        const assetId = this.dataset.assetId;
                        const isSaved = this.classList.contains("bg-main-two-600");

                        try {
                            const response = await fetch(
                                "{{ route('saved.item.toggle') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                    },
                                    body: JSON.stringify({
                                        itemType: 'asset',
                                        asset_id: assetId
                                    })
                                });

                            const result = await response.json();

                            if (response.ok) {
                                if (isSaved) {
                                    this.classList.add("bg-white", "text-main-two-600");
                                    this.classList.remove("bg-main-two-600", "text-white");
                                } else {
                                    this.classList.add("bg-main-two-600", "text-white");
                                    this.classList.remove("bg-white", "text-main-two-600");
                                }
                                this.style.transform = "scale(1.2)";
                                setTimeout(() => this.style.transform = "scale(1)", 200);
                            } else {
                                console.error("Error:", result.message);
                            }
                        } catch (error) {
                            console.error("Request failed:", error);
                        }
                    });
                });
            });
        </script>
    @endif
    @include('homepage.events.components.script-wishlist-handle')
@endpush
