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
    <script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>
    <script>
        $(document).on('shown.bs.modal', '.modal', function() {

            const $modal = $(this);
            const modalId = $modal.attr('id');

            if (modalId.startsWith('modalResubmissionAssetBooking-')) {
                // Flatpickr dengan class agar tidak bentrok
                flatpickr($modal.find(".usage_date")[0], {
                    dateFormat: "Y-m-d",
                    minDate: "today"
                });

                flatpickr($modal.find(".start_time")[0], {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });

                flatpickr($modal.find(".end_time")[0], {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });

                const assetId = $modal.find(".asset_id").val();
                const typeEvent = $modal.find(".type_event");
                const selectedCategoryId = $modal.data('asset-category-id');

                function calculatePrice() {
                    let selectedOption = typeEvent.find(":selected");
                    let selectedPrice = selectedOption.data("price");

                    if (selectedPrice) {
                        let fullPrice = parseFloat(selectedPrice);
                        let dpPrice = fullPrice * 0.3;
                        let remainingPrice = fullPrice - dpPrice;

                        $modal.find(".dp_price").text(`DP 30% : Rp${dpPrice.toLocaleString()}`);
                        $modal.find(".remaining_price").text(`Pelunasan : Rp${remainingPrice.toLocaleString()}`);
                        $modal.find(".full_price").text(`Total Harga : Rp${fullPrice.toLocaleString()}`);
                        $modal.find(".amount").val(fullPrice);
                    } else {
                        $modal.find(".dp_price").text("DP 30% : Rp0");
                        $modal.find(".remaining_price").text("Pelunasan : Rp0");
                        $modal.find(".full_price").text("Total Harga : Rp0");
                    }
                }

                function loadCategories() {
                    $.ajax({
                        url: "{{ route('asset-booking.getDataCategory', '') }}/" + assetId,
                        type: "GET",
                        success: function(response) {
                            typeEvent.empty();
                            typeEvent.append('<option value="" hidden>Pilih Jenis Acara</option>');

                            response.data.forEach(category => {
                                let isSelected = category.id == selectedCategoryId ?
                                    "selected" : "";
                                typeEvent.append(`
                                <option value="${category.id}" data-price="${category.external_price}" ${isSelected}>
                                    ${category.category_name}
                                </option>
                            `);
                            });

                            calculatePrice();
                        }
                    });
                }

                loadCategories();
                typeEvent.off('change').on('change', calculatePrice); // Hindari event ganda

                // Format tanggal lokal
                let usageData = $modal.find(".usage_date").val();
                if (usageData) {
                    let usageDate = new Date(usageData);
                    let formattedDisplayDate = usageDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    $modal.find(".usage_date_display").val(formattedDisplayDate);
                }
            }



            if (modalId.startsWith('modalResubmissionAssetBookingAnnual-')) {
                flatpickr("#usage_date_annual", {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                });
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

                let selectedDate = new Date(usageDateInput.val());
                if (!isNaN(selectedDate)) {
                    startDate = selectedDate;
                }

                // Load harga saat halaman load
                loadFirstCategoryPrice();
            }
        });
    </script>

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

    {{-- @include('components.script-crud') --}}
@endpush
