<style>
    .left-sidebar {
        font-family: 'Inter', sans-serif;
        background-color: #134e4a !important; /* primary-900 */
        box-shadow: 4px 0 10px -3px rgba(0, 0, 0, 0.1);
        border-right: none;
    }

    .brand-logo {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }

    .sidebar-nav ul .nav-small-cap {
        color: #5eead4 !important; /* primary-300 */
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-top: 1.5rem;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link {
        color: #ccfbf1 !important; /* primary-100 */
        border-radius: 0.75rem;
        transition: all 0.2s ease-in-out;
        margin-bottom: 0.25rem;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link i {
        color: #99f6e4 !important; /* primary-200 */
        font-size: 1.25rem;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover,
    .sidebar-nav ul .sidebar-item .sidebar-link.active {
        background-color: #0d9488 !important; /* primary-600 */
        color: #ffffff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover i,
    .sidebar-nav ul .sidebar-item .sidebar-link.active i {
        color: #ffffff !important;
    }

    .close-btn {
        color: #ccfbf1 !important;
    }

    /* Scrollbar styling for sidebar */
    .scroll-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .scroll-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    .scroll-sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
</style>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/dashboard" class="text-nowrap logo-img">
                <img src="{{ asset('images/profile/YPC.png') }}" width="60" alt="" class="m-auto" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <!-- DASHBOARD -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/dashboard" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- DATA MASTER -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Data Master</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/user" aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">User</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/participant" aria-expanded="false">
                        <span>
                            <i class="ti ti-tie"></i>
                        </span>
                        <span class="hide-menu">Participant</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/group" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Group</span>
                    </a>
                </li>
                @if (Auth::user()->level === 'admin')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="/device" aria-expanded="false">
                            <span>
                                <i class="ti ti-square"></i>
                            </span>
                            <span class="hide-menu">Device</span>
                        </a>
                    </li>
                @endif

                <!-- MANAJEMEN WAKTU -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Manajemen Waktu</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/jamKerja" aria-expanded="false">
                        <span>
                            <i class="ti ti-briefcase"></i>
                        </span>
                        <span class="hide-menu">Jam Kerja</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/shift" aria-expanded="false">
                        <span>
                            <i class="ti ti-sitemap"></i>
                        </span>
                        <span class="hide-menu">Shift</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/waktuLibur" aria-expanded="false">
                        <span>
                            <i class="ti ti-circle-off"></i>
                        </span>
                        <span class="hide-menu">Waktu Libur</span>
                    </a>
                </li>

                <!-- PRESENSI -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Manajemen Presensi</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/jadwalParticipant" aria-expanded="false">
                        <span>
                            <i class="ti ti-clock"></i>
                        </span>
                        <span class="hide-menu">Jadwal Participant</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/presensi" aria-expanded="false">
                        <span>
                            <i class="ti ti-presentation"></i>
                        </span>
                        <span class="hide-menu">Data Presensi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('laporan.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-report"></i>
                        </span>
                        <span class="hide-menu">Laporan</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
