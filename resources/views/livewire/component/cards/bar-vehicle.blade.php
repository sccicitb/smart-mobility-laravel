<div class="w-full mx-auto" x-data="barChart{{ str_replace('-', '_', $chartId) }}()" x-init="$nextTick(() => initChart())">
    <div class="h-64 p-1 rounded-lg relative">
        <canvas :id="componentId"></canvas>

        {{-- Render ikon --}}
        @if (!empty($chartData['iconComponents']))
            <div
                class="absolute inset-y-0 text-neutral-600 {{ $positionText ? 'lg:-right-6 right-5' : 'lg:-left-6 left-5' }} flex flex-col justify-evenly pt-6 pb-3 pointer-events-none">
                @foreach ($chartData['iconComponents'] as $icon)
                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                @endforeach
            </div>
        @endif

    </div>
</div>

<script>
    function barChart{{ str_replace('-', '_', $chartId) }}() {
        return {
            componentId: '{{ $chartId }}',
            chart: null,

            initChart() {
                const ctx = document.getElementById(this.componentId);
                if (!ctx) {
                    console.error('Canvas element not found:', this.componentId);
                    return;
                }

                const context = ctx.getContext('2d');

                // Destroy existing chart if it exists
                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }

                // Also check for any existing Chart.js instances on this canvas
                const existingChart = Chart.getChart(ctx);
                if (existingChart) {
                    existingChart.destroy();
                }

                const chartData = @json($chartData);
                const positionText = @json($positionText);
                const numericValues = (chartData?.values || []).map(v => Number(v));

                const data = {
                    labels: chartData?.labels || [],
                    datasets: [{
                        data: numericValues,
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
                            legend: {
                                display: false
                            },
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
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                reverse: positionText,
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    callback: function(value, index) {
                                        const dataValue = data.datasets[0].data[index];
                                        const percent = chartData?.percentages ? chartData.percentages[index] :
                                            '';
                                        const vehicleType = chartData?.vehicleTypes ? chartData.vehicleTypes[
                                            index] : '';
                                        return `${vehicleType} (${percent}) ${dataValue}`;
                                    },
                                },
                                border: {
                                    display: false
                                },
                                position: positionText ? 'left' : 'right',
                            },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                    },
                };

                this.chart = new Chart(context, config);
            }
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for Livewire events
        document.addEventListener('bar-chart-updated', function(event) {
            console.log("Chart Data Received:", event.detail);

            // Handle array format from Livewire dispatch
            let eventData = event.detail;

            // If it's an array, get the first element
            if (Array.isArray(eventData) && eventData.length > 0) {
                eventData = eventData[0];
            }

            const {
                id,
                data,
                positionText
            } = eventData;

            // Validate that we have both id and data
            if (!id || !data) {
                console.warn("Invalid event data - missing id or data:", {
                    id,
                    data
                });
                return;
            }

            const el = document.getElementById(id);

            if (!el) {
                console.warn("Canvas not found for id:", id);
                // Retry after a short delay in case the element hasn't been rendered yet
                setTimeout(() => {
                    const retryEl = document.getElementById(id);
                    if (retryEl) {
                        renderChart(retryEl, id, data, positionText);
                    } else {
                        console.error("Canvas still not found after retry:", id);
                    }
                }, 100);
                return;
            }

            renderChart(el, id, data, positionText);
        });

        function renderChart(el, id, data, positionText) {
            try {
                const ctx = el.getContext('2d');

                // Properly clean up any existing Chart.js instances
                const existingChart = Chart.getChart(el);
                if (existingChart) {
                    existingChart.destroy();
                }

                // Also clean up our window reference
                if (window['chart_' + id]) {
                    delete window['chart_' + id];
                }

                // Handle the nested data structure - use incomingVehicles or outgoingVehicles
                let chartData = data;

                // If data has incomingVehicles or outgoingVehicles, use one of them
                if (data.incomingVehicles && positionText) {
                    chartData = data.incomingVehicles;
                } else if (data.outgoingVehicles && !positionText) {
                    chartData = data.outgoingVehicles;
                }

                // Create new chart
                window['chart_' + id] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels || [],
                        datasets: [{
                            data: (chartData.values || []).map(v => Number(v)),
                            backgroundColor: chartData.color || '#4ade80',
                            barThickness: chartData.thickness || 30,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                reverse: positionText,
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    callback: function(value, index) {
                                        const dataValue = chartData.values[index];
                                        const percent = chartData.percentages ? chartData
                                            .percentages[index] : '';
                                        const label = chartData.labels[index];
                                        return `${label} (${percent}) ${dataValue}`;
                                    },
                                },
                                position: positionText ? 'left' : 'right',
                            }
                        }
                    }
                });
                lucide.createIcons()

                console.log("Chart rendered successfully for id:", id);
            } catch (error) {
                console.error("Error rendering chart:", error);
            }
        }
    });
</script>
