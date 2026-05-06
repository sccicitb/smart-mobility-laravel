<div class="px-6 py-4 flex flex-col gap-6 w-full">

    {{-- Loading Overlay (moved inside main container as first child) --}}
    <div wire:loading class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999]">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
            bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 max-w-sm">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-200 rounded-full"></div>
                <div class="w-16 h-16 border-4 border-[#16598b] border-t-transparent rounded-full animate-spin absolute top-0"></div>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Loading Data...</h3>
                <div class="text-sm text-gray-600">Please wait while we fetch the latest information</div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Integrasi Sistem Transportasi Publik</h1>
            <div class="text-sm text-gray-500 mt-0.5">Pemantauan kendaraan umum (Bus, AUP, Bus Besar) dalam arus lalu lintas</div>
        </div>
        <div class="flex gap-2">
            <button type="button" wire:key="tp-filter-today" wire:click="setFilter('today')"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $filter === 'today' ? 'bg-[#7c2d2d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Hari Ini
            </button>
            <button type="button" wire:key="tp-filter-week" wire:click="setFilter('week')"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $filter === 'week' ? 'bg-[#7c2d2d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Minggu Ini
            </button>
            <button type="button" wire:key="tp-filter-month" wire:click="setFilter('month')"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $filter === 'month' ? 'bg-[#7c2d2d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Bulan Ini
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Bus (BS)</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <i data-lucide="bus" class="w-4 h-4 text-indigo-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalBs) }}</div>
            <div class="text-xs text-gray-400 mt-1">kendaraan</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Angk. Umum (AUP)</span>
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4 text-green-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalAup) }}</div>
            <div class="text-xs text-gray-400 mt-1">kendaraan</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Bus Besar (BB)</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                    <i data-lucide="bus" class="w-4 h-4 text-teal-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalBb) }}</div>
            <div class="text-xs text-gray-400 mt-1">kendaraan</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Total Publik</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i data-lucide="activity" class="w-4 h-4 text-blue-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalPublik) }}</div>
            <div class="text-xs text-gray-400 mt-1">BS + AUP + BB</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Load Factor</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i data-lucide="gauge" class="w-4 h-4 text-amber-600"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $loadFactor }}%</div>
            <div class="w-full bg-gray-100 rounded-full h-1 mt-2">
                <div class="h-1 rounded-full {{ $loadFactor >= 20 ? 'bg-green-500' : 'bg-amber-400' }}"
                    style="width: {{ min($loadFactor, 100) }}%"></div>
            </div>
            <div class="text-xs text-gray-400 mt-1">dari {{ number_format($totalKendaraan) }} total</div>
        </div>
    </div>

    {{-- Charts side by side --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Chart: publik per simpang --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="mb-4">
                <h2 class="text-base font-semibold text-gray-800">Kendaraan Publik per Simpang</h2>
                <div class="text-xs text-gray-500 mt-0.5">Distribusi Bus, AUP, dan Bus Besar per lokasi simpang</div>
            </div>
            @if (empty($simpangData['labels']))
                <div class="flex flex-col items-center justify-center h-56 text-gray-400 gap-2">
                    <i data-lucide="map-pin" class="w-10 h-10 opacity-30"></i>
                    <span class="text-sm">Belum ada data untuk periode ini</span>
                </div>
            @else
                <div class="relative h-64">
                    <canvas id="simpangChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Chart: publik per pendekat --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="mb-4">
                <h2 class="text-base font-semibold text-gray-800">Kendaraan Publik per Pendekat</h2>
                <div class="text-xs text-gray-500 mt-0.5">Volume Bus, AUP, dan Bus Besar berdasarkan tipe pendekat</div>
            </div>
            @if (empty($pendekatData['labels']))
                <div class="flex flex-col items-center justify-center h-56 text-gray-400 gap-2">
                    <i data-lucide="bar-chart-2" class="w-10 h-10 opacity-30"></i>
                    <span class="text-sm">Belum ada data untuk periode ini</span>
                </div>
            @else
                <div class="relative h-64">
                    <canvas id="pendekatChart"></canvas>
                </div>
                <div class="flex gap-4 mt-3 justify-end text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-sm bg-indigo-500"></span>Bus</span>
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-sm bg-green-500"></span>AUP</span>
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-sm bg-teal-500"></span>Bus Besar</span>
                </div>
            @endif
        </div>

    </div>

    {{-- Hidden data store — updated by Livewire morph on every filter change --}}
    <div id="tp-chart-data" style="display:none"
        data-simpang-labels='@json($simpangData["labels"] ?? [])'
        data-simpang-bs='@json($simpangData["bs"] ?? [])'
        data-simpang-aup='@json($simpangData["aup"] ?? [])'
        data-simpang-bb='@json($simpangData["bb"] ?? [])'
        data-pendekat-labels='@json($pendekatData["labels"] ?? [])'
        data-pendekat-bs='@json($pendekatData["bs"] ?? [])'
        data-pendekat-aup='@json($pendekatData["aup"] ?? [])'
        data-pendekat-bb='@json($pendekatData["bb"] ?? [])'
    ></div>
    

</div>

@push('scripts')
<script>
(function () {
    let simpangChart = null;
    let pendekatChart = null;

    document.addEventListener('livewire:init', () => {
        Livewire.on('filterChanged', (filter) => {
            console.log('Filter changed to:', filter);
        });
    });

    function readStore() {
        const store = document.getElementById('tp-chart-data');
        if (!store) return {};

        return {
            simpangLabels: JSON.parse(store.dataset.simpangLabels || '[]'),
            simpangBs: JSON.parse(store.dataset.simpangBs || '[]'),
            simpangAup: JSON.parse(store.dataset.simpangAup || '[]'),
            simpangBb: JSON.parse(store.dataset.simpangBb || '[]'),
            pendekatLabels: JSON.parse(store.dataset.pendekatLabels || '[]'),
            pendekatBs: JSON.parse(store.dataset.pendekatBs || '[]'),
            pendekatAup: JSON.parse(store.dataset.pendekatAup || '[]'),
            pendekatBb: JSON.parse(store.dataset.pendekatBb || '[]'),
        };
    }

    function buildAll() {
        buildSimpang();
        buildPendekat();
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function buildSimpang() {
        const canvas = document.getElementById('simpangChart');
        if (!canvas) return;

        const data = readStore();
        const labels = data.simpangLabels || [];
        const bs = data.simpangBs || [];
        const aup = data.simpangAup || [];
        const bb = data.simpangBb || [];

        if (!labels.length) return;
        if (simpangChart) simpangChart.destroy();

        simpangChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Bus (BS)', data: bs, backgroundColor: 'rgba(99,102,241,0.85)', borderRadius: 3, stack: 's' },
                    { label: 'AUP', data: aup, backgroundColor: 'rgba(34,197,94,0.85)', borderRadius: 3, stack: 's' },
                    { label: 'Bus Besar (BB)', data: bb, backgroundColor: 'rgba(20,184,166,0.85)', borderRadius: 3, stack: 's' },
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { font: { size: 10 }, padding: 8, boxWidth: 10 }
                    }
                },
                scales: {
                    x: { stacked: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 } } },
                    y: { stacked: true, grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }

    function buildPendekat() {
        const canvas = document.getElementById('pendekatChart');
        if (!canvas) return;

        const data = readStore();
        const labels = data.pendekatLabels || [];
        const bs = data.pendekatBs || [];
        const aup = data.pendekatAup || [];
        const bb = data.pendekatBb || [];

        if (!labels.length) return;
        if (pendekatChart) pendekatChart.destroy();

        pendekatChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Bus (BS)', data: bs, backgroundColor: 'rgba(99,102,241,0.85)', borderRadius: 4, stack: 's' },
                    { label: 'AUP', data: aup, backgroundColor: 'rgba(34,197,94,0.85)', borderRadius: 4, stack: 's' },
                    { label: 'Bus Besar (BB)', data: bb, backgroundColor: 'rgba(20,184,166,0.85)', borderRadius: 4, stack: 's' },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: { stacked: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 } } }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', buildAll);
    document.addEventListener('livewire:navigated', buildAll);
    document.addEventListener('livewire:init', () => {
        Livewire.on('tp-chart-update', () => setTimeout(buildAll, 50));
    });
})();
</script>
@endpush