@extends('layout.layout')
@section('title', 'Event Management')
@php
    $title = 'Event Management';
    $subTitle = 'Event Management';
@endphp
@section('content')

    <ul class="nav button-tab nav-pills mb-16" id="pills-tab-four" role="tablist">
        <li class="nav-item" role="presentation">
            <button
                class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10 active"
                id="pills-button-icon-details-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-details"
                type="button" role="tab" aria-controls="pills-button-icon-details" aria-selected="true">
                <iconify-icon icon="hugeicons:folder-details" class="text-xl"></iconify-icon>
                <span class="line-height-1">Rincian</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10"
                id="pills-button-icon-loan-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-loan"
                type="button" role="tab" aria-controls="pills-button-icon-loan-tab" aria-selected="false">
                <iconify-icon icon="mdi:file-transfer-outline" class="text-xl"></iconify-icon>
                <span class="line-height-1">Peminjaman</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10"
                id="pills-button-icon-participant-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-participant"
                type="button" role="tab" aria-controls="pills-button-icon-participant" aria-selected="false">
                <iconify-icon icon="la:users-cog" class="text-xl"></iconify-icon>
                <span class="line-height-1">Peserta</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10"
                id="pills-button-icon-speaker-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-speaker"
                type="button" role="tab" aria-controls="pills-button-icon-speaker" aria-selected="false">
                <iconify-icon icon="gravity-ui:person-speaker" class="text-xl"></iconify-icon>
                <span class="line-height-1">Pembicara</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2 fw-semibold text-primary-light radius-4 px-16 py-10"
                id="pills-button-icon-attendance-tab" data-bs-toggle="pill" data-bs-target="#pills-button-icon-attendance"
                type="button" role="tab" aria-controls="pills-button-icon-attendance" aria-selected="false">
                <iconify-icon icon="mdi:clipboard-check-multiple-outline" class="text-xl"></iconify-icon>
                <span class="line-height-1">Presensi Kehadiran</span>
            </button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tab-fourContent">
        <div class="tab-pane fade show active" id="pills-button-icon-details" role="tabpanel"
            aria-labelledby="pills-button-icon-details-tab" tabindex="0">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="card p-0 radius-12 overflow-hidden">
                        <div class="card-body p-0">
                            <img src="{{ asset('storage/' . $event->banner_path) }}" alt=""
                                class="w-100 h-100 object-fit-cover">
                            <div class="p-32">
                                <h3 class="mb-16"> {{ $event->title }} </h3>
                                <p class="text-neutral-500 mb-16">{{ $event->description }}</p>
                                <hr>
                                @if (!is_null($event->benefit))
                                    @php
                                        $benefits = explode('|', $event->benefit);
                                    @endphp
                                    <h5 class="my-16">Benefit</h5>
                                    <ul>
                                        @foreach ($benefits as $benefit)
                                            <li><i class="ri-circle-fill circle-icon text-dark w-auto text-xs"></i>
                                                {{ trim($benefit) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <hr class="my-16">
                                <p class="text-neutral-500 mb-16">{{ $event->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Start -->
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-24">
                        <!-- Latest Blog -->
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h6 class="text-xl mb-0">Latest Posts</h6>
                            </div>
                            <div class="card-body d-flex flex-column gap-24 p-24">
                                <div class="d-flex flex-wrap">
                                    <a href="" class="blog__thumb w-100 radius-12 overflow-hidden">
                                        <img src="{{ asset('assets/images/blog/blog5.png') }}" alt=""
                                            class="w-100 h-100 object-fit-cover">
                                    </a>
                                    <div class="blog__content">
                                        <h6 class="mb-8">
                                            <a href=""
                                                class="text-line-2 text-hover-primary-600 text-md transition-2">How
                                                to hire a right
                                                business executive for your company</a>
                                        </h6>
                                        <p class="text-line-2 text-sm text-neutral-500 mb-0">Lorem ipsum dolor sit
                                            amet consectetur
                                            adipisicing elit. Omnis dolores explicabo corrupti, fuga necessitatibus
                                            fugiat adipisci
                                            quidem eveniet enim minus.</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <a href="" class="blog__thumb w-100 radius-12 overflow-hidden">
                                        <img src="{{ asset('assets/images/blog/blog6.png') }}" alt=""
                                            class="w-100 h-100 object-fit-cover">
                                    </a>
                                    <div class="blog__content">
                                        <h6 class="mb-8">
                                            <a href=""
                                                class="text-line-2 text-hover-primary-600 text-md transition-2">The
                                                Gig Economy:
                                                Adapting to a Flexible Workforce</a>
                                        </h6>
                                        <p class="text-line-2 text-sm text-neutral-500 mb-0">Lorem ipsum dolor sit
                                            amet consectetur
                                            adipisicing elit. Omnis dolores explicabo corrupti, fuga necessitatibus
                                            fugiat adipisci
                                            quidem eveniet enim minus.</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <a href="" class="blog__thumb w-100 radius-12 overflow-hidden">
                                        <img src="{{ asset('assets/images/blog/blog7.png') }}" alt=""
                                            class="w-100 h-100 object-fit-cover">
                                    </a>
                                    <div class="blog__content">
                                        <h6 class="mb-8">
                                            <a href=""
                                                class="text-line-2 text-hover-primary-600 text-md transition-2">The
                                                Future of
                                                Remote Work: Strategies for Success</a>
                                        </h6>
                                        <p class="text-line-2 text-sm text-neutral-500 mb-0">Lorem ipsum dolor sit
                                            amet consectetur
                                            adipisicing elit. Omnis dolores explicabo corrupti, fuga necessitatibus
                                            fugiat adipisci
                                            quidem eveniet enim minus.</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <a href="" class="blog__thumb w-100 radius-12 overflow-hidden">
                                        <img src="{{ asset('assets/images/blog/blog6.png') }}" alt=""
                                            class="w-100 h-100 object-fit-cover">
                                    </a>
                                    <div class="blog__content">
                                        <h6 class="mb-8">
                                            <a href=""
                                                class="text-line-2 text-hover-primary-600 text-md transition-2">Lorem
                                                ipsum dolor
                                                sit amet consectetur adipisicing.</a>
                                        </h6>
                                        <p class="text-line-2 text-sm text-neutral-500 mb-0">Lorem ipsum dolor sit
                                            amet consectetur
                                            adipisicing elit. Omnis dolores explicabo corrupti, fuga necessitatibus
                                            fugiat adipisci
                                            quidem eveniet enim minus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h6 class="text-xl mb-0">Tags</h6>
                            </div>
                            <div class="card-body p-24">
                                <ul>
                                    <li
                                        class="w-100 d-flex align-items-center justify-content-between flex-wrap gap-8 border-bottom border-dashed pb-12 mb-12">
                                        <a href="" class="text-hover-primary-600 transition-2"> Techbology
                                        </a>
                                        <span
                                            class="text-neutral-500 w-28-px h-28-px rounded-circle bg-neutral-100 d-flex justify-content-center align-items-center transition-2 text-xs fw-semibold">
                                            01 </span>
                                    </li>
                                    <li
                                        class="w-100 d-flex align-items-center justify-content-between flex-wrap gap-8 border-bottom border-dashed pb-12 mb-12">
                                        <a href="" class="text-hover-primary-600 transition-2"> Business
                                        </a>
                                        <span
                                            class="text-neutral-500 w-28-px h-28-px rounded-circle bg-neutral-100 d-flex justify-content-center align-items-center transition-2 text-xs fw-semibold">
                                            01 </span>
                                    </li>
                                    <li
                                        class="w-100 d-flex align-items-center justify-content-between flex-wrap gap-8 border-bottom border-dashed pb-12 mb-12">
                                        <a href="" class="text-hover-primary-600 transition-2"> Consulting
                                        </a>
                                        <span
                                            class="text-neutral-500 w-28-px h-28-px rounded-circle bg-neutral-100 d-flex justify-content-center align-items-center transition-2 text-xs fw-semibold">
                                            01 </span>
                                    </li>
                                    <li
                                        class="w-100 d-flex align-items-center justify-content-between flex-wrap gap-8 border-bottom border-dashed pb-12 mb-12">
                                        <a href="" class="text-hover-primary-600 transition-2"> Course </a>
                                        <span
                                            class="text-neutral-500 w-28-px h-28-px rounded-circle bg-neutral-100 d-flex justify-content-center align-items-center transition-2 text-xs fw-semibold">
                                            01 </span>
                                    </li>
                                    <li class="w-100 d-flex align-items-center justify-content-between flex-wrap gap-8">
                                        <a href="" class="text-hover-primary-600 transition-2"> Real Estate
                                        </a>
                                        <span
                                            class="text-neutral-500 w-28-px h-28-px rounded-circle bg-neutral-100 d-flex justify-content-center align-items-center transition-2 text-xs fw-semibold">
                                            01 </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h6 class="text-xl mb-0">Tags</h6>
                            </div>
                            <div class="card-body p-24">
                                <div class="d-flex align-items-center flex-wrap gap-8">
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Development </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Design </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Technology </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Popular </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Codignator </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Javascript </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        Bootstrap </a>
                                    <a href=""
                                        class="btn btn-sm btn-primary-600 bg-primary-50 bg-hover-primary-600 text-primary-600 border-0 d-inline-flex align-items-center gap-1 text-sm px-16 py-6">
                                        PHP </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-button-icon-loan" role="tabpanel"
            aria-labelledby="pills-button-icon-loan-tab" tabindex="0">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card basic-data-table">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="text-xl mb-0">Daftar Peminjaman</h6>
                            @php
                                $isJurusanBooked = collect($assetBookings)->contains(function ($booking) {
                                    return $booking->status === 'booked' &&
                                        $booking->asset &&
                                        $booking->asset->facility_scope === 'jurusan';
                                });

                                $isUmumBooked = collect($assetBookings)->contains(function ($booking) {
                                    return $booking->status === 'booked' &&
                                        $booking->asset &&
                                        $booking->asset->facility_scope === 'umum';
                                });

                                $isRejectedDoc = collect($assetBookings)->contains(function ($booking) {
                                    return $booking->status === 'rejected_full_payment';
                                });
                                $jurusanAssetBooking = $assetBookings->filter(function ($booking) {
                                    return $booking->asset && $booking->asset->facility_scope === 'jurusan';
                                });

                            @endphp

                            <div class="d-flex gap-3">
                                @if ($isJurusanBooked)
                                    <button
                                        class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 open-upload-modal"
                                        data-bs-toggle="modal" data-bs-target="#modalUploadDocument-{{ $event->id }}"
                                        data-asset-jurusan="true">
                                        <iconify-icon icon="tabler:upload"
                                            class="icon text-xl line-height-1"></iconify-icon>
                                        Upload Surat Peminjaman Fasilitas Jurusan
                                    </button>
                                @endif

                                @if ($isUmumBooked)
                                    <button
                                        class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2 open-upload-modal"
                                        data-bs-toggle="modal" data-bs-target="#modalUploadDocument-{{ $event->id }}"
                                        data-asset-jurusan="false">
                                        <iconify-icon icon="tabler:upload"
                                            class="icon text-xl line-height-1"></iconify-icon>
                                        Upload Surat Peminjaman Fasilitas Umum
                                    </button>
                                @endif


                                @if ($isRejectedDoc)
                                    <button
                                        class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                        data-bs-toggle="modal" data-bs-target="#modalUploadDocument-{{ $event->id }}">
                                        <iconify-icon icon="tabler:upload"
                                            class="icon text-xl line-height-1"></iconify-icon>
                                        Upload Ulang Surat Peminjaman
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-24">
                            <div class="table-responsive">
                                <table id="loanAssetEventTable"
                                    class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Aksi</th>
                                            <th>Tanggal</th>
                                            <th>Tempat</th>
                                            <th>Ket.</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-button-icon-participant" role="tabpanel"
            aria-labelledby="pills-button-icon-participant-tab" tabindex="0">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card basic-data-table">
                        <div class="card-header border-bottom">
                            <h6 class="text-xl mb-0">Daftar Peserta</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="eventParticipantTable"
                                    class="table bordered-table mb-0 w-100 h-100 row-border order-column  sm-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>No WA</th>
                                            <th>Asal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-button-icon-speaker" role="tabpanel"
            aria-labelledby="pills-button-icon-speaker-tab" tabindex="0">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card basic-data-table">
                        <div class="card-header d-flex justify-content-between border-bottom">
                            <h6 class="text-xl mb-0">Daftar Pembicara</h6>
                            <button
                                class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#modalAddSpeaker">
                                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                                Tambah Pembicara
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="eventSpeakerTable"
                                    class="table bordered-table mb-0 w-100 h-100 row-border order-column sm-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Nama</th>
                                            <th>Event</th>
                                            <th>Peran</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-button-icon-attendance" role="tabpanel"
            aria-labelledby="pills-button-icon-attendance-tab" tabindex="0">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="card basic-data-table">
                        <div
                            class="card-header pt-16 pb-0 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                            <h6 class="text-lg mb-0">Presensi Kehadiran Event</h6>
                            <ul class="nav bordered-tab d-inline-flex nav-pills mb-0" id="pills-tab-six" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-16 py-10 active" id="pills-header-participant-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-header-participant" type="button"
                                        role="tab" aria-controls="pills-header-participant"
                                        aria-selected="true">Peserta</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-16 py-10" id="pills-header-organizer-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-header-organizer" type="button"
                                        role="tab" aria-controls="pills-header-organizer"
                                        aria-selected="false">Panitia</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-24 pt-10">
                            <div class="tab-content" id="pills-tabContent-six">
                                <div class="tab-pane fade show active" id="pills-header-participant" role="tabpanel"
                                    aria-labelledby="pills-header-participant-tab" tabindex="0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-start my-3 gap-3">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <select class="form-select form-select-sm w-auto" id="event_step_id_select">
                                                @foreach ($event->steps as $step)
                                                    @php
                                                        $eventName = $step->step_name ?? $step->event->title;
                                                        $dateTimeEvent =
                                                            '(' .
                                                            \Carbon\Carbon::parse($step->event_date)->translatedFormat(
                                                                'd M Y',
                                                            ) .
                                                            ', ' .
                                                            \Carbon\Carbon::parse(
                                                                $step->event_time_start,
                                                            )->translatedFormat('H.i') .
                                                            ' - ' .
                                                            \Carbon\Carbon::parse(
                                                                $step->event_time_end,
                                                            )->translatedFormat('H.i') .
                                                            ')';
                                                    @endphp
                                                    <option value="{{ $step->id }}">{{ $eventName }}
                                                        {{ $dateTimeEvent }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <select class="form-select form-select-sm w-auto" id="attendance_type">
                                                <option value="arrival">Presensi Datang</option>
                                                <option value="departure">Presensi Pulang</option>
                                            </select>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <input type="text" id="ticket_code_input"
                                                class="form-control form-control-sm"
                                                placeholder="Masukkan kode tiket atau scan barcode" autofocus>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="eventParticipantAttendance"
                                            class="table bordered-table mb-0 w-100 h-100 row-border order-column sm-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Presensi Datang</th>
                                                    <th>Presensi Pulang</th>
                                                    <th>Kode Tiket</th>
                                                    <th>Nama</th>
                                                    <th>Asal</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-header-organizer" role="tabpanel"
                                    aria-labelledby="pills-header-organizer-tab" tabindex="0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-start my-3 gap-3">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <select class="form-select form-select-sm w-auto" id="event_step_id">
                                                @foreach ($event->steps as $step)
                                                    @php
                                                        if ($step->step_name != null) {
                                                            $eventName = $step->step_name;
                                                        } else {
                                                            $eventName = $step->event->title;
                                                        }

                                                        $dateTimeEvent =
                                                            '(' .
                                                            \Carbon\Carbon::parse($step->event_date)->translatedFormat(
                                                                'd M Y',
                                                            ) .
                                                            ', ' .
                                                            \Carbon\Carbon::parse(
                                                                $step->event_time_start,
                                                            )->translatedFormat('H.i') .
                                                            ' - ' .
                                                            \Carbon\Carbon::parse(
                                                                $step->event_time_end,
                                                            )->translatedFormat('H.i') .
                                                            ')';
                                                    @endphp
                                                    <option value="{{ $step->id }}"> {{ $eventName }}
                                                        {{ $dateTimeEvent }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <!-- Tombol Presensi Datang -->
                                            <button class="btn btn-sm btn-success-600" data-bs-toggle="modal"
                                                data-bs-target="#confirmAttendanceModal" data-type="checkin">
                                                Presensi Datang (Semua)
                                            </button>

                                            <!-- Tombol Presensi Pulang -->
                                            <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                                data-bs-target="#confirmAttendanceModal" data-type="checkout">
                                                Presensi Pulang (Semua)
                                            </button>
                                        </div>
                                        <div class="modal fade" id="confirmAttendanceModal" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                                <form id="formPresensiAll" method="POST"
                                                    data-table="eventTeamAttendance">
                                                    @csrf
                                                    <div class="modal-content radius-16 bg-base">
                                                        <div class="modal-body p-24 text-center">
                                                            <span class="mb-16 fs-1 line-height-1 text-success">
                                                                <iconify-icon icon="ci:check-all"
                                                                    class="menu-icon"></iconify-icon>
                                                            </span>
                                                            <h6 id="modalPresensiText"
                                                                class="text-lg fw-semibold text-primary-light mb-0">
                                                                Apakah Anda yakin ingin melakukan presensi semua anggota?
                                                            </h6>
                                                            <div
                                                                class="d-flex align-items-center justify-content-center gap-3 mt-24">
                                                                <button type="button" data-bs-dismiss="modal"
                                                                    class="w-50 btn btn-outline-neutral-900 text-md px-40 py-11 radius-8">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="w-50 btn btn-success-600 text-md px-24 py-12 radius-8">
                                                                    Ya
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="eventTeamAttendance"
                                            class="table bordered-table mb-0 w-100 h-100 row-border order-column sm-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Presensi Datang</th>
                                                    <th>Presensi Pulang</th>
                                                    <th>Nama</th>
                                                    <th>Jabatan</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboardPage.events.speaker.add-speaker')
    @include('dashboardPage.events.modal.uploadDocumentAssetEvent')
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.open-upload-modal');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const isJurusan = button.getAttribute('data-asset-jurusan');
                    const eventId = button.getAttribute('data-bs-target').replace(
                        '#modalUploadDocument-', '');
                    const input = document.querySelector(`#assetJurusanInput-${eventId}`);

                    if (input) {
                        input.value = isJurusan;
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formPresensiAll');
            const modalText = document.getElementById('modalPresensiText');
            const selectStep = document.getElementById('event_step_id');

            // Tangkap semua tombol pemicu modal
            const buttons = document.querySelectorAll('[data-bs-target="#confirmAttendanceModal"]');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const type = button.getAttribute('data-type'); // checkin / checkout
                    const stepId = selectStep.value;

                    // Ubah teks modal sesuai jenis presensi
                    modalText.innerText =
                        `Apakah Anda yakin ingin melakukan presensi ${type === 'checkin' ? 'datang' : 'pulang'} seluruh anggota?`;

                    // Ubah action URL
                    const actionUrl =
                        "{{ route('events.attendanceMemberAll', ['eventStepId' => 'STEP_ID_PLACEHOLDER', 'checkType' => 'TYPE_PLACEHOLDER']) }}";
                    const finalUrl = actionUrl
                        .replace('STEP_ID_PLACEHOLDER', stepId)
                        .replace('TYPE_PLACEHOLDER', type);

                    form.setAttribute('action', finalUrl);
                });
            });
        });
    </script>
    <script>
        let assetBookingTableInitialized = false;

        document.addEventListener('DOMContentLoaded', function() {
            const assetBookingTabButton = document.getElementById('pills-button-icon-loan-tab');
            const eventId = "{{ $event->id ?? '' }}";
            assetBookingTabButton.addEventListener('click', function() {
                if (!assetBookingTableInitialized) {
                    $('#loanAssetEventTable').DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('assetBookingEvent.getData', ':eventId') }}".replace(
                                ':eventId', eventId),
                            type: 'GET',
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                            {
                                data: 'datetime',
                                name: 'datetime'
                            },
                            {
                                data: 'location',
                                name: 'location'
                            },
                            {
                                data: 'usage_event_name',
                                name: 'usage_event_name'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            }
                        ],
                    });

                    assetBookingTableInitialized = true;
                }
            });
        });
    </script>
    <script>
        let participantTableInitialized = false;

        document.addEventListener('DOMContentLoaded', function() {
            const participantTabButton = document.getElementById('pills-button-icon-participant-tab');
            const eventId = "{{ $event->id ?? '' }}";
            participantTabButton.addEventListener('click', function() {
                if (!participantTableInitialized) {
                    $('#eventParticipantTable').DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('events.getDataParticipants', ':eventId') }}".replace(
                                ':eventId', eventId),
                            type: 'GET',
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                            {
                                data: 'user.name',
                                name: 'user.name'
                            },
                            {
                                data: 'user.email',
                                name: 'user.email'
                            },
                            {
                                data: 'user.phone_number',
                                name: 'user.phone_number'
                            },
                            {
                                data: 'category_user',
                                name: 'category_user'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            }
                        ],
                    });

                    participantTableInitialized = true;
                }
            });
        });
    </script>
    <script>
        let speakerTableInitialized = false;

        document.addEventListener('DOMContentLoaded', function() {
            const speakerTabButton = document.getElementById('pills-button-icon-speaker-tab');
            const eventId = "{{ $event->id ?? '' }}";
            speakerTabButton.addEventListener('click', function() {
                if (!speakerTableInitialized) {
                    $('#eventSpeakerTable').DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('events.getDataSpeakers', ':eventId') }}".replace(
                                ':eventId', eventId),
                            type: 'GET',
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'event',
                                name: 'event'
                            },
                            {
                                data: 'role',
                                name: 'role'
                            }
                        ],
                    });

                    speakerTableInitialized = true;
                }
            });
        });
    </script>

    <script>
        let teamMemberTableInitialized = false;
        let teamMemberTable;
        const eventStepId = document.getElementById('event_step_id');

        document.addEventListener('DOMContentLoaded', function() {
            const teamMemberTabButton = document.getElementById('pills-header-organizer-tab');

            // Auto pilih option pertama jika tidak ada nilai
            if (!eventStepId.value) {
                eventStepId.selectedIndex = 0;
            }

            function initOrReloadTeamTable() {
                if (!teamMemberTableInitialized) {
                    teamMemberTable = $('#eventTeamAttendance').DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('events.getDataMembers') }}",
                            type: 'GET',
                            data: function(d) {
                                d.event_step_id = eventStepId.value;
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'checkin',
                                name: 'checkin'
                            },
                            {
                                data: 'checkout',
                                name: 'checkout'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'position',
                                name: 'position'
                            },
                        ],
                    });

                    teamMemberTableInitialized = true;
                }
            }

            // Inisialisasi saat tab diklik
            teamMemberTabButton.addEventListener('click', function() {
                initOrReloadTeamTable();
            });

            // Reload saat dropdown berubah
            eventStepId.addEventListener('change', function() {
                if (teamMemberTableInitialized) {
                    teamMemberTable.ajax.reload();
                }
            });
        });
    </script>

    <script>
        let attendanceParticipantTableInitialized = false;
        let attendanceParticipantTable;
        const eventStepIdSelect = document.getElementById('event_step_id_select');

        document.addEventListener('DOMContentLoaded', function() {
            const attendanceParticipantTabButton = document.getElementById('pills-button-icon-attendance-tab');

            // Auto pilih option pertama jika tidak ada nilai
            if (!eventStepIdSelect.value) {
                eventStepIdSelect.selectedIndex = 0;
            }

            function initOrReloadParticipantTable() {
                if (!attendanceParticipantTableInitialized) {
                    attendanceParticipantTable = $('#eventParticipantAttendance').DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('events.getDataEventParticipants') }}",
                            type: 'GET',
                            data: function(d) {
                                d.event_step_id = eventStepIdSelect.value;
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'checkin',
                                name: 'checkin'
                            },
                            {
                                data: 'checkout',
                                name: 'checkout'
                            },
                            {
                                data: 'ticket_code',
                                name: 'ticket_code'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'category_user',
                                name: 'category_user'
                            },
                        ],
                    });

                    attendanceParticipantTableInitialized = true;
                }
            }

            // Inisialisasi saat tab diklik
            attendanceParticipantTabButton.addEventListener('click', function() {
                initOrReloadParticipantTable();
            });

            // Reload saat dropdown berubah
            eventStepIdSelect.addEventListener('change', function() {
                if (attendanceParticipantTableInitialized) {
                    attendanceParticipantTable.ajax.reload();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notyf = new Notyf({
                duration: 4000,
                position: {
                    x: 'right',
                    y: 'bottom',
                }
            });

            const inputCode = document.getElementById('ticket_code_input');
            const eventStepSelect = document.getElementById('event_step_id_select');
            const attendanceTypeSelect = document.getElementById('attendance_type');

            inputCode.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();

                    const ticketCode = inputCode.value.trim();
                    const eventStepId = eventStepSelect.value;
                    const attendanceType = attendanceTypeSelect.value;

                    if (!ticketCode || !eventStepId || !attendanceType) return;

                    fetch('{{ route('events.attendanceParticipant') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ticket_code: ticketCode,
                                event_step_id: eventStepId,
                                attendance_type: attendanceType,
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                notyf.success(data.message);;
                                $('#eventParticipantAttendance').DataTable().ajax.reload(null, false);
                            } else {
                                notyf.error(data.message || 'Gagal mencatat presensi.');
                            }
                            inputCode.value = '';
                            inputCode.focus();
                        })
                        .catch(error => {
                            if (error.errors) {
                                Object.values(error.errors).forEach(messages => {
                                    messages.forEach(message => {
                                        notyf.error(message);
                                    });
                                });
                            } else {
                                notyf.error(error.message || 'Terjadi kesalahan pada server!');
                            }
                            inputCode.value = '';
                            inputCode.focus();
                        });
                }
            });
        });
    </script>
    @include('components.script-crud')
@endpush
