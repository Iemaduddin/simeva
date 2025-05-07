  <!-- Modal View Event -->
  <div class="modal fade" id="detailCalendarEvent" tabindex="-1" aria-labelledby="detailCalendarEvent" aria-hidden="true">
      <div class="modal-dialog modal-dialog modal-dialog-centered">
          <div class="modal-content radius-16 bg-base">
              <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                  <h1 class="modal-title fs-5" id="detailCalendarEvent">Detail Event</h1>
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
                      <span class="text-secondary-light txt-sm fw-medium">Dibuat oleh:</span>
                      <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4 detail-event-created-by"></h6>
                  </div>

              </div>
          </div>
      </div>
  </div>
  <script src="{{ asset('assets/js/full-calendar.js') }}"></script>
  <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
  <script src="{{ asset('assets/js/moment-with-locales.js') }}"></script>
  <script>
      let datenow = "{{ \Carbon\Carbon::now()->format('Y-m-d') }}";
      let userLoggedIn = @json(auth()->check() ? true : false);
      let userRole = @json(auth()->check() ? auth()->user()->getRoleNames()->first() : '');
      let userShortenName = @json(auth()->check() ? auth()->user()->organizer->shorten_name ?? '' : '');


      let calendar;

      document.addEventListener('DOMContentLoaded', function() {
          calendar = $("#calendar").fullCalendar({
              locale: 'id',
              header: {
                  left: "title",
                  center: "agendaDay,agendaWeek,month",
                  right: "prev,next today",
              },
              editable: false,
              selectable: false,
              droppable: false,
              firstDay: 1,
              defaultView: "month",
              view: 'dayGridView',

              // Untuk menghapus text jam
              timeFormat: {
                  month: '',
              },
              events: function(start, end, callback) {
                  // Dapatkan kategori yang dipilih
                  let selectedCategories = [];
                  $('.category-filter:checked').each(function() {
                      selectedCategories.push($(this).val());
                  });

                  // Jika "All" dicentang, berarti semua kategori dicentang
                  if ($('#category-all').prop('checked')) {
                      selectedCategories = ['All'];
                  }

                  // Kirim data filter ke server
                  $.ajax({
                      url: "{{ route('calendarEvents.getData') }}",
                      method: 'GET',
                      data: {
                          category: selectedCategories,
                      },

                      success: function(data) {
                          callback(data);
                      },
                      failure: function() {
                          alert('Error loading calendar data');
                      }
                  });
              },
              eventRender: function(event, element) {
                  let eventCategory = event.category;
                  let colorClass = '';

                  if (eventCategory) {
                      switch (eventCategory) {
                          case 'Seminar':
                              colorClass = 'bg-danger-600';
                              break;
                          case 'Kuliah Tamu':
                              colorClass = 'bg-success-600';
                              break;
                          case 'Pelatihan':
                              colorClass = 'bg-primary-600';
                              break;
                          case 'Workshop':
                              colorClass = 'bg-info-600';
                              break;
                          case 'Kompetisi':
                              colorClass = 'bg-warning-600';
                              break;
                          case 'Lainnya':
                              colorClass = 'bg-purple-600';
                              break;
                          default:
                              colorClass = 'bg-purple-600'; // fallback
                      }
                  } else {
                      colorClass = 'bg-purple-600'; // fallback jika tidak ada category
                  }

                  // Tambahkan class ke element event
                  element.addClass(colorClass);

                  let context = element.closest('.calendar-wrapper').data('context');

                  if (context === 'dashboard') {
                      let isOwner = false;

                      if (userRole === 'Super Admin' && event.event_owner === 'Admin') {
                          isOwner = true;
                      } else if (userRole === 'Organizer' && event.event_owner === userShortenName) {
                          isOwner = true;
                      }

                      if (isOwner) {
                          let deleteIcon = $(
                              '<span style="color:red; margin-right:5px; cursor:pointer;">✖</span>'
                          );
                          deleteIcon.attr('data-id', event.id);

                          // Tambahkan icon di awal title
                          element.find('.fc-event-title').prepend(deleteIcon);

                          const modal = $('#modalConfirmDelete');
                          const form = modal.find('#idDeleteEvent')[0];

                          const originalAction = $(form).data(
                              'original-action'); // ambil dari atribut data

                          deleteIcon.off('click').on('click', function(e) {
                              e.stopPropagation();

                              const calendarEventId = $(this).data('id');
                              const id = calendarEventId.split('_')[1];

                              form.action = originalAction.replace(':id',
                                  id); // selalu replace dari original

                              modal.modal('show');
                          });

                      }
                  }
              },
              @if (auth()->check() &&
                      auth()->user()->hasAnyRole(['Super Admin', 'Organizer']))
                  dayClick: function(date, allDay, jsEvent, view) {
                      var today = moment().startOf('day');
                      var clickedDate = moment(date); // Convert date ke moment

                      if (clickedDate.isSameOrAfter(today, 'day')) {
                          $('.event_date').val(clickedDate.format('YYYY-MM-DD'));
                          $('#addCalendarEvent').modal('show');
                      } else {
                          alert('Tanggal tidak boleh sebelum hari ini!');
                      }
                  },
              @endif
              eventClick: function(event, jsEvent, view) {
                  const context = $(jsEvent.target).closest('.calendar-wrapper').data('context');

                  if (userLoggedIn && userRole === 'Super Admin') {
                      if (event.event_owner === 'Admin' && context === 'dashboard') {
                          showEditModal(event);
                      } else {
                          showDetailModal(event);
                      }
                  } else if (userLoggedIn && userRole === 'Organizer') {
                      if (event.event_owner === userShortenName && context === 'dashboard') {
                          showEditModal(event);
                      } else {
                          showDetailModal(event);
                      }
                  } else {
                      showDetailModal(event);
                  }
              },
          });
      });

      function showDetailModal(event) {
          $('#detailCalendarEvent')
              .find('.detail-event-title').text(event.title)
              .end()
              .find('.detail-event-date').text(formatDateRange(event.start, event.end, event
                  .allDay))
              .end()
              .find('.detail-event-created-by').text(event.created_by)
              .end()
              .modal('show');
      }

      function showEditModal(event) {
          let modal;
          if (event.end !== null) {
              modal = $('#editCalendarEventMultiDay');
              modal.find('input[name="event_date_start"]').val(moment(event.start).format('YYYY-MM-DD'));
              modal.find('input[name="event_date_end"]').val(moment(event.end).format('YYYY-MM-DD'));

          } else {
              modal = $('#editCalendarEvent');
          }

          // ISI VALUE FORM
          modal.find('input[name="title"]').val(event.title);
          modal.find('input[name="event_date"]').val(moment(event.start).format('YYYY-MM-DD'));

          // Cek apakah allDay
          if (event.allDay) {
              modal.find('input[name="all_day"]').prop('checked', true);
              modal.find('input[name="time_start"]').val('').prop('disabled', true);
              modal.find('input[name="time_end"]').val('').prop('disabled', true);
          } else {
              modal.find('input[name="all_day"]').prop('checked', false);
              modal.find('input[name="time_start"]').val(moment(event.start).format('HH:mm')).prop('disabled', false);
              modal.find('input[name="time_end"]').val(moment(event.end).format('HH:mm')).prop('disabled', false);
          }

          // Cek is_public
          modal.find('input[name="is_public"]').prop('checked', event.is_public ?? false);

          const fullId = event.id; // misal event.id = 'calendar_123'
          const id = fullId.split('_')[1]; // ambil setelah karakter '_'

          const form = modal.find('#idUpdateCalendar')[0]; // ambil elemen form (native DOM)

          form.action = form.action.replace(':id', id); // ganti ':id' di action dengan id

          modal.modal('show');
      }

      // pakai moment.js
      moment.locale('id')

      function formatDateRange(start, end, isAllDay = false) {
          const startMoment = moment(start);
          const endMoment = moment(end);

          if (isAllDay && !end) {
              return startMoment.format('dddd, DD MMMM YYYY');
          } else if (isAllDay && end) {
              return `${startMoment.format('dddd, DD MMMM YYYY')} - ${endMoment.format('dddd, DD MMMM YYYY')}`;
          } else {
              return `${startMoment.format('dddd, DD MMMM YYYY [pukul] HH.mm')} - ${endMoment.format('HH.mm')}`;
          }
      }


      // Ketika checkbox All dicentang, centang semua kategori
      $('#category-all').on('change', function() {
          if ($(this).prop('checked')) {
              $('.category-filter').prop('checked', true);
          } else {
              $('.category-filter').not('#category-all').prop('checked', false);
          }
          calendar.fullCalendar('refetchEvents'); // Refresh events with the new filters
      });

      // Ketika kategori individual dicentang atau tidak, update All checkbox
      $('.category-filter').not('#category-all').on('change', function() {
          var allChecked = true;
          $('.category-filter').not('#category-all').each(function() {
              if (!$(this).prop('checked')) {
                  allChecked = false;
              }
          });
          $('#category-all').prop('checked', allChecked);
          calendar.fullCalendar('refetchEvents'); // Refresh events with the new filters
      });
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
          document.querySelectorAll('.form-event-calendar').forEach(function(form) {
              form.addEventListener('submit', function(event) {
                  event.preventDefault(); // Mencegah submit form secara tradisional

                  const formData = new FormData(form);

                  // Menemukan tombol submit di dalam form
                  const submitButton = form.querySelector('button[type="submit"]');
                  if (submitButton) {

                      // Simpan teks asli tombol jika belum disimpan
                      if (!submitButton.dataset.originalText) {
                          submitButton.dataset.originalText = submitButton.textContent.trim();
                      }
                      // Menonaktifkan tombol submit
                      submitButton.disabled = true;

                      // Menambahkan spinner jika belum ada
                      if (!submitButton.querySelector('.spinner-border')) {
                          const spinner = document.createElement('span');
                          spinner.className = 'spinner-border spinner-border-sm me-3 text-center';
                          spinner.role = 'status';
                          spinner.ariaHidden = 'true';
                          submitButton.prepend(spinner);
                      }

                      // Mengubah teks tombol menjadi "Memproses..."
                      submitButton.childNodes.forEach(node => {
                          if (node.nodeType === 3) { // text node
                              node.textContent = '';
                          }
                      });
                  }
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
                          } else if (data.status === 'error') {
                              const firstErrorKey = Object.keys(data.errors)[0];
                              const firstErrorMessage = data.errors[firstErrorKey][0];
                              notyf.error(firstErrorMessage);
                          }
                      })
                      .catch(error => {
                          if (error.errors) {
                              Object.values(error.errors).forEach(messages => {
                                  messages.forEach(message => {
                                      notyf.error(message);
                                  });
                              });
                          } else {
                              notyf.error(error.message || 'Terjadi kesalahan pada server!');
                          }
                      })
                      .finally(() => {
                          // Mengaktifkan kembali tombol submit setelah selesai
                          if (submitButton) {
                              submitButton.disabled = false;

                              // Mengembalikan teks tombol ke kondisi semula
                              submitButton.textContent = submitButton.dataset.originalText;

                              // Hapus spinner
                              const spinner = submitButton.querySelector('.spinner-border');
                              if (spinner) {
                                  spinner.remove();
                              }
                          }
                      });
              });
          });
      });
  </script>
