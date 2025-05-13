@extends('layout.layout')
@section('title', 'Dashboard')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/lib/event-calendar.min.css') }}">

@endsection
@php
    $title = 'Dashboard';
@endphp

@section('content')
    @php
        $role = Auth::user()->getRoleNames()->first();
    @endphp
    <div class="row gy-4">
        <div class="col-xxl-4">
            @if ($role == 'Kaur RT')
                <div class="card radius-12 mb-24">
                    <div class="card-body p-16">
                        <div
                            class="px-20 py-16 shadow-none radius-8 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div>
                                    <span class="mb-2 fw-medium text-secondary-light text-md me-3">Total Aset</span>
                                    <span>
                                        <select id="typeAssetFilter"
                                            data-route="{{ route('dash.kaurRT.getDataAsset', ['type' => '__TYPE__']) }}"
                                            class="form-select form-select-sm w-auto bg-base text-secondary-light">
                                            <option value="all">Semua Aset</option>
                                            <option value="daily">Harian</option>
                                            <option value="annual">Tahunan</option>
                                        </select>
                                    </span>

                                    <h6 class="fw-semibold mb-1" id="totalAsset"></h6>
                                </div>
                                <span
                                    class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-lilac-200 text-lilac-600">
                                    <iconify-icon icon="hugeicons:building-03"
                                        class="icon text-2xl line-height-1"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($role == 'UPT PU')
                <div class="card radius-12 mb-24">
                    <div class="card-body p-16">
                        <div
                            class="px-20 py-16 shadow-none radius-8 gradient-deep-3 left-line line-bg-success position-relative overflow-hidden">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div>
                                    <span class="mb-2 fw-medium text-secondary-light text-md me-3">Total Pemasukan</span>
                                    <span>
                                        <select id="yearIncome"
                                            data-route="{{ route('dash.uptPU.getAssetBookingIncome', ['year' => '__YEAR__']) }}"
                                            class="form-select form-select-sm w-auto bg-base text-secondary-light">
                                            @for ($yearIncome = now()->year; $yearIncome >= now()->year - 4; $yearIncome--)
                                                <option value="{{ $yearIncome }}">Tahun {{ $yearIncome }}</option>
                                            @endfor
                                        </select>
                                    </span>

                                    <p class="fw-semibold mb-1 fs-5" id="totalIncome"></p>
                                </div>
                                <span
                                    class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-success-200 text-success-600">
                                    <iconify-icon icon="streamline:subscription-cashflow"
                                        class="icon text-2xl line-height-1"></iconify-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card radius-12 mb-24 px-3">
                <div class="my-24">
                    <div id="statusAssetBookings"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Status Peminjaman Aset</h6>
                        <a href="{{ route('asset.fasum.eventBookings') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Lihat Semua
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm basic-data-table">
                        <table class="table bordered-table mb-0 w-100 h-100 row-border order-column sm-table"
                            id="submissionBookingTable">
                            <thead>
                                <tr>
                                    <th scope="col">Nama Aset </th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assetBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->asset->name }}</td>
                                        <td>{{ $booking->event->title ?? $booking->usage_event_name }}</td>
                                        @php
                                            switch ($booking->status) {
                                                case 'submission_booking':
                                                    $statusText = 'Perlu Konfirmasi booking';
                                                    break;
                                                case 'submission_full_payment':
                                                    $statusText = 'Perlu Konfirmasi Surat Peminjaman';
                                                    break;
                                            }
                                        @endphp
                                        <td>
                                            <span class="badge bg-secondary">{{ $statusText }} </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada peminjaman aset dalam waktu dekat
                                            ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Penggunaan Aset Fasilitas Umum
                            {{ $role == 'Kaur RT' ? ' (Pihak Internal) ' : ' (Pihak Eksternal) ' }}</h6>
                        <select id="yearAssetSelect"
                            class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                            @for ($yearAssetBooking = now()->year; $yearAssetBooking >= now()->year - 4; $yearAssetBooking--)
                                <option value="{{ $yearAssetBooking }}">Tahun {{ $yearAssetBooking }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="card-body p-24 mb-8">
                    <div id="assetLineChart" class="apexcharts-tooltip-style-1"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-12">
            <div class="card h-100 p-24">
                <div class="card-header text-center">
                    <h6>Jadwal Penggunaan Aset Fasilitas Umum</h6>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xl-12">
                            <div id="timeline-usage-asset" data-default-scope="{{ 'umum' }}"
                                data-fetch-url="{{ route('asset-booking.getTimelineUsageAsset') }}">
                            </div>
                            <div class="row my-24 mx-16 d-flex justify-content-around">
                                <div class="col-xl-12">
                                    <p class="text-lg fw-bold">Status Peminjaman:</p>
                                </div>

                                @php
                                    $statuses = [
                                        ['color' => 'text-primary-600', 'text' => 'Sudah Dipesan (Booked)'],
                                        ['color' => 'text-warning-600', 'text' => 'Proses Pengajuan (Submission)'],
                                        ['color' => 'text-success-600', 'text' => 'Disetujui (Approved)'],
                                    ];
                                @endphp

                                @foreach ($statuses as $status)
                                    <div class="col-xl-4 d-flex align-items-center gap-8">
                                        <i class="ri-circle-fill circle-icon {{ $status['color'] }}  w-auto"></i>
                                        <p class="m-0 fw-bold">{{ $status['text'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Detail Timeline Asset Booking -->
    <div class="modal fade" id="detailTimelineUsageAsset" tabindex="-1" aria-labelledby="detailTimelineUsageAsset"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                    <h1 class="modal-title fs-5" id="detailTimelineUsageAsset">Rincian Data Booking Aset</h1>
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
                        <span class="text-secondary-light txt-sm fw-medium">Dibooking oleh:</span>
                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4 detail-usage-user-booking"></h6>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    {{-- Filter Asset by asset booking type --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const typeAssetSelect = document.getElementById('typeAssetFilter');
            const totalAsset = document.getElementById('totalAsset');

            const routeTemplate = typeAssetSelect.dataset.route;

            function getUrl(type) {
                return routeTemplate.replace('__TYPE__', type);
            }

            async function fetchTotal(type) {
                try {
                    const url = getUrl(type);
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    totalAsset.textContent = data.total + ' Aset' ?? 0;
                } catch (error) {
                    console.error('Error fetching total:', error);
                    totalAsset.textContent = 'Error';
                }
            }

            // Initial load
            fetchTotal(typeAssetSelect.value);

            // When user changes type
            typeAssetSelect.addEventListener('change', function() {
                fetchTotal(this.value);
            });
        });
    </script>
    {{-- Filter Year Income Asset Booking --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const yearIncomeSelect = document.getElementById('yearIncome');
            const totalIncome = document.getElementById('totalIncome');

            const routeTemplate = yearIncomeSelect.dataset.route;

            function getUrl(year) {
                return routeTemplate.replace('__YEAR__', year);
            }

            async function fetchTotalIncome(year) {
                try {
                    const url = getUrl(year);
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    totalIncome.textContent = 'Rp' + new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(data.total ?? 0).replace('Rp', '').trim();
                } catch (error) {
                    console.error('Error fetching total:', error);
                    totalIncome.textContent = 'Error';
                }
            }

            // Initial load
            fetchTotalIncome(yearIncomeSelect.value);

            // When user changes type
            yearIncomeSelect.addEventListener('change', function() {
                fetchTotalIncome(this.value);
            });
        });
    </script>
    {{-- Get Data Chart Status Peminjaman Aset (Donut Chart) --}}
    <script>
        const role = "{{ $role ?? '-' }}"

        fetch('{{ route('dash.kaurPU.getStatusAssetBookingChart') }}')
            .then(response => response.json())
            .then(data => {
                const dataLabelAssetBooking = data.labels;
                const dataStatusAssetBooking = data.statusTotal.map(Number);
                const dataColorAssetBooking = data.colors;

                let statusAssetBookings = {
                    labels: dataLabelAssetBooking,
                    series: dataStatusAssetBooking,
                    colors: dataColorAssetBooking,

                    legend: {
                        show: true,
                        position: "bottom",
                    },
                    chart: {
                        type: "donut",
                        height: 500,
                        toolbar: {
                            show: true,
                            offsetX: 0,
                            offsetY: 0,
                            tools: {
                                download: true,
                                selection: true,
                            },
                            export: {
                                csv: {
                                    filename: "Status Peminjaman Aset",
                                    columnDelimiter: ',',
                                    headerCategory: 'category',
                                    headerValue: 'value',
                                    dateFormatter(timestamp) {
                                        return new Date(timestamp).toDateString()
                                    }
                                },
                                svg: {
                                    filename: "Status Peminjaman Aset",
                                },
                                png: {
                                    filename: "Status Peminjaman Aset",
                                }
                            },
                            autoSelected: 'zoom'
                        },
                    },
                    title: {
                        text: role == 'Kaur RT' ? 'Jumlah Peminjaman Aset Fasilitas Umum (Pihak Internal)' :
                            'Jumlah Peminjaman Aset Fasilitas Umum (Pihak Eksternal)',
                        align: 'center',
                        margin: 30,
                        offsetX: 0,
                        offsetY: 0,
                        floating: false,
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                            fontFamily: undefined,
                            color: '#263238'
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: "bottom"
                            }
                        }
                    }],
                };

                let chartStatusAssetBookings = new ApexCharts(document.querySelector("#statusAssetBookings"),
                    statusAssetBookings);
                chartStatusAssetBookings.render();

            });
    </script>

    {{-- DataTable untuk Submission Asset Booking --}}
    <script>
        $(document).ready(function() {
            $('#submissionBookingTable').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                lengthMenu: [5, 10, 15, 20, 25, 50, -1],
            });
        });
    </script>
    {{-- Get Data Chart Jumlah Penggunaan Aset Per Tahun (Line Chart) --}}
    <script>
        let assetChartLine = null;
        const colorChart = ['#487fff', '#f87171', '#4ade80', '#fde047', '#60a5fa', '#48cef7', '#cd9ffa', '#00b8f2',
            '#7f27ff', '#8252e9', '#e30a0a', '#f4941e', '#de3ace'
        ];

        function renderAssetChart(yearFilter) {
            fetch(`{{ route('dash.kaurPU.getUsageAssetChart') }}?year=${yearFilter}`)
                .then(res => res.json())
                .then(data => {
                    const options = {
                        chart: {
                            type: 'line',
                            height: 400
                        },
                        series: data.series,
                        xaxis: {
                            categories: data.categories
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Penggunaan Aset'
                            },
                            min: 0,
                            forceNiceScale: true
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3',
                                    'transparent'
                                ], // takes an array which will be repeated on columns
                                opacity: 0.5
                            },
                        },
                        markers: {
                            size: 1
                        },
                        colors: colorChart,
                        dataLabels: {
                            enabled: true
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        title: {
                            text: 'Penggunaan Aset Per Bulan',
                            align: 'left'
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'left',
                            floating: false,
                            offsetY: 10,
                        }
                    };

                    if (assetChartLine) {
                        assetChartLine.updateOptions(options);
                    } else {
                        assetChartLine = new ApexCharts(document.querySelector("#assetLineChart"), options);
                        assetChartLine.render();
                    }
                });
        }

        // Load pertama kali dengan organizer pertama
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('yearAssetSelect');
            if (select && select.value) {
                renderAssetChart(select.value);
            }

            // Ganti chart saat organizer dipilih
            select.addEventListener('change', (e) => {
                renderAssetChart(e.target.value);
            });
        });
    </script>
    @include('components.timeline-usage-asset')
@endpush
