<!-- Modal untuk menambah atau mengedit event -->
@if (auth()->check())
    @if (auth()->user()->hasRole(['Super Admin', 'Tenant']))
        <div id="event-modal" class="modal fade" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
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
                            <input type="hidden" id="available_days" value="{{ $assetDetails->available_at }}">
                            <input type="hidden" id="status" value="{{ $assetDetails->status }}">
                            <div class="row gy-4">
                                <div class="col-sm-4">
                                    <label for="usage_date_display" class="text-neutral-700 text-lg fw-medium mb-12">
                                        Tanggal dan Waktu Acara <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="usage_date_display" placeholder="Pilih Tanggal" readonly>
                                    <input type="hidden" id="usage_date" name="usage_date">
                                </div>

                                <div class="col-sm-4">
                                    <label for="start_time" class="text-neutral-700 text-lg fw-medium mb-12">Waktu Mulai
                                        Acara
                                        <span class="text-danger-600">*</span> </label>
                                    <input type="time"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="start_time" name="start_time" placeholder="Masukkan Waktu Mulai Acara"
                                        required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="end_time" class="text-neutral-700 text-lg fw-medium mb-12">Waktu Selesai
                                        Acara
                                        <span class="text-danger-600">*</span> </label>
                                    <input type="time"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="end_time" name="end_time" placeholder="Masukkan Waktu Selesai Acara"
                                        required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="usage_event_name" class="text-neutral-700 text-lg fw-medium mb-12">Nama
                                        Acara
                                        <span class="text-danger-600">*</span> </label>
                                    <input type="text"
                                        class="common-input bg-main-25 rounded-pill border-transparent focus-border-main-600"
                                        id="usage_event_name" name="usage_event_name" placeholder="Masukkan nama acara"
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
<script>
    let isUserLoggedIn = @json(Auth::check());
</script>

<script src="{{ asset('assets/js/full-calendar.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr.js/flatpickr.js') }}"></script>
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
<script>
    $(document).ready(function() {

        let date = new Date();
        let d = date.getDate();
        let m = date.getMonth();
        let y = date.getFullYear();
        let selectedEvent = null;
        let newEventData = null;

        // Inisialisasi Calendar
        let calendar = $("#calendar").fullCalendar({
            locale: 'id',
            header: {
                left: "title",
                center: "agendaDay,agendaWeek,month",
                right: "prev,next today",
            },
            editable: true,
            selectable: true,
            droppable: true,
            firstDay: 1, // Mulai Senin
            defaultView: "month",
            events: [{
                    title: "All Day Event",
                    start: new Date(y, m, 1),
                },
                {
                    id: 999,
                    title: "Repeating Event",
                    start: new Date(y, m, d - 3, 16, 0),
                    allDay: false,
                    className: "bg-info",
                },
            ],
            eventClick: function(event) {
                selectedEvent = event;
                $("#event-title").val(event.title);
                $("#event-category").val(event.className[0]);
                // $("#modal-title").text("Edit Event");
                $("#event-modal").modal("show");
            },
            dayClick: function(date, allDay, jsEvent, view) {
                if (!isUserLoggedIn) {
                    $("#event-beforeLogin-modal").modal("show");
                    return;
                }
                let statusAsset = $('#status').val();
                if (statusAsset == 'OPEN') {
                    // Pastikan user sudah login sebelum mengambil nilai dari #available_days
                    let availableDays = $("#available_days").length ? $("#available_days").val()
                        .split(
                            ", ").map(x => x.trim()) : [];

                    if (availableDays.length === 0) {
                        $("#event-modal").modal("show");
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


                    $("#event-modal").modal("show");

                    let year = clickedDate.getFullYear();
                    let month = (clickedDate.getMonth() + 1).toString().padStart(2, '0');
                    let day = clickedDate.getDate().toString().padStart(2, '0');

                    let formattedSelectedDate = `${year}-${month}-${day}`;

                    let prevDate = new Date(date);
                    prevDate.setDate(prevDate.getDate() - 1);

                    let prevYear = prevDate.getFullYear();
                    let prevMonth = (prevDate.getMonth() + 1).toString().padStart(2, '0');
                    let prevDay = prevDate.getDate().toString().padStart(2, '0');

                    let formattedPrevDate = `${prevYear}-${prevMonth}-${prevDay}`;

                    let formattedDisplayDate = clickedDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    // Set value tetap 'YYYY-MM-DD' dan tampilan berubah
                    $("#usage_date").val(formattedSelectedDate); // Simpan format YYYY-MM-DD
                    $("#usage_date_display").val(formattedDisplayDate);
                } else {
                    $('#status-closed-modal').modal('show');
                }

            }


        });
    });
</script>
