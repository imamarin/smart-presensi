<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .app-header {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid #f0fdfa;
    }

    .navbar-light .navbar-nav .nav-link {
        color: #4b5563;
        transition: all 0.2s ease-in-out;
    }

    .navbar-light .navbar-nav .nav-link:hover,
    .navbar-light .navbar-nav .nav-link:focus {
        color: #0d9488;
        background-color: #f0fdfa;
        border-radius: 0.5rem;
    }

    .dropdown-menu-animate-up {
        border: none;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-radius: 0.75rem;
        padding: 0.5rem;
    }

    .dropdown-item {
        border-radius: 0.5rem;
        transition: all 0.2s;
        color: #374151;
    }

    .dropdown-item:hover {
        background-color: #f0fdfa;
        color: #0d9488;
    }

    .btn-outline-primary {
        color: #0d9488;
        border-color: #0d9488;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-outline-primary:hover {
        background-color: #0d9488;
        color: #ffffff;
        border-color: #0d9488;
        box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);
    }
</style>

<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light px-4 py-2">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover p-2" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2 fs-5"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover p-1" href="#" id="drop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle p-1" style="border: 2px solid #0d9488;">
                            <img src="{{ asset('images/profile/user-1.jpg') }}" alt="" width="35" height="35" class="rounded-circle">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up mt-2" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="{{ route('me') }}" class="d-flex align-items-center gap-2 dropdown-item px-3 py-2 mb-1">
                                <i class="ti ti-user fs-5"></i>
                                <p class="mb-0 fs-4 fw-medium">My Profile</p>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="d-block w-100 mt-2 pt-2" style="border-top: 1px solid #f3f4f6;">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100 d-block">Logout</button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
