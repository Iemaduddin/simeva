@extends('layout.landingPageLayout')
@section('title', 'Kalender')
@php
    $title = 'Calendar';
    $subTitle = 'Components / Calendar';
@endphp

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
                <div class="col-lg-9">
                    <div class="breadcrumb__wrapper">
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Kalender Politeknik Negeri Malang
                        </h1>
                        <ul class="breadcrumb__list d-flex align-items-center justify-content-center gap-4">
                            <li class="breadcrumb__item">
                                <a href="{{ route('home') }}"
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium">
                                    <i class="text-lg d-inline-flex ph-bold ph-house"></i> Beranda</a>
                            </li>
                            <li class="breadcrumb__item ">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600">Kalender </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->
    <div class="container">
        <div class="row gy-4 my-24">
            <div class="col-xxl-2">
                <div class="card h-100 p-0">
                    <div class="card-body p-24">
                        <h5>Filter</h5>
                        <div class="d-flex flex-column gap-16">
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="All" id="category-all" checked>
                                    <label class="form-check-label fw-bold text-dark flex-grow-1"
                                        for="category-all">All</label>
                                </div>
                            </div>
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="Seminar" id="category-seminar" checked>
                                    <label class="form-check-label fw-bold text-danger-700 flex-grow-1"
                                        for="category-seminar">Seminar</label>
                                </div>
                            </div>
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="Kuliah Tamu" id="category-kuliah-tamu" checked>
                                    <label class="form-check-label fw-bold text-success-700 flex-grow-1"
                                        for="category-kuliah-tamu">Kuliah Tamu</label>
                                </div>
                            </div>
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="Pelatihan" id="category-pelatihan" checked>
                                    <label class="form-check-label fw-bold text-primary-700 flex-grow-1"
                                        for="category-pelatihan">Pelatihan</label>
                                </div>
                            </div>
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="Workshop" id="category-workshop" checked>
                                    <label class="form-check-label fw-bold text-info-700 flex-grow-1"
                                        for="category-workshop">Workshop</label>
                                </div>
                            </div>
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="Kompetisi" id="category-kompetisi" checked>
                                    <label class="form-check-label fw-bold text-warning-700 flex-grow-1"
                                        for="category-kompetisi">Kompetisi</label>
                                </div>
                            </div>
                            <div class="flex-between gap-16">
                                <div class="form-check common-check mb-0">
                                    <input class="form-check-input category-filter" type="checkbox" name="category[]"
                                        value="Lainnya" id="category-lainnya" checked>
                                    <label class="form-check-label fw-bold text-purple-700 flex-grow-1"
                                        for="category-lainnya">Lainnya</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="col-xxl-10 col-lg-8">
                <div class="card h-100 p-0">
                    <div class="card-body p-24">
                        <div class="calendar-wrapper" data-context="homepage">
                            <div id='calendar'></div>
                            <div style='clear:both'></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="editCalendarEvent" tabindex="-1" aria-labelledby="editCalendarEventLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="editCalendarEvent">Detail Event</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-24">
                    <div class="mb-12">
                        <span class="text-secondary-light txt-sm fw-medium">Nama Event:</span>
                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4 detail-event-title"></h6>
                    </div>
                    <div class="mb-12">
                        <span class="text-secondary-light txt-sm fw-medium">Tanggal Pelaksanaan:</span>
                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4 detail-event-date"></h6>
                    </div>
                    <div class="mb-12">
                        <span class="text-secondary-light txt-sm fw-medium">Dibuat oleh:</span>
                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4 detail-event-created-by"></h6>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Seminar → danger */
        .fc-event.bg-danger-600 {
            background-color: var(--danger-100) !important;
            border-color: var(--danger-200) !important;
            color: var(--danger-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Kuliah Tamu → success */
        .fc-event.bg-success-600 {
            background-color: var(--success-100) !important;
            border-color: var(--success-200) !important;
            color: var(--success-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Pelatihan → primary */
        .fc-event.bg-primary-600 {
            background-color: var(--primary-100) !important;
            border-color: var(--primary-200) !important;
            color: var(--primary-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Workshop → info */
        .fc-event.bg-info-600 {
            background-color: var(--info-100) !important;
            border-color: var(--info-200) !important;
            color: var(--info-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Kompetisi → warning */
        .fc-event.bg-warning-600 {
            background-color: var(--warning-100) !important;
            border-color: var(--warning-200) !important;
            color: var(--warning-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Lainnya → neutral */
        .fc-event.bg-purple-600 {
            background-color: var(--purple-50) !important;
            border-color: var(--purple-100) !important;
            color: var(--purple-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
@push('script')
    @include('components.calendar-event')
@endpush
