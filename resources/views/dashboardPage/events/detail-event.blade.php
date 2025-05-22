@extends('layout.layout')
@section('title', 'Event Management')
@php
    $title = 'Event Management';
    $subTitle = 'Event Management';
    $subSubTitle = 'Detail Event';
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
                            <img src="{{ $event->banner_path ? asset('storage/' . $event->banner_path) : asset('assets/images/banner.png') }}"
                                alt="Banner Event" class="w-100 h-100 object-fit-cover">
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
                                @if ($event->steps->whereNotNull('event_speaker')->count() > 0)
                                    <h5 class="mb-16">Narasumber</h5>

                                    @foreach ($event->steps as $step)
                                        @if ($step->event_speaker && $step->event_speaker->count() > 0)
                                            <p class="mt-3 fw-bold text-xl">
                                                {{ \Carbon\Carbon::parse($step->event_date)->isoFormat('dddd, D MMMM Y') }}
                                            </p>
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
                                                                    <li>- {{ $speaker->name }}</li>
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
                                    $sponsors = json_decode($event->sponsored_by, true);
                                    $mediaPartners = json_decode($event->media_partner, true);

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
                                @if ((is_array($sponsors) && count($sponsors)) || (is_array($mediaPartners) && count($mediaPartners)))
                                    <span class="d-block border-bottom border-main-100 my-32"></span>
                                    <div class="row">
                                        @if (is_array($sponsors) && count($sponsors))
                                            <div class="col-xxl-6">
                                                <h6 class="mb-16">Sponsored By</h6>
                                                @foreach ($sponsors as $sponsor)
                                                    <img src="{{ asset('storage/' . $sponsor) }}" alt="sponsor event"
                                                        width="80px">
                                                @endforeach
                                            </div>
                                        @endif
                                        @if (is_array($mediaPartners) && count($mediaPartners))
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
                    </div>
                </div>

                <!-- Sidebar Start -->
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-24">

                        <div class="card">
                            <div class="border border-neutral-30 rounded-12 bg-main-25 p-24 bg-main-25">
                                <div class="d-flex justify-content-center mb-20">
                                    <img src="{{ asset('storage/' . $event->organizers->logo) }}" class="rounded-circle"
                                        alt="logo_organizers" style="width: 200px;height:200px">
                                </div>
                                <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                    <h6 class="text-center">{{ $event->organizers->user->name }}</h6>
                                </div>
                                <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                    <div class="flex-align gap-12">
                                        <span class="text-neutral-700 text-2xl d-flex">
                                            <iconify-icon icon="ph:note" class="menu-icon"></iconify-icon>
                                            <span class="text-neutral-700 text-lg fw-normal ms-3">Pendaftaran</span>
                                        </span>
                                    </div>
                                    <p class="ms-40 text-neutral-700 fw-bold">
                                        {{ \Carbon\Carbon::parse($event->registration_date_start)->translatedFormat('d F Y (H.i)') }}
                                        -
                                        {{ \Carbon\Carbon::parse($event->registration_date_end)->translatedFormat('d F Y (H.i)') }}
                                    </p>
                                </div>
                                <div class="border-bottom border-neutral-40 pb-20 mb-20 flex-between flex-wrap">
                                    <div class="flex-align gap-12">
                                        <span class="text-neutral-700 text-2xl d-flex"><iconify-icon icon="ph:timer"
                                                class="menu-icon"></iconify-icon>
                                            <span class="text-neutral-700 text-lg fw-normal ms-3">Pelaksanaan</span>
                                        </span>
                                    </div>
                                    @if (!is_null($event->steps) && count($event->steps) > 1)
                                        <ul class="list-dotted d-flex flex-column gap-15">
                                            @foreach ($event->steps as $step)
                                                @php
                                                    $day = \Carbon\Carbon::parse($step->event_date)->isoFormat('dddd');
                                                    $tanggal = \Carbon\Carbon::parse(
                                                        $step->event_date,
                                                    )->translatedFormat('d F Y');
                                                    $jamMulai = \Carbon\Carbon::parse(
                                                        $step->event_time_start,
                                                    )->translatedFormat('H.i');
                                                    $jamSelesai = \Carbon\Carbon::parse(
                                                        $step->event_time_end,
                                                    )->translatedFormat('H.i');
                                                @endphp
                                                <li class="ms-40 text-neutral-700 fw-bold mt-10">
                                                    <span class="w-6-px h-6-px bg-dark rounded-circle"></span>
                                                    &nbsp;{{ $step->step_name }} <br>
                                                    &nbsp;&nbsp;&nbsp; {{ $day }}, {{ $tanggal }}
                                                    ({{ $jamMulai }}
                                                    -
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
                                        <span class="text-neutral-700 text-2xl d-flex">
                                            <iconify-icon icon="ph-map-pin-area" class="menu-icon"></iconify-icon>
                                            <span class="text-neutral-700 text-lg fw-normal ms-3">Tempat</span>
                                        </span>
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
                                                        $isBooked = \App\Models\AssetBooking::where(
                                                            'asset_id',
                                                            $asset->id,
                                                        )
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
                                                            $event_location =
                                                                $assetName .
                                                                ' ' .
                                                                ($jurusan ?? '') .
                                                                ' (Belum Disetujui)';
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
                                                        $isBooked = \App\Models\AssetBooking::where(
                                                            'asset_id',
                                                            $asset->id,
                                                        )
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
                                                                'Offline: ' .
                                                                $assetName .
                                                                ' ' .
                                                                ($jurusan ?? '') .
                                                                ' (Belum Disetujui)' .
                                                                '<br>Online: ' .
                                                                $location_decode[0]['location_online'];
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
                                                    <p class="text-neutral-700">{!! $event_location !!}</p>
                                                </div>
                                            @else
                                                <div class="ms-40 mb-10">
                                                    <p class="text-neutral-700">{!! $event_location !!}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="border-bottom border-neutral-40 pb-10 mb-10 flex-between flex-wrap">
                                    <div class="flex-align gap-12">
                                        <span class="text-neutral-700 text-2xl d-flex">

                                            <iconify-icon icon="ph:user-circle" class="menu-icon"></iconify-icon>
                                            <span class="text-neutral-700 text-lg fw-normal ms-3">Kuota</span>
                                        </span>
                                    </div>
                                    <p class="ms-40 text-neutral-700 fw-bold">
                                        {{ $event->remaining_quota }}/{{ $event->quota }} </p>
                                </div>

                                <div class="border-bottom border-neutral-40 pb-10 mb-10 flex-between flex-wrap">
                                    <div class="flex-align gap-12">
                                        <span class="text-neutral-700 text-2xl d-flex">
                                            <iconify-icon icon="ph-currency-circle-dollar"
                                                class="menu-icon"></iconify-icon>
                                            <span class="text-neutral-700 text-lg fw-normal ms-3">Biaya </span>
                                        </span>
                                    </div>

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

                                $organizer = \App\Models\Organizer::findOrFail(Auth::user()->organizer->id);
                                $leaders = \App\Models\TeamMember::where('organizer_id', $organizer->id)
                                    ->where('is_leader', true)
                                    ->get();
                            @endphp
                            <div class="d-flex gap-3">
                                <button data-bs-toggle="modal" data-bs-target="#modalLoanForm" data-asset-jurusan="true"
                                    class='btn btn-sm btn-info cursor-pointer'>Download Surat Peminjaman</button>
                                @include('dashboardPage.assets.asset-booking-event.modal.download-loan-form')
                                @php
                                    $suratDispo = \App\Models\AssetBookingDocument::where('event_id', $event->id)
                                        ->where('document_type', 'Surat Disposisi')
                                        ->first();
                                @endphp
                                @if ($suratDispo)
                                    <a href="{{ asset('storage/' . $suratDispo->document_path) }}?v={{ $suratDispo->updated_at->timestamp }}"
                                        class="btn btn-dark text-sm btn-sm" target="_blank" rel="noopener noreferrer">
                                        Download Surat Disposisi
                                    </a>
                                @endif
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
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0 align-content-center">Daftar Peserta</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('participants.export-excel', ['eventId' => $event->id]) }}"
                                    class="btn btn-info-900 btn-sm px-4 py-2 rounded-2 d-flex align-items-center px-12 py-12 radius-8 gap-2">
                                    <iconify-icon icon="typcn:export-outline" class="text-xl"></iconify-icon>
                                    Export Peserta
                                </a>
                                <button
                                    class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                                    data-bs-toggle="modal" data-bs-target="#modalAddParticipant">
                                    <iconify-icon icon="ic:baseline-plus"
                                        class="icon text-xl line-height-1"></iconify-icon>
                                    Tambah Peserta
                                </button>
                            </div>
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

                                    <div class="d-flex flex-wrap align-items-center justify-content-between my-3 gap-3">
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
                                            <select class="form-select form-select-sm w-auto" id="attendance_type">
                                                <option value="arrival">Presensi Datang</option>
                                                <option value="departure">Presensi Pulang</option>
                                            </select>
                                            <div class="">

                                                <input type="text" id="ticket_code_input"
                                                    class="form-control form-control-sm"
                                                    placeholder="Masukkan kode tiket atau scan barcode" autofocus>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('attendance.export-excel', ['eventId' => $event->id, 'category' => 'participant']) }}"
                                                class="btn btn-info-900 btn-sm px-4 py-2 rounded-2 d-flex align-items-center px-12 py-12 radius-8 gap-2">
                                                <iconify-icon icon="typcn:export-outline" class="text-xl"></iconify-icon>
                                                Export Presensi
                                            </a>
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
                                            <a href="{{ route('attendance.export-excel', ['eventId' => $event->id, 'category' => 'member']) }}"
                                                class="btn btn-info-900 rounded-2 d-flex align-items-center radius-8 gap-2">
                                                <iconify-icon icon="typcn:export-outline" class="text-xl"></iconify-icon>
                                                Export Presensi
                                            </a>
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
    @include('dashboardPage.events.participants.add-participant')
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
        $(document).ready(function() {
            // ================== Password Show Hide Js Start ==========
            $(document).on("click", ".toggle-password", function() {
                const inputId = $(this).data("toggle"); // tanpa #
                const input = $("#" + inputId);

                // Toggle type input
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    $(this).removeClass("ri-eye-line").addClass("ri-eye-off-line");
                } else {
                    input.attr("type", "password");
                    $(this).removeClass("ri-eye-off-line").addClass("ri-eye-line");
                }
            });
        });
        // ========================= Password Show Hide Js End ===========================
    </script>
    {{-- Handle Provinsi, Kota, Kec., Kel./Desa --}}
    <script>
        $(document).ready(function() {
            let apiBaseUrl = "https://iemaduddin.github.io/api-wilayah-indonesia/api";
            let cachedProvinces = null;

            function loadDropdown(url, target, selected = null, callback = null) {
                target.empty().append('<option value="" disabled selected>Pilih</option>');
                $.get(url, function(data) {
                    data.forEach(item => {
                        target.append(
                            `<option value="${item.id}" ${item.id == selected ? 'selected' : ''}>${item.name}</option>`
                        );
                    });
                    if (callback) callback();
                });
            }

            function loadProvinces(target, selected = null) {
                if (cachedProvinces) {
                    target.empty().append('<option value="" disabled>Pilih Provinsi</option>');
                    cachedProvinces.forEach(item => {
                        target.append(`<option value="${item.id}">${item.name}</option>`);
                    });

                    // Set selected setelah dropdown diisi
                    if (selected) {
                        target.val(selected).trigger('change');
                    }
                } else {
                    $.get(`${apiBaseUrl}/provinces.json`, function(data) {
                        cachedProvinces = data;
                        loadProvinces(target, selected); // Load ulang dengan selected
                    });
                }
            }


            loadProvinces($('.select-province'));

            $(document).on('change', '.select-province', function() {
                let provinceId = $(this).val();
                let citySelect = $('.select-city');
                citySelect.empty().append(
                    '<option value="" disabled selected>Pilih Kabupaten/Kota</option>');
                $('.select-subdistrict, .select-village').empty().append(
                    '<option value="">Pilih Kabupaten/Kota</option>');
                if (provinceId) loadDropdown(`${apiBaseUrl}/regencies/${provinceId}.json`, citySelect);
            });

            $(document).on('change', '.select-city', function() {
                let cityId = $(this).val();
                let subdistrictSelect = $('.select-subdistrict');
                subdistrictSelect.empty().append(
                    '<option value="" disabled selected>Pilih Kecamatan</option>');
                $('.select-village').empty().append('<option value="">Pilih Kecamatan</option>');
                if (cityId) loadDropdown(`${apiBaseUrl}/districts/${cityId}.json`, subdistrictSelect);
            });

            $(document).on('change', '.select-subdistrict', function() {
                let subdistrictId = $(this).val();
                let villageSelect = $('.select-village');
                villageSelect.empty().append(
                    '<option value="" disabled selected>Pilih Kelurahan/Desa</option>');
                if (subdistrictId) loadDropdown(`${apiBaseUrl}/villages/${subdistrictId}.json`,
                    villageSelect);
            });

            $('#modalAddParticipant').on('show.bs.modal', function() {
                $('.select-province, .select-city, .select-subdistrict, .select-village').empty().append(
                    '<option value="">Pilih</option>');
                loadProvinces($('.select-province'));
            });

            $(document).on('click', '[data-bs-target^="#modalUpdateParticipant-"]', function() {
                let targetModal = $($(this).data('bs-target'));
                let selectedProvince = targetModal.find('.select-province').data('selected');
                let selectedCity = targetModal.find('.select-city').data('selected');
                let selectedSubdistrict = targetModal.find('.select-subdistrict').data('selected');
                let selectedVillage = targetModal.find('.select-village').data('selected');

                loadProvinces(targetModal.find('.select-province'), selectedProvince);
                if (selectedProvince) {
                    loadDropdown(`${apiBaseUrl}/regencies/${selectedProvince}.json`, targetModal.find(
                        '.select-city'), selectedCity, function() {
                        if (selectedCity) {
                            loadDropdown(`${apiBaseUrl}/districts/${selectedCity}.json`, targetModal
                                .find('.select-subdistrict'), selectedSubdistrict,
                                function() {
                                    if (selectedSubdistrict) {
                                        loadDropdown(
                                            `${apiBaseUrl}/villages/${selectedSubdistrict}.json`,
                                            targetModal.find('.select-village'),
                                            selectedVillage);
                                    }
                                });
                        }
                    });
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
