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
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Managment</span>
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
                <span class="hide-menu">Presensi</span>
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

          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
