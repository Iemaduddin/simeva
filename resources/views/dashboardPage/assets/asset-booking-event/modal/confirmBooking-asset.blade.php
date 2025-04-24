<div class="modal fade" id="modalConfirmAssetBooking-{{ $eventId }}" tabindex="-1"
    aria-labelledby="modalConfirmAssetBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="modalConfirmAssetBookingLabel">Konfirmasi Booking Aset</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assetBookingEvent.confirm', $eventId) }}" method="POST"
                data-table="{{ $tableId }}">
                @csrf
                <div class="modal-body p-24">
                    <p><strong>Peminjam:</strong> {{ $assetBooking['user'] }}</p>
                    <p><strong>Event:</strong> {{ $assetBooking['event_name'] }}</p>
                    <hr>
                    <div class="table-responsive">
                        <table class="table basic-table bordered-table w-100 row-border" id="confirmAssetBookingEvent">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Event</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listAssetBookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $booking->asset->name }} <br>
                                            {{ \Carbon\Carbon::parse($booking->usage_date_start)->format('d F Y') }}
                                            ({{ \Carbon\Carbon::parse($booking->usage_date_start)->format('H.i') }} -
                                            {{ \Carbon\Carbon::parse($booking->usage_date_end)->format('H.i') }})
                                        </td>
                                        <td>{{ $booking->usage_event_name }}</td>
                                        <td>
                                            @if ($booking->status === 'submission_booking')
                                                <input type="hidden" name="bookings[{{ $loop->index }}][id]"
                                                    value="{{ $booking->id }}">

                                                <select name="bookings[{{ $loop->index }}][status]"
                                                    class="form-select status-select" data-id="{{ $booking->id }}">
                                                    <option value="approved">✔️ Approve</option>
                                                    <option value="rejected">✖️ Reject</option>
                                                </select>

                                                <div class="reason-reject mt-3 d-none" id="reason-{{ $booking->id }}"
                                                    style="flex: 1;">
                                                    <textarea class="form-control reason-textarea" name="bookings[{{ $loop->index }}][reason_rejected]"
                                                        placeholder="Masukkan alasan penolakan" rows="2" disabled></textarea>
                                                </div>
                                            @elseif ($booking->status === 'booked')
                                                <input type="hidden" name="bookings[{{ $loop->index }}][id]"
                                                    value="{{ $booking->id }}">
                                                <input type="hidden" name="bookings[{{ $loop->index }}][status]"
                                                    value="approved">
                                                <span class="badge bg-primary">✅ Booking Disetujui</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer d-flex align-items-end justify-content-end gap-3 mt-24">
                        <button type="reset"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-24 py-12 radius-8"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                            Konfirmasi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Event delegation untuk handle change di semua select status
    document.addEventListener('change', function(e) {
        // Cek jika event berasal dari elemen dengan class 'status-select'
        if (e.target && e.target.classList.contains('status-select')) {
            const id = e.target.dataset.id;
            const reasonBox = document.getElementById(`reason-${id}`);
            const textarea = reasonBox?.querySelector('textarea');

            if (e.target.value === 'rejected') {
                reasonBox.classList.remove('d-none');
                textarea?.removeAttribute('disabled');
                textarea?.setAttribute('required', 'true');
            } else {
                reasonBox.classList.add('d-none');
                textarea?.removeAttribute('required');
                textarea?.setAttribute('disabled', true);
                if (textarea) textarea.value = '';
            }
        }
    });
</script>
