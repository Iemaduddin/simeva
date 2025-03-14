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
            @php
                $statusBooking = [
                    'all' => ['label' => 'Data Booking Aset Keseluruhan', 'color' => 'primary-900'],
                    'submission_booking' => ['label' => 'Konfirmasi Booking', 'color' => 'warning-600'],
                    'submission_payment' => ['label' => 'Konfirmasi Pembayaran', 'color' => 'success-600'],
                    'waiting_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'info-600'],
                    'approved' => ['label' => 'Disetujui', 'color' => 'success-600'],
                    'done' => ['label' => 'Selesai', 'color' => 'primary-600'],
                    'rejected' => ['label' => 'Ditolak', 'color' => 'danger-600'],
                    'cancelled' => ['label' => 'Dibatalkan', 'color' => 'dark'],
                ];

                $tableId = $kode_jurusan
                    ? 'assetBookingFasilitasJurusan-' . $kode_jurusan . '-Table'
                    : 'assetBookingFasilitasUmumTable';
            @endphp
            <ul class="nav bordered-tab border border-top-0 border-start-0 border-end-0 d-inline-flex nav-pills mb-16"
                id="pills-tab" role="tablist">
                @foreach ($statusBooking as $statusKey => $statusData)
                    <li class="nav-item d-flex justify-content-between align-items-center" role="presentation">
                        <button class="nav-link tab-asset-booking px-16 py-10 {{ $loop->first ? 'active' : '' }}"
                            id="pills-{{ $statusKey }}-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-{{ $statusKey }}" type="button" role="tab"
                            aria-controls="pills-{{ $statusKey }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            data-status-booking="{{ $statusKey }}">
                            <span
                                class="w-12-px h-12-px bg-{{ $statusData['color'] }} rounded-circle fw-medium me-4"></span>
                            {{ $statusData['label'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="pills-tabContent">
                @foreach ($statusBooking as $category_status => $data)
                    @php
                        $currentTableId = $tableId . '-' . $category_status; // ID tabel untuk category_status saat ini
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $category_status }}"
                        role="tabpanel" aria-labelledby="pills-{{ $category_status }}-tab" tabindex="0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table basic-table bordered-table w-100 row-border sm-table"
                                    id="{{ $currentTableId }}">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Nama Aset</th>
                                            <th>Nama Peminjam</th>
                                            <th>Nama Event</th>
                                            {{-- <th>Kategori Event</th> --}}
                                            {{-- <th>Waktu Loading</th> --}}
                                            <th>Waktu Pemakaian</th>
                                            {{-- <th>Waktu Bongkar</th> --}}
                                            <th>Pembayaran</th>
                                            <th>Harga Sewa</th>
                                            {{-- <th>Status Peminjaman</th> --}}
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
                                data: 'action',
                                name: 'action'
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
                            // {
                            //     data: 'asset_category.category_name',
                            //     name: 'asset_category.category_name'
                            // },
                            // {
                            //     data: 'loading_date',
                            //     name: 'loading_date',
                            //     render: function(data, type, row) {
                            //         function formatDate(dateStr) {
                            //             if (!dateStr) return ''; // Jika null, kosongkan
                            //             let date = new Date(dateStr);
                            //             let options = {
                            //                 day: '2-digit',
                            //                 month: 'short',
                            //                 year: 'numeric',
                            //                 hour: '2-digit',
                            //                 minute: '2-digit'
                            //             };
                            //             return date.toLocaleDateString('id-ID', options).replace(
                            //                     '.', ':')
                            //                 .replace(':', '.');
                            //         }

                            //         return `${formatDate(row.loading_date_start)} - <br> ${formatDate(row.loading_date_end)}`;
                            //     }
                            // },

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
                            // {
                            //     data: 'unloading_date',
                            //     name: 'unloading_date',
                            //     render: function(data, type, row) {
                            //         function formatDate(dateStr) {
                            //             if (!dateStr) return ''; // Jika null, kosongkan
                            //             let date = new Date(dateStr);
                            //             let options = {
                            //                 day: '2-digit',
                            //                 month: 'short',
                            //                 year: 'numeric',
                            //                 hour: '2-digit',
                            //                 minute: '2-digit'
                            //             };
                            //             return date.toLocaleDateString('id-ID', options).replace(
                            //                     '.', ':')
                            //                 .replace(':', '.');
                            //         }

                            //         return row.unloading_date ? formatDate(row.unloading_date) :
                            //             '-';
                            //     }
                            // },
                            {
                                data: 'payment_type',
                                name: 'payment_type'
                            },
                            {
                                data: 'total_amount',
                                name: 'total_amount',
                                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
                            },
                        ],

                    });
                    loadedTabs[statusBooking] = true;
                }
            }
            $('.tab-asset-booking').on('click', function() {
                let statusBooking = $(this).data('status-booking');
                setTimeout(() => {
                    $('.modal').appendTo(
                    'body'); // Pindahkan semua modal ke body agar tetap bisa muncul di semua tab
                }, 500);
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
