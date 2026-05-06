<div 
    class="bg-white rounded-2xl shadow-sm border border-gray-100
           p-5 flex items-center justify-between cursor-pointer
           transition-all duration-200 ease-in-out
           hover:shadow-md hover:border-[#892120]/30
           hover:bg-[#892120]/5 group"
    x-data="{ icon: '{{ $icon }}' }"
    x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons() })"
>
    @if (!empty($link))
        <a href="{{ $link }}" class="flex items-center justify-between w-full">
    @endif

        <div>
            <p class="text-xs font-medium text-gray-500 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-800 group-hover:text-[#892120] transition-colors">
                @if (is_numeric($value))
                    {{ number_format($value, 0, ',', '.') }}
                @else
                    {{ $value }}
                @endif
                @if ($unit)
                    <span class="text-sm font-medium text-gray-500 ml-0.5">{{ $unit }}</span>
                @endif
            </p>
        </div>

        <div class="w-11 h-11 bg-[#892120]/10 group-hover:bg-[#892120]/20 rounded-xl flex items-center justify-center transition-colors">
            <i :data-lucide="icon" class="w-5 h-5 text-[#892120]"></i>
        </div>

    @if (!empty($link))
        </a>
    @endif
</div>
