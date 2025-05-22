<div class="modal fade" id="modalRegisterEvent{{ $event->id }}" tabindex="-1"
    aria-labelledby="modalLabel{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('register.event', ['id' => $event->id]) }}" method="POST" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{ $event->id }}">Konfirmasi
                    Pendaftaran Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mendaftar pada event berikut?</p>
                <ul>
                    <li><strong>Event:</strong> {{ $event->title }}</li>
                    <li><strong>Penyelenggara:</strong> {{ $event->organizers->shorten_name }}
                    </li>
                </ul>
                @php
                    $userCategory = Auth::user()->category_user;
                    // Cari harga sesuai kategori user
                    $userPrice = $event->prices->firstWhere('scope', $userCategory);
                    // Jika tidak ketemu, cari harga umum
                    if (!$userPrice) {
                        $userPrice = $event->prices->firstWhere('scope', 'Umum');
                    }
                @endphp
                @if (!$event->is_free)
                    <div class="alert alert-warning mt-3">
                        <strong>Event ini berbayar.</strong><br>
                        Harga:
                        <strong>{{ $userPrice->price && $userPrice->price != 0 ? 'Rp' . number_format($userPrice->price, 0, ',', '.') : 'Gratis' }}</strong>
                    </div>
                    <div class="mb-3">
                        <label for="payment_proof_{{ $event->id }}" class="form-label">Upload Bukti
                            Pembayaran</label>
                        <input type="hidden" name="price" value="{{ $userPrice->price }}" class="form-control"
                            required>
                        <input type="file" name="proof_of_payment" class="form-control" accept=".png,.jpeg,.jpg"
                            required>
                    </div>
                @else
                    <div class="alert alert-success mt-3">
                        Event ini <strong>gratis</strong>, Anda tidak perlu membayar apa pun.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-main">Daftar</button>
            </div>
        </form>
    </div>
</div>
