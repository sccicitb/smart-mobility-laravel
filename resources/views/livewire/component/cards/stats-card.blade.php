<div class="bg-white rounded-2xl shadow-xs p-6 flex items-center justify-between" 
     x-data="{ icon: '{{ $icon }}' }" 
     x-init="$nextTick(() => { lucide.createIcons() })">
    <div>
        <h3 class="text-gray-700 font-semibold text-lg">{{ $title }}</h3>
        <p class="text-2xl font-bold text-gray-900">
            @if(is_numeric($value))
                {{ number_format($value, 0, ',', '.') }}
            @else
                {{ $value }}
            @endif
            <span class="text-sm font-medium text-gray-500">{{ $unit }}</span>
        </p>
    </div>

    <div class="bg-[#ffc0bf] p-3 rounded-full">
        <i :data-lucide="icon" class="w-8 h-8 text-[#892120]"></i>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Untuk Livewire
    document.addEventListener("livewire:navigated", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Untuk update component
    Livewire.hook('morph.updated', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush