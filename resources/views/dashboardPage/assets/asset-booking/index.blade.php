@extends('layout.layout')
@section('title', 'Asset Bookings Management')
@php
    $title = 'Asset Bookings Management';
    $subTitle = 'Asset Bookings Management';

@endphp

@section('content')
    <div id="asset-container" data-kode-jurusan="{{ $kode_jurusan }}"></div>
    <div class="card basic-data-table ">
        <div class="card-header d-flex justify-content-between">
            <h6 class=" card-title mb-0 align-content-center px-20 pt-20">Aset Fasilitas
                {{ $kode_jurusan ?? 'Umum' }}
            </h6>
            <button class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalAddManualBooking">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Tambah Booking
            </button>
        </div>
        <div class="card-body px-24">
            @php
                $statusBooking = [
                    // 'all' => ['label' => 'Data Booking Aset Keseluruhan', 'color' => 'primary-900'],
                    'submission_booking' => ['label' => 'Konfirmasi Booking', 'color' => 'warning-600'],
                    'submission_payment' => ['label' => 'Konfirmasi Pembayaran', 'color' => 'success-600'],
                    'waiting_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'info-600'],
                    'approved' => ['label' => 'Disetujui', 'color' => 'success-600'],
                    // 'done' => ['label' => 'Selesai', 'color' => 'primary-600'],
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
    @include('dashboardPage.assets.asset-booking.modal.add-manual-booking')
@endsection
@push('script')
    <script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>

    <script>
        // Gunakan delegation
        let selectedCategory = null;

        $(document).on('change', '#assetSelect', function() {
            const assetId = $(this).val();
            const $categorySelect = $('#categorySelect');
            const $priceTypeSelect = $('#priceTypeSelect');
            $('#priceDisplay').val('');

            $categorySelect.html('<option value="">-- Memuat Kategori --</option>');

            if (assetId) {
                $.ajax({
                    url: `{{ route('getDataAssetCategory', ':id') }}`.replace(':id', assetId),
                    type: 'GET',
                    success: function(data) {
                        $categorySelect.empty().append(
                            '<option value="">-- Pilih Kategori --</option>');
                        $.each(data, function(key, category) {
                            $categorySelect.append(`<option value="${category.id}" 
                        data-price='${JSON.stringify(category)}'>${category.category_name}</option>`);
                        });
                    },
                    error: function() {
                        $categorySelect.html('<option value="">Gagal memuat kategori</option>');
                    }
                });
            }
        });

        $(document).on('change', '#categorySelect', function() {
            const selectedOption = $(this).find('option:selected');
            selectedCategory = selectedOption.data('price');
            $('#priceDisplay').val('');
        });

        $(document).on('change', '#priceTypeSelect', function() {
            if (!selectedCategory) {
                $('#priceDisplay').val('');
                $('#priceValue').val('');
                return;
            }

            const type = $(this).val();
            const basePrice = parseFloat(selectedCategory.external_price);
            let finalPrice = 0;

            if (type === 'external') {
                finalPrice = basePrice;
            } else if (type === 'internal') {
                finalPrice = basePrice * (selectedCategory.internal_price_percentage / 100);
            } else if (type === 'social') {
                finalPrice = basePrice * (selectedCategory.social_price_percentage / 100);
            }

            // Tampilkan ke user (dengan format rupiah)
            $('#priceDisplay').val('Rp ' + finalPrice.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            }));

            // Simpan ke hidden input untuk backend (format angka murni)
            $('#priceValue').val(finalPrice.toFixed(2));
        });
    </script>
    <script>
        function toggleBookingDateTime(status) {
            const wrapper = $('.booking-date-time-wrapper');
            const fields = wrapper.find('input');
            const paymentTypeSelect = $('select[name="payment_type"]');
            const dpOption = paymentTypeSelect.find('option[value="dp"]');
            const lunasOption = paymentTypeSelect.find('option[value="lunas"]');

            const proofInput = $('#proof_of_payment');
            const proofLabel = $('#proof_label');

            if (status === 'booked') {
                wrapper.hide();
                fields.prop('readonly', false);
                fields.prop('disabled', true);
                // Hapus required agar tidak error saat hidden
                fields.removeAttr('required');
                // Aktifkan semua opsi pembayaran & reset selection
                paymentTypeSelect.prop('readonly', false);
                dpOption.prop('disabled', false);
                lunasOption.prop('disabled', true);
                paymentTypeSelect.val('');

                // Reset proof input requirement
                proofInput.removeAttr('required');
                proofLabel.html('Bukti Pembayaran');
            } else if (status === 'approved_full_payment') {
                wrapper.show();
                // Tambahkan kembali required & readonly
                fields.prop('readonly', true);
                fields.prop('disabled', false);

                fields.each(function() {
                    $(this).attr('required', true);
                    fields.removeAttr('disabled');

                });

                // Nonaktifkan DP dan set default ke Lunas
                paymentTypeSelect.prop('readonly', true);
                dpOption.prop('disabled', true);
                lunasOption.prop('disabled', false);
                paymentTypeSelect.val('lunas');

                // Set proof required dan tampilkan bintang merah
                proofInput.prop('required', true);
                if (!proofLabel.html().includes('*')) {
                    proofLabel.html('Bukti Pembayaran <span class="text-danger">*</span>');
                }
            }
        }


        $(document).ready(function() {
            const $statusSelect = $('select[name="status"]');

            // Initial load
            toggleBookingDateTime($statusSelect.val());

            // On change
            $statusSelect.on('change', function() {
                toggleBookingDateTime($(this).val());
            });
        });
    </script>


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
                                name: 'user.name',
                                render: function(data, type, row) {
                                    return row.user && row.user.name ? row.user.name : (row
                                        .external_user ?? '-');
                                }
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

    @include('components.script-crud')
@endpush
