@extends('layout.landingPageLayout')

@section('title', 'Kegiatanku')
@section('content')
    <!-- ==================== Breadcrumb Start Here ==================== -->
    <section class="breadcrumb py-120 bg-main-25 position-relative z-1 overflow-hidden mb-0">
        <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt=""
            class="shape one animation-scalation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt=""
            class="shape two animation-scalation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt=""
            class="shape eight animation-walking d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt=""
            class="shape six animation-scalation d-md-block d-none">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape nine animation-scalation">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="breadcrumb__wrapper">
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Kegiatanku</h1>
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
                                <span class="text-main-two-600">Kegiatanku </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container p-30 border border-main rounded-12 my-20">
        {{-- content --}}
        <div class="card basic-data-table">
            <div class="card-header d-flex justify-content-between bg-white">
                <h5 class="card-title my-10 align-content-center">Daftar Event</h5>
            </div>
            <div class="card-body bg-main-25">
                <div class="table-responsive overflow-x-auto">
                    <table class="table min-w-max vertical-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Nama Event</th>
                                <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Waktu Pelaksanaan
                                </th>
                                <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Status</th>
                                <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($myEvents as $itemEvent)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-24">
                                            <div
                                                class="w-60 h-60 border border-neutral-40 rounded-8 d-flex justify-content-center align-items-center bg-white">
                                                <img src=" {{ asset('storage/' . $itemEvent->event->pamphlet_path) }}"
                                                    width="50">
                                            </div>
                                            <div>
                                                <h6 class="text-md mb-12">{{ $itemEvent->event->title }}</h6>
                                                <div class="d-flex align-items-center gap-16">
                                                    <div class="d-flex align-items-center gap-4">
                                                        <span class="text-xs text-neutral-500 fw-bold">Biaya:</span>
                                                        <span
                                                            class="p-5 border border-neutral-40 fw-bold {{ $itemEvent->event->is_free ? 'text-warning-600' : 'text-success-600' }} rounded-4 text-sm text-neutral-500">
                                                            {{ $itemEvent->event->is_free ? 'Gratis' : 'Rp' . number_format(optional($itemEvent->transaction)->total_amount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if (!is_null($itemEvent->event->steps) && count($itemEvent->event->steps) > 1)
                                            <ul class="list-dotted d-flex flex-column gap-15">
                                                @foreach ($itemEvent->event->steps as $step)
                                                    @php
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
                                                        {{ $step->step_name }} <br>
                                                        {{ $tanggal }} ({{ $jamMulai }} - {{ $jamSelesai }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            @php
                                                $eventStep = $itemEvent->event->steps->first();
                                                $tanggal = \Carbon\Carbon::parse(
                                                    $eventStep->event_date,
                                                )->translatedFormat('d F Y');
                                                $jamMulai = \Carbon\Carbon::parse(
                                                    $eventStep->event_time_start,
                                                )->translatedFormat('H.i');
                                                $jamSelesai = \Carbon\Carbon::parse(
                                                    $eventStep->event_time_end,
                                                )->translatedFormat('H.i');
                                            @endphp

                                            <p class="ms-40 text-neutral-700 fw-bold">
                                                {{ $tanggal }} ({{ $jamMulai }} - {{ $jamSelesai }})
                                            </p>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = '';
                                            $statusText = '';
                                            $reasonRejected = null;

                                            switch ($itemEvent->status) {
                                                case 'pending_approval':
                                                    $badgeClass = 'bg-warning-600';
                                                    $statusText = 'Menunggu Verifikasi';
                                                    break;
                                                case 'registered':
                                                    $badgeClass = 'bg-main-600';
                                                    $statusText = 'Terdaftar';
                                                    break;
                                                case 'attended':
                                                    $badgeClass = 'bg-success-600';
                                                    $statusText = 'Sudah Hadir';
                                                    break;
                                                case 'rejected':
                                                    $badgeClass = 'bg-danger-600';
                                                    $statusText = 'Ditolak';
                                                    $reasonRejected = $itemEvent->reason;
                                                    break;
                                            }
                                        @endphp

                                        <span class="px-20 py-8 {{ $badgeClass }} rounded-8 text-white fw-medium mb-20">
                                            {{ $statusText }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-8">
                                            @php
                                                $event = $itemEvent->event;
                                            @endphp
                                            @if ($itemEvent->status === 'pending_approval' || $itemEvent->status === 'rejected')
                                                <a class='btn btn-sm btn-outline-warning cursor-pointer'
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalRegisterEvent{{ $event->id }}"><i
                                                        class="ph-bold ph-pen text-xl"></i></a>
                                                @include('homepage.events.modal.repeatRegistrationEvent')
                                            @endif
                                            @if ($itemEvent->status !== 'pending_approval' && $itemEvent->status !== 'rejected')
                                                <a href="{{ route('e-ticket.event', $itemEvent->id) }}" target="_blank"
                                                    class='btn btn-sm btn-outline-success cursor-pointer'><i
                                                        class="ph-bold ph-ticket text-xl"></i></a>
                                            @endif
                                            <a href="{{ route('detail_event', $itemEvent->event->id) }}"
                                                class='btn btn-sm btn-outline-primary cursor-pointer' data-bs-toggle="modal"
                                                data-bs-target="#modalDetailParticipant-{{ $event->id }}"><i
                                                    class="ph-bold ph-dots-three-outline text-xl"></i></a>
                                            @include('homepage.events.modal.detail_participant')
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
