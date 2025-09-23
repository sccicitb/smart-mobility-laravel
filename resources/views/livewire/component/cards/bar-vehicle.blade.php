<div class="w-full mx-auto"
     x-data="barChart{{ str_replace('-', '_', $this->id) }}()"
     x-init="$nextTick(() => initChart())">

    <div class="h-64 p-1 rounded-lg relative">
        <canvas :id="'bar-chart-' + componentId"></canvas>

        {{-- Render ikon --}}
        @if(!empty($chartData['iconComponents']))
            <div class="absolute inset-y-0 text-neutral-600 {{ $positionText ? 'lg:-right-6 right-5' : 'lg:-left-6 left-5' }} flex flex-col justify-evenly pt-6 pb-3 pointer-events-none">
                @foreach($chartData['iconComponents'] as $icon)
                    <div>{!! $icon !!}</div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function barChart{{ str_replace('-', '_', $this->id) }}() {
    return {
        componentId: '{{ $this->id }}',
        chart: null,
        
        initChart() {
            const ctx = document.getElementById('bar-chart-' + this.componentId).getContext('2d');
            
            // Destroy existing chart if it exists
            if(this.chart) {
                this.chart.destroy();
            }

            const chartData = @json($chartData);
            const positionText = @json($positionText);

            const data = {
                labels: chartData?.labels || [],
                datasets: [{
                    data: chartData?.values || [],
                    backgroundColor: chartData?.color || '#4ade80',
                    barThickness: chartData?.thickness || 30,
                }],
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = chartData?.tooltipLabels?.[context.dataIndex] || '';
                                    return `${label}: ${context.raw} ${chartData?.format || ''}`;
                                }
                            }
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { display: false },
                            border: { display: false },
                            reverse: positionText,
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                callback: function(value, index) {
                                    const dataValue = data.datasets[0].data[index];
                                    const percent = chartData?.percentages ? chartData.percentages[index] : '';
                                    const vehicleType = chartData?.vehicleTypes ? chartData.vehicleTypes[index] : '';
                                    return `${vehicleType} (${percent}) ${dataValue}`;
                                },
                            },
                            border: { display: false },
                            position: positionText ? 'left' : 'right',
                        },
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                },
            };

            this.chart = new Chart(ctx, config);
        }
    }
}
</script>