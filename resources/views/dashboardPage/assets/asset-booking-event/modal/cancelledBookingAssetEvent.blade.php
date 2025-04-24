<div class="modal fade" id="modalCancelBooking-{{ $eventId }}" tabindex="-1" aria-labelledby="modalCancelBookingLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-xl fw-semibold mb-0" id="modalCancelBookingLabel">Konfirmasi Pembatalan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assetBookingEvent.cancel', ['eventId' => $eventId]) }}" method="POST"
                data-table="{{ $tableId }}">
                @csrf
                <div class="modal-body">
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
                                            @if ($booking->status !== 'cancelled')
                                                <input type="hidden" name="bookings[{{ $loop->index }}][id]"
                                                    value="{{ $booking->id }}">

                                                <select name="bookings[{{ $loop->index }}][status]"
                                                    class="form-select status-select" data-id="{{ $booking->id }}">
                                                    <option value="approved">Tetap</option>
                                                    <option value="rejected">Batalkan</option>
                                                </select>

                                                <div class="reason-reject mt-3 d-none" id="reason-{{ $booking->id }}"
                                                    style="flex: 1;">
                                                    <textarea class="form-control reason-textarea" name="bookings[{{ $loop->index }}][reason_cancelled]"
                                                        placeholder="Masukkan alasan pembatalan" rows="2" disabled></textarea>
                                                </div>
                                            @elseif ($booking->status === 'cancelled')
                                                <input type="hidden" name="bookings[{{ $loop->index }}][id]"
                                                    value="{{ $booking->id }}">
                                                <input type="hidden" name="bookings[{{ $loop->index }}][status]"
                                                    value="approved">
                                                <span class='badge bg-warning'>⚠ Dibatalkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-danger-600">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
