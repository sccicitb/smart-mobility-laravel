<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 h-full">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-gray-800">{{ $title }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">
                {{ $selectedDate }} 
                @if($selectedSimpang) &bull; Simpang: {{ $selectedSimpang }} @endif
                &bull; Filter: {{ $parentFilter }}
            </p>
        </div>
        
        <!-- Chart Type Selector -->
        <div class="flex gap-2">
            <button wire:click="changeChartType('line')" 
                class="px-4 py-2 btn rounded-lg text-sm font-semibold transition-colors
                    {{ $chartType === 'line' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Line
            </button>
            <button wire:click="changeChartType('bar')" 
                class="px-4 py-2 btn rounded-lg text-sm font-semibold transition-colors
                    {{ $chartType === 'bar' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Bar
            </button>
            <button wire:click="changeChartType('donut')" 
                class="px-4 py-2 btn rounded-lg text-sm font-semibold transition-colors
                    {{ $chartType === 'donut' ? 'bg-[#892120] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Donut
            </button>
        </div>
    </div>

    <!-- Data Summary -->
    {{-- <div class="grid grid-cols-3 gap-2 mb-4">
        <div class="bg-blue-50 p-2 rounded text-center">
            <p class="text-xs text-blue-600 font-medium">Kendaraan Ringan</p>
            <p class="text-sm font-bold text-blue-800">{{ number_format(array_sum($data['kendaraan_ringan'] ?? [])) }}</p>
        </div>
        <div class="bg-orange-50 p-2 rounded text-center">
            <p class="text-xs text-orange-600 font-medium">Kendaraan Berat</p>
            <p class="text-sm font-bold text-orange-800">{{ number_format(array_sum($data['kendaraan_berat'] ?? [])) }}</p>
        </div>
        <div class="bg-green-50 p-2 rounded text-center">
            <p class="text-xs text-green-600 font-medium">Total Kendaraan</p>
            <p class="text-sm font-bold text-green-800">{{ number_format(array_sum($data['total_kendaraan'] ?? [])) }}</p>
        </div>
    </div> --}}
    
    <div class="relative h-72">
        <canvas id="{{ $chartId }}" class="h-full w-full"></canvas>
    </div>

    <!-- Debug Info - AKTIF untuk development -->
    {{-- @if(config('app.debug'))
    <div class="mt-4 p-3 bg-gray-100 rounded text-xs">
        <strong>🐛 DEBUG INFO:</strong><br>
        Chart ID: <code>{{ $chartId }}</code><br>
        Chart Type: <code>{{ $chartType }}</code><br>
        Filter: <code>{{ $parentFilter }}</code><br>
        Date: <code>{{ $selectedDate }}</code><br>
        Simpang: <code>{{ $selectedSimpang ?? 'All' }}</code><br>
        Labels Count: <code>{{ count($labels) }}</code><br>
        Data Points: 
        <code>
            Ringan: {{ count($data['kendaraan_ringan'] ?? []) }}, 
            Berat: {{ count($data['kendaraan_berat'] ?? []) }}, 
            Total: {{ count($data['total_kendaraan'] ?? []) }}
        </code>
    </div>
    @endif --}}
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

        if (!chartElement) {
            console.error('Chart element not found:', chartId);
            return;
        }

        // Destroy chart lama jika ada
        if (chart) {
            chart.destroy();
            chart = null;
        }

        // Validasi data
        if (!labels?.length || !datasets || Object.keys(datasets).length === 0) {
            chartElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No data available</div>';
            console.warn('No data available for chart', {labels, datasets});
            return;
        }

        // Siapkan datasets Chart.js
        let chartDatasets = [];
        
        if (chartType === 'doughnut') {
            // Doughnut: total tiap kategori
            const totalKendaraanRingan = datasets.kendaraan_ringan?.reduce((sum, val) => sum + val, 0) || 0;
            const totalKendaraanBerat = datasets.kendaraan_berat?.reduce((sum, val) => sum + val, 0) || 0;
            
            chartDatasets.push({
                data: [totalKendaraanRingan, totalKendaraanBerat],
                backgroundColor: ['#3B82F6', '#F59E0B'],
                borderColor: ['#2563EB', '#D97706'],
                borderWidth: 2
            });
            
            // Update labels untuk donut
            labels = ['Kendaraan Ringan', 'Kendaraan Berat'];
        } else {
            // Line / Bar - tampilkan semua kategori
            const datasetConfigs = [
                {
                    key: 'kendaraan_ringan',
                    label: 'Kendaraan Ringan',
                    backgroundColor: '#3B82F6',
                    borderColor: '#2563EB'
                },
                {
                    key: 'kendaraan_berat', 
                    label: 'Kendaraan Berat',
                    backgroundColor: '#F59E0B',
                    borderColor: '#D97706'
                },
                {
                    key: 'total_kendaraan',
                    label: 'Total Kendaraan',
                    backgroundColor: '#10B981',
                    borderColor: '#059669'
                }
            ];
            
            chartDatasets = datasetConfigs.map(config => ({
                label: config.label,
                data: datasets[config.key] || [],
                backgroundColor: chartType === 'line' ? 'transparent' : config.backgroundColor + '80',
                borderColor: config.borderColor,
                borderWidth: 2,
                fill: chartType === 'line' ? false : true,
                tension: chartType === 'line' ? 0.4 : 0,
                pointBackgroundColor: config.borderColor,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: chartType === 'line' ? 4 : 0
            }));
        }

        console.log('Creating chart:', {
            type: chartType,
            labels: labels.length,
            datasets: chartDatasets.length,
            chartId
        });

        // Buat chart baru
        try {
            chart = new Chart(chartElement, {
                type: chartType === 'donut' ? 'doughnut' : chartType,
                data: {
                    labels: labels,
                    datasets: chartDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + new Intl.NumberFormat().format(context.parsed.y || context.parsed);
                                }
                            }
                        }
                    },
                    scales: chartType !== 'doughnut' ? {
                        x: { 
                            title: { 
                                display: true,
                                text: '{{ $parentFilter === "15min" ? "Waktu (15 menit)" : ($parentFilter === "today" ? "Jam" : "Periode") }}'
                            },
                            grid: {
                                display: true,
                                color: '#f3f4f6'
                            }
                        },
                        y: { 
                            beginAtZero: true,
                            title: { 
                                display: true,
                                text: 'Jumlah Kendaraan'
                            },
                            grid: {
                                display: true,
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat().format(value);
                                }
                            }
                        }
                    } : {},
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
            
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
            chartElement.innerHTML = '<div class="flex items-center justify-center h-full text-red-500">Error loading chart</div>';
        }
    }

    // Inisialisasi awal
    createChart();

    // Update chart dari Livewire / browser event
    function updateChart(eventData) {
        console.log('Chart update event received:', eventData);
        if (!eventData || eventData.chartId !== chartId) {
            console.log('Event ignored - wrong chartId or no data');
            return;
        }
        createChart(eventData);
    }

    // Event listener Livewire
    if (typeof Livewire !== 'undefined' && Livewire.on) {
        Livewire.on('chartDataUpdated', updateChart);
    }

    // Event listener browser fallback
    window.addEventListener('chartDataUpdated', e => updateChart(e.detail));
    
    // Cleanup saat component di-destroy
    window.addEventListener('beforeunload', function() {
        if (chart) {
            chart.destroy();
        }
    });
});
</script>