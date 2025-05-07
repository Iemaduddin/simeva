<div class="modal fade" id="editCalendarEvent" tabindex="-1" aria-labelledby="editCalendarEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="editCalendarEventLabel">Edit Event</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form class="form-event-calendar" id="idUpdateCalendar"
                    action="{{ route('update.calendarEvent', ':id') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Event</label>
                            <input type="text" class="form-control radius-8" name="title"
                                placeholder="Enter Event Title" required>
                        </div>

                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal</label>
                            <input type="text" class="form-control radius-8 event_date" name="event_date" readonly
                                required>
                        </div>

                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jam Mulai</label>
                            <input type="text" class="form-control radius-8 time_start" name="time_start">
                        </div>

                        <div class="col-md-4 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jam Selesai</label>
                            <input type="text" class="form-control radius-8 time_end" name="time_end">
                        </div>

                        <!-- ALL DAY checkbox -->
                        <div class="col-md-12 mb-10">
                            <div class="form-check style-check d-flex align-items-center mb-2">
                                <input class="form-check-input radius-4 border border-neutral-400 me-2 all_day"
                                    type="checkbox" name="all_day"
                                    id="all_day"@if (auth()->user()->hasRole('Super Admin')) checked @endif>
                                <label class="form-check-label" for="all_day">Sepanjang Hari (All
                                    Day)</label>
                            </div>
                        </div>
                        @if (auth()->user()->hasRole('Super Admin'))
                            <!-- Is Public checkbox (optional) -->
                            <div class="col-md-12 mb-10">
                                <div class="form-check style-check d-flex align-items-center mb-2">
                                    <input class="form-check-input radius-4 border border-neutral-400 me-2"
                                        type="checkbox" name="is_public" id="is_public"
                                        @if (auth()->user()->hasRole('Super Admin')) checked @endif>
                                    <label class="form-check-label" for="is_public">Publikasikan</label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                        <button type="reset"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Perbarui
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
