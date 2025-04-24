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
                <form class="navbar-search">
                    <input type="text" name="search" placeholder="Search">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle
                    class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                <div class="dropdown d-none d-sm-inline-block">
                    <button
                        class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                        type="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/images/lang-flag.png') }}" alt="image"
                            class="w-24 h-24 object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div
                            class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language</h6>
                            </div>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="english">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag1.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">English</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="english">
                            </div>

                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="japan">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag2.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">Japan</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="japan">
                            </div>

                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="france">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag3.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">France</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="france">
                            </div>

                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="germany">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag4.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">Germany</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="germany">
                            </div>

                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="korea">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag5.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">South Korea</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="korea">
                            </div>

                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="bangladesh">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag6.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">Bangladesh</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="bangladesh">
                            </div>

                            <div
                                class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="india">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag7.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">India</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="india">
                            </div>
                            <div class="form-check style-check d-flex align-items-center justify-content-between">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                    for="canada">
                                    <span
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag8.png') }}" alt=""
                                            class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">Canada</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="canada">
                            </div>
                        </div>
                    </div>
                </div><!-- Language dropdown end -->

                <div class="dropdown">
                    <button
                        class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                        type="button" data-bs-toggle="dropdown">
                        <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div
                            class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
                            </div>
                            <span
                                class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">

                            <a href="javascript:void(0)"
                                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                <div
                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                        <img src="{{ asset('assets/images/notification/profile-3.png') }}"
                                            alt="">
                                        <span
                                            class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                    <span
                                        class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                </div>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                <div
                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                        <img src="{{ asset('assets/images/notification/profile-4.png') }}"
                                            alt="">
                                        <span
                                            class="w-8-px h-8-px  bg-neutral-300 rounded-circle position-absolute end-0 bottom-0"></span>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                    <span
                                        class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">2</span>
                                </div>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                <div
                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                        <img src="{{ asset('assets/images/notification/profile-5.png') }}"
                                            alt="">
                                        <span
                                            class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                    <span
                                        class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
                                </div>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                <div
                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                        <img src="{{ asset('assets/images/notification/profile-6.png') }}"
                                            alt="">
                                        <span
                                            class="w-8-px h-8-px bg-neutral-300 rounded-circle position-absolute end-0 bottom-0"></span>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                    <span
                                        class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
                                </div>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                <div
                                    class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                        <img src="{{ asset('assets/images/notification/profile-7.png') }}"
                                            alt="">
                                        <span
                                            class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                    <span
                                        class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                </div>
                            </a>

                        </div>
                        <div class="text-center py-12 px-16">
                            <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All
                                Message</a>
                        </div>
                    </div>
                </div><!-- Message dropdown end -->

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
                                if ($logoPath && \Illuminate\Support\Facades\Storage::exists($logoPath)) {
                                    $imageProfile = asset('storage/' . $logoPath);
                                } elseif ($logoPath) {
                                    $imageProfile = asset($logoPath);
                                } else {
                                    $imageProfile = asset('assets/images/user.png');
                                }
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
