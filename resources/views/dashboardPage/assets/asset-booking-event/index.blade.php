@extends('layout.layout')
@section('title', 'Asset Bookings Management')
@php
    $title = 'Asset Bookings Management';
    $subTitle = 'Asset Bookings Management';

@endphp

@section('content')
    <div id="asset-container" data-kode-jurusan="{{ $kode_jurusan }}"></div>
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between">
            <h6 class="card-title mb-0 align-content-center px-20 pt-20">Aset Fasilitas
                {{ $kode_jurusan ?? 'Umum' }}
            </h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <label for="filterTahun" class="form-label mb-0">Tahun:</label>
                <select id="filterTahun" name="tahun" class="form-select form-select-sm" style="width: auto;">
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                </select>

                <a href="{{ route('assetBookingEvent.report') }}" id="btnDownload"
                    class="btn btn-dark d-flex align-items-center gap-2">
                    <iconify-icon icon="typcn:export-outline" class="text-xl"></iconify-icon>
                    Rekapan Booking
                </a>


                <!-- Tombol Tambah Booking -->
                <button class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#modalAddManualBooking">
                    <iconify-icon icon="ic:baseline-plus" class="text-xl"></iconify-icon>
                    Tambah Booking
                </button>
            </div>
        </div>

        <div class="card-body px-24">
            @php
                $statusBooking = [
                    'submission_booking' => ['label' => 'Konfirmasi Booking', 'color' => 'warning-600'],
                    'booked' => ['label' => 'Booking Disetujui', 'color' => 'primary-900'],
                    'submission_full_payment' => ['label' => 'Konfirmasi Surat', 'color' => 'primary-600'],
                    'approved' => ['label' => 'Disetujui', 'color' => 'success-600'],
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
                                            <th>Nama Peminjam</th>
                                            <th>Keterangan</th>
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
    @include('dashboardPage.assets.asset-booking-event.modal.add-manual-booking')
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
                                "{{ route('assets.getDataBookingEvent', ':kodeJurusan') }}".replace(
                                    ':kodeJurusan',
                                    kodeJurusan) : "{{ route('assets.getDataBookingEvent') }}",
                            type: 'GET',
                            data: {
                                status_booking: statusBooking
                            },
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                searchable: false,
                                orderable: false
                            }, {
                                data: 'action',
                                name: 'action'
                            },
                            {
                                data: 'shorten_name',
                                name: 'shorten_name',
                            },
                            {
                                data: 'description',
                                name: 'description',
                            }
                        ],

                    });
                    loadedTabs[statusBooking] = true;
                }
            }
            $('.tab-asset-booking').on('click', function() {
                let statusBooking = $(this).data('status-booking');
                setTimeout(() => {
                    $('.modal').appendTo(
                        'body'
                    ); // Pindahkan semua modal ke body agar tetap bisa muncul di semua tab
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
    <script>
        const selectTahun = document.getElementById('filterTahun');
        const btnDownload = document.getElementById('btnDownload');

        function updateHref() {
            const year = selectTahun.value;
            const baseUrl = "{{ route('assetBookingEvent.report') }}";
            btnDownload.href = baseUrl + '?year=' + encodeURIComponent(year);
        }

        // Update link saat halaman load dan saat user ganti tahun
        updateHref();
        selectTahun.addEventListener('change', updateHref);
    </script>
    @include('components.script-crud')
@endpush
