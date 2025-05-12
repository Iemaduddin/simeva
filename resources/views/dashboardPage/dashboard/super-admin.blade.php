@extends('layout.layout')
@section('title', 'Dashboard')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/lib/event-calendar.min.css') }}">

@endsection
@php
    $title = 'Dashboard';
@endphp

@section('content')

    <div class="row gy-4">
        <div class="col-12">
            <div class="card radius-12">
                <div class="card-body p-16">
                    <div class="row gy-4">
                        <div class="col-xxl-4 col-xl-4 col-sm-12">
                            <div
                                class="px-20 py-16 shadow-none radius-8 gradient-deep-1 left-line line-bg-primary position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-12 fw-medium text-secondary-light text-md">Total User</span>
                                        <h6 class="fw-semibold mb-1">{{ $totalUser }} user</h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center  mb-12 bg-primary-100 text-primary-600">
                                        <iconify-icon icon="gridicons:multiple-users"
                                            class="icon text-2xl line-height-1"></iconify-icon>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-sm-12">
                            <div
                                class="px-20 py-16 shadow-none radius-8 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md me-3">Total Event
                                            (Publik)</span>
                                        <span>
                                            <select id="yearFilter"
                                                data-route="{{ route('dash.super.getTotalEventByYear', ['year' => '__YEAR__']) }}"
                                                class="form-select form-select-sm w-auto bg-base text-secondary-light">
                                                @for ($year = now()->year; $year >= now()->year - 4; $year--)
                                                    <option value="{{ $year }}">Tahun {{ $year }}</option>
                                                @endfor
                                            </select>
                                        </span>

                                        <h6 class="fw-semibold mb-1" id="totalEvent"></h6>
                                    </div>
                                    <span
                                        class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-2xl mb-12 bg-warning-focus text-warning-600">
                                        <iconify-icon icon="carbon:event"
                                            class="icon text-2xl line-height-1"></iconify-icon>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-sm-12">
                            <div
                                class="px-20 py-16 shadow-none radius-8 gradient-deep-2 left-line line-bg-lilac position-relative overflow-hidden">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md me-3">Total Aset</span>
                                        <span>
                                            <select id="typeAssetFilter"
                                                data-route="{{ route('dash.super.getTotalAssetByType', ['type' => '__TYPE__']) }}"
                                                class="form-select form-select-sm w-auto bg-base text-secondary-light">
                                                <option value="umum">Fasilitas Umum</option>
                                                @foreach ($jurusans as $kode_jurusan)
                                                    <option value="{{ $kode_jurusan }}">Jurusan {{ $kode_jurusan }}</option>
                                                @endforeach
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
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">User Baru</h6>
                        <a href="{{ route('tenantUsers') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Lihat Semua
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama </th>
                                    <th scope="col">Role </th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($newUsers as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->roles->value('name') }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($user->created_at)->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }}
                                            <br>
                                            <p class="text-success-600">
                                                ({{ \Carbon\Carbon::parse($user->created_at)->diffForHumans(['parts' => 4]) }})
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada user baru dalam 7 hari terakhir
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Event Sedang Diselenggarakan</h6>
                        <a href="{{ route('data.listEvent') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Lihat Semua
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama Event </th>
                                    <th scope="col">Penyelenggara </th>
                                    <th scope="col">Pelaksanaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($onGoingEvents as $event)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->organizers->shorten_name }}</td>
                                        <td>
                                            @if ($event->steps->count() > 1)
                                                {{ \Carbon\Carbon::parse($event->steps->sortBy('event_date')->first()->event_date)->isoFormat('D MMMM Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($event->steps->sortByDesc('event_date')->first()->event_date)->isoFormat('D MMMM Y') }}
                                            @elseif ($event->steps->count() == 1)
                                                {{ \Carbon\Carbon::parse($event->steps->first()->event_date)->isoFormat('D MMMM Y') }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada event yang sedang diselenggarakan.
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
                        <h6 class="mb-2 fw-bold text-lg mb-0">Daftar Event Setiap Penyelenggara Tahun
                            {{ \Carbon\Carbon::now()->year }}</h6>
                        <select id="organizerSelect"
                            class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                            @foreach ($organizers as $type => $organizersGroup)
                                <optgroup label="{{ $type }}">
                                    @foreach ($organizersGroup as $organizer)
                                        <option value="{{ $organizer->id }}">{{ $organizer->user->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body p-24 mb-8">
                    <div id="eventChartBar" class="apexcharts-tooltip-style-1"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-2 fw-bold text-lg">Status Peminjaman Aset <br> (Fasilitas Umum)
                        Tahun {{ \Carbon\Carbon::now()->year }}</h6>
                </div>
                <div class="card-body p-24">
                    <div class="mt-32">
                        <div id="statusAssetBookings"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Peminjaman Aset (Pihak Eksternal)</h6>
                        <a href="{{ route('asset.fasum.bookings') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Lihat Semua
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama Aset </th>
                                    <th scope="col">Nama Peminjam </th>
                                    <th scope="col">Nama Event </th>
                                    <th scope="col">Waktu Pemakaian</th>
                                    <th scope="col">Pembayaran </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assetBookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $booking->asset->name }}</td>
                                        <td>{{ $booking->user->name ?? $booking->external_user }}</td>
                                        <td>{{ $booking->usage_event_name }}</td>
                                        <td>

                                            {{ \Carbon\Carbon::parse($booking->usage_date_start)->isoFormat('D MMMM Y') }}
                                            -
                                            <br>
                                            {{ \Carbon\Carbon::parse($booking->usage_date_start)->isoFormat('D MMMM Y') }}

                                        </td>
                                        <td>{{ strtoupper($booking->payment_type) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada user baru dalam 7 hari terakhir
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
            <div class="card h-100 p-24">
                <div class="card-header text-center">
                    <h6>Jadwal Penggunaan Aset</h6>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xl-12">

                            <div id="timeline-usage-asset"></div>
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

@endsection
@push('script')
    {{-- Filter event by year --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const yearSelect = document.getElementById('yearFilter');
            const totalEvent = document.getElementById('totalEvent');

            const routeTemplate = yearSelect.dataset.route; // e.g. /dashboard/total-event-by-year/__YEAR__

            function getUrl(year) {
                return routeTemplate.replace('__YEAR__', year);
            }

            async function fetchTotal(year) {
                try {
                    const url = getUrl(year);
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    totalEvent.textContent = data.total ?? 0;
                } catch (error) {
                    console.error('Error fetching total:', error);
                    totalEvent.textContent = 'Error';
                }
            }

            // Initial load
            fetchTotal(yearSelect.value);

            // When user changes year
            yearSelect.addEventListener('change', function() {
                fetchTotal(this.value);
            });
        });
    </script>

    {{-- Filter asset by type --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const typeSelect = document.getElementById('typeAssetFilter');
            const totalAsset = document.getElementById('totalAsset');

            const routeTemplate = typeSelect.dataset.route; // e.g. /dashboard/total-event-by-year/__YEAR__

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
                    totalAsset.textContent = data.total ?? 0;
                } catch (error) {
                    console.error('Error fetching total:', error);
                    totalAsset.textContent = 'Error';
                }
            }

            // Initial load
            fetchTotal(typeSelect.value);

            // When user changes year
            typeSelect.addEventListener('change', function() {
                fetchTotal(this.value);
            });
        });
    </script>
    @include('components.timeline-usage-asset')
    {{-- Get Data Chart Status Peminjaman Aset (Donut Chart) --}}
    <script>
        fetch('{{ route('dash.super.getDataStatusAssetBookingChart') }}')
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
    {{-- Get Data Chart Jumlah Event Per Organizer (Bar Chart) --}}
    <script>
        let chartEventBar = null;

        function renderEventChart(organizerId) {
            fetch(`{{ route('dash.super.getDataEventChart') }}?organizer_id=${organizerId}`)
                .then(res => res.json())
                .then(data => {
                    const options = {
                        chart: {
                            type: 'bar',
                            height: 400
                        },
                        series: [{
                            name: 'Jumlah Event',
                            data: data.data
                        }],
                        xaxis: {
                            categories: data.labels
                        },
                        colors: ['#3b82f6'],
                        dataLabels: {
                            enabled: true
                        },
                        title: {
                            text: 'Jumlah Event per Bulan - ' + new Date().getFullYear(),
                            align: 'center'
                        }
                    };

                    if (chartEventBar) {
                        chartEventBar.updateOptions(options);
                    } else {
                        chartEventBar = new ApexCharts(document.querySelector("#eventChartBar"), options);
                        chartEventBar.render();
                    }
                });
        }

        // Load pertama kali dengan organizer pertama
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('organizerSelect');
            if (select && select.value) {
                renderEventChart(select.value);
            }

            // Ganti chart saat organizer dipilih
            select.addEventListener('change', (e) => {
                renderEventChart(e.target.value);
            });
        });
    </script>
@endpush
