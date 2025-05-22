<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle
                    class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

                @php
                    use Illuminate\Support\Carbon;

                    $unreadNotifications = auth()
                        ->user()
                        ->unreadNotifications()
                        ->whereDate('created_at', Carbon::today()) // Hanya notif hari ini
                        ->get();

                    $allNotifications = auth()
                        ->user()
                        ->notifications()
                        ->whereDate('created_at', Carbon::today()) // Hanya notif hari ini
                        ->get();

                    // Jika notif <= 5, tampilkan semua (unread & read), jika > 5 hanya unread
                    $displayNotifications = $allNotifications->count() <= 5 ? $allNotifications : $unreadNotifications;
                @endphp

                <div class="dropdown">
                    <button
                        class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center position-relative"
                        type="button" data-bs-toggle="dropdown">
                        <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                        @if ($unreadNotifications->count() > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger text-white text-xs">
                                {{ $unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div
                            class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                            </div>
                            <span
                                class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">
                                {{ $displayNotifications->count() }}
                            </span>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-4" id="notif-list">
                            @foreach ($displayNotifications as $notification)
                                @php
                                    $data = $notification->data;
                                    $isRead = $notification->read_at !== null;
                                    $bgClass = $isRead ? 'bg-light' : 'bg-primary-25';

                                    // Ambil pengirim notifikasi
                                    $sender = \App\Models\User::find($data['user_id']);
                                    $profilePhoto = $sender->profile_picture ?? 'default-avatar.png';

                                    // Cek apakah ada booking dan asset
                                    $booking = $data['booking'] ?? null;
                                    $asset = $booking->asset ?? null;
                                    $jurusan = $asset->jurusan ?? null;

                                    // Tentukan route berdasarkan jurusan
                                    if ($jurusan) {
                                        $routeName = 'asset.fasjur.bookings';
                                        $routeParam = ['kode_jurusan' => $jurusan->kode_jurusan];
                                    } else {
                                        $routeName = 'asset.fasum.bookings';
                                        $routeParam = [];
                                    }
                                @endphp

                                <a href="javascript:void(0);"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between {{ $bgClass }}"
                                    onclick="markAsRead('{{ $notification->id }}', '{{ route($routeName, $routeParam) }}')">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $profilePhoto) }}" alt="User Avatar"
                                            class="w-44-px h-44-px rounded-circle flex-shrink-0">
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">
                                                {{ $data['title'] ?? 'Notifikasi Baru' }}
                                            </h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">
                                                {{ $data['message'] ?? 'Tidak ada pesan' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">
                                        {{ Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </span>
                                </a>
                            @endforeach

                        </div>

                        <div class="text-center py-12 px-16">
                            <a href="" class="text-primary-600 fw-semibold text-md">See All Notifications</a>
                        </div>
                    </div>
                </div>

                {{-- <script>
                    function markAsRead(notificationId) {
                        fetch(`/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => {
                            if (response.ok) {
                                location.reload();
                            }
                        });
                    }
                </script> --}}



                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button"
                        data-bs-toggle="dropdown">
                        @php
                            $imageProfile = '';
                            if (Auth::user()->hasRole('Organizer')) {
                                $logoPath = Auth::user()->organizer->logo;
                                $imageProfile = $logoPath
                                    ? asset('storage/' . $logoPath)
                                    : asset('assets/images/user.png');
                            } else {
                                $profilPath = Auth::user()->profile_picture;
                                $imageProfile = $profilPath
                                    ? asset('storage/' . $profilPath)
                                    : asset('assets/images/user.png');
                            }

                        @endphp
                        <img src="{{ $imageProfile }}" alt="image"
                            class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div
                            class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                @if (Auth::check())
                                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ Auth::user()->name }}
                                    </h6>

                                    <span class="text-secondary-light fw-medium text-sm">
                                        {{ Auth::user()->getRoleNames()->first() }}</span>
                                @endif
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        <ul class="to-top-list">
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="{{ route('profileUserDashboard') }}">
                                    <iconify-icon icon="mage:user" class="icon text-xl"></iconify-icon> My
                                    Profile
                                </a>
                            </li>
                            {{-- <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="{{ route('email') }}">
                                    <iconify-icon icon="tabler:message-check" class="icon text-xl"></iconify-icon>
                                    Inbox
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="{{ route('company') }}">
                                    <iconify-icon icon="icon-park-outline:setting-two"
                                        class="icon text-xl"></iconify-icon> Setting
                                </a>
                            </li> --}}
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                    href="javascript:void()"
                                    onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                </a>
                                <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div><!-- Profile dropdown end -->
            </div>
        </div>
    </div>
</div>
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
