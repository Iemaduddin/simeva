@extends('layout.landingPageLayout')

@section('title', 'Rincian Aset BMN')

@section('content')
    <style>
        .facility-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Maksimal 2 kolom */
            gap: 8px;
            max-width: 600px;
            /* Batasan lebar */
        }

        .facility-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }


        .accordion-body__desc {
            text-indent: -20px;
            /* Menggeser angka ke kiri */
            padding-left: 30px;
            /* Menjorokkan teks ke dalam */
            display: block;
            /* Pastikan elemen blok agar aturan CSS berfungsi */
        }
    </style>
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Rincian Aset BMN</h1>
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
                                <a href="{{ route('aset-bmn') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    Aset BMN</a>
                            </li>
                            <li class="breadcrumb__item ">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600"> Rincian Aset BMN </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="course-details py-120">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-7">
                    <!-- Simpan assetId dalam elemen hidden -->
                    <input type="hidden" id="asset-id" value="{{ $assetDetails->id }}">

                    @php
                        $asset_images = json_decode($assetDetails->asset_images, true);
                    @endphp
                    <div class="border border-neutral-30 rounded-12 bg-main-25 p-12">
                        <div id="assetCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                            <div class="carousel-inner">
                                @foreach ($asset_images as $asset)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img class="d-block w-100 rounded-12 cover-img"
                                            src="{{ asset('storage/' . $asset) }}" alt="Gambar Aset" style="height: 500px">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Tombol Navigasi -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#assetCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#assetCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <h3 class="mt-24 mb-24">{{ $assetDetails->name }}</h3>
                    <p class="text-neutral-700">{{ $assetDetails->description }}</p>
                    <h5 class="mt-12">Fasilitas</h5>
                    @php
                        $facilityList = explode('|', $assetDetails->facility);
                    @endphp
                    <div class="facility-container">
                        @foreach ($facilityList as $asset_facility)
                            <div class="facility-item">
                                <span class="text-neutral-700 text-2xl d-flex">
                                    <i class="ph-bold ph-check text-main-600"></i>
                                </span>
                                <span class="text-neutral-700 text-md">{{ $asset_facility }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex-align gap-8 mt-20">
                        <span
                            class="badge {{ $assetDetails->status === 'OPEN' ? 'badge-success' : 'badge-danger' }}rounded-10 px-10 py-10 bg-success text-white text-sm fw-bold ">
                            {{ $assetDetails->status }}
                        </span>
                        @if ($assetDetails->available_at)
                            <div class="row">
                                <div class="col-12 fw-bold">Tersedia:</div>
                                <div class="col-12">{{ implode(', ', explode('|', $assetDetails->available_at)) }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="row gy-4 my-20">
                <div class="col-xl-8">
                    <div class="card ">
                        @if ($assetDetails->booking_type === 'annual')
                            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <h5 class="mb-0">Daftar Penggunaan</h5>
                                </div>
                                <button type="button" class="btn btn-sm btn-main rounded-6 d-flex align-items-center gap-2"
                                    data-aos="fade-right" data-bs-toggle="modal" data-bs-target="#event-modal-annual">
                                    <i class="ph-bold ph-plus d-flex text-lg"></i>
                                    Booking Aset Sekarang
                                </button>
                            </div>
                        @endif
                        <div class="card-body p-24">
                            @if ($assetDetails->booking_type === 'daily')
                                <div id="calendar"></div>
                            @else
                                <style>
                                    .table th,
                                    .table td {
                                        text-align: center;
                                        vertical-align: middle;
                                        min-width: 80px;
                                        height: 50px;
                                        border: 1px solid #dee2e6;
                                        background-color: #ffffff;
                                        position: relative;
                                        overflow: visible;
                                    }

                                    .event-wrapper {
                                        position: absolute;
                                        top: 50%;
                                        left: 0;
                                        transform: translateY(-50%);
                                        width: 100%;
                                        height: 24px;
                                        /* sedikit lebih tinggi */
                                        z-index: 2;
                                        /* pointer-events: none; */
                                    }

                                    .bg-color-yellow:hover,
                                    .bg-color-yellow:focus {
                                        background-color: #ffab00 !important;
                                    }

                                    .bg-color-blue:hover,
                                    .bg-color-blue:focus {
                                        background-color: #0066ff !important;

                                    }

                                    .bg-color-light-green:hover,
                                    .bg-color-light-green:focus {
                                        background-color: #22c55e !important;

                                    }

                                    .event-bar {
                                        width: 100%;
                                        height: 100%;
                                        /* border-radius: 4px; */
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 14px;
                                        font-weight: bold;
                                        color: white;
                                        white-space: nowrap;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                        padding: 0 4px;
                                    }
                                </style>


                                <div class="table-responsive">
                                    @php
                                        $bulanList = [
                                            'Jan',
                                            'Feb',
                                            'Mar',
                                            'Apr',
                                            'Mei',
                                            'Jun',
                                            'Jul',
                                            'Ags',
                                            'Sep',
                                            'Okt',
                                            'Nov',
                                            'Des',
                                        ];
                                        $currentYear = \Carbon\Carbon::now()->year;
                                    @endphp

                                    <table id="annual-asset-booking" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                @foreach ($bulanList as $bulan)
                                                    <th>{{ $bulan }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($year = $currentYear; $year < $currentYear + 10; $year++)
                                                @php
                                                    $events = $assetBookings->filter(function ($booking) use ($year) {
                                                        $start = \Carbon\Carbon::parse($booking->usage_date_start);
                                                        $end = \Carbon\Carbon::parse($booking->usage_date_end);
                                                        return $start->year <= $year && $end->year >= $year;
                                                    });

                                                    $rows = []; // untuk baris stacking per tahun

                                                    foreach ($events as $event) {
                                                        $start = \Carbon\Carbon::parse($event->usage_date_start);
                                                        $end = \Carbon\Carbon::parse($event->usage_date_end);

                                                        $startMonth = $start->year < $year ? 1 : $start->month;
                                                        $endMonth = $end->year > $year ? 12 : $end->month;

                                                        $placed = false;
                                                        foreach ($rows as &$row) {
                                                            $conflict = false;
                                                            for ($m = $startMonth; $m <= $endMonth; $m++) {
                                                                if (isset($row[$m])) {
                                                                    $conflict = true;
                                                                    break;
                                                                }
                                                            }
                                                            if (!$conflict) {
                                                                for ($m = $startMonth; $m <= $endMonth; $m++) {
                                                                    $row[$m] = $event;
                                                                }
                                                                $placed = true;
                                                                break;
                                                            }
                                                        }
                                                        unset($row);

                                                        if (!$placed) {
                                                            $newRow = [];
                                                            for ($m = $startMonth; $m <= $endMonth; $m++) {
                                                                $newRow[$m] = $event;
                                                            }
                                                            $rows[] = $newRow;
                                                        }
                                                    }
                                                @endphp

                                                @if (count($rows) > 0)
                                                    @foreach ($rows as $index => $row)
                                                        <tr>
                                                            @if ($index == 0)
                                                                <td rowspan="{{ count($rows) }}">{{ $year }}</td>
                                                            @endif

                                                            @for ($month = 1; $month <= 12; $month++)
                                                                @php
                                                                    if (isset($row[$month])) {
                                                                        $event = $row[$month];
                                                                        $start = \Carbon\Carbon::parse(
                                                                            $event->usage_date_start,
                                                                        );
                                                                        $end = \Carbon\Carbon::parse(
                                                                            $event->usage_date_end,
                                                                        );

                                                                        $startMonth =
                                                                            $start->year < $year ? 1 : $start->month;
                                                                        $endMonth =
                                                                            $end->year > $year ? 12 : $end->month;
                                                                        $colspan = $endMonth - $startMonth + 1;
                                                                    }
                                                                @endphp

                                                                @if (isset($row[$month]))
                                                                    <td colspan="{{ $colspan }}">
                                                                        <div class="event-wrapper">
                                                                            <button
                                                                                class="event-bar btn btn-sm
                                            @if (str_contains($event->status, 'submission') || str_contains($event->status, 'rejected_booking')) bg-color-yellow
                                            @elseif (str_contains($event->status, 'booked')) bg-color-blue
                                            @elseif (str_contains($event->status, 'approved_full_payment')) bg-color-light-green @endif"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#detail-booking-asset-annual"
                                                                                data-usage-event-name="{{ $event->usage_event_name }}"
                                                                                data-usage-date-start="{{ $event->usage_date_start }}"
                                                                                data-usage-date-end="{{ $event->usage_date_end }}"
                                                                                data-status="{{ $event->status }}"
                                                                                data-user-name="{{ $event->user->name ?? $event->external_user }}">
                                                                                {{ $event->usage_event_name }}
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                    @php $month += ($colspan - 1); @endphp
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                            @endfor
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>{{ $year }}</td>
                                                        @for ($month = 1; $month <= 12; $month++)
                                                            <td></td>
                                                        @endfor
                                                    </tr>
                                                @endif
                                            @endfor
                                        </tbody>
                                    </table>

                                </div>


                            @endif
                            <div class="row my-24 mx-16 d-flex justify-content-around">
                                <div class="col-xl-12">
                                    <h5>Status Peminjaman:</h5>
                                </div>

                                @php
                                    $statuses = [
                                        ['color' => 'bg-color-blue', 'text' => 'Sudah Dipesan (Booked)'],
                                        ['color' => 'bg-color-yellow', 'text' => 'Proses Pengajuan (Submission)'],
                                        ['color' => 'bg-color-light-green', 'text' => 'Disetujui (Approved)'],
                                    ];
                                @endphp

                                @foreach ($statuses as $status)
                                    <div class="col-xl-4 d-flex align-items-center gap-8">
                                        <div
                                            class="w-16 h-16 border border-2 border-white {{ $status['color'] }} rounded-circle">
                                        </div>
                                        <h6 class="m-0">{{ $status['text'] }}</h6>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="accordion common-accordion" id="accordionExampleOne">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#borrowingProcedures" aria-expanded="true"
                                    aria-controls="borrowingProcedures">Tata Cara Peminjaman</button>
                            </h2>
                            @php
                                $procedures = [
                                    'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellendus pariatur eaque beatae culpa totam, magni animi perferendis vero nemo cum.',
                                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae sint dolores deleniti ab odio corrupti aut numquam commodi eius odit.',
                                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum aliquam nulla sapiente, quibusdam ex eos molestias facere incidunt a quasi!',
                                    'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Et quis cupiditate iusto incidunt distinctio dolores quas rerum, non totam iste molestiae recusandae aliquam, adipisci odit!',
                                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi magnam nam quod tempore ipsum repellendus.',
                                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae quas debitis ducimus hic repellendus. Unde repellat nihil magni quos, laboriosam adipisci cupiditate iure excepturi sit minima in debitis deserunt minus beatae quam fuga quo. Voluptas minima atque ut blanditiis corporis!',
                                ];
                            @endphp
                            <div id="borrowingProcedures" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionExampleOne">
                                <div class="accordion-body">
                                    @foreach ($procedures as $procedure)
                                        <p class="accordion-body__desc">{{ $loop->iteration }}. {{ $procedure }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-24">PERHATIAN!
                    </h5>
                    <p class="text-neutral-700">Untuk pihak internal Polinema (Staff, Dosen, dan Organisasi Kemahasiswaan
                        Intra) dapat melakukan
                        peminjaman menggunakan akun yang telah disediakan.</p>
                </div>
            </div>
        </div>
    </section>
    @auth
        @if (auth()->user()->getRoleNames()->first() == 'Tenant')
            <div id="event-modal-annual" class="modal fade" tabindex="-1" aria-labelledby="modal-title"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-10">
                        <div class="modal-header">
                            <h5 id="modal-title" class="modal-title">Lengkapi data berikut</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('asset.booking.tenant', $assetDetails->id) }}" class="needs-validation"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="booking_type_annual" value="annual">
                                <input type="hidden" name="asset_id" value="{{ $assetDetails->id }}">

                                <div class="row gy-4">
                                    <div class="col-md-4">
                                        <label for="usage_event_name"
                                            class="text-neutral-700 text-lg fw-medium mb-12">Keterangan
                                            Penggunaan
                                            <span class="text-danger-600">*</span> </label>
                                        <input type="text"
                                            class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                            name="usage_event_name" placeholder="Masukkan Keterangan Penggunaan Aset"
                                            required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="usage_date_display" class="text-neutral-700 text-lg fw-medium mb-12">
                                            Mulai Tanggal Sewa <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="text"
                                            class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                            id="usage_date_annual" name="usage_date_start" placeholder="Pilih Tanggal">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="usage_event_name" class="text-neutral-700 text-lg fw-medium mb-12">Durasi
                                            Sewa
                                            (Tahun)
                                            <span class="text-danger-600">*</span> </label>
                                        <input type="number"
                                            class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                            name="duration" value="1" placeholder="Masukkan Durasi Sewa Aset" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="file_personal_identity"
                                            class="text-neutral-700 text-lg fw-medium mb-24">Scan
                                            KTP (.pdf)
                                            <span class="text-danger-600">*</span> </label>
                                        <input type="file" accept=".pdf" name="file_personal_identity"
                                            class="form-control border-transparent focus-border-main-600"
                                            id="file_personal_identity" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-neutral-700 text-lg fw-medium mb-24">Jenis Pembayaran
                                            <span class="text-danger-600">*</span> </label>
                                        <div class="flex-align gap-24">
                                            <div class="form-check common-check common-radio mb-0">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                    id="DP" value="dp" required>
                                                <label class="form-check-label fw-normal flex-grow-1" for="DP">DP
                                                    (30%)</label>
                                            </div>
                                            <div class="form-check common-check common-radio mb-0">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                    id="Lunas" value="lunas" required>
                                                <label class="form-check-label fw-normal flex-grow-1"
                                                    for="Lunas">Lunas</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-sm-12">
                                        <label class="text-success-700 text-lg fw-bold mb-12">Info Tarif:</label>

                                        <!-- Periode Sewa -->
                                        <div class="row mb-12">
                                            <div class="col-12">
                                                <p id="rental_period" class="text-neutral-700 text-md"> <strong>Periode
                                                        : </strong> -</p>
                                            </div>
                                        </div>

                                        <!-- Harga DP & Lunas -->
                                        <div class="row justify-content-start">
                                            <div class="col-sm-6">
                                                <h6 class="fw-bold">Pembayaran secara DP</h6>
                                                <p id="dp_price_annual">DP 30% : Rp0</p>
                                                <p id="remaining_price_annual">Pelunasan : Rp0</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <h6 class="fw-bold">Pembayaran secara Lunas</h6>
                                                <input type="hidden" name="amount" id="amount_annual">
                                                <p id="full_price_annual">Pelunasan : Rp0</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-main">
                                    Ajukan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div id="event-modal-annual" class="modal fade" tabindex="-1" aria-labelledby="modal-title"
                aria-hidden="true">
                <div class="modal-dialog modala-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center my-20">
                            <h5 class="my-25">Maaf, Anda tidak memiliki akses untuk melakukan peminjaman aset</h5>
                            <p>Silahkan mendaftar pada sistem sebagai tenant.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div id="event-modal-annual" class="modal fade" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modala-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center my-20">
                        <h5 class="my-25">Harap Register/Login terlebih dahulu</h5>
                        <a href="{{ route('showLoginPage') }}" class="btn btn-primary rounded-pill">Login</a>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    <div class="modal fade" id="not-available-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Peminjaman Tidak Tersedia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-neutral-700">Maaf, Peminjaman Aset {{ $assetDetails->name }} tidak dapat dilakukan pada
                        hari yang dipilih.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="status-closed-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Peminjaman Tidak Tersedia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-neutral-700">Maaf, Aset {{ $assetDetails->name }} saat ini sedang tidak tersedia.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal untuk detail event (Sewa Harian) -->
    <div class="modal fade" id="event-details-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-4 fw-bold">Status</div>
                        <div class="col-8 ps-2">
                            <span class="event-status"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-4 fw-bold">Pengguna</div>
                        <div class="col-8 ps-2">
                            <span class="event-user-category"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-4 fw-bold">Tgl. Penggunaan</div>
                        <div class="col-8 ps-2">
                            <span class="usage-date"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-4 fw-bold">Tgl. Loading</div>
                        <div class="col-8 ps-2">
                            <span class="loading-date"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk detail penggunaan aset (Sewa Tahunan) -->
    <div class="modal fade" id="detail-booking-asset-annual" tabindex="-1" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rincian Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-5 fw-bold">Pengguna</div>
                        <div class="col-7 ps-2">
                            <span id="modal-user-name"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-5 fw-bold">Penggunaan Tempat</div>
                        <div class="col-7 ps-2">
                            <span id="modal-usage-event-name"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-5 fw-bold">Periode Sewa</div>
                        <div class="col-7 ps-2">
                            <span id="modal-usage-date"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-5 fw-bold">Status</div>
                        <div class="col-7 ps-2">
                            <span id="modal-status"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>

    <script>
        flatpickr("#usage_date_annual", {
            dateFormat: "Y-m-d",
            minDate: "today",
        });

        const bookingModal = document.getElementById('detail-booking-asset-annual');

        bookingModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Tombol yang membuka modal

            // Ambil data-* dari button
            const usageEventName = button.getAttribute('data-usage-event-name');
            const usageDateStart = button.getAttribute('data-usage-date-start');
            const usageDateEnd = button.getAttribute('data-usage-date-end');
            const status = button.getAttribute('data-status');
            const userName = button.getAttribute('data-user-name');

            const startDate = new Date(usageDateStart);
            const endDate = new Date(usageDateEnd);

            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const periode =
                `${startDate.toLocaleDateString('id-ID', options)} – ${endDate.toLocaleDateString('id-ID', options)}`;

            let statusText;
            if (status.includes('submission')) {
                statusText = 'Proses Pengajuan';
            } else if (status === 'booked') {
                statusText = 'Booking Disetujui';

            } else {
                statusText = 'Sewa Disetujui';

            }
            // Masukkan ke dalam modal
            document.getElementById('modal-usage-event-name').textContent = usageEventName;
            document.getElementById('modal-usage-date').textContent = periode;
            document.getElementById('modal-status').textContent = statusText;
            document.getElementById('modal-user-name').textContent = userName;
        });
    </script>
    {{-- <script>
        const assetId = '{{ $assetDetails->id }}';
        $(document).ready(function() {
            $('#annual-asset-booking').DataTable({
                // processing: true,
                serverSide: true,
                searching: false,
                ordering: false,
                paging: false,
                info: false,
                lengthChange: false,
                ajax: {
                    url: "{{ route('asset-booking.getAnnualAssetBooking', ':assetId') }}".replace(
                        ':assetId', assetId),
                    type: 'GET',
                },
                columns: [{
                        data: 'year',
                        name: 'year',
                    },
                    @for ($month = 1; $month <= 12; $month++)
                        {
                            data: 'month_{{ $month }}',
                            name: 'month_{{ $month }}',
                            render: function(data, type, row, meta) {
                                if (!data) {
                                    return '';
                                }

                                let btnClass = '';
                                if (data.status.includes('submission')) {
                                    btnClass = 'bg-color-yellow';
                                } else if (data.status.includes('booked')) {
                                    btnClass = 'bg-color-blue';
                                } else if (data.status.includes('approved_full_payment')) {
                                    btnClass = 'bg-color-light-green';
                                }

                                return `
                            <div class="event-wrapper">
                                <button 
                                    class="event-bar btn btn-sm ${btnClass}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detail-booking-asset-annual"
                                    data-id="${data.id}"
                                >
                                    ${data.usage_event_name}
                                </button>
                            </div>
                        `;
                            }
                        },
                    @endfor
                ],
            });
        });
    </script> --}}



    <script>
        $(document).ready(function() {
            let assetIdAnnual = $("#asset-id").val();
            let durationInput = $("input[name='duration']");
            let usageDateInput = $("#usage_date_annual");
            let externalPrice = 0; // Harga per tahun
            let startDate = null; // Tanggal mulai sewa

            function calculateAnnualPrice() {
                let duration = parseInt(durationInput.val()) || 1; // default 1 kalau kosong
                if (externalPrice > 0 && startDate) {
                    let fullPrice = externalPrice * duration;
                    let dpPrice = fullPrice * 0.3;
                    let remainingPrice = fullPrice - dpPrice;

                    $("#dp_price_annual").text(`DP 30% : Rp${dpPrice.toLocaleString()}`);
                    $("#remaining_price_annual").text(`Pelunasan : Rp${remainingPrice.toLocaleString()}`);
                    $("#full_price_annual").text(`Pelunasan : Rp${fullPrice.toLocaleString()}`);

                    $('#amount_annual').val(fullPrice);

                    // Hitung tanggal selesai (menambah tahun sesuai durasi)
                    let endDate = new Date(startDate);
                    endDate.setFullYear(endDate.getFullYear() + duration);

                    // Format tanggal ke bahasa Indonesia
                    let startDateFormatted = startDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    let endDateFormatted = endDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    // Tampilkan periode sewa
                    $('#rental_period').html(
                        `<strong>Periode :</strong> ${startDateFormatted} - ${endDateFormatted}`);
                }
            }

            function loadFirstCategoryPrice() {
                $.ajax({
                    url: "{{ route('asset-booking.getDataCategory', '') }}" + "/" + assetIdAnnual,
                    type: "GET",
                    success: function(response) {
                        if (response.data.length > 0) {
                            externalPrice = parseFloat(response.data[0].external_price) || 0;
                            calculateAnnualPrice(); // Hitung langsung setelah dapat harga
                        }
                    }
                });
            }

            usageDateInput.change(function() {
                let selectedDate = new Date(usageDateInput.val());
                if (!isNaN(selectedDate)) {
                    startDate = selectedDate;
                    calculateAnnualPrice();
                }
            });

            durationInput.on('input', function() {
                calculateAnnualPrice();
            });

            // Load harga saat halaman load
            loadFirstCategoryPrice();
        });
    </script>

    @if ($assetDetails->booking_type === 'daily')
        @include('components.script-crud')
    @endif
    @include('components.calendar')
@endpush
