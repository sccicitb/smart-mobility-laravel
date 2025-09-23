<div class="bg-white rounded-2xl shadow-xs p-3 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $title }}</h3>
        
        <!-- Chart Type Selector -->
        <div class="flex gap-2">
            <button wire:click="changeChartType('line')" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                    {{ $chartType === 'line' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Line
            </button>
            <button wire:click="changeChartType('bar')" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                    {{ $chartType === 'bar' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Bar
            </button>
            <button wire:click="changeChartType('donut')" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                    {{ $chartType === 'donut' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Donut
            </button>
        </div>
    </div>
    <div class="relative h-72">
        <canvas id="{{ $chartId }}" class="h-full w-full"></canvas>
    </div>

 <!-- Debug Info - AKTIF -->
    {{-- <div class="mb-4 p-3 bg-yellow-100 rounded text-sm border">
        <strong>🐛 DEBUG INFO:</strong><br>
        Chart ID: <code>{{ $chartId }}</code><br>
        Chart Type: <code>{{ $chartType }}</code><br>
        Labels Count: <code>{{ count($labels) }}</code><br>
        Data Keys: <code>{{ implode(', ', array_keys($data)) }}</code><br>
        Labels: <code>{{ json_encode($labels) }}</code><br>
        Data: <code>{{ json_encode($data) }}</code>
    </div> --}}

    <!-- Test: Static HTML dulu -->
    {{-- <div class="mb-4 p-3 bg-green-100 rounded">
        <strong>✅ Test Static HTML:</strong> Ini muncul berarti view ter-load dengan benar
    </div> --}}

    <!-- Chart Container -->
    {{-- <div id="chart-container-{{ $chartId }}" class="border-2 border-dashed border-gray-300 p-4" style="height: 400px;">
        <div id="{{ $chartId }}" style="width: 100%; height: 100%;"></div>
        <div id="fallback-{{ $chartId }}" class="text-center text-gray-500 hidden">Chart will appear here</div>
    </div> --}}

        {{-- <div id="{{ $chartId }}" style="height: 400px;"></div> --}}

</div>

<!-- Load Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartId = '{{ $chartId }}';
    let chart = null;

    function createChart(payload = null) {
        const chartType = payload?.chartType || '{{ $chartType }}';
        const labels = payload?.labels || @json($labels);
        const datasets = payload?.data || @json($data);
        const chartElement = document.getElementById(chartId);

        if (!chartElement) return;

        // Destroy chart lama jika ada
        if (chart) chart.destroy();

        // Validasi data
        if (!labels?.length || !datasets || Object.keys(datasets).length === 0) {
            chartElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No data available</div>';
            return;
        }

        // Siapkan datasets Chart.js
        let chartDatasets = [];
        if (chartType === 'doughnut') {
            // Doughnut: total tiap label
            const dataValues = labels.map((_, i) =>
                Object.values(datasets).reduce((sum, d) => sum + (d[i]||0), 0)
            );
            chartDatasets.push({
                data: dataValues,
                backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6']
            });
        } else {
            // Line / Bar
            chartDatasets = Object.entries(datasets).map(([key, d], index) => ({
                label: key.charAt(0).toUpperCase() + key.slice(1),
                data: d,
                backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6'][index % 5],
                borderColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6'][index % 5],
                fill: chartType === 'line' ? false : true,
                tension: chartType === 'line' ? 0.4 : 0
            }));
        }

        // Buat chart baru
        chart = new Chart(chartElement, {
            type: chartType === 'donut' ? 'doughnut' : chartType,
            data: {
                labels: labels,
                datasets: chartDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' }},
                scales: chartType !== 'doughnut' ? {
                    x: { title: { display: true } },
                    y: { beginAtZero: true, title: { display: true } }
                } : {}
            }
        });
    }

    // Inisialisasi awal
    createChart();

    // Update chart dari Livewire / browser event
    function updateChart(eventData) {
        if (!eventData || eventData.chartId !== chartId) return;
        createChart(eventData);
    }

    // Event listener Livewire
    if (typeof Livewire !== 'undefined' && Livewire.on) {
        Livewire.on('chartDataUpdated', updateChart);
    }

    // Event listener browser fallback
    window.addEventListener('chartDataUpdated', e => updateChart(e.detail));
});
</script>
