<div class="px-6 py-4 w-full animate-pulse">
    {{-- Filter buttons skeleton --}}
    <div class="flex gap-3 mb-6">
        <div class="h-9 w-20 bg-gray-200 rounded-lg"></div>
        <div class="h-9 w-20 bg-gray-200 rounded-lg"></div>
        <div class="h-9 w-24 bg-gray-200 rounded-lg"></div>
    </div>

    {{-- Stats cards skeleton --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @for ($i = 0; $i < 3; $i++)
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="h-3 w-24 bg-gray-200 rounded mb-3"></div>
                <div class="h-7 w-32 bg-gray-200 rounded mb-2"></div>
                <div class="h-3 w-16 bg-gray-200 rounded"></div>
            </div>
        @endfor
    </div>

    {{-- Chart skeleton --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm mb-6">
        <div class="h-4 w-48 bg-gray-200 rounded mb-4"></div>
        <div class="h-64 w-full bg-gray-100 rounded-xl"></div>
    </div>

    {{-- Vehicle cards skeleton --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @for ($i = 0; $i < 2; $i++)
            <div class="bg-white rounded-2xl p-5 shadow-sm">
                <div class="h-4 w-36 bg-gray-200 rounded mb-4"></div>
                <div class="h-48 w-full bg-gray-100 rounded-xl"></div>
            </div>
        @endfor
    </div>
</div>
