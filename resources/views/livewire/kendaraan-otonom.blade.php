﻿<div class="px-6 py-4 flex flex-col gap-6 w-full">

    {{-- Loading Overlay --}}
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
            <h1 class="text-xl font-bold text-gray-800">Pengujian Kendaraan Otonom</h1>
            <div class="text-sm text-gray-500 mt-0.5">Analisis kesiapan infrastruktur jalan untuk Autonomous Vehicle</div>
        </div>
        <div class="flex gap-2">
            <button type="button" wire:key="av-filter-today" wire:click="setFilter('today')"
                class="px-4 py-2 rounded-lg btn text-sm font-semibold transition-colors {{ $filter === 'today' ? 'bg-[#7c2d2d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Hari Ini
            </button>
            <button type="button" wire:key="av-filter-week" wire:click="setFilter('week')"
                class="px-4 py-2 rounded-lg btn text-sm font-semibold transition-colors {{ $filter === 'week' ? 'bg-[#7c2d2d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Minggu Ini
            </button>
            <button type="button" wire:key="av-filter-month" wire:click="setFilter('month')"
                class="px-4 py-2 rounded-lg btn text-sm font-semibold transition-colors {{ $filter === 'month' ? 'bg-[#7c2d2d] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Bulan Ini
            </button>
        </div>
    </div>

    {{-- Summary Metric Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Simpang Siap AV</span>
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-green-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-green-600">{{ $readyCount }}</div>
            <div class="text-xs text-gray-400 mt-1">dari {{ count($readinessData) }} simpang</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Avg SM Dominance</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i data-lucide="bike" class="w-4 h-4 text-purple-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ $avgHeterogenitas }}%</div>
            <div class="text-xs {{ $avgHeterogenitas > 60 ? 'text-red-400' : 'text-green-500' }} mt-1 font-medium">
                {{ $avgHeterogenitas > 60 ? 'Heterogenitas Tinggi' : 'Heterogenitas Aman' }}
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Avg V/C Ratio</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i data-lucide="gauge" class="w-4 h-4 text-blue-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ number_format($avgVc, 2) }}</div>
            <div class="text-xs {{ $avgVc > 0.85 ? 'text-red-400' : ($avgVc > 0.75 ? 'text-amber-500' : 'text-green-500') }} mt-1 font-medium">
                {{ $avgVc > 0.85 ? 'Kapasitas Kritis' : ($avgVc > 0.75 ? 'Kapasitas Terbatas' : 'Kapasitas Aman') }}
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium">Avg Obstacle Density</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800">{{ $avgObstacle }}%</div>
            <div class="text-xs {{ $avgObstacle > 5 ? 'text-red-400' : 'text-green-500' }} mt-1 font-medium">
                {{ $avgObstacle > 5 ? 'Gangguan Sensor Tinggi' : 'Gangguan Sensor Rendah' }}
            </div>
        </div>

    </div>

    {{-- Readiness Chart + Score Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- AV Readiness Score Chart --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="mb-4">
                <h2 class="text-base font-semibold text-gray-800">AV Infrastructure Readiness Score</h2>
                <div class="text-xs text-gray-500 mt-0.5">Skor 0–100 berdasarkan heterogenitas, V/C, dan obstacle density</div>
            </div>
            @if (empty($vcData['labels']))
                <div class="flex flex-col items-center justify-center h-56 text-gray-400 gap-2">
                    <i data-lucide="cpu" class="w-10 h-10 opacity-30"></i>
                    <span class="text-sm">Belum ada data untuk periode ini</span>
                </div>
            @else
                <div class="relative h-64">
                    <canvas id="readinessChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Readiness Table --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="mb-4">
                <h2 class="text-base font-semibold text-gray-800">Status per Simpang</h2>
                <div class="text-xs text-gray-500 mt-0.5">Ringkasan metrik uji per simpang</div>
            </div>
            @if (empty($readinessData))
                <div class="flex flex-col items-center justify-center h-56 text-gray-400 gap-2">
                    <i data-lucide="table-2" class="w-10 h-10 opacity-30"></i>
                    <span class="text-sm">Belum ada data untuk periode ini</span>
                </div>
            @else
                <div class="overflow-auto max-h-72">
                    <table class="w-full text-xs">
                        <thead class="sticky top-0">
                            <tr class="bg-[#7c2d2d] text-white">
                                <th class="px-3 py-2.5 text-left rounded-tl-lg">Simpang</th>
                                <th class="px-3 py-2.5 text-right">SM %</th>
                                <th class="px-3 py-2.5 text-right">V/C</th>
                                <th class="px-3 py-2.5 text-right">KTB %</th>
                                <th class="px-3 py-2.5 text-right">Score</th>
                                <th class="px-3 py-2.5 text-center rounded-tr-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($readinessData as $i => $row)
                                <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-100 hover:bg-blue-50 transition-colors">
                                    <td class="px-3 py-2 font-medium text-gray-700">{{ $row['nama'] }}</td>
                                    <td class="px-3 py-2 text-right {{ $row['sm_ratio'] > 60 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                                        {{ $row['sm_ratio'] }}%
                                    </td>
                                    <td class="px-3 py-2 text-right {{ ($row['vc_ratio'] ?? 0) > 0.85 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                                        {{ $row['vc_ratio'] !== null ? number_format($row['vc_ratio'], 2) : '–' }}
                                    </td>
                                    <td class="px-3 py-2 text-right {{ $row['ktb_ratio'] > 5 ? 'text-amber-500 font-semibold' : 'text-gray-600' }}">
                                        {{ $row['ktb_ratio'] }}%
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-gray-700">{{ $row['score'] }}</td>
                                    <td class="px-3 py-2 text-center">
                                        @if ($row['status'] === 'ready')
                                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">Siap</span>
                                        @elseif ($row['status'] === 'conditional')
                                            <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">Kondisional</span>
                                        @else
                                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Belum Siap</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    {{-- V/C & Obstacle Multi-Metric Chart --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-gray-800">Analisis Metrik Uji per Simpang</h2>
            <div class="text-xs text-gray-500 mt-0.5">Perbandingan SM Dominance, V/C Ratio, dan Obstacle Density (KTB)</div>
        </div>
        @if (empty($vcData['labels']))
            <div class="flex flex-col items-center justify-center h-48 text-gray-400 gap-2">
                <i data-lucide="bar-chart-2" class="w-10 h-10 opacity-30"></i>
                <span class="text-sm">Belum ada data untuk periode ini</span>
            </div>
        @else
            <div class="relative" style="height: 260px">
                <canvas id="metricChart"></canvas>
            </div>
        @endif
    </div>

    {{-- Virtual Test Drive Log --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="mb-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-[#7c2d2d]/10 flex items-center justify-center">
                <i data-lucide="route" class="w-4 h-4 text-[#7c2d2d]"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">Virtual Test Drive Log</h2>
                <div class="text-xs text-gray-500">Rute dengan data paling terbaru — kandidat uji coba kendaraan otonom</div>
            </div>
        </div>

        @if (empty($testLog))
            <div class="flex flex-col items-center justify-center h-40 text-gray-400 gap-2">
                <i data-lucide="map" class="w-10 h-10 opacity-30"></i>
                <span class="text-sm">Tidak ada rute terdaftar di database</span>
            </div>
        @else
            <div class="overflow-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                            <th class="px-4 py-2.5 text-left font-semibold">#</th>
                            <th class="px-4 py-2.5 text-left font-semibold">Simpang</th>
                            <th class="px-4 py-2.5 text-left font-semibold">Nama Ruas</th>
                            <th class="px-4 py-2.5 text-center font-semibold">Arah</th>
                            <th class="px-4 py-2.5 text-right font-semibold">Jarak (km)</th>
                            <th class="px-4 py-2.5 text-right font-semibold">Lebar (m)</th>
                            <th class="px-4 py-2.5 text-center font-semibold">Status</th>
                            <th class="px-4 py-2.5 text-right font-semibold">Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testLog as $i => $log)
                            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-100 hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-2.5 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-2.5 font-medium text-gray-700">{{ $log->Nama_Simpang ?? '–' }}</td>
                                <td class="px-4 py-2.5 text-gray-600">{{ $log->nama_ruas ?? '–' }}</td>
                                <td class="px-4 py-2.5 text-center text-gray-500">
                                    <span class="inline-flex items-center gap-1">
                                        {{ $log->dari_arah }}
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        {{ $log->ke_arah }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">{{ number_format($log->jarak_km, 2) }}</td>
                                <td class="px-4 py-2.5 text-right font-mono text-gray-700">{{ $log->lebar_jalan ?? '–' }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    @if ($log->status === 'active')
                                        <span class="bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Aktif</span>
                                    @elseif ($log->status === 'maintenance')
                                        <span class="bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">Maintenance</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-500 font-semibold px-2 py-0.5 rounded-full">{{ $log->status ?? '–' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-right text-gray-400 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($log->updated_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Hidden data store --}}
    <div id="avo-chart-data" style="display:none"
        data-labels='@json($vcData["labels"] ?? [])'
        data-vc='@json($vcData["vc"] ?? [])'
        data-sm='@json($vcData["sm"] ?? [])'
        data-ktb='@json($vcData["ktb"] ?? [])'
        data-score='@json($vcData["score"] ?? [])'
    ></div>

</div>

@push('scripts')
<script>
(function () {
    let readinessChart = null;
    let metricChart    = null;

    document.addEventListener('livewire:init', () => {
        Livewire.on('filterChanged', (filter) => {
            console.log('Filter changed to:', filter);
        });
    });

    function readStore() {
        const s = document.getElementById('avo-chart-data');
        if (!s) return {};
        return {
            labels: JSON.parse(s.dataset.labels || '[]'),
            vc:     JSON.parse(s.dataset.vc     || '[]'),
            sm:     JSON.parse(s.dataset.sm     || '[]'),
            ktb:    JSON.parse(s.dataset.ktb    || '[]'),
            score:  JSON.parse(s.dataset.score  || '[]'),
        };
    }

    function buildAll() {
        buildReadiness();
        buildMetric();
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function buildReadiness() {
        const canvas = document.getElementById('readinessChart');
        if (!canvas) return;

        const d      = readStore();
        const labels = d.labels;
        const scores = d.score;

        if (!labels.length) return;
        if (readinessChart) readinessChart.destroy();

        const colors = scores.map(s =>
            s >= 70 ? 'rgba(34,197,94,0.85)' :
            s >= 45 ? 'rgba(251,191,36,0.85)' :
                      'rgba(239,68,68,0.85)'
        );

        readinessChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Readiness Score',
                    data: scores,
                    backgroundColor: colors,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            afterLabel: (item) => item.raw >= 70 ? '✓ Siap AV' : item.raw >= 45 ? '⚠ Kondisional' : '✗ Belum Siap'
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: {
                        min: 0, max: 100,
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    }

    function buildMetric() {
        const canvas = document.getElementById('metricChart');
        if (!canvas) return;

        const d      = readStore();
        const labels = d.labels;
        const sm     = d.sm;
        const vc     = d.vc;
        const ktb    = d.ktb;

        if (!labels.length) return;
        if (metricChart) metricChart.destroy();

        metricChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'SM Dominance (%)',
                        data: sm,
                        backgroundColor: 'rgba(147,51,234,0.75)',
                        borderRadius: 4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'V/C Ratio (×100)',
                        data: vc.map(v => v * 100),
                        backgroundColor: 'rgba(59,130,246,0.75)',
                        borderRadius: 4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Obstacle Density KTB (%)',
                        data: ktb,
                        type: 'line',
                        borderColor: 'rgba(249,115,22,0.9)',
                        backgroundColor: 'rgba(249,115,22,0.15)',
                        borderWidth: 2,
                        pointRadius: 4,
                        fill: false,
                        tension: 0.3,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { font: { size: 11 }, padding: 10, boxWidth: 12 }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 } } }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', buildAll);
    document.addEventListener('livewire:navigated', buildAll);
    document.addEventListener('livewire:init', () => {
        Livewire.on('av-chart-update', () => setTimeout(buildAll, 50));
    });
})();
</script>
@endpush