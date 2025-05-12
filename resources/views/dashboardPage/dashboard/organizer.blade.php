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
        <div class="col-xxl-4">
            <div class="card radius-12 mb-24">
                <div class="card-body p-16">
                    <div
                        class="px-20 py-16 shadow-none radius-8 gradient-deep-4 left-line line-bg-warning position-relative overflow-hidden">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            @php
                                $shorten_name = Auth::user()->organizer->shorten_name;
                            @endphp
                            <div>
                                <span class="mb-2 fw-medium text-secondary-light text-md me-3">Total Event</span>
                                <span>
                                    <select id="yearFilter"
                                        data-route="{{ route('dash.organizer.getDataEventByYear', ['shorten_name' => $shorten_name, 'year' => '__YEAR__']) }}"
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
                                <iconify-icon icon="carbon:event" class="icon text-2xl line-height-1"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card radius-12">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Event dalam waktu dekat</h6>
                        <a href="{{ route('data.events', $shorten_name) }}"
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
                                    <th scope="col">Nama Event </th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($upcomingEvents as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>

                                        @php
                                            $step = $event->steps()->orderBy('event_date')->first();
                                            $diff = now()->diff($step->event_date);
                                        @endphp
                                        <td
                                            class="{{ now()->lt($step->event_date) ? 'text-warning-600' : 'text-success-600' }}">
                                            @if (now()->lt($step->event_date))
                                                Kurang
                                                {{ $diff->d > 0 ? $diff->d . ' hari' : '' }}
                                                {{ $diff->h > 0 ? $diff->h . ' jam' : '' }}
                                                {{ $diff->i > 0 ? $diff->i . ' menit' : '' }}
                                            @else
                                                Sudah dimulai
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada event yang diselenggarakan dalam
                                            waktu dekat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-8">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Status Peminjaman Aset</h6>
                        <a href="{{ route('data.events', $shorten_name) }}"
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
                                    <th scope="col">Nama Aset </th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assetBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->asset->name }}</td>
                                        <td>{{ $booking->event->title }}</td>
                                        @php
                                            switch ($booking->status) {
                                                case 'submission_booking':
                                                    $statusText = '⏳ Proses Pengajuan Booking';
                                                    $badgeClass = 'bg-secondary';
                                                    break;
                                                case 'booked':
                                                    $statusText = '✅ Booking Disetujui';
                                                    $badgeClass = 'bg-primary';
                                                    break;
                                                case 'submission_full_payment':
                                                    $statusText = '⏳ Pengajuan Surat Peminjaman';
                                                    $badgeClass = 'bg-secondary';
                                                    break;
                                                case 'approved':
                                                    $statusText = '📦 Peminjaman Disetujui';
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'rejected':
                                                    $statusText = '✖️ Peminjaman Ditolak';
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                case 'rejected_full_payment':
                                                    $statusText = '✖️ Surat Peminjaman Ditolak';
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                                case 'cancelled':
                                                    $statusText = '❌ Peminjaman Dibatalkan';
                                                    $badgeClass = 'bg-dark';
                                                    break;
                                            }
                                        @endphp
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $statusText }} </span>
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
                        <h6 class="mb-2 fw-bold text-lg mb-0">Peserta yang Baru Bergabung</h6>
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
                                    <th scope="col">Nama </th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($newParticipants as $participant)
                                    <tr>
                                        <td>{{ $participant->user->name }}</td>
                                        <td>{{ $participant->event->title }}</td>
                                        @php
                                            $created = \Carbon\Carbon::parse($participant->created_at);
                                            $now = \Carbon\Carbon::now();
                                            $diff = $created->diff($now);
                                        @endphp
                                        <td class="text-success-600">
                                            Mendaftar sejak
                                            {{ $diff->d > 0 ? $diff->d . ' hari ' : '' }}
                                            {{ $diff->h > 0 ? $diff->h . ' jam ' : '' }}
                                            {{ $diff->i > 0 ? $diff->i . ' menit' : '' }}
                                            yang lalu
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada peserta baru yang mendaftar dalam
                                            waktu dekat ini.
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
                        <h6 class="mb-2 fw-bold text-lg mb-0">Jumlah Event yang Diselenggarakan</h6>
                        <select id="yearEventSelect"
                            class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                            @for ($yearEvent = now()->year; $yearEvent >= now()->year - 4; $yearEvent--)
                                <option value="{{ $yearEvent }}">Tahun {{ $yearEvent }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="card-body p-24 mb-8">
                    <div id="eventChartBar" class="apexcharts-tooltip-style-1"></div>
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
    {{-- Get Data Chart Jumlah Event Per Tahun (Bar Chart) --}}
    <script>
        let chartEventBar = null;

        function renderEventChart(yearFilter) {
            const routeTemplate = `{{ route('dash.organizer.getEventChart', ['shorten_name' => '__SHORTEN_NAME__']) }}`;
            const shortenName = '{{ $shorten_name ?? '' }}';

            const finalUrl = routeTemplate.replace('__SHORTEN_NAME__', shortenName) + `?year=${yearFilter}`;

            fetch(finalUrl)
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
                            text: 'Jumlah Event per Tahun',
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
            const select = document.getElementById('yearEventSelect');
            if (select && select.value) {
                renderEventChart(select.value);
            }

            // Ganti chart saat organizer dipilih
            select.addEventListener('change', (e) => {
                renderEventChart(e.target.value);
            });
        });
    </script>
    @include('components.timeline-usage-asset')
@endpush
