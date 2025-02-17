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
                    </ul>
                </div>
                <!-- Menu End  -->
            </div>

            <!-- Header Right Start -->
            <div class="header-right d-flex align-items-center">
                @auth
                    <!-- Jika Sudah Login, Tampilkan Nama & Dropdown -->
                    <div class="dropdown d-lg-block ">
                        <a class="btn bg-main-50 border border-main-600 px-24 hover-bg-main-600 rounded-pill p-9 d-flex align-items-center justify-content-center text-2xl text-neutral-500 hover-text-white hover-border-main-600 me-5 dropdown-toggle"
                            href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ph ph-user-circle"></i>
                            <h6 class="ms-2 m-0 text-neutral-500 dropdown-text">{{ Auth::user()->name }}</h6>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end w-75" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item hover-bg-main-600 hover-text-white text-neutral-700"
                                    href="/dashboard/index2"><i class="ph ph-user"></i>
                                    Dashboard</a></li>
                            <li><a class="dropdown-item hover-bg-main-600 hover-text-white text-neutral-700"
                                    href=""><i class="ph ph-user"></i>
                                    Profilku</a></li>
                            <li><a class="dropdown-item hover-bg-main-600 hover-text-white text-neutral-700"
                                    href=""><i class="ph ph-cube"></i>
                                    Itemku</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="ph ph-sign-out"></i>
                                        Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Login Link -->
                    <a href="{{ route('showLoginPage') }}"
                        class="btn bg-main-50 border border-main-600 px-24 hover-bg-main-600 rounded-pill p-9 d-flex align-items-center justify-content-center text-2xl text-neutral-500 hover-text-white hover-border-main-600 me-5 btn-login-home">
                        <i class="ph ph-user-circle"></i>
                        <h6 class="ms-2 m-0 login-text">Login</h6>
                    </a>
                @endauth

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
