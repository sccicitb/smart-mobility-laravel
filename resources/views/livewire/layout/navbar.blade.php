<nav class="navbar">
    <div class="navigasi">
        <div class="d-none d-md-block" style="width: 200px;">
        </div>
        <!-- Breadcrumb -->
        <div class="autoBread" style="padding: 10px;">
            <div class="d-flex align-items-center">
                <h5 class="text-white mb-0">{{ $pageTitle }}</h5>
                <nav aria-label="breadcrumb" class="ms-2">
                    <ol class="breadcrumb mb-0 bg-transparent">
                        <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                        @foreach ($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item text-white-50">{{ $breadcrumb }}</li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Profile Dropdown (di kanan) -->
        <div class="d-none d-md-block">
            <div class="dropdown">
                <button class="btn dropdown-toggle text-white d-flex align-items-center gap-2" type="button"
                    id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>Hi, {{ auth()->user()->name }}!</span>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=DB0000&color=fff"
                        alt="Profile" class="profile-img" width="32" height="32">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="lucide-user"></i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="lucide-settings"></i>
                            Pengaturan
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="GET">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="lucide-log-out"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>