@extends('layout.landingPageLayout')

@section('title', 'Booking Aset')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Booking Aset</h1>
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
                                <span class="text-main-two-600">Daftar Booking Aset </span>
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
                <h5 class="card-title my-10 align-content-center">Daftar Peminjaman Aset</h5>
            </div>
            @php
                $status_booking = [
                    'waiting_booking' => ['label' => 'Menunggu Konfirmasi Booking', 'icon' => 'ph-clock'],
                    'payment' => ['label' => 'Perlu Dibayar', 'icon' => 'ph-credit-card'],
                    'waiting_payment' => ['label' => 'Menunggu Konfirmasi Pembayaran', 'icon' => 'ph-hourglass'],
                    'approved' => ['label' => 'Disetujui', 'icon' => 'ph-check-circle'],
                    'done' => ['label' => 'Selesai', 'icon' => 'ph-check'],
                    'rejected' => ['label' => 'Ditolak', 'icon' => 'ph-x-circle'],
                    'cancelled' => ['label' => 'Dibatalkan', 'icon' => 'ph-prohibit'],
                ];

                // Mengelompokkan data berdasarkan status
                $groupedBookings = [
                    'waiting_booking' => $myAsset->where('status', 'submission_booking'),
                    'waiting_payment' => $myAsset->whereIn('status', [
                        'submission_dp_payment',
                        'submission_full_payment',
                    ]),
                    'payment' => $myAsset->whereIn('status', ['booked', 'approved_dp_payment']),
                    'approved' => $myAsset->where('status', 'approved_full_payment'),
                    'done' => $myAsset
                        ->where('status', 'approved_full_payment')
                        ->filter(fn($booking) => \Carbon\Carbon::parse($booking->usage_date_end)->isPast()),
                    'rejected' => $myAsset->whereIn('status', [
                        'rejected_booking',
                        'rejected_dp_payment',
                        'rejected_full_payment',
                        'rejected',
                    ]),
                    'cancelled' => $myAsset->where('status', 'cancelled'),
                ];
            @endphp
            <div class="nav-tab-wrapper bg-white p-16" data-aos="zoom-out">
                <ul class="nav nav-pills common-tab gap-16" id="pills-tab" role="tablist">
                    @foreach ($status_booking as $category_status => $data)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link tab-asset-booking rounded-pill bg-main-25 text-md fw-medium text-neutral-500 flex-center w-100 gap-8  @if ($loop->first) active @endif"
                                id="pills-{{ $category_status }}-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-{{ $category_status }}" type="button" role="tab"
                                aria-controls="pills-{{ $category_status }}" aria-selected="true"
                                data-status-booking="{{ $category_status }}">
                                <i class="text-xl d-flex ph-bold {{ $data['icon'] }}"></i>
                                {{ $data['label'] }}
                                <span
                                    class="badge bg-danger text-white px-5 py-4 rounded-pill">{{ $groupedBookings[$category_status]->count() }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-body bg-main-25">
                <div class="tab-content" id="pills-tabContent">
                    @foreach ($status_booking as $category_status => $data)
                        <div class="tab-pane fade @if ($loop->first) show active @endif"
                            id="pills-{{ $category_status }}" role="tabpanel"
                            aria-labelledby="pills-{{ $category_status }}-tab" tabindex="0">
                            <div class="table-responsive overflow-x-auto">
                                <table class="table min-w-max vertical-middle mb-0 w-100"
                                    id="assetBookingProfile-{{ $category_status }}">
                                    <thead>
                                        <tr>
                                            <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Nama Aset</th>
                                            <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Waktu Pemakaian
                                            </th>
                                            <th class="text-neutral-500 fw-semibold px-24 py-20 border-0">Aksi</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @foreach ($groupedBookings[$category_status] as $assetBooking)
                                            <tr>
                                                <td
                                                    class="border-bottom border-dashed border-neutral-40 text-neutral-500 bg-transparent px-24 py-20">
                                                    <div class="d-flex align-items-center gap-24">
                                                        <div
                                                            class="w-60 h-60 border border-neutral-40 rounded-8 d-flex justify-content-center align-items-center bg-white">
                                                            @php
                                                                $asset_images = json_decode(
                                                                    $assetBooking->asset->asset_images,
                                                                    true,
                                                                );
                                                            @endphp
                                                            <img src="{{ isset($asset_images[0]) ? asset('storage/' . $asset_images[0]) : '' }}"
                                                                alt="">
                                                        </div>
                                                        <div>
                                                            <h6 class="text-md mb-12">
                                                                {{ optional($assetBooking->asset)->name }}</h6>
                                                            <div class="d-flex align-items-center gap-16">
                                                                <div class="d-flex align-items-center gap-4">
                                                                    <span class="text-xs text-neutral-500">Event:</span>
                                                                    <span
                                                                        class="p-5 border border-neutral-40 bg-white rounded-4 text-sm text-neutral-500">
                                                                        {{ $assetBooking->usage_event_name }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="border-bottom border-dashed border-neutral-40 text-neutral-500 bg-transparent px-24 py-20">
                                                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_start)->format('d-M-Y H.i') }}
                                                    - <br>
                                                    {{ \Carbon\Carbon::parse($assetBooking->usage_date_end)->format('d-M-Y H.i') }}
                                                </td>
                                                <td
                                                    class="border-bottom border-dashed border-neutral-40 text-neutral-500 bg-transparent px-24 py-20">
                                                    <div class="d-flex gap-8">
                                                        <a class='btn btn-sm btn-outline-danger cursor-pointer d-inline-flex align-items-center justify-content-center'
                                                            data-bs-toggle='modal'
                                                            data-bs-target="#modalSubmissionAssetBooking-{{ $assetBooking->id }}-{{ $category_status }}">
                                                            RIncian
                                                        </a>
                                                        @if ($assetBooking->status === 'rejected_booking')
                                                            <a class='btn btn-sm btn-outline-main cursor-pointer d-inline-flex align-items-center justify-content-center'
                                                                data-bs-toggle='modal'
                                                                data-bs-target="#modalSubmissionAssetBooking-{{ $assetBooking->id }}-{{ $category_status }}">
                                                                🔄 Ajukan Ulang Booking
                                                            </a>
                                                            @include('dashboardPage.assets.asset-booking.modal.resubmissionBooking')
                                                        @elseif ($assetBooking->status === 'booked')
                                                            <a class="btn btn-sm btn-outline-main cursor-pointer d-inline-flex align-items-center justify-content-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalpayAndCompleteFile-{{ $assetBooking->id }}-{{ $category_status }}">
                                                                Bayar
                                                                {{ $assetBooking->payment_type === 'dp' ? 'DP' : '' }}
                                                                dan Lengkapi Berkas
                                                            </a>
                                                            @include('dashboardpage.assets.asset-booking.modal.payAndCompleteFile')
                                                        @elseif ($assetBooking->status === 'rejected_dp_payment')
                                                            <a class="btn btn-sm btn-outline-main cursor-pointer d-inline-flex align-items-center justify-content-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalpayAndCompleteFile-{{ $assetBooking->id }}-{{ $category_status }}">
                                                                📤 Upload Ulang Bukti Pembayaran dan Berkas
                                                            </a>
                                                            @include('dashboardpage.assets.asset-booking.modal.payAndCompleteFile')
                                                        @elseif ($assetBooking->status === 'rejected_full_payment')
                                                            <a class="btn btn-sm btn-outline-main cursor-pointer d-inline-flex align-items-center justify-content-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalpayInFull-{{ $assetBooking->id }}-{{ $category_status }}">
                                                                📤 Upload Ulang Bukti Pembayaran dan Berkas
                                                            </a>
                                                            @include('dashboardpage.assets.asset-booking.modal.payInFull')
                                                        @elseif ($assetBooking->status === 'approved_dp_payment')
                                                            <a class="btn btn-sm btn-outline-main cursor-pointer d-inline-flex align-items-center justify-content-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalpayInFull-{{ $assetBooking->id }}-{{ $category_status }}">
                                                                Bayar Pelunasan
                                                            </a>
                                                            @include('dashboardpage.assets.asset-booking.modal.payInFull')
                                                        @elseif ($assetBooking->status === 'approved_full_payment')
                                                            <a class="btn btn-sm btn-outline-main cursor-pointer d-inline-flex align-items-center justify-content-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalConfirmAssetBooking-' . {{ $assetBooking->id }} . '">
                                                                ⬇️ Surat Disposisi
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            const user_id = "{{ $user->id }}";
            let loadedTabs = {};

            function loadTable(statusBooking) {
                if (!loadedTabs[statusBooking]) {
                    $(`#assetBookingProfile-${statusBooking}`).DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('profile.getDataMyAssetBooking', ':userId') }}".replace(
                                ':userId', user_id),
                            type: 'GET',
                            data: {
                                status_booking: statusBooking
                            }
                        },
                        columns: [{
                                data: "nama_aset",
                                name: "nama_aset"
                            },
                            {
                                data: "waktu_pemakaian",
                                name: "waktu_pemakaian"
                            },
                            {
                                data: "aksi",
                                name: "aksi",
                                orderable: false,
                                searchable: false
                            },
                        ],
                        createdRow: function(row, data, dataIndex) {
                            $(row).find('td').addClass(
                                'border-bottom border-dashed border-neutral-40 text-neutral-500 bg-transparent px-24 py-20'
                            ); // Tambahkan class ke semua kolom dalam baris
                        }

                    });
                    loadedTabs[statusBooking] = true;
                }
            }
            $('.tab-asset-booking').on('click', function() {
                let statusBooking = $(this).data('status-booking');
                if (!statusBooking) return;
                loadTable(statusBooking);
            })

            // Muat Tab pertama saat halaman dibuka
            let firstTab = $('.tab-asset-booking.active');
            if (firstTab.length > 0) {
                let firstStatusBooking = firstTab.data('status-booking');
                loadTable(firstStatusBooking);
            }

        });
    </script>

    @include('components.script-crud')
@endpush
