@extends('layout.layout')
@section('title', 'Asset Bookings Management')
@php
    $title = 'Asset Bookings Management';
    $subTitle = 'Asset Bookings Management';

@endphp

@section('content')
    <div id="asset-container" data-kode-jurusan="{{ $kode_jurusan }}"></div>
    <div class="card basic-data-table ">
        <h6 class="card-header card-title mb-0 align-content-center px-20 pt-20">Aset Fasilitas
            {{ $kode_jurusan ?? 'Umum' }}
        </h6>
        <div class="card-body px-24">
            <ul class="nav bordered-tab border border-top-0 border-start-0 border-end-0 d-inline-flex nav-pills mb-16"
                id="pills-tab" role="tablist">
                <li class="nav-item d-flex justify-content-between align-items-center" role="presentation">

                    <button class="nav-link tab-asset-booking px-16 py-10 active" id="pills-all-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all"
                        aria-selected="true" data-status-booking="all">
                        <span class="w-12-px h-12-px bg-primary-900 rounded-circle fw-medium me-4"></span>
                        Data Booking Aset Keseluruhan</button>
                </li>
                <li class="nav-item d-flex justify-content-between align-items-center" role="presentation">

                    <button class="nav-link tab-asset-booking px-16 py-10" id="pills-submission-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-submission" type="button" role="tab" aria-controls="pills-submission"
                        aria-selected="true" data-status-booking="submission">
                        <span class="w-12-px h-12-px bg-warning-600 rounded-circle fw-medium me-4"></span>
                        Proses Pengajuan (Submission)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-asset-booking px-16 py-10" id="pills-booked-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-booked" type="button" role="tab" aria-controls="pills-booked"
                        aria-selected="false" data-status-booking="booked">
                        <span class="w-12-px h-12-px bg-info-600 rounded-circle fw-medium me-4"></span>
                        Sudah Dipesan (Booked)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-asset-booking px-16 py-10" id="pills-approved-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-approved" type="button" role="tab" aria-controls="pills-approved"
                        aria-selected="false" data-status-booking="approved">
                        <span class="w-12-px h-12-px bg-success-600 rounded-circle fw-medium me-4"></span>
                        Disetujui (Approved)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-asset-booking px-16 py-10" id="pills-rejected-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-rejected" type="button" role="tab" aria-controls="pills-rejected"
                        aria-selected="false" data-status-booking="rejected">
                        <span class="w-12-px h-12-px bg-danger-600 rounded-circle fw-medium me-4"></span>
                        Ditolak (Rejected)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link tab-asset-booking px-16 py-10" id="pills-cancelled-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-cancelled" type="button" role="tab" aria-controls="pills-cancelled"
                        aria-selected="false" data-status-booking="cancelled">
                        <span class="w-12-px h-12-px bg-dark rounded-circle fw-medium me-4"></span>
                        Dibatalkan (Cancelled)</button>
                </li>
            </ul>
            @php
                $statusBookingTabContent = ['all', 'submission', 'booked', 'approved', 'rejected', 'cancelled'];
                $tableId = $kode_jurusan
                    ? 'assetBookingFasilitasJurusan-' . $kode_jurusan . '-Table'
                    : 'assetBookingFasilitasUmumTable';
            @endphp

            <div class="tab-content" id="pills-tabContent">
                @foreach ($statusBookingTabContent as $index => $status)
                    @php
                        $currentTableId = $tableId . '-' . $status; // ID tabel untuk status saat ini
                    @endphp
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="pills-{{ $status }}"
                        role="tabpanel" aria-labelledby="pills-{{ $status }}-tab" tabindex="0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table basic-table bordered-table w-100 row-border sm-table"
                                    id="{{ $currentTableId }}">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Aset</th>
                                            <th>Nama Peminjam</th>
                                            <th>Nama Event</th>
                                            <th>Kategori Event</th>
                                            <th>Waktu Loading</th>
                                            <th>Waktu Pemakaian</th>
                                            <th>Waktu Bongkar</th>
                                            <th>Pembayaran</th>
                                            <th>Harga Sewa</th>
                                            <th>Status Peminjaman</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>
    <script>
        $(document).ready(function() {
            const kodeJurusan = "{{ $kode_jurusan ?? '' }}"; // Jika null, dikosongkan
            const tableId = kodeJurusan ? `assetBookingFasilitasJurusan-${kodeJurusan}-Table` :
                "assetBookingFasilitasUmumTable";

            let loadedTabs = {};

            function loadTable(statusBooking) {
                if (!loadedTabs[statusBooking]) {
                    $(`#${tableId}-${statusBooking}`).DataTable({
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: kodeJurusan ?
                                "{{ route('assets.getDataBooking', ':kodeJurusan') }}".replace(
                                    ':kodeJurusan',
                                    kodeJurusan) : "{{ route('assets.getDataBooking') }}",
                            type: 'GET',
                            data: {
                                status_booking: statusBooking
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'asset.name',
                                name: 'asset.name'
                            },
                            {
                                data: 'user.name',
                                name: 'user.name'
                            },
                            {
                                data: 'usage_event_name',
                                name: 'usage_event_name'
                            },
                            {
                                data: 'asset_category.category_name',
                                name: 'asset_category.category_name'
                            },
                            {
                                data: 'loading_date',
                                name: 'loading_date',
                                render: function(data, type, row) {
                                    function formatDate(dateStr) {
                                        if (!dateStr) return ''; // Jika null, kosongkan
                                        let date = new Date(dateStr);
                                        let options = {
                                            day: '2-digit',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        };
                                        return date.toLocaleDateString('id-ID', options).replace(
                                                '.', ':')
                                            .replace(':', '.');
                                    }

                                    return `${formatDate(row.loading_date_start)} - <br> ${formatDate(row.loading_date_end)}`;
                                }
                            },

                            {
                                data: 'usage_date',
                                name: 'usage_date',
                                render: function(data, type, row) {
                                    function formatDate(dateStr) {
                                        let date = new Date(dateStr);
                                        let options = {
                                            day: '2-digit',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        };
                                        return date.toLocaleDateString('id-ID', options).replace(
                                                '.', ':')
                                            .replace(':', '.');
                                    }

                                    return `${formatDate(row.usage_date_start)} - <br> ${formatDate(row.usage_date_end)}`;
                                }
                            },
                            {
                                data: 'unloading_date',
                                name: 'unloading_date',
                                render: function(data, type, row) {
                                    function formatDate(dateStr) {
                                        if (!dateStr) return ''; // Jika null, kosongkan
                                        let date = new Date(dateStr);
                                        let options = {
                                            day: '2-digit',
                                            month: 'short',
                                            year: 'numeric',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        };
                                        return date.toLocaleDateString('id-ID', options).replace(
                                                '.', ':')
                                            .replace(':', '.');
                                    }

                                    return row.unloading_date ? formatDate(row.unloading_date) :
                                        '-';
                                }
                            },
                            {
                                data: 'payment_type',
                                name: 'payment_type'
                            },
                            {
                                data: 'total_amount',
                                name: 'total_amount',
                                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function(data, type, row) {
                                    if (!data) return ''; // Jika data null atau undefined

                                    // Kapitalisasi kata pertama
                                    let capitalizedStatus = data.charAt(0).toUpperCase() + data
                                        .slice(1);

                                    // Tentukan kelas warna berdasarkan status
                                    let statusClass = 'bg-dark'; // Default merah jika tidak cocok
                                    if (data === 'submission') statusClass = 'bg-warning-600';
                                    else if (data === 'booked') statusClass = 'bg-info-600';
                                    else if (data === 'approved') statusClass = 'bg-success-600';
                                    else if (data === 'rejected') statusClass = 'bg-danger-600';

                                    return `<span class="btn rounded-10 w-75 px-10 py-10 ${statusClass} text-white text-sm fw-bold">
                                        ${capitalizedStatus}
                                    </span>`;
                                }
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                        ],

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

    @include('components.script-crud')
@endpush
