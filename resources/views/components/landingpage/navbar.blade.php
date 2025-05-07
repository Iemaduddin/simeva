<style>
    .text-truncate-custom {
        max-width: 150px;
        /* Atur sesuai kebutuhan */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
    }
</style>
<!-- ==================== Mobile Menu Start Here ==================== -->
<div class="mobile-menu scroll-sm d-lg-none d-block">
    <button type="button" class="close-button"><i class="ph ph-x"></i> </button>
    <div class="mobile-menu__inner">
        <!-- Logo Start -->
        <div class="logo d-flex align-items-center justify-content-center">
            <!-- Logo Section -->
            <a href="{{ route('home') }}" class="link me-12">
                <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema" style="width: 3em;">
            </a>
            <!-- Title Section -->
            <h2 class="text-main-600 m-0"><i>SIMEVA</i></h2>
        </div>
        <!-- Logo End  -->
        <div class="mobile-menu__menu">

            <ul class="nav-menu flex-align nav-menu--mobile">

                <li class="nav-menu__item {{ Route::currentRouteName() === 'home' ? 'activePage' : '' }}">
                    <a href="{{ route('home') }}" class="nav-menu__link">Beranda</a>
                </li>
                <li class="nav-menu__item {{ Route::currentRouteName() === 'event' ? 'activePage' : '' }}">
                    <a href="{{ route('event') }}" class="nav-menu__link">Event</a>
                </li>
                <li class="nav-menu__item {{ Route::currentRouteName() === 'organizer' ? 'activePage' : '' }}">
                    <a href="{{ route('organizer') }}" class="nav-menu__link">Penyelenggara</a>
                </li>
                <li class="nav-menu__item {{ Route::currentRouteName() === 'calender' ? 'activePage' : '' }}">
                    <a href="{{ route('calender') }}" class="nav-menu__link">Kalender</a>
                </li>
                <li class="nav-menu__item {{ Route::currentRouteName() === 'aset-bmn' ? 'activePage' : '' }}">
                    <a href="{{ route('aset-bmn') }}" class="nav-menu__link">Aset BMN</a>
                </li>
                <li class="nav-menu__item {{ Route::currentRouteName() === 'tutorial' ? 'activePage' : '' }}">
                    <a href="{{ route('tutorial') }}" class="nav-menu__link">Panduan</a>
                </li>
                @auth

                    <!-- Jika Sudah Login, Tampilkan Nama & Dropdown -->
                    <div class="dropdown mt-24">
                        <a class="btn bg-main-50 border border-main-600 px-24 hover-bg-main-600 rounded-pill p-9 d-flex align-items-center justify-content-center text-2xl text-neutral-500 hover-text-white hover-border-main-600 me-5 dropdown-toggle"
                            href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                                    $imageProfile = $profilPath ? asset('storage/' . $profilPath) : '';
                                }
                            @endphp
                            @if ($imageProfile !== '')
                                <div style="width: 30px; height: 30px;">
                                    <img id="profilePicture" src="{{ $imageProfile }}" alt="Profile Picture"
                                        class="rounded-circle object-fit-cover w-100 h-100">
                                </div>
                            @else
                                <i class="ph ph-user-circle"></i>
                            @endif
                            @if (Auth::user()->roles()->where('name', 'Organizer')->exists())
                                <h6 class="ms-2 m-0 text-neutral-500 dropdown-text">
                                    {{ Auth::user()->organizer->shorten_name }}</h6>
                            @else
                                <h6 class="ms-2 m-0 text-neutral-500 dropdown-text text-truncate-custom">
                                    {{ Auth::user()->name }}</h6>
                            @endif
                        </a>


                        <ul class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0"
                            aria-labelledby="userDropdown">
                            <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                <div class="card-body p-16">
                                    @hasanyrole(['Super Admin', 'UPT PU', 'Kaur RT', 'Admin Jurusan', 'Organizer'])
                                        <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                href="{{ route('stakeholderUsers') }}"><i class="ph ph-user"></i>
                                                Dashboard</a></li>
                                    @endhasanyrole
                                    @hasanyrole(['Participant', 'Tenant'])
                                        <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                href="{{ route('profileUserHomepage') }}"><i class="ph ph-user"></i>
                                                Pengaturan Akun</a></li>
                                        @hasrole('Tenant')
                                            <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                    href="{{ route('profile.myAssetBooking') }}"><i class="ph ph-cube"></i>
                                                    Daftar Booking</a>
                                            </li>
                                            <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                    href="{{ route('item.saved', 'asset') }}"><i class="ph ph-bookmark-simple"></i>
                                                    Daftar Simpan</a>
                                            </li>
                                        @endhasrole
                                        @hasrole('Participant')
                                            <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                    href="{{ route('profile.myEvent') }}"><i class="ph ph-cube"></i>
                                                    Kegiatanku</a>
                                            </li>
                                            <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                    href="{{ route('item.saved', 'event') }}"><i class="ph ph-bookmark-simple"></i>
                                                    Daftar Simpan</a>
                                            </li>
                                        @endhasrole
                                    @endhasanyrole


                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger hover-bg-main-50"><i
                                                    class="ph ph-sign-out"></i>
                                                Logout</button>
                                        </form>
                                    </li>
                                </div>
                            </div>
                        </ul>
                    </div>
                @else
                    <!-- Login Link -->
                    <div class="mt-24">

                        <a href="{{ route('showLoginPage') }}"
                            class="btn bg-main-50 border border-main-600 px-24 hover-bg-main-600 rounded-pill p-9 d-flex align-items-center justify-content-center text-2xl text-neutral-500 hover-text-white hover-border-main-600 me-5 btn-login-home">
                            <i class="ph ph-user-circle"></i>
                            <h6 class="ms-2 m-0 login-text">Login</h6>
                        </a>
                    </div>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <!-- ==================== Mobile Menu End Here ==================== -->


    <!-- ==================== Header Start Here ==================== -->
    <header class="header">
        <div class="container">
            <nav class="header-inner flex-between gap-8">

                <div class="header-content-wrapper flex-align flex-grow-1 ms-10">
                    <!-- Logo Start -->
                    <div class="logo d-flex align-items-center justify-content-center">
                        <!-- Logo Section -->
                        <a href="{{ route('home') }}" class="link me-12">
                            <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="Logo Polinema"
                                style="width: 3em;">
                        </a>
                        <!-- Title Section -->
                        <h2 class="text-main-600 m-0"><i>SIMEVA</i></h2>
                    </div>
                    <!-- Logo End  -->

                    <!-- Menu Start  -->
                    <div class="header-menu d-lg-block d-none">

                        <ul class="nav-menu flex-align ">
                            <li class="nav-menu__item {{ Route::currentRouteName() === 'home' ? 'activePage' : '' }}">
                                <a href="{{ route('home') }}" class="nav-menu__link">Beranda</a>
                            </li>
                            <li class="nav-menu__item {{ Route::currentRouteName() === 'event' ? 'activePage' : '' }}">
                                <a href="{{ route('event') }}" class="nav-menu__link">Event</a>
                            </li>
                            <li
                                class="nav-menu__item {{ Route::currentRouteName() === 'organizer' || Route::currentRouteName() === 'detail_organizer' ? 'activePage' : '' }}">
                                <a href="{{ route('organizer') }}" class="nav-menu__link">Penyelenggara</a>
                            </li>
                            <li class="nav-menu__item {{ Route::currentRouteName() === 'calender' ? 'activePage' : '' }}">
                                <a href="{{ route('calender') }}" class="nav-menu__link">Kalender</a>
                            </li>
                            <li class="nav-menu__item {{ Route::currentRouteName() === 'aset-bmn' ? 'activePage' : '' }}">
                                <a href="{{ route('aset-bmn') }}" class="nav-menu__link">Aset BMN</a>
                            </li>
                            <li class="nav-menu__item {{ Route::currentRouteName() === 'tutorial' ? 'activePage' : '' }}">
                                <a href="{{ route('tutorial') }}" class="nav-menu__link">Panduan</a>
                            </li>
                        </ul>
                    </div>
                    <!-- Menu End  -->
                </div>

                <!-- Header Right Start -->
                <div class="header-right d-flex align-items-center">
                    @php
                        use Illuminate\Support\Carbon;

                        $user = auth()->user();

                        if ($user) {
                            $unreadNotifications = $user
                                ->unreadNotifications()
                                ->whereDate('created_at', Carbon::today())
                                ->get();

                            $allNotifications = $user->notifications()->whereDate('created_at', Carbon::today())->get();

                            $displayNotifications =
                                $allNotifications->count() <= 5 ? $allNotifications : $unreadNotifications;
                        } else {
                            $displayNotifications = collect(); // Kosongkan jika tidak ada user
                        }
                    @endphp

                    @if (Auth::check())

                        <div class="dropdown flex-shrink-0">
                            <button
                                class="w-44 h-44 flex-center bg-main-50 rounded-circle text-main-600 text-lg hover-text-white hover-bg-main-600 transition-1"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ph-bold ph-bell-simple"></i>
                                @if ($unreadNotifications->count() > 0)
                                    <span
                                        class="w-22 h-22 flex-center rounded-circle bg-main-two-600 text-white text-xs position-absolute top-n6 end-n4">
                                        {{ $unreadNotifications->count() }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-lg bg-white p-0 scroll-sm">
                                <div
                                    class="m-16 py-12 px-16 rounded-10 bg-main-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                    <div>
                                        <h6 class="text-lg text-main-light fw-semibold mb-0">Notifications</h6>
                                    </div>
                                    <span
                                        class="text-main-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">
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

                                            $routeName = 'profile.myAssetBooking';
                                            $routeParam = ['id' => $sender->id];

                                        @endphp

                                        <a href="javascript:void(0);"
                                            class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between {{ $bgClass }}"
                                            onclick="markAsRead('{{ $notification->id }}', '{{ route($routeName, $routeParam) }}')">
                                            <div class="text-black hover-text-primary d-flex align-items-center gap-3">
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
                                    <a href="" class="text-primary-600 fw-semibold text-md">See All
                                        Notifications</a>
                                </div>
                            </div>
                        </div>
                        <!-- Jika Sudah Login, Tampilkan Nama & Dropdown -->
                        <div class="dropdown d-none d-lg-block ">
                            <a class="btn bg-main-50 border border-main-600 px-24 hover-bg-main-600 rounded-pill p-9 d-flex align-items-center justify-content-center text-2xl text-neutral-500 hover-text-white hover-border-main-600 me-5 dropdown-toggle"
                                href="#" role="button" id="userDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
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
                                        $imageProfile = $profilPath ? asset('storage/' . $profilPath) : '';
                                    }
                                @endphp
                                @if ($imageProfile !== '')
                                    <div style="width: 30px; height: 30px;">
                                        <img id="profilePicture" src="{{ $imageProfile }}" alt="Profile Picture"
                                            class="rounded-circle object-fit-cover w-100 h-100">
                                    </div>
                                @else
                                    <i class="ph ph-user-circle"></i>
                                @endif
                                @if (Auth::user()->roles()->where('name', 'Organizer')->exists())
                                    <h6 class="ms-2 m-0 text-neutral-500 dropdown-text">
                                        {{ Auth::user()->organizer->shorten_name }}</h6>
                                @else
                                    <h6 class="ms-2 m-0 text-neutral-500 dropdown-text text-truncate-custom">
                                        {{ Auth::user()->name }}</h6>
                                @endif
                            </a>


                            <ul class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0"
                                aria-labelledby="userDropdown">
                                <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                    <div class="card-body p-16">
                                        @hasanyrole(['Super Admin', 'UPT PU', 'Kaur RT', 'Admin Jurusan', 'Organizer'])
                                            <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                    href="{{ route('stakeholderUsers') }}"><i class="ph ph-user"></i>
                                                    Dashboard</a></li>
                                        @endhasanyrole
                                        @hasanyrole(['Participant', 'Tenant'])
                                            <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                    href="{{ route('profileUserHomepage') }}"><i class="ph ph-user"></i>
                                                    Pengaturan Akun</a></li>
                                            @hasrole('Tenant')
                                                <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                        href="{{ route('profile.myAssetBooking') }}"><i class="ph ph-cube"></i>
                                                        Daftar Booking</a>
                                                </li>
                                                <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                        href="{{ route('item.saved', 'asset') }}"><i
                                                            class="ph ph-bookmark-simple"></i>
                                                        Daftar Simpan</a>
                                                </li>
                                            @endhasrole
                                            @hasrole('Participant')
                                                <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                        href="{{ route('profile.myEvent') }}"><i class="ph ph-cube"></i>
                                                        Kegiatanku</a>
                                                </li>
                                                <li><a class="dropdown-item hover-bg-main-50  text-neutral-700"
                                                        href="{{ route('item.saved', 'event') }}"><i
                                                            class="ph ph-bookmark-simple"></i>
                                                        Daftar Simpan</a>
                                                </li>
                                            @endhasrole
                                        @endhasanyrole


                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="dropdown-item text-danger hover-bg-main-50"><i
                                                        class="ph ph-sign-out"></i>
                                                    Logout</button>
                                            </form>
                                        </li>
                                    </div>
                                </div>
                            </ul>
                        </div>
                    @else
                        <!-- Login Link -->
                        <div class="d-none d-lg-block">
                            <a href="{{ route('showLoginPage') }}"
                                class="btn bg-main-50 border border-main-600 px-24 hover-bg-main-600 rounded-pill p-9 d-flex align-items-center justify-content-center text-2xl text-neutral-500 hover-text-white hover-border-main-600 me-5 btn-login-home">
                                <i class="ph ph-user-circle"></i>
                                <h6 class="ms-2 m-0 login-text">Login</h6>
                            </a>
                        </div>
                    @endif

                    <!-- Mobile Menu Toggle -->
                    <button type="button"
                        class="toggle-mobileMenu d-lg-none px-24 text-neutral-200 d-flex align-items-center justify-content-center">
                        <i class="ph ph-list"></i>
                    </button>
                </div>
                <!-- Header Right End -->

            </nav>
        </div>
        <!-- CSS untuk efek hover pada teks -->
        <style>
            .dropdown-toggle:hover .dropdown-text {
                color: white !important;
            }

            .btn-login-home:hover .login-text {
                color: white !important;
            }
        </style>
    </header>
    <!-- ==================== Header End Here ==================== -->
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
