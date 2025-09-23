<nav class="h-24 w-full items-center justify-center flex flex-col inline-block align-middle">
    <div class="w-full justify-between flex pb-8 px-[20px] m-auto items-center pt-4">
        {{-- <div class="d-none d-md-block" style="width: 100px;">
        </div> --}}
        <!-- Breadcrumb -->
        <div class="justify-start items-center p-[10px]">
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
      <div class="hidden md:block" x-data="{ open: false }">
        <div class="relative">
            <button @click="open = !open" class="flex items-center gap-2 bg-white text-[#892120] font-semibold px-3 py-1.5 rounded-lg focus:outline-none text-sm">
                <span>Hi, {{ auth()->user()->name }}!</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=892120&color=fff"
                    alt="Profile" class="w-7 h-7 rounded-full font-semibold">
            </button>

            <!-- Dropdown menu -->
            <ul x-show="open" @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                style="display: none;">
                <li>
                    <a href="#" class="block px-2.5 text-sm font-semibold py-1.5 text-gray-700 hover:bg-gray-100">
                        <i class="lucide-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-2.5 text-sm font-semibold py-1.5 text-gray-700 hover:bg-gray-100">
                        <i class="lucide-settings"></i> Pengaturan
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="GET">
                        @csrf
                        <button type="submit" class="w-full text-left block px-2.5 text-sm font-semibold py-1.5 text-gray-700 hover:bg-gray-100">
                            <i class="lucide-log-out"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
