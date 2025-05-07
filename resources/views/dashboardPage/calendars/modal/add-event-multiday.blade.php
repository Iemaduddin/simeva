<div class="modal fade" id="addMultiCalendarEvent" tabindex="-1" aria-labelledby="addMultiCalendarEventLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="addMultiCalendarEventLabel">Tambah Event Baru</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <form class="form-event-calendar" action="{{ route('add.calendarEvent') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Event</label>
                            <input type="text" class="form-control radius-8" name="title"
                                placeholder="Enter Event Title" required>
                        </div>

                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal Mulai</label>
                            <input type="text" class="form-control radius-8 event_date_start" name="event_date_start"
                                readonly required>
                        </div>
                        <div class="col-md-6 mb-20">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal Akhir</label>
                            <input type="text" class="form-control radius-8 event_date_end" name="event_date_end"
                                readonly required>
                        </div>
                        <input type="hidden" name="all_day_true" value="all_day">
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
                            Tambah
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
