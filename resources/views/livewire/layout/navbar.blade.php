<!-- Navbar -->
<nav id="navbar" class="fixed top-0 left-0 h-16 bg-red-900 text-white shadow-lg transition-all duration-300 ease-in-out z-30" style="left: 256px; width: calc(100% - 256px); background-color: #7c2d2d;">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('dashboard') }}" class="text-gray-200 hover:text-white transition-colors">Home</a>
            @if(!request()->routeIs('dashboard'))
                <span class="text-gray-300">/</span>
                <span class="text-gray-300">{{ str_replace('-', ' ', ucfirst(request()->route()->getName())) }}</span>
            @endif
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg border-none transition-all hover:bg-white hover:bg-opacity-90">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=7c2d2d&color=fff" alt="Profile" class="w-6 h-6 rounded-full">
                <span class="text-sm font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-lg btn border-none shadow-xl py-2 z-50" style="display: none;">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-none transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
                <hr class="my-1">
                <form action="{{ route('logout') }}" method="GET" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 border-none hover:bg-red-50 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Adjust navbar position when sidebar is toggled -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const navbar = document.getElementById('navbar');
        
        if (!sidebar || !navbar) return;
        
        // Function to update navbar position
        function updateNavbarPosition() {
            if (sidebar.classList.contains('collapsed')) {
                navbar.style.left = '80px';
            } else {
                navbar.style.left = '256px';
            }
        }
        
        // Update navbar position when sidebar is toggled
        const observer = new MutationObserver(function(mutations) {
            updateNavbarPosition();
        });
        
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        
        // Initial position
        updateNavbarPosition();
    });
</script>
