<div class="px-6 flex flex-col w-full gap-4">
    {{-- <h3 class="text-3xl font-bold text-white">{{ $title }}</h3> --}}
    <!-- Filter Buttons -->
    <div class="flex gap-3">
        <button wire:click="setFilter('today')" 
            class="px-4 py-2 rounded-lg text-sm font-semibold 
                   {{ $filter === 'today' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700' }}">
            Today
        </button>
        <button wire:click="setFilter('week')" 
            class="px-4 py-2 rounded-lg text-sm font-semibold 
                   {{ $filter === 'week' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700' }}">
            Week
        </button>
        <button wire:click="setFilter('month')" 
            class="px-4 py-2 rounded-lg text-sm font-semibold 
                   {{ $filter === 'month' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700' }}">
            Month
        </button>
    </div>

    <!-- Stats Cards - TETAP SAMA, tidak diubah -->
    <div class="w-full lg:flex gap-7">
        <div class="w-full lg:w-1/2 grid grid-cols-1 xl:grid-cols-2 mb-7 lg:mb-0 gap-7">
            @livewire('component.cards.stats-card', [
                'title' => 'Carbon Emissions',
                'value' => $data['carbon'],
                'unit' => 'Kg',
                'icon' => 'wind'
            ], key("carbon-{$filter}"))
            
            @livewire('component.cards.stats-card', [
                'title' => 'Level of Service',
                'value' => $data['service'],
                'unit' => '',
                'icon' => 'arrows-up-from-line'
            ], key("service-{$filter}"))
            
            @livewire('component.cards.stats-card', [
                'title' => 'Peak Flow Time',
                'value' => $data['peak'],
                'unit' => '',
                'icon' => 'clock'
            ], key("peak-{$filter}"))
                
            @livewire('component.cards.stats-card', [
                'title' => 'Total Losses',
                'value' => $data['cost'],
                'unit' => '',
                'icon' => 'circle-dollar-sign'
            ], key("cost-{$filter}"))
        </div>

        <!-- Placeholder card -->

         <div class="w-full lg:w-1/2 bg-white rounded-2xl p-8 grid grid-cols-2 gap-5">
            <div class="w-full flex my-auto">
                @livewire('component.cards.bar-vehicle', [
                    'positionText' => true,
                    'chartData' => $vehicleData['incomingVehicles'],
                ], key('bar-vehicle-incoming'))
            </div>
            <div class="w-full flex my-auto">
                @livewire('component.cards.bar-vehicle', [
                    'positionText' => false,
                    'chartData' => $vehicleData['outgoingVehicles'],
                ], key('bar-vehicle-outgoing'))
            </div>
        </div>
    </div>

    <!-- Chart Section - INDEPENDENT dari stats -->
    <div class="w-full lg:flex flex-row-reverse gap-7">
        <div class="w-full lg:w-1/2 mb-7 lg:mb-0">
            @livewire('component.cards.charts-card', [
                'chartType' => 'line',
                'title' => 'Trend Data Analysis',
                'filter' => $filter 
            ], key("charts-card"))
        </div>
        <div class="w-full lg:w-1/2 flex justify-center h-92 rounded-xl bg-black items-center">
            @livewire('component.cards.video-player', [
                'src' => 'https://cctvjss.jogjakota.go.id/atcs/ATCS_jlagran.stream/chunklist_w464847900.m3u8',
                'poster' => 'https://wallpapers.com/images/hd/plain-black-desktop-2560-x-1440-ugpl0479gu0vuwnl.jpg'
            ], key('video'))
        </div>
    </div>
</div>