<div 
    class="bg-white/90 rounded-2xl shadow-sm 
           p-6 flex items-center justify-between cursor-pointer
           transition-all duration-200 ease-in-out
           hover:shadow-md hover:shadow-red-800/50
           hover:bg-red-700/40 hover:text-white text-gray-700"
    x-data="{ icon: '{{ $icon }}' }"
    x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons() })"
>
    @if (!empty($link))
        <a href="{{ $link }}" class="flex items-center justify-between w-full">
    @endif

        <div>
            <h3 class="font-semibold text-lg">{{ $title }}</h3>
            <p class="text-2xl font-bold">
                @if (is_numeric($value))
                    {{ number_format($value, 0, ',', '.') }}
                @else
                    {{ $value }}
                @endif
                @if ($unit)
                    <span class="text-sm font-medium">{{ $unit }}</span>
                @endif
            </p>
        </div>

        <div class="bg-[#ffc0bf] p-3 rounded-full">
            <i :data-lucide="icon" class="w-8 h-8 text-[#892120]"></i>
        </div>

    @if (!empty($link))
        </a>
    @endif
</div>
