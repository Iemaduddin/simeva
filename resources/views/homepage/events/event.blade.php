@extends('layout.landingPageLayout')

@section('title', 'Event')
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
                        <h1 class="breadcrumb__title display-4 fw-semibold text-center"> Daftar Event</h1>
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
                                    class="breadcrumb__link text-neutral-500 hover-text-main-600 fw-medium"> Event</a>
                            </li>
                            <li class="breadcrumb__item ">
                                <i class="text-neutral-500 d-flex ph-bold ph-caret-right"></i>
                            </li>
                            <li class="breadcrumb__item">
                                <span class="text-main-two-600"> Daftar Event </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->
    <!-- ============================== Course List View Section Start ============================== -->
    <section class="course-list-view py-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="sidebar rounded-12 bg-main-25 p-32 border border-neutral-30">
                        <form id="event-filter-form">
                            <div class="flex-between mb-24">
                                <h4 class="mb-0">Filter</h4>
                                <button type="button"
                                    class="sidebar-close text-xl text-neutral-500 d-lg-none hover-text-main-600">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            </div>
                            <h6 class="text-lg mb-24 fw-medium">Lingkup Event</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check common-radio mb-0">
                                        <input class="form-check-input" type="radio" name="scope" id="all-scope"
                                            value="all" checked>
                                        <label class="form-check-label fw-normal flex-grow-1" for="all-scope">Semua
                                            Lingkup</label>
                                    </div>
                                    <span class="text-neutral-500">{{ $allEvents->count() }}</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check common-radio mb-0">
                                        <input class="form-check-input" type="radio" name="scope" id="umum-scope"
                                            value="Umum">
                                        <label class="form-check-label fw-normal flex-grow-1" for="umum-scope">Umum</label>
                                    </div>
                                    <span class="text-neutral-500">{{ $allEvents->where('scope', 'Umum')->count() }}</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check common-radio mb-0">
                                        <input class="form-check-input" type="radio" name="scope" id="campus-scope"
                                            value="Internal Kampus">
                                        <label class="form-check-label fw-normal flex-grow-1" for="campus-scope">Internal
                                            Kampus</label>
                                    </div>
                                    <span
                                        class="text-neutral-500">{{ $allEvents->where('scope', 'Internal Kampus')->count() }}</span>
                                </div>
                                @foreach ($jurusans as $jurusan)
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check common-radio mb-0">
                                            <input class="form-check-input" type="radio" name="scope"
                                                value="{{ $jurusan->id }}" id="jurusan-{{ $jurusan->id }}">
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="jurusan-{{ $jurusan->id }}">Internal {{ $jurusan->nama }}</label>
                                        </div>
                                        @php
                                            $count = $allEvents
                                                ->filter(function ($event) use ($jurusan) {
                                                    return $event->scope === 'Internal Jurusan' &&
                                                        $event->organizers &&
                                                        $event->organizers->user &&
                                                        $event->organizers->user->jurusan_id == $jurusan->id;
                                                })
                                                ->count();
                                        @endphp
                                        <span class="text-neutral-500">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>
                            <h6 class="text-lg mb-24 fw-medium">Penyelenggara Event</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check common-radio mb-0">
                                        <input class="form-check-input" type="radio" name="organizer" id="all-organizer"
                                            value="all" checked>
                                        <label class="form-check-label fw-normal flex-grow-1" for="all-organizer">Semua
                                            Penyelenggara</label>
                                    </div>
                                    <span class="text-neutral-500">{{ $allEvents->count() }}</span>
                                </div>
                                @foreach ($organizers as $orgId => $orgName)
                                    <div
                                        class="flex-between gap-16 {{ $loop->iteration > 10 ? 'd-none toggle-organizer' : '' }}">
                                        <div class="form-check common-check common-radio mb-0">
                                            <input class="form-check-input" type="radio" name="organizer"
                                                id="{{ $loop->iteration }}-org" value="{{ $orgId }}"
                                                @if ($loop->first)  @endif>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="{{ $loop->iteration }}-org">{{ $orgName }}</label>
                                        </div>
                                        <span class="text-neutral-500">
                                            {{ $allEvents->where('organizer_id', $orgId)->count() }}
                                        </span>
                                    </div>
                                @endforeach

                                @if (count($organizers) > 10)
                                    <a href="javascript:void(0);"
                                        class="text-sm text-main-600 fw-semibold text-md hover-text-decoration-underline"
                                        id="see_more_organizer">
                                        Lihat Selengkapnya
                                    </a>
                                @endif

                                <span class="d-block border border-neutral-30 border-dashed"></span>
                                <h6 class="text-lg fw-medium">Kategori Event</h6>
                                <div class="d-flex flex-column gap-16">
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="all"
                                                name="event_category[]" id="3254" checked>
                                            <label class="form-check-label fw-normal flex-grow-1" for="3254">Semua
                                                Kategori</label>
                                        </div>
                                        <span class="text-neutral-500">{{ $allEvents->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="Seminar"
                                                name="event_category[]" id="1522" checked>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="1522">Seminar</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('event_category', 'Seminar')->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="Kuliah Tamu"
                                                name="event_category[]" id="2545" checked>
                                            <label class="form-check-label fw-normal flex-grow-1" for="2545">
                                                Kuliah Tamu</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('event_category', 'Kuliah Tamu')->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="Pelatihan"
                                                name="event_category[]" id="3215" checked>
                                            <label class="form-check-label fw-normal flex-grow-1" for="3215">
                                                Pelatihan</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('event_category', 'Pelatihan')->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="Workshop"
                                                name="event_category[]" id="5526" checked>
                                            <label class="form-check-label fw-normal flex-grow-1" for="5526">
                                                Workshop</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('event_category', 'Workshop')->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="Kompetisi"
                                                name="event_category[]" id="1563" checked>
                                            <label class="form-check-label fw-normal flex-grow-1" for="1563">
                                                Kompetisi</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('event_category', 'Kompetisi')->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" value="Lainnya"
                                                name="event_category[]" id="4955" checked>
                                            <label class="form-check-label fw-normal flex-grow-1" for="4955">
                                                Lainnya</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('event_category', 'Lainnya')->count() }}</span>
                                    </div>
                                </div>
                                <span class="d-block border border-neutral-30 border-dashed"></span>
                                <h6 class="text-lg fw-medium">Biaya Event</h6>
                                <div class="d-flex flex-column gap-16">
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_free[]"
                                                value="all" id="all-costs" checked>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="all-costs">Semua</label>
                                        </div>
                                        <span class="text-neutral-500">{{ $allEvents->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_free[]"
                                                value="true" id="free" checked>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="free">Gratis</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('is_free', false)->count() }}</span>
                                    </div>
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_free[]"
                                                value="false" id="paid" checked>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="paid">Berbayar</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allEvents->where('is_free', true)->count() }}</span>
                                    </div>
                                </div>
                                <span class="d-block border border-neutral-30 border-dashed"></span>

                                <button type="reset"
                                    class="btn btn-outline-main rounded-pill flex-center gap-16 fw-semibold w-100">
                                    <i class="ph-bold ph-arrow-clockwise d-flex text-lg"></i>
                                    Reset Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 ps-40">
                    <div class="course-list-wrapper">
                        <div class="flex-between gap-16 flex-wrap mb-40">


                            <span id="showing-text" class="text-neutral-500">
                                Menampilkan {{ $events->firstItem() }} hingga {{ $events->lastItem() }} dari
                                {{ $events->total() }}
                                event
                                @if ($events->total() > $events->count())
                                    (Difilter dari {{ $events->total() }} total event)
                                @endif
                            </span>

                        </div>
                        @include('homepage.events.components.event-card', ['events' => $events])
                    </div>

                    <div id="pagination-buttons">
                        @include('homepage.events.components.pagination-button', ['events' => $events])
                    </div>
                </div>
            </div>
    </section>

@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.form-check-input').on('change', function() {
                fetchFilteredEvents();
            });

            $('#reset-filter').on('click', function() {
                setTimeout(fetchFilteredEvents, 100);
            });

            // Tangani klik pagination
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();

                const page = $(this).data('page');
                if (!page || $(this).parent().hasClass('disabled')) return;

                fetchFilteredEvents(page);
            });

            function fetchFilteredEvents(page = 1) {
                const form = $('#event-filter-form');
                const data = form.serialize();

                $.ajax({
                    url: "{{ route('event') }}?page=" + page,
                    method: 'GET',
                    data: data,
                    beforeSend: function() {
                        $('#event-results').html('<center><p>Loading...</p></center>');
                    },
                    success: function(response) {
                        $('#event-results').html(response.eventHtml);
                        // Update pagination
                        $('#pagination-buttons').html(response.paginationHtml);

                        // Update informasi jumlah data
                        let showingText =
                            `Menampilkan ${response.from} hingga ${response.to} dari ${response.filtered ? response.filtered : response.total} event`;
                        if (response.filtered && response.filtered !== response.total) {
                            showingText += ` (Difilter dari ${response.total} total event)`;
                        }
                        $('#showing-text').text(showingText);
                    },
                    error: function() {
                        $('#event-results').html('<p>Error loading data</p>');
                    }
                });
            }
        });
    </script>

    <script>
        $(function() {
            // Tangani klik checkbox "Semua Kategori"
            $('input[name="event_category[]"][value="all"]').on('change', function() {
                const isChecked = $(this).is(':checked');

                // Centang atau uncheck semua checkbox lain yang punya name sama tapi bukan "all"
                $('input[name="event_category[]"]').not('[value="all"]').prop('checked', isChecked);
            });

            // Kalau user uncheck salah satu kategori, otomatis uncheck juga "Semua Kategori"
            $('input[name="event_category[]"]').not('[value="all"]').on('change', function() {
                if (!$(this).is(':checked')) {
                    $('input[name="event_category[]"][value="all"]').prop('checked', false);
                }

                // Kalau semua kategori dicentang manual, aktifkan kembali checkbox all
                const allOthers = $('input[name="event_category[]"]').not('[value="all"]');
                const allChecked = allOthers.length === allOthers.filter(':checked').length;

                if (allChecked) {
                    $('input[name="event_category[]"][value="all"]').prop('checked', true);
                }
            });
        });
        $(function() {
            // Tangani klik checkbox "Semua Kategori"
            $('input[name="is_free[]"][value="all"]').on('change', function() {
                const isChecked = $(this).is(':checked');

                // Centang atau uncheck semua checkbox lain yang punya name sama tapi bukan "all"
                $('input[name="is_free[]"]').not('[value="all"]').prop('checked', isChecked);
            });

            // Kalau user uncheck salah satu kategori, otomatis uncheck juga "Semua Kategori"
            $('input[name="is_free[]"]').not('[value="all"]').on('change', function() {
                if (!$(this).is(':checked')) {
                    $('input[name="is_free[]"][value="all"]').prop('checked', false);
                }

                // Kalau semua kategori dicentang manual, aktifkan kembali checkbox all
                const allOthers = $('input[name="is_free[]"]').not('[value="all"]');
                const allChecked = allOthers.length === allOthers.filter(':checked').length;

                if (allChecked) {
                    $('input[name="is_free[]"][value="all"]').prop('checked', true);
                }
            });
        });
    </script>

    <!-- ============================== Course List View Section End ============================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('see_more_organizer');
            let expanded = false;

            toggleBtn?.addEventListener('click', function() {
                document.querySelectorAll('.toggle-organizer').forEach(el => {
                    el.classList.toggle('d-none');
                });

                expanded = !expanded;
                toggleBtn.textContent = expanded ? 'Lihat Lebih Sedikit' : 'Lihat Selengkapnya';
            });
        });
    </script>
    @include('homepage.events.components.script-wishlist-handle')
@endpush
