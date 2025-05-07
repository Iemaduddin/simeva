@extends('layout.layout')
@section('title', 'Calendar Event Management')
@php
    $title = 'Calendar Event Management';
    $subTitle = 'Calendar Event Management';
@endphp

@section('content')
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
            background-color: var(--lilac-50) !important;
            border-color: var(--lilac-100) !important;
            color: var(--lilac-700) !important;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <div class="row gy-4">
        <div class="col-xxl-2 col-lg-4">
            <div class="card h-100 p-0">
                <div class="card-body p-24">
                    <button type="button"
                        class="btn btn-primary text-sm btn-sm px-12 py-12 w-100 radius-8 d-flex align-items-center gap-2 mb-32"
                        data-bs-toggle="modal" data-bs-target="#addMultiCalendarEvent">
                        <iconify-icon icon="fa6-regular:square-plus" class="icon text-lg line-height-1"></iconify-icon>
                        Tambah Event
                    </button>
                    <hr>
                    <h6 class="my-10">Filter</h6>
                    <hr>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border input-form-dark me-2 my-3"
                            type="checkbox" name="checkbox" value="All" id="category-all" checked>
                        <label class="form-check-label text-dark" for="category-all">All</label>
                    </div>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border border-neutral-400 me-2 my-3"
                            type="checkbox" name="category[]" value="Seminar" id="category-seminar" checked>
                        <label class="form-check-label text-danger-700" for="category-seminar">Seminar</label>
                    </div>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border border-neutral-400 me-2 my-3"
                            type="checkbox" name="category[]" value="Kuliah Tamu" id="category-kuliah-tamu" checked>
                        <label class="form-check-label text-success-700" for="category-kuliah-tamu">Kuliah Tamu</label>
                    </div>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border border-neutral-400 me-2 my-3"
                            type="checkbox" name="category[]" value="Pelatihan" id="category-pelatihan" checked>
                        <label class="form-check-label text-primary-700" for="category-pelatihan">Pelatihan</label>
                    </div>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border border-neutral-400 me-2 my-3"
                            type="checkbox" name="category[]" value="Workshop" id="category-workshop" checked>
                        <label class="form-check-label text-info-700" for="category-workshop">Workshop</label>
                    </div>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border border-neutral-400 me-2 my-3"
                            type="checkbox" name="category[]" value="Kompetisi" id="category-kompetisi" checked>
                        <label class="form-check-label text-warning-700" for="category-kompetisi">Kompetisi</label>
                    </div>
                    <div class="form-check style-check d-flex align-items-center mb-2">
                        <input class="form-check-input category-filter radius-4 border border-neutral-400 me-2 my-3"
                            type="checkbox" name="category[]" value="Lainnya" id="category-lainnya" checked>
                        <label class="form-check-label text-purple" for="category-lainnya">Lainnya</label>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xxl-10 col-lg-8">
            <div class="card h-100 p-0">
                <div class="card-body p-24">

                    <div class="calendar-wrapper" data-context="dashboard">
                        <div id='calendar'></div>
                        <div style='clear:both'></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Event Multi Day -->
    @include('dashboardPage.calendars.modal.add-event-multiday')

    <!-- Modal Add Event -->
    @include('dashboardPage.calendars.modal.add-event')

    <!-- Modal Edit Event -->
    @include('dashboardPage.calendars.modal.update-event')

    <!-- Modal Edit Event Multi Day -->
    @include('dashboardPage.calendars.modal.update-event-multiday')


    <!-- Modal Delete Event -->
    <div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-body p-24 text-center">
                    <span class="mb-16 fs-1 line-height-1 text-danger">
                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                    </span>
                    <h6 class="text-lg fw-semibold text-primary-light mb-0">Apakah Anda yakin ingin menghapus event ini
                        pada kalender?
                    </h6>
                    <form class="form-event-calendar" id="idDeleteEvent"
                        data-original-action="{{ route('destroy.calendarEvent', ':id') }}"
                        action="{{ route('destroy.calendarEvent', ':id') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                            <button type="button" data-bs-dismiss="modal"
                                class="w-50 btn btn-outline-neutral-900 text-md px-40 py-11 radius-8">
                                Batal
                            </button>
                            <button type="submit"
                                class="w-50 btn btn-danger border border-danger text-md px-24 py-12 radius-8">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    @include('components.calendar-event')
    <script>
        flatpickr(".event_date_start", {
            dateFormat: "Y-m-d",
            minDate: "today",
            enableTime: false,
        });
        flatpickr(".event_date_end", {
            dateFormat: "Y-m-d",
            minDate: "today",
            enableTime: false,
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                const allDayCheckbox = form.querySelector('.all_day');
                const timeStart = form.querySelector('.time_start');
                const timeEnd = form.querySelector('.time_end');

                if (!allDayCheckbox || !timeStart || !timeEnd) return; // skip jika elemen tidak ada

                const fpStart = flatpickr(timeStart, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                });

                const fpEnd = flatpickr(timeEnd, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                });



                const disableTime = allDayCheckbox.checked;
                timeStart.disabled = disableTime;
                timeEnd.disabled = disableTime;

                allDayCheckbox.addEventListener('change', function() {
                    const disableTime = this.checked;
                    timeStart.disabled = disableTime;
                    timeEnd.disabled = disableTime;
                });
            });
        });
    </script>
@endpush
