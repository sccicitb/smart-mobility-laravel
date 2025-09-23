<div class="px-6 flex flex-col w-full gap-4">
    @if(session('welcome'))
    <div id="welcomeModal" class="fixed inset-0 bg-black backdrop-blur-sm bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-[#16598b] px-10 py-5 rounded-2xl shadow-lg p-6 w-11/12 max-w-4xl text-center overflow-y-auto max-h-[90%]">
            <p class="text-white">{{ session('welcome') }}</p>
            <h2 class="text-3xl font-semibold mb-4 text-gray-800 text-white">Welcome to Smart Mobility Simulator</h2>
            <div class="w-full flex flex-col md:flex-row lg:flex-col">
                <div class="text-white w-full text-justify text-lg">{{$descriptionWelcome}}</div>
                <div class="w-full flex flex-col lg:flex-row justify-around items-center">
                    <div class="flex flex-col items-center justify-center">
                        <img src="{{ asset('images/realtime_traffic_data.jpg')}}" alt="Welcome Image" class="w-40 h-40 mx-auto my-4 rounded-lg shadow-xs">
                        <div class="font-semibold text-white">Realtime Traffic Data</div>
                    </div>
                    <div class="h-fit">
                        <i data-lucide="plus" class="w-10 h-10 text-white"></i>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <img src="{{ asset('images/Intersection_Simulation.jpg')}}" alt="Welcome Image" class="w-40 h-40 mx-auto my-4 rounded-lg shadow-xs">
                        <div class="font-semibold text-white">Intersection Simulation</div>
                    </div>
                    <div class="h-fit rotate-90 lg:rotate-0">
                        <i data-lucide="chevron-right" class="w-10 h-10 text-white"></i>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <img src="{{ asset('images/Impact_Analysist.png')}}" alt="Welcome Image" class="w-40 h-40 mx-auto my-4 rounded-lg">
                        <div class="font-semibold text-white">Impact Analysis</div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="document.getElementById('welcomeModal').classList.add('opacity-0'); setTimeout(()=>{document.getElementById('welcomeModal').remove()}, 300);" class="px-3 py-2 bg-white text-gray-700 font-semibold shadow-xs rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
             lucide.replace();
        });
    </script>
                    {{-- const modal = document.getElementById('welcomeModal');
            setTimeout(() => {
                modal.classList.add('opacity-0');
                setTimeout(()=>{ modal.remove() }, 300);
            }, 5000);  --}}
    @endif

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

        <div class="w-full lg:w-1/2 rounded-2xl p-3 bg-white">
            <div class="text-gray-700 font-semibold text-lg">{{$titleBar}}</div>
            <div class="grid grid-cols-2 gap-5">
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