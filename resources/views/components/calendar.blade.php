<!-- Modal untuk menambah atau mengedit event -->
<input type="hidden" id="available_days" value="{{ $assetDetails->available_at }}">
<input type="hidden" id="status" value="{{ $assetDetails->status }}">
@if (auth()->check())
    @if (auth()->user()->hasRole(['Super Admin', 'Tenant']))
        <div id="event-modal-daily" class="modal fade" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content p-10">
                    <div class="modal-header">
                        <h5 id="modal-title" class="modal-title">Lengkapi data berikut</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('asset.booking.tenant', $assetDetails->id) }}" id="form-event"
                        class="needs-validation" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="asset_id" value="{{ $assetDetails->id }}">

                            <div class="row gy-4">
                                <div class="col-sm-4">
                                    <label for="usage_date_display" class="text-neutral-700 text-lg fw-medium mb-2">
                                        Tanggal Peminjaman <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="usage_date_display" placeholder="Pilih Tanggal" readonly>
                                    <input type="hidden" id="usage_date" name="usage_date">
                                </div>

                                <!-- Waktu Mulai -->
                                <div class="col-sm-4">
                                    <label for="start_time" class="text-neutral-700 text-lg fw-medium mb-2">
                                        Waktu Mulai <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="start_time" name="start_time" placeholder="Masukkan Waktu Mulai Penggunaan"
                                        required>
                                </div>

                                <!-- Waktu Selesai -->
                                <div class="col-sm-4">
                                    <label for="end_time" class="text-neutral-700 text-lg fw-medium mb-2">
                                        Waktu Selesai <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="end_time" name="end_time" placeholder="Masukkan Waktu Selesai Penggunaan"
                                        required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="usage_event_name" class="text-neutral-700 text-lg fw-medium mb-12">Nama
                                        Acara
                                        <span class="text-danger-600">*</span> </label>
                                    <input type="text"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="usage_event_name" name="usage_event_name" placeholder="Masukkan Nama Acara"
                                        required>
                                </div>

                                <div class="col-sm-4">
                                    <label for="type_event" class="text-neutral-700 text-lg fw-medium mb-12">
                                        Jenis Acara <span class="text-danger-600">*</span>
                                    </label>
                                    <select
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600 form-select py-14"
                                        id="type_event" name="type_event" required>
                                        <option value="" selected hidden>Pilih Jenis Acara</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="file_personal_identity"
                                        class="text-neutral-700 text-lg fw-medium mb-24">Scan
                                        KTP
                                        <span class="text-danger-600">*</span> </label>
                                    <input type="file" accept=".pdf, .jpg, .jpeg, .png" name="file_personal_identity"
                                        class="form-control border-transparent focus-border-main-600"
                                        id="file_personal_identity" required>
                                </div>
                                <div class="col-sm-4">
                                    <label class="text-neutral-700 text-lg fw-medium mb-24">Jenis Pembayaran
                                        <span class="text-danger-600">*</span> </label>
                                    <div class="flex-align gap-24">
                                        <div class="form-check common-check common-radio mb-0">
                                            <input class="form-check-input" type="radio" name="payment_type"
                                                id="DP" value="dp" required>
                                            <label class="form-check-label fw-normal flex-grow-1" for="DP">DP
                                                (30%)</label>
                                        </div>
                                        <div class="form-check common-check common-radio mb-0">
                                            <input class="form-check-input" type="radio" name="payment_type"
                                                id="Lunas" value="lunas" required>
                                            <label class="form-check-label fw-normal flex-grow-1"
                                                for="Lunas">Lunas</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-sm-12">
                                    <label class="text-success-700 text-lg fw-bold mb-12">Info Tarif: </label>
                                    <div class="row justify-content-start">
                                        <div class="col-sm-4">
                                            <h6>Pembayaran secara DP</h6>
                                            <p id="dp_price">DP 30% : Rp0</p>
                                            <p id="remaining_price">Pelunasan : Rp0</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <h6>Pembayaran secara Lunas</h6>
                                            <input type="hidden" name="amount" id="amount">
                                            <p id="full_price">Pelunasan : Rp0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-secondary rounded-pill flex-center gap-8"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-main rounded-pill flex-center gap-8">
                                    Ajukan
                                    <i class="ph-bold ph-arrow-up-right d-flex text-lg"></i>
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@else
    <div id="event-beforeLogin-modal" class="modal fade" tabindex="-1" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modala-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center my-20">
                    <h5 class="my-25">Harap Register/Login terlebih dahulu</h5>
                    <a href="{{ route('showLoginPage') }}" class="btn btn-primary rounded-pill">Login</a>
                </div>
            </div>
        </div>
    </div>
@endif
<style>
    .fc-event-inner {
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 100% !important;
    }

    .fc-event-time {
        /* display: none !important; */
        margin-right: 5px;
    }
</style>
<script>
    let isUserLoggedIn = @json(Auth::check());
</script>

<script src="{{ asset('assets/js/full-calendar.js') }}"></script>
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data hari tersedia dari backend (ubah ke format array JavaScript)
        const availableDays = @json(explode('|', $assetDetails->available_at));

        // Konversi nama hari ke indeks (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
        const dayMapping = {
            "Minggu": 0,
            "Senin": 1,
            "Selasa": 2,
            "Rabu": 3,
            "Kamis": 4,
            "Jumat": 5,
            "Sabtu": 6
        };

        const enabledDays = availableDays.map(day => dayMapping[day.trim()]).filter(day => day !== undefined);

        // Inisialisasi Flatpickr untuk memilih range tanggal (Bahasa Indonesia)
        flatpickr("#usage_date_display", {
            mode: "range",
            // dateFormat: "d F Y", // Format tanggal dalam bahasa Indonesia
            minDate: "today", // Tidak bisa memilih sebelum hari ini
            // locale: "id", // Menggunakan bahasa Indonesia
            disable: [
                function(date) {
                    return !enabledDays.includes(date.getDay()); // Hanya izinkan hari tertentu
                }
            ],
            onClose: function(selectedDates, dateStr, instance) {
                document.getElementById("usage_date").value =
                    dateStr; // Simpan nilai ke hidden input
            }
        });

        // Inisialisasi Flatpickr untuk waktu mulai & selesai (Bahasa Indonesia)
        flatpickr("#start_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            // locale: "id"
        });

        flatpickr("#end_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            // locale: "id"
        });
    });
</script>



<script>
    let calendar;
    document.addEventListener('DOMContentLoaded', function() {
        let assetId = '{{ $assetDetails->id }}';

        calendar = $("#calendar").fullCalendar({
            locale: 'id',
            header: {
                left: "title",
                center: "agendaDay,agendaWeek,month",
                right: "prev,next today",
            },
            editable: false,
            selectable: true,
            droppable: false,
            firstDay: 1,
            defaultView: "month",
            events: {
                url: "{{ route('asset-booking.getDataCalendar', ['assetId' => ':assetId']) }}".replace(
                    ':assetId',
                    assetId),
                method: 'GET',
                failure: function() {
                    alert('Error loading calendar data');
                }
            },
            viewDisplay: function(view) {
                let availableDays = $("#available_days").length ? $("#available_days").val()
                    .split("|").map(x => x.trim()) : [];

                let today = new Date();
                today.setHours(0, 0, 0, 0); // Hilangkan jam untuk perbandingan hanya pada tanggal

                $(".fc-day").each(function() {
                    let date = $(this).data("date");
                    if (date) {
                        let currentDate = new Date(date);
                        let dayName = currentDate.toLocaleDateString("id-ID", {
                            weekday: "long"
                        });
                        // **Disable tanggal jika lebih kecil dari hari ini atau tidak tersedia dalam availableDays**
                        if (currentDate < today || !availableDays.includes(dayName)) {
                            $(this).css({
                                "background-color": "#F5F5F5", // Warna abu-abu
                                "pointer-events": "none", // Nonaktifkan klik
                                "opacity": "0.5" // Buat lebih transparan
                            });
                        }
                    }
                });
            },
            eventRender: function(event, element) {
                // 1. Terapkan warna default (background dan border event)
                element.css({
                    'backgroundColor': event.backgroundColor,
                    'borderColor': event.borderColor,
                });

                // 2. Set border berdasarkan kategori user
                let borderColor = event.status.includes('submission') ? '#443627' :
                    event.status.includes('booked') ? '#003092' : '#1F7D53';
                element.css('borderLeft', `10px solid ${borderColor}`);


                // 3. Tambahkan ikon berdasarkan kategori user
                if (event.icon) {
                    let iconElement =
                        `<i class="ph-bold ${event.icon}" style="margin-right:5px;"></i>`;

                    let titleElement = element.find('.fc-event-title');
                    if (titleElement.length) {
                        titleElement.prepend(iconElement);
                    }
                }
                // 4. Tambahkan tooltip
                element.tooltip({
                    title: `Status: ${event.status.includes('submission') ? 'Proses Pengajuan' :
                event.status.includes('booked') ? 'Sudah Dipesan' : 'Disetujui'}\n
                Peminjam: ${event.userCategory === 'Internal Kampus' ? 'Internal' : 'Eksternal'}`,
                    placement: 'top',
                    container: 'body'
                });
            },

            // Styling event dan cell setelah render
            eventAfterAllRender: function(view) {
                $('.fc-day').each(function() {
                    let $cell = $(this);
                    let date = moment($cell.data('date'));
                    let dateStr = date.format('YYYY-MM-DD');

                    let events = $('#calendar').fullCalendar('clientEvents');
                    let eventOnDay = events.find(function(event) {
                        return moment(event.start).format('YYYY-MM-DD') === dateStr;
                    });

                    if (eventOnDay) {
                        // Menggunakan class alih-alih CSS langsung
                        $cell.removeClass('bg-primary')
                            .addClass(eventOnDay.userCategory === 'Internal Kampus' ?
                                'bg-primary-100' : 'bg-success-100');
                    }
                });
            },


            // Tampilkan modal detail saat event diklik
            eventClick: function(event) {
                // calendar.fullCalendar('gotoDate', event.start);
                // calendar.fullCalendar('changeView', 'agendaDay');
                $('#event-details-modal')
                    // .find('.modal-title').text(event.title).end()    
                    .find('.event-status').text(event.status.includes('submission') ?
                        'Proses Pengajuan' :
                        event.status.includes('booked') ? 'Sudah Dipesan' : 'Disetujui').end()
                    .find('.event-user-category').text(
                        event.user
                    ).end()
                    .find('.usage-date').text(formatDateRange(event.start, event.end)).end()
                    .find('.loading-date').text(
                        event.loadingDate
                    ).end()
                    .modal('show');
            },

            dayClick: function(date, allDay, jsEvent, view) {
                // const booking_type = "{{ $assetDetails->booking_type ?? '' }}";
                if (!isUserLoggedIn) {
                    $("#event-beforeLogin-modal").modal("show");
                    return;
                }
                let statusAsset = $('#status').val();
                if (statusAsset == 'OPEN') {
                    // Pastikan user sudah login sebelum mengambil nilai dari #available_days
                    let availableDays = $("#available_days").length ? $("#available_days").val()
                        .split(
                            "|").map(x => x.trim()) : [];

                    if (availableDays.length === 0) {
                        $("#event-modal-daily").modal("show");
                        $("#event-modal-annual").modal("show");
                        return;
                    }

                    let clickedDate = new Date(date);
                    let dayName = clickedDate.toLocaleDateString('id-ID', {
                        weekday: 'long'
                    }).trim();



                    if (!availableDays.includes(dayName)) {
                        $("#not-available-modal").modal("show");
                        return;
                    }

                    $("#event-modal-daily").modal("show");


                    let year = clickedDate.getFullYear();
                    let month = (clickedDate.getMonth() + 1).toString().padStart(2, '0');
                    let day = clickedDate.getDate().toString().padStart(2, '0');

                    let formattedSelectedDate = `${year}-${month}-${day}`;

                    // Ini buat tanggal sebelumnya, kalau kamu masih butuh
                    let prevDate = new Date(
                        clickedDate); // harusnya pakai clickedDate, bukan 'date'
                    prevDate.setDate(prevDate.getDate() - 1);

                    let prevYear = prevDate.getFullYear();
                    let prevMonth = (prevDate.getMonth() + 1).toString().padStart(2, '0');
                    let prevDay = prevDate.getDate().toString().padStart(2, '0');

                    let formattedPrevDate = `${prevYear}-${prevMonth}-${prevDay}`;

                    // Set hidden dan tampilan (tidak pakai format apa-apa)
                    $("#usage_date").val(formattedSelectedDate); // Hidden: tetap 'YYYY-MM-DD'
                    $("#usage_date_display").val(formattedSelectedDate); // Display: sama, langsung

                } else {
                    $('#status-closed-modal').modal('show');
                }

            }
        });
    });

    function formatDateRange(start, end) {
        let optionsDateTime = {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };

        let optionsTime = {
            hour: '2-digit',
            minute: '2-digit'
        };

        return new Date(start).toLocaleString('id-ID', optionsDateTime) + ' - ' +
            new Date(end).toLocaleString('id-ID', optionsTime);
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Notyf
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'bottom',
            },
        });

        // Tangani submit form menggunakan AJAX
        document.getElementById('form-event').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit form secara tradisional

            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        notyf.success(data.message);
                        form.reset(); // Reset form setelah sukses
                        const modal = form.closest('.modal');
                        if (modal) {
                            const modalId = modal.id;
                            $(`#${modalId}`).modal('hide'); // Tutup modal
                        }
                        if (typeof calendar !== 'undefined' && calendar !== null) {
                            // Refresh Calendar
                            calendar.fullCalendar('refetchEvents');
                        }
                    } else {
                        notyf.error(data.message);
                    }
                })
                .catch(error => {
                    notyf.error('Terjadi kesalahan saat memproses permintaan.');
                });
        });
    });
</script>

<script>
    $(document).ready(function() {
        let assetId = $("#asset-id").val();
        let typeEvent = $("#type_event");

        // Fungsi untuk menghitung harga
        function calculatePrice() {
            let selectedOption = typeEvent.find(":selected");
            let selectedPrice = selectedOption.data("price"); // Default 1 jika kosong

            if (selectedPrice) {
                let fullPrice = parseFloat(selectedPrice);
                let dpPrice = fullPrice * 0.3;
                let remainingPrice = fullPrice - dpPrice;

                $("#dp_price").text(`DP 30% : Rp${dpPrice.toLocaleString()}`);
                $("#remaining_price").text(`Pelunasan : Rp${remainingPrice.toLocaleString()}`);
                $("#full_price").text(`Pelunasan : Rp${fullPrice.toLocaleString()}`);
                $('#amount').val(fullPrice);
            } else {
                $("#dp_price").text("DP 30% : Rp0");
                $("#remaining_price").text("Pelunasan : Rp0");
                $("#full_price").text("Pelunasan : Rp0");
            }
        }

        function loadCategories() {
            $.ajax({
                url: "{{ route('asset-booking.getDataCategory', '') }}" + "/" + assetId,
                type: "GET",
                success: function(response) {
                    typeEvent.empty();
                    typeEvent.append('<option value="" selected hidden>Pilih Jenis Acara</option>');
                    response.data.forEach(category => {
                        typeEvent.append(
                            `<option value="${category.id}" data-price="${category.external_price}">${category.category_name}</option>`
                        );
                    });
                }
            });
        }

        loadCategories();

        // Event listener untuk perubahan jenis acara
        typeEvent.change(calculatePrice);
    });
</script>
