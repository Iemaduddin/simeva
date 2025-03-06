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
                        $facilityList = explode(',', $assetDetails->facility);
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
                        <div class="row">
                            <div class="col-12 fw-bold">Tersedia:</div>
                            <div class="col-12">{{ $assetDetails->available_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row gy-4 my-20">
                <div class="col-xl-8">
                    <div class="card ">
                        <div class="card-body p-24">
                            <div id="calendar"></div>
                            <div class="row my-24 mx-16 d-flex justify-content-around">
                                <div class="col-xl-12">
                                    <h5>Status Peminjaman:</h5>
                                </div>

                                @php
                                    $statuses = [
                                        ['color' => 'bg-color-info', 'text' => 'Sudah Dipesan (Booked)'],
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
    <div class="modal fade" id="not-available-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Peminjaman Tidak Tersedia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Maaf, Peminjaman Aset {{ $assetDetails->name }} tidak dapat dilakukan pada hari yang dipilih.</p>
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
                    <p>Maaf, Aset {{ $assetDetails->name }} saat ini sedang tidak tersedia.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    @include('components.script-crud')
    @include('components.calendar')
@endpush
