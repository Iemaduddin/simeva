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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            @foreach ($notifications as $notification)
                                @php
                                    $data = $notification->data;
                                    $isRead = $notification->read_at !== null;

                                    $sender = \App\Models\User::find($data['user_id']);
                                    $profilePhoto = $sender->profile_picture ?? 'default-avatar.png';

                                    $booking = $data['booking'] ?? null;
                                    $eventId = $data['event_id'] ?? null;
                                    $asset = $booking->asset ?? null;
                                    $jurusan = $asset->jurusan ?? null;
                                    $role = auth()->user()->getRoleNames()->first(); // Simpan dulu untuk efisiensi

                                    $routeName = $role === 'Participant' ? 'profile.myEvent' : 'profile.myAssetBooking';

                                    // Default fallback jika routing gagal
                                    $link = '#';
                                    if ($routeName) {
                                        try {
                                            $link = route($routeName);
                                        } catch (\Exception $e) {
                                            $link = '#';
                                        }
                                    }

                                @endphp

                                <tr>
                                    <td class="p-0">
                                        <a href="javascript:void(0);"
                                            class="d-block px-24 py-12 d-flex align-items-start gap-3 justify-content-between"
                                            onclick="markAsRead('{{ $notification->id }}', '{{ $link }}')">
                                            <div
                                                class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                <img src="{{ $profilePhoto ? asset('storage/' . $profilePhoto) : asset('assets/images/user.png') }}"
                                                    onerror="this.onerror=null; this.src='{{ asset('assets/images/user.png') }}';"
                                                    alt="User Avatar" class="rounded-circle flex-shrink-0" width="60px">

                                                <div>
                                                    <h6 class="text-md fw-semibold mb-4">
                                                        {{ $data['title'] ?? 'Notifikasi Baru' }}
                                                    </h6>
                                                    <p class="mb-0 text-sm text-secondary-light">
                                                        {{ $data['message'] ?? 'Tidak ada pesan' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="text-sm text-secondary-light flex-shrink-0">
                                                {{ $isRead ? 'Telah dibaca' : \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}

                                            </span>
                                        </a>
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
