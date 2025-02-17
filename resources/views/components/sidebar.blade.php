<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('index') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/simeva-light.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/simeva-dark.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="dropdown">
            <li>
                <a href="{{ route('index2') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Users Management</li>
            <li class="dropdown mt-2">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Data Users</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('stakeholderUsers') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Stakeholder </a>
                    </li>
                    <li>
                        <a href="{{ route('organizerUsers') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                            Organizer</a>
                    </li>
                    <li>
                        <a href="{{ route('mahasiswaUsers') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Mahasiswa</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                            Tenant</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route(name: 'jurusanProdi') }}">
                    <iconify-icon icon="icons8:library" class="menu-icon"></iconify-icon>
                    <span>Jurusan & Prodi</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Asset Management</li>
            <li class="dropdown mt-2">
                <a href="javascript:void(0)">
                    <iconify-icon icon="bi:building-gear" class="menu-icon"></iconify-icon>
                    <span>Data Assets</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('assets.fasum') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Fasilitas
                            Umum </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'TI') }}"><i
                                class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Fasilitas
                            TI </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'TS') }}"><i
                                class="ri-circle-fill circle-icon text-warning-900 w-auto"></i> Fasilitas
                            Sipil </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'TM') }}"><i
                                class="ri-circle-fill circle-icon text-primary-700 w-auto"></i> Fasilitas
                            Mesin </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'TE') }}"><i
                                class="ri-circle-fill circle-icon text-warning-700 w-auto"></i> Fasilitas
                            Elektro </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'TK') }}"><i
                                class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Fasilitas
                            Kimia </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'AK') }}"><i
                                class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Fasilitas
                            Akuntansi </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.fasjur', 'AN') }}"><i
                                class="ri-circle-fill circle-icon text-primary-900 w-auto"></i> Fasilitas
                            AN </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <iconify-icon icon="bi:building-check" class="menu-icon"></iconify-icon>
                    <span>Assets Booking</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <iconify-icon icon="bi:building-down" class="menu-icon"></iconify-icon>
                    <span>Assets Transaction</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
