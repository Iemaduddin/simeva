<script src="{{ asset('assets/js/event-calendar.min.js') }}"></script>
<script src="{{ asset('assets/js/moment-with-locales.js') }}"></script>

<script>
    let timelineCalendar = null;

    function loadTimelineUsage(targetSelector, scope) {
        const container = document.querySelector(targetSelector);
        const fetchUrl = container.dataset.fetchUrl;
        if (!fetchUrl) return;

        $.ajax({
            url: fetchUrl,
            method: 'GET',
            data: {
                scope
            },
            success: function(response) {
                // Hapus isi container
                container.innerHTML = '';

                // Destroy instance lama (kalau ada)
                if (timelineCalendar && typeof timelineCalendar.destroy === 'function') {
                    timelineCalendar.destroy();
                    timelineCalendar = null;
                }
                // Buat instance kalender baru
                timelineCalendar = EventCalendar.create(container, {
                    view: 'resourceTimelineMonth',
                    locale: 'id',
                    headerToolbar: {
                        start: 'prev,next today',
                        center: 'title',
                        end: 'resourceTimelineDay,resourceTimelineWeek,resourceTimelineMonth'
                    },
                    buttonText: {
                        today: 'today',
                        resourceTimelineDay: 'day',
                        resourceTimelineWeek: 'week',
                        resourceTimelineMonth: 'month'
                    },
                    firstDay: 1, // Senin sebagai hari pertama
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    resources: response.resources || [],
                    events: response.events || [],
                    resourceLabelDidMount: function(info) {
                        // Ubah cursor menjadi pointer
                        info.el.style.cursor = 'pointer';

                        // Tambahkan event listener
                        info.el.addEventListener('click', function(e) {
                            e.preventDefault();

                            // Dapatkan URL dari extendedProps
                            const detailUrl = info.resource.extendedProps &&
                                info.resource.extendedProps.detailAsset;

                            if (detailUrl) {
                                // Buka URL di tab baru
                                window.open(detailUrl, '_blank');
                            }
                        });
                    },
                    eventClick: function(info) {

                        const event = info.event;

                        // Tambahkan pengecekan null/undefined untuk menghindari error
                        const userBooking = event.extendedProps && event
                            .extendedProps
                            .userBooking ? event.extendedProps.userBooking : '-';
                        $('#detailTimelineUsageAsset')
                            .find('.detail-event-title').text(event.title)
                            .end()
                            .find('.detail-event-date').text(formatDateRange(event
                                .start, event.end))
                            .end()
                            .find('.detail-usage-user-booking').text(userBooking)
                            .end()
                            .modal('show');
                    },
                    eventDidMount: function(info) {
                        if (info.event.extendedProps && info.event.extendedProps
                            .description) {
                            info.el.title = info.event.extendedProps.description;
                        }
                    },
                    dayMaxEvents: true,
                    nowIndicator: true,
                    allDaySlot: true,
                    navLinks: true,
                    editable: false,
                    selectable: false,
                    selectMirror: true,
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }

                });
            },
            error: function(xhr) {
                console.error('Gagal memuat data kalender:', xhr);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        moment.locale('id');
        const selectFilter = document.querySelector('#facility-filter');
        const timelineContainer = document.querySelector('#timeline-usage-asset');

        if (!timelineContainer) return;

        const targetSelector = `#${timelineContainer.id}`;
        const fetchScope = () => {
            return selectFilter ? selectFilter.value : timelineContainer.dataset.defaultScope;
        };

        // Event listener (jika ada filter)
        if (selectFilter) {
            selectFilter.addEventListener('change', function() {
                loadTimelineUsage(targetSelector, this.value);
            });
        }

        // First load
        loadTimelineUsage(targetSelector, fetchScope());
    });

    function formatDateRange(start, end) {
        const startMoment = moment(start);
        const endMoment = moment(end);

        const durationInHours = endMoment.diff(startMoment, 'hours');

        if (durationInHours >= 24) {
            // Jika selisih >= 24 jam, tampilkan tanggal mulai dan tanggal akhir
            return `${startMoment.format('dddd, DD MMMM YYYY')} - ${endMoment.format('dddd, DD MMMM YYYY')}`;
        } else {
            // Jika selisih < 24 jam, tampilkan tanggal + jam mulai dan jam akhir
            return `${startMoment.format('dddd, DD MMMM YYYY [pukul] HH.mm')} - ${endMoment.format('HH.mm')}`;
        }
    }
</script>
