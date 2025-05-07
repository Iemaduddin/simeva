@extends('layout.landingPageLayout')

@section('title', 'Aset BMN')
@section('content')
    <!-- ========================= Banner Section Start =============================== -->
    <section class="banner py-80 position-relative overflow-hidden">

        <img src="{{ asset('assets/images/shapes/shape1.png') }}" alt="" class="shape one animation-rotation">
        <img src="{{ asset('assets/images/shapes/shape2.png') }}" alt="" class="shape two animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape3.png') }}" alt="" class="shape three animation-walking">
        <img src="{{ asset('assets/images/shapes/shape4.png') }}" alt="" class="shape four animation-scalation">
        <img src="{{ asset('assets/images/shapes/shape5.png') }}" alt="" class="shape five animation-walking">

        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-xl-6">
                    <div class="banner-content pe-md-4">
                        <h2 class="text-main-600 wow bounceInLeft">Penjadwalan dan Pemanfaatan</h2>
                        <h3 class="wow bounceInLeft">Aset Barang Milik Negara Polinema
                        </h3>
                        <p class="text-neutral-500 wow bounceInUp">Sistem Informasi Manajemen Event dan Aset
                            (SIMEVA) dirancang untuk membantu Anda memantau ketersediaan aset Barang Milik Negara yang dapat
                            dipinjam atau disewa sesuai kebutuhan event atau agenda Anda. Jelajahi lebih lanjut untuk
                            mendapatkan informasi selengkapnya.</p>
                        <div class="wow bounceInLeft">
                            <p class="mt-40">
                                Unit yang terkait atas Pemanfaatan Aset BMN :
                            </p>
                            <ul class="list-dotted d-flex flex-column gap-10 my-20">
                                <li>UPT. Pengelola Usaha</li>
                                <li>Ur. Rumah Tangga</li>
                                <li>UPT. Perawatan & Perbaikan</li>
                                <li>Pengelola Auditorium AH Lt. 1</li>
                                <li>Pengelola Masjid</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="container">
                        <div class="choose-us__thumbs position-relative">
                            <div class="text-end" data-aos="zoom-out">
                                <div class="d-sm-inline-block d-block position-relative">
                                    <img src="{{ asset('assets/images/aset_bmn/graha_polinema.jpg') }}"
                                        style="width: 1000px;height:600px" alt=""
                                        class="choose-us__img object-fit-cover rounded-12" data-tilt data-tilt-max="16"
                                        data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-full-page-listening>
                                    <span
                                        class="shadow-main-two flex-center bg-main-two-600 rounded-circle position-absolute inset-block-start-0 inset-inline-start-0 mt-40 ms--40 animation-upDown"
                                        style="width: 150px; height: 150px;">
                                        <h3 class="text-white text-center pt-20 wow bounceInLeft">20+<br> Aset</h3>
                                    </span>
                                </div>
                            </div>
                            <div class="animation-video w-75" data-aos="zoom-in">
                                <img src="{{ asset('assets/images/aset_bmn/bus_polinema.jpg') }}" alt=""
                                    class="border border-white border-3 object-fit-cover rounded-circle"
                                    style="width:350px; height:350px" data-tilt>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
    </section>
    <!-- ========================= Banner SEction End =============================== -->
    <section class="course-list-view py-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="sidebar rounded-12 bg-main-25 p-32 border border-neutral-30">
                        <form id="asset-filter-form">
                            <div class="flex-between mb-24">
                                <h4 class="mb-0">Filter</h4>
                                <button type="button"
                                    class="sidebar-close text-xl text-neutral-500 d-lg-none hover-text-main-600">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                            </div>

                            <h6 class="text-lg mb-24 fw-medium">Jenis Aset</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check common-radio mb-0">
                                        <input class="form-check-input" type="radio" name="facility_scope" value="umum"
                                            id="umum-scope"
                                            {{ request('facility_scope', 'umum') === 'umum' ? 'checked' : '' }}>

                                        <label class="form-check-label fw-normal flex-grow-1" for="umum-scope">Umum</label>
                                    </div>
                                    <span
                                        class="text-neutral-500">{{ $allAssets->where('facility_scope', 'umum')->count() }}</span>
                                </div>
                                @foreach ($jurusans as $jurusan)
                                    <div class="flex-between gap-16">
                                        <div class="form-check common-check common-radio mb-0">
                                            <input class="form-check-input" type="radio" name="facility_scope"
                                                value="{{ $jurusan->id }}" id="jurusan-{{ $jurusan->id }}"
                                                {{ request('facility_scope') == $jurusan->id ? 'checked' : '' }}>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="jurusan-{{ $jurusan->id }}">{{ $jurusan->nama }}</label>
                                        </div>
                                        <span
                                            class="text-neutral-500">{{ $allAssets->where('jurusan_id', $jurusan->id)->count() }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>

                            <h6 class="text-lg mb-24 fw-medium">Jenis Booking</h6>
                            <div class="d-flex flex-column gap-16">
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="booking_type[]" value="all"
                                            id="all-booking-type" checked>
                                        <label class="form-check-label fw-normal flex-grow-1" for="all-costs">Semua</label>
                                    </div>
                                    <span class="text-neutral-500">{{ $allAssets->count() }}</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="booking_type[]" value="daily"
                                            id="daily" checked>
                                        <label class="form-check-label fw-normal flex-grow-1"
                                            for="daily">Harian</label>
                                    </div>
                                    <span
                                        class="text-neutral-500">{{ $allAssets->where('booking_type', 'daily')->count() }}</span>
                                </div>
                                <div class="flex-between gap-16">
                                    <div class="form-check common-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="booking_type[]"
                                            value="annual" id="annual" checked>
                                        <label class="form-check-label fw-normal flex-grow-1"
                                            for="annual">Tahunan</label>
                                    </div>
                                    <span
                                        class="text-neutral-500">{{ $allAssets->where('booking_type', 'annual')->count() }}</span>
                                </div>
                            </div>
                            <span class="d-block border border-neutral-30 border-dashed my-24"></span>
                            <button type="reset"
                                class="btn btn-outline-main rounded-pill flex-center gap-16 fw-semibold w-100">
                                <i class="ph-bold ph-arrow-clockwise d-flex text-lg"></i>
                                Reset Filters
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-8 ps-40">
                    <div class="course-list-wrapper">
                        <div class="flex-between gap-16 flex-wrap mb-40">
                            <span id="showing-text" class="text-neutral-500">
                                Menampilkan {{ $assets->firstItem() }} hingga {{ $assets->lastItem() }} dari
                                {{ $assets->total() }}
                                aset
                                @if ($assets->total() > $assets->count())
                                    (Difilter dari {{ $assets->total() }} total aset)
                                @endif
                            </span>
                            @include('homepage.assets.components.asset-card', ['assets' => $assets])
                        </div>

                        <div id="pagination-buttons">
                            @include('homepage.assets.components.pagination-button', ['assets' => $assets])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $(function() {
            // Tangani klik checkbox "Semua Kategori"
            $('input[name="booking_type[]"][value="all"]').on('change', function() {
                const isChecked = $(this).is(':checked');

                // Centang atau uncheck semua checkbox lain yang punya name sama tapi bukan "all"
                $('input[name="booking_type[]"]').not('[value="all"]').prop('checked', isChecked);
            });

            // Kalau user uncheck salah satu kategori, otomatis uncheck juga "Semua Kategori"
            $('input[name="booking_type[]"]').not('[value="all"]').on('change', function() {
                if (!$(this).is(':checked')) {
                    $('input[name="booking_type[]"][value="all"]').prop('checked', false);
                }

                // Kalau semua kategori dicentang manual, aktifkan kembali checkbox all
                const allOthers = $('input[name="booking_type[]"]').not('[value="all"]');
                const allChecked = allOthers.length === allOthers.filter(':checked').length;

                if (allChecked) {
                    $('input[name="booking_type[]"][value="all"]').prop('checked', true);
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.form-check-input').on('change', function() {
                fetchFilteredAssets();
            });

            $('#reset-filter').on('click', function() {
                setTimeout(fetchFilteredAssets, 100);
            });

            // Tangani klik pagination
            $(document).on('click', '.pagination .page-link', function(e) {
                e.preventDefault();

                const page = $(this).data('page');
                if (!page || $(this).parent().hasClass('disabled')) return;

                fetchFilteredAssets(page);
            });

            function fetchFilteredAssets(page = 1) {
                const form = $('#asset-filter-form');
                const data = form.serialize();

                $.ajax({
                    url: "{{ route('aset-bmn') }}?page=" + page,
                    method: 'GET',
                    data: data,
                    beforeSend: function() {
                        $('#asset-results').html('<center><p>Loading...</p></center>');
                    },
                    success: function(response) {
                        $('#asset-results').html(response.assetHtml);
                        // Update pagination
                        $('#pagination-buttons').html(response.paginationHtml);

                        // Update informasi jumlah data
                        let showingText =
                            `Menampilkan ${response.from !== null ?response.from : 0} hingga ${response.to!== null ?response.to : 0} dari ${response.filtered ? response.filtered : response.total} aset`;
                        if (response.filtered && response.filtered !== response.total) {
                            showingText += ` (Difilter dari ${response.total} total aset)`;
                        }
                        $('#showing-text').text(showingText);
                    },
                    error: function() {
                        $('#asset-results').html('<p>Error loading data</p>');
                    }
                });
            }
        });
    </script>
    @if (auth()->check())
        <script>
            document.addEventListener("DOMContentLoaded", async function() {
                const buttons = document.querySelectorAll(".wishlist-btn");
                const assetIds = Array.from(buttons).map(button => button.dataset.assetId);

                try {
                    const response = await fetch("{{ route('saved.item.check') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            itemType: 'asset',
                            asset_ids: assetIds
                        })
                    });

                    const savedAssets = await response.json();

                    buttons.forEach(button => {
                        if (savedAssets.includes(button.dataset.assetId)) {
                            button.classList.add("bg-main-two-600", "text-white");
                            button.classList.remove("bg-white", "text-main-two-600");
                        } else {
                            button.classList.add("bg-white", "text-main-two-600");
                            button.classList.remove("bg-main-two-600", "text-white");
                        }
                    });

                } catch (error) {
                    console.error("Error fetching saved:", error);
                }

                buttons.forEach(button => {
                    button.addEventListener("click", async function() {
                        const assetId = this.dataset.assetId;
                        const isSaved = this.classList.contains("bg-main-two-600");

                        try {
                            const response = await fetch(
                                "{{ route('saved.item.toggle') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                    },
                                    body: JSON.stringify({
                                        itemType: 'asset',
                                        asset_id: assetId
                                    })
                                });

                            const result = await response.json();

                            if (response.ok) {
                                if (isSaved) {
                                    this.classList.add("bg-white", "text-main-two-600");
                                    this.classList.remove("bg-main-two-600", "text-white");
                                } else {
                                    this.classList.add("bg-main-two-600", "text-white");
                                    this.classList.remove("bg-white", "text-main-two-600");
                                }
                                this.style.transform = "scale(1.2)";
                                setTimeout(() => this.style.transform = "scale(1)", 200);
                            } else {
                                console.error("Error:", result.message);
                            }
                        } catch (error) {
                            console.error("Request failed:", error);
                        }
                    });
                });
            });
        </script>
    @endif
@endpush
