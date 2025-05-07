<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="/" class="sidebar-logo">
            <img src="{{ asset('assets/images/simeva-light.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/simeva-dark.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo_polinema.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="/">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            @hasanyrole(['Super Admin', 'Organizer'])
                <li>
                    <a href="{{ route('calendarEvent') }}">
                        <iconify-icon icon="oui:calendar" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                </li>
            @endhasanyrole
            @hasrole('Super Admin')
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
                            <a href="{{ route('tenantUsers') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                                Tenant</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('jurusanProdi') }}">
                        <iconify-icon icon="hugeicons:building-05" class="menu-icon"></iconify-icon>
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
                <li class="dropdown mt-2">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="bi:building-check" class="menu-icon"></iconify-icon>
                        <span>Asset Bookings</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('asset.fasum.bookings') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Fasilitas
                                Umum </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'TI') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Fasilitas
                                TI </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'TS') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-900 w-auto"></i> Fasilitas
                                Sipil </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'TM') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-700 w-auto"></i> Fasilitas
                                Mesin </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'TE') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-800 w-auto"></i> Fasilitas
                                Elektro </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'TK') }}"><i
                                    class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Fasilitas
                                Kimia </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'AK') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-600 w-auto"></i> Fasilitas
                                Akuntansi </a>
                        </li>
                        <li>
                            <a href="{{ route('asset.fasjur.bookings', 'AN') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-900 w-auto"></i> Fasilitas
                                AN </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-menu-group-title">Events Management</li>
                <li>
                    <a href="{{ route('data.listEvent') }}">
                        <iconify-icon icon="carbon:event" class="menu-icon"></iconify-icon>
                        <span>List Event</span>
                    </a>
                </li>
            @endhasrole
            @hasrole('UPT PU')
                <li>
                    <a href="{{ route('tenantUsers') }}">
                        <iconify-icon icon="fluent:person-home-28-regular" class="menu-icon"></iconify-icon>
                        <span>Tenant Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('asset.fasum.bookings') }}">
                        <iconify-icon icon="icons8:library" class="menu-icon"></iconify-icon>
                        <span>Asset Bookings</span>
                    </a>
                </li>
            @endhasrole
            @hasrole('Kaur RT')
                <li>
                    <a href="{{ route('assets.fasum') }}">
                        <iconify-icon icon="bi:building-gear" class="menu-icon"></iconify-icon>
                        <span>Data Asset Fasum</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('asset.fasum.eventBookings') }}"><iconify-icon icon="icons8:library"
                            class="menu-icon"></iconify-icon>
                        <span>Asset Bookings </span>

                    </a>
                </li>
            @endhasrole
            @hasrole('Admin Jurusan')
                @php
                    $kode_jurusan_user = Auth::user()->jurusan->kode_jurusan;
                @endphp
                <li>
                    <a href="{{ route('assets.fasjur', $kode_jurusan_user) }}"><iconify-icon icon="bi:building-gear"
                            class="menu-icon"></iconify-icon>
                        <span>Data Asset {{ $kode_jurusan_user }} </span>

                    </a>
                </li>
                <li>
                    <a href="{{ route('asset.fasjur.bookings', $kode_jurusan_user) }}"><iconify-icon
                            icon="icons8:library" class="menu-icon"></iconify-icon>
                        <span>Asset {{ $kode_jurusan_user }} Bookings </span>

                    </a>
                </li>
            @endhasrole
            @hasrole('Organizer')
                @php
                    $shorten_name = Auth::user()->organizer->shorten_name;
                @endphp
                @if (Auth::user()->organizer->organizer_type !== 'Kampus' && Auth::user()->organizer->organizer_type !== 'Jurusan')
                    <li>
                        <a href="{{ route('data.team-members', $shorten_name) }}"><iconify-icon
                                icon="fluent:people-team-20-regular" class="menu-icon"></iconify-icon>
                            <span> Kelola Anggota Tim</span>

                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('data.events', $shorten_name) }}"><iconify-icon icon="ic:baseline-event-note"
                            class="menu-icon"></iconify-icon>
                        <span> Kelola Event </span>

                    </a>
                </li>
            @endhasrole

    </div>
</aside>
