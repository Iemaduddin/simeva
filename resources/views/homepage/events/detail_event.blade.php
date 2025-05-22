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
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Beranda</a>
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

                            <img src="{{ asset('storage/' . $event->banner_path) }}" alt="Course Image"
                                class="rounded-12 cover-img transition-2">

                            @php
                                $organizer = $event->organizers;
                                $user = $organizer->user;
                                $jurusan = $user->jurusan;

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
                                class="flex-align gap-8 btn {{ $badgeClass }} rounded-pill px-24 py-12 text-white position-absolute inset-block-start-0 inset-inline-start-0 mt-20 ms-20 z-1">
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
                        <div class="p-20">
                            <h2 class="mt-24 mb-24">{{ $event->title }}</h2>
                            <p class="text-neutral-700">{{ $event->description }}</p>
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            @if (!is_null($event->benefit))
                                @php
                                    $benefits = explode('|', $event->benefit);
                                @endphp
                                <h4 class="mb-16">Benefit</h4>
                                <ul class="list-dotted d-flex flex-column gap-15">
                                    @foreach ($benefits as $benefit)
                                        <li>{{ trim($benefit) }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <span class="d-block border-bottom border-main-100 my-32"></span>
                            @if ($event->steps->whereNotNull('event_speaker')->count() > 0)
                                <h3 class="mb-16">Narasumber</h3>

                                @foreach ($event->steps as $step)
                                    @if ($step->event_speaker && $step->event_speaker->count() > 0)
                                        <h6 class="mt-3">
                                            {{ \Carbon\Carbon::parse($step->event_date)->isoFormat('dddd, D MMMM Y') }}
                                        </h6>
                                        <ul class="list-dotted d-flex flex-column gap-15">
                                            @php
                                                $speakers = $step->event_speaker->groupBy('role');
                                            @endphp

                                            @foreach ($speakers as $role => $speakerGroup)
                                                <li>
                                                    <strong>{{ $role }}</strong>:
                                                    @if ($speakerGroup->count() > 1)
                                                        <ul class="ps-3 mt-1">
                                                            @foreach ($speakerGroup as $speaker)
                                                                <li>{{ $speaker->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        {{ $speakerGroup->first()->name }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            @endif
                            @php
                                $sponsors = json_decode($event->sponsored_by) ?? '';
                                $mediaPartners = json_decode($event->media_partner) ?? '';

                                $contactPersonsRaw = $event->contact_person ?? '';
                                $contactPersons = array_filter(explode('|', $contactPersonsRaw), function ($cp) {
                                    return trim($cp) !== '';
                                });
                            @endphp
                            @if (count($contactPersons) > 0)
                                <span class="d-block border-bottom border-main-100 my-32"></span>
                                <h6 class="mb-16">Contact Person</h6>
                                <ul class="list-dotted d-flex flex-column gap-15">
                                    @foreach ($contactPersons as $cp)
                                        <li>{{ trim($cp) }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if ($sponsors || $mediaPartners)
                                <span class="d-block border-bottom border-main-100 my-32"></span>
                                <div class="row">
                                    @if ($sponsors)
                                        <div class="col-xxl-6">
                                            <h6 class="mb-16">Sponsored By</h6>
                                            @foreach ($sponsors as $sponsor)
                                                <img src="{{ asset('storage/' . $sponsor) }}" alt="sponsor event"
                                                    width="80px">
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($mediaPartners)
                                        <div class="col-xxl-6">
                                            <h6 class="mb-16">Media Partner</h6>
                                            @foreach ($mediaPartners as $media)
                                                <img src="{{ asset('storage/' . $media) }}" alt="media partner event"
                                                    width="80px">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Details Content End -->

                </div>
                <div class="col-xl-4">
                    <div class="course-details__sidebar border border-neutral-30 rounded-12 bg-white p-8">
                        <div class="border border-neutral-30 rounded-12 bg-main-25 p-24 bg-main-25">
                            <div class="d-flex justify-content-center mb-20">
                                <img src="{{ asset('storage/' . $event->organizers->logo) }}" class="rounded-circle"
                                    alt="logo_organizers" style="width: 200px;height:200px">
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <h5 class="text-center">{{ $event->organizers->user->name }}</h5>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-note"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Pendaftaran</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">
                                    {{ \Carbon\Carbon::parse($event->registration_date_start)->translatedFormat('d F Y (H.i)') }}
                                    -
                                    {{ \Carbon\Carbon::parse($event->registration_date_end)->translatedFormat('d F Y (H.i)') }}
                                </p>
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i class="ph-bold ph-timer"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Pelaksanaan</span>
                                </div>
                                @if (!is_null($event->steps) && count($event->steps) > 1)
                                    <ul class="list-dotted d-flex flex-column gap-15">
                                        @foreach ($event->steps as $step)
                                            @php
                                                $day = \Carbon\Carbon::parse($step->event_date)->isoFormat('dddd');
                                                $tanggal = \Carbon\Carbon::parse($step->event_date)->translatedFormat(
                                                    'd F Y',
                                                );
                                                $jamMulai = \Carbon\Carbon::parse(
                                                    $step->event_time_start,
                                                )->translatedFormat('H.i');
                                                $jamSelesai = \Carbon\Carbon::parse(
                                                    $step->event_time_end,
                                                )->translatedFormat('H.i');
                                            @endphp
                                            <li class="ms-40 text-neutral-700 fw-bold mt-10">
                                                {{ $step->step_name }} <br>
                                                {{ $day }}, {{ $tanggal }} ({{ $jamMulai }} -
                                                {{ $jamSelesai }})
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    @php
                                        $eventStep = $event->steps->first();
                                        $day = \Carbon\Carbon::parse($step->event_date)->isoFormat('dddd');
                                        $tanggal = \Carbon\Carbon::parse($eventStep->event_date)->translatedFormat(
                                            'd F Y',
                                        );
                                        $jamMulai = \Carbon\Carbon::parse(
                                            $eventStep->event_time_start,
                                        )->translatedFormat('H.i');
                                        $jamSelesai = \Carbon\Carbon::parse(
                                            $eventStep->event_time_end,
                                        )->translatedFormat('H.i');
                                    @endphp

                                    <p class="ms-40 text-neutral-700 fw-bold">
                                        {{ $day }}, {{ $tanggal }} ({{ $jamMulai }} -
                                        {{ $jamSelesai }})
                                    </p>

                                @endif
                            </div>

                            <div class="border-bottom border-neutral-40 pb-20 mb-20">
                                <div class="flex-align gap-12 mb-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                            class="ph-bold ph-map-pin-area"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Tempat</span>
                                </div>

                                @if (!is_null($event->steps) && $event->steps->count() > 0)
                                    @foreach ($event->steps as $stepEvent)
                                        @php
                                            $event_location = '';
                                            $location_decode = json_decode($stepEvent->location ?? '[]', true);

                                            if (isset($location_decode[0])) {
                                                if (
                                                    $location_decode[0]['type'] === 'offline' &&
                                                    $stepEvent->location_type === 'campus'
                                                ) {
                                                    $asset = \App\Models\Asset::where(
                                                        'id',
                                                        $location_decode[0]['location'],
                                                    )->first();
                                                    $assetName = \App\Models\Asset::where(
                                                        'id',
                                                        $location_decode[0]['location'],
                                                    )->value('name');
                                                    $jurusan = null;
                                                    if ($asset->jurusan_id) {
                                                        $jurusan = $asset->jurusan->nama;
                                                    }
                                                    $isBooked = \App\Models\AssetBooking::where('asset_id', $asset->id)
                                                        ->where('event_id', $event->id)
                                                        ->whereIn('status', [
                                                            'booked',
                                                            'approved',
                                                            'submission_full_payment',
                                                        ])
                                                        ->exists();

                                                    if ($isBooked) {
                                                        $event_location = $assetName . ' ' . $jurusan ?? '';
                                                    } else {
                                                        $event_location = '-';
                                                    }
                                                } elseif (
                                                    $location_decode[0]['type'] === 'offline' &&
                                                    $stepEvent->location_type === 'manual'
                                                ) {
                                                    $event_location =
                                                        $location_decode[0]['location'] .
                                                        ' (' .
                                                        $location_decode[0]['address'] .
                                                        ')';
                                                } elseif ($location_decode[0]['type'] === 'online') {
                                                    $event_location = $location_decode[0]['location'];
                                                } elseif (
                                                    $location_decode[0]['type'] === 'hybrid' &&
                                                    $stepEvent->location_type === 'campus'
                                                ) {
                                                    $asset = \App\Models\Asset::where(
                                                        'id',
                                                        $location_decode[0]['location_offline'],
                                                    )->first();
                                                    $assetName = \App\Models\Asset::where(
                                                        'id',
                                                        $location_decode[0]['location_offline'],
                                                    )->value('name');
                                                    $jurusan = null;
                                                    if ($asset->jurusan_id) {
                                                        $jurusan = $asset->jurusan->nama;
                                                    }
                                                    $isBooked = \App\Models\AssetBooking::where('asset_id', $asset->id)
                                                        ->where('event_id', $event->id)
                                                        ->whereIn('status', [
                                                            'booked',
                                                            'approved',
                                                            'submission_full_payment',
                                                        ])
                                                        ->exists();

                                                    if ($isBooked) {
                                                        $event_location =
                                                            'Offline: ' .
                                                            $assetName .
                                                            ' ' .
                                                            $jurusan .
                                                            '<br>Online: ' .
                                                            $location_decode[0]['location_online'];
                                                    } else {
                                                        $event_location =
                                                            'Offline: - ' .
                                                            ' ' .
                                                            $jurusan .
                                                            '<br>Online: ' .
                                                            $location_decode[0]['location_online']; // kalau tidak booked → kosong
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if (!empty($event_location) && count($event->steps) > 1)
                                            <div class="ms-40 mb-10">
                                                <p class="text-neutral-600 mb-2">
                                                    <strong>{{ \Carbon\Carbon::parse($stepEvent->event_date)->isoFormat('dddd, D MMMM Y') }}</strong>
                                                </p>
                                                <p class="text-neutral-700">{!! $event_location !!}</p>
                                            </div>
                                        @elseif (!empty($event_location) && count($event->steps) == 1)
                                            <div class="ms-40 mb-10">
                                                <p class="text-neutral-600 mb-2">
                                                    <strong>{{ \Carbon\Carbon::parse($stepEvent->event_date)->isoFormat('dddd, D MMMM Y') }}</strong>
                                                </p>
                                                <p class="text-neutral-700">{{ $event_location }}</p>
                                            </div>
                                        @else
                                            <div class="ms-40 mb-10">
                                                <p class="text-neutral-700">{{ $event_location }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                            class="ph-bold ph-user-circle"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Kuota</span>
                                </div>
                                <p class="ms-40 text-neutral-700 fw-bold">
                                    {{ $event->remaining_quota }}/{{ $event->quota }} </p>
                            </div>

                            <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                <div class="flex-align gap-12">
                                    <span class="text-neutral-700 text-2xl d-flex"><i
                                            class="ph-bold ph-currency-circle-dollar"></i></span>
                                    <span class="text-neutral-700 text-lg fw-normal">Biaya </span>
                                </div>
                                @auth
                                    @php
                                        $userCategory = Auth::user()->category_user;
                                        // Cari harga sesuai kategori user
                                        $userPrice = $event->prices->firstWhere('scope', $userCategory);
                                        // Jika tidak ketemu, cari harga umum
                                        if (!$userPrice) {
                                            $userPrice = $event->prices->firstWhere('scope', 'Umum');
                                        }
                                    @endphp

                                    @if ($userPrice && !is_null($event->prices))
                                        <p class="ms-40 text-neutral-700 fw-bold">
                                            {{ $userPrice->price && $userPrice->price != 0 ? 'Rp' . number_format($userPrice->price, 0, ',', '.') : 'Gratis' }}
                                        </p>
                                    @else
                                        <p class="ms-40 text-neutral-700 fw-bold">Gratis</p>
                                    @endif
                                @else
                                    @if (!is_null($event->prices) && count($event->prices) > 1)
                                        <ul class="list-dotted d-flex flex-column gap-15">
                                            @foreach ($event->prices as $itemPrice)
                                                <li class="ms-40 text-neutral-700 fw-bold mt-10">
                                                    {{ $itemPrice->category_name }} <br>
                                                    {{ $itemPrice->price && $itemPrice->price != 0 ? 'Rp' . number_format($itemPrice->price, 0, ',', '.') : 'Gratis' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="ms-40 text-neutral-700 fw-bold">
                                            {{ optional($event->prices)->price ?? 'Gratis' }}</p>
                                    @endif
                                @endauth

                            </div>
                            @if ($event->registration_date_end < \Carbon\Carbon::now())
                                <center>
                                    <div class="alert alert-danger mt-3">
                                        <strong>Pendaftaran Telah Ditutup.</strong>
                                    </div>
                                </center>
                            @else
                                @auth
                                    @if (Auth::user()->hasRole('Participant'))
                                        @if (!$event->participants->contains('user_id', Auth::id()))
                                            <center>
                                                <button type="button" class="btn btn-main rounded-pill flex-align gap-8"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalRegisterEvent{{ $event->id }}">
                                                    Daftar Sekarang
                                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                                </button>
                                            </center>
                                        @elseif (
                                            $event->participants->contains('user_id', Auth::id()) &&
                                                optional($event->participants->firstWhere('user_id', Auth::id()))->status == 'pending_approval')
                                            <center>
                                                <div class="alert alert-warning mt-3">
                                                    <strong>Anda sudah mendaftar di event ini. Silahkan menunggu verifikasi dari
                                                        penyelenggara.</strong>
                                                </div>
                                            </center>
                                        @else
                                            @if (
                                                $event->participants->contains('user_id', Auth::id()) &&
                                                    optional($event->participants->firstWhere('user_id', Auth::id()))->status == 'rejected')
                                                <center>
                                                    <div class="alert alert-warning mt-3">
                                                        <strong>Pendaftaran Anda ditolak. Silahkan ulangi
                                                            pendaftarannya.</strong>
                                                    </div>
                                                </center>
                                            @else
                                                <center>
                                                    <div class="alert alert-success mt-3">
                                                        <strong>Anda sudah terdaftar di event ini.</strong>
                                                    </div>
                                                </center>
                                            @endif
                                        @endif
                                        @include('homepage.events.modal.registrationEvent')
                                    @else
                                        <center>
                                            <div class="alert alert-danger mt-3">
                                                <strong>Anda tidak memiliki akses untuk mendaftar event.</strong>
                                            </div>
                                        </center>
                                    @endif
                                @else
                                    <center>
                                        <button type="button" class="btn btn-main rounded-pill flex-align gap-8"
                                            data-aos="fade-right" data-bs-toggle="modal"
                                            data-bs-target="#loginRegisterModal">
                                            Daftar Sekarang
                                            <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                        </button>
                                    </center>
                                    <!-- Modal -->
                                    <div class="modal fade" id="loginRegisterModal" tabindex="-1"
                                        aria-labelledby="loginRegisterModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center my-20">
                                                    <h5 class="my-25">Harap Register/Login sebagai Participant terlebih dahulu
                                                    </h5>
                                                    <a href="{{ route('showLoginPage') }}"
                                                        class="btn btn-primary rounded-pill">Login</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-align gap-8 mb-16 my-30" data-aos="fade-down">
                <h3 class="text-dark mb-0"> Event Serupa</h3>
            </div>
            <div class="row gy-4">
                @include('homepage.events.components.event-card-vertical', [
                    'events' => $simillarEvents,
                    'message' => 'Tidak ada event yang serupa',
                ])
            </div>
        </div>
    </section>
    @include('homepage.events.components.script-wishlist-handle')
@endsection
