@extends('layout.layout')
@section('title', 'Notifikasi')
@php
    $title = 'Notifikasi';
    $subTitle = 'Notifikasi';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex align-items-center gap-2">
            <iconify-icon icon="ic:baseline-notifications" class="text-xl text-primary-700"></iconify-icon>
            <h5 class="card-title mb-0">Notifikasi</h5>
        </div>

        <div class="table-responsive">

            <table id="notificationsTable" class="table">
                <thead>
                    <tr>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isRead = $notification->read_at !== null;

                            $sender = \App\Models\User::find($data['user_id']);
                            $profilePhoto = $sender->profile_picture ?? 'default-avatar.png';

                            $booking = $data['booking'] ?? null;
                            $eventId = $data['event_id'] ?? null;
                            $asset = $booking->asset ?? null;
                            $jurusan = $asset->jurusan ?? null;
                            $role = auth()->user()->getRoleNames()->first(); // Simpan dulu untuk efisiensi
                            if ($role === 'Organizer' && $eventId) {
                                $routeName = 'detail.event.page';
                                $routeParam = ['id' => $eventId];
                            } elseif ($role === 'UPT PU') {
                                $routeName = 'asset.fasum.bookings';
                                $routeParam = [];
                            } elseif ($role === 'Kaur RT') {
                                $routeName = 'asset.fasum.eventBookings';
                                $routeParam = [];
                            } elseif ($role === 'Admin Jurusan' && isset($jurusan['kode_jurusan'])) {
                                $routeName = 'asset.fasjur.bookings';
                                $routeParam = ['kode_jurusan' => $jurusan['kode_jurusan']];
                            }

                            // Default fallback jika routing gagal
                            $link = '#';
                            if ($routeName && Route::has($routeName)) {
                                try {
                                    $link = route($routeName, $routeParam);
                                } catch (\Exception $e) {
                                    $link = '#';
                                }
                            }
                        @endphp

                        <tr>
                            <td class="p-0">
                                <a href="javascript:void(0);"
                                    class="d-block px-24 py-12 d-flex align-items-start gap-3 justify-content-between"
                                    onclick="markAsRead('{{ $notification->id }}', '{{ $link }}')">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ $profilePhoto ? asset('storage/' . $profilePhoto) : asset('assets/images/user.png') }}"
                                            onerror="this.onerror=null; this.src='{{ asset('assets/images/user.png') }}';"
                                            alt="User Avatar" class="w-44-px h-44-px rounded-circle flex-shrink-0">

                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">
                                                {{ $data['title'] ?? 'Notifikasi Baru' }}
                                            </h6>
                                            <p class="mb-0 text-sm text-secondary-light">
                                                {{ $data['message'] ?? 'Tidak ada pesan' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">
                                        {{ $isRead ? 'Telah dibaca' : \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}

                                    </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#notificationsTable').DataTable({
                paging: true,
                searching: false,
                ordering: false,
                info: false,
                lengthChange: false
            });

            // Sembunyikan header
            $('#notificationsTable thead').hide();
        });
    </script>
    <script>
        function markAsRead(notificationId, redirectUrl) {
            fetch("{{ route('notifications.read', '__ID__') }}".replace('__ID__', notificationId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = redirectUrl; // Redirect ke halaman sesuai route
                    }
                });
        }
    </script>
@endpush
