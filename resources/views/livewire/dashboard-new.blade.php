<div class="px-6 flex flex-col w-full gap-4">
    <div wire:loading class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999]">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
                bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 max-w-sm">
            <!-- Spinner -->
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-200 rounded-full"></div>
                <div
                    class="w-16 h-16 border-4 border-[#16598b] border-t-transparent rounded-full animate-spin absolute top-0">
                </div>
            </div>

            <!-- Loading Text -->
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Loading Data...</h3>
                <p class="text-sm text-gray-600">Please wait while we fetch the latest information</p>
            </div>
        </div>
    </div>

    @if (session('welcome'))
        <div id="welcomeModal"
            class="fixed inset-0 bg-black backdrop-blur-sm bg-opacity-50 flex items-center justify-center z-50">
            <div
                class="bg-[#16598b] px-10 py-5 rounded-2xl shadow-lg p-6 w-11/12 max-w-4xl text-center overflow-y-auto max-h-[90%]">
                <p class="text-white">{{ session('welcome') }}</p>
                <h2 class="text-3xl font-semibold mb-4 text-gray-800 text-white">Welcome to Smart Mobility Simulator
                </h2>
                <div class="w-full flex flex-col md:flex-row lg:flex-col">
                    <div class="text-white w-full text-justify text-lg">{{ $descriptionWelcome }}</div>
                    <div class="w-full flex flex-col lg:flex-row justify-around items-center">
                        <div class="flex flex-col items-center justify-center">
                            <img src="{{ asset('images/realtime_traffic_data.jpg') }}" alt="Welcome Image"
                                class="w-40 h-40 mx-auto my-4 rounded-lg shadow-xs">
                            <div class="font-semibold text-white">Realtime Traffic Data</div>
                        </div>
                        <div class="h-fit">
                            <i data-lucide="plus" class="w-10 h-10 text-white"></i>
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <img src="{{ asset('images/Intersection_Simulation.jpg') }}" alt="Welcome Image"
                                class="w-40 h-40 mx-auto my-4 rounded-lg shadow-xs">
                            <div class="font-semibold text-white">Intersection Simulation</div>
                        </div>
                        <div class="h-fit rotate-90 lg:rotate-0">
                            <i data-lucide="chevron-right" class="w-10 h-10 text-white"></i>
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <img src="{{ asset('images/Impact_Analysist.png') }}" alt="Welcome Image"
                                class="w-40 h-40 mx-auto my-4 rounded-lg">
                            <div class="font-semibold text-white">Impact Analysis</div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button
                        onclick="document.getElementById('welcomeModal').classList.add('opacity-0'); setTimeout(()=>{document.getElementById('welcomeModal').remove()}, 300);"
                        class="px-3 py-2 bg-white text-gray-700 font-semibold shadow-xs rounded-lg">Tutup</button>
                </div>
            </div>
        </div>
        {{-- const modal = document.getElementById('welcomeModal');
        setTimeout(() => {
            modal.classList.add('opacity-0');
            setTimeout(()=>{ modal.remove() }, 300);
        }, 5000);
    --}}
    @endif
    <!-- Overlay -->
    <div id="guideOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-40"></div>

    <div id="guideModal" class="absolute hidden z-50">
        <div id="guideDialog" class="bg-[#16598b] rounded-2xl shadow-xs p-4 w-96 text-center text-white">
            <h2 class="text-lg font-bold mb-2" id="guideTitle">Title Step</h2>
            <p class="text-sm text-justify" id="guideDesc">Description Step</p>
            <div class="flex justify-between gap-3 mt-4">
                <button id="guidePrev"
                    class="px-3 py-1 rounded bg-gray-100 hover:bg-yellow-400/60 text-gray-800 hover:text-white font-semibold cursor-not-allowed"
                    disabled>Previous</button>
                <div class="flex justify-end gap-3">
                    <button id="guideNext"
                        class="px-3 py-1 rounded bg-gray-100 hover:bg-green-400/60 text-gray-800 hover:text-white font-semibold">Next</button>
                    <button id="guideClose"
                        class="px-3 py-1 rounded bg-gray-100 hover:bg-red-400/60 text-gray-800 hover:text-white font-semibold">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="w-full md:flex md:justify-between">
        <div class="flex gap-3" id="filterButtons">
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
        <div class="flex gap-3">
            <button id="startGuideBtn"
                class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#16598b] text-white hover:bg-[#124d7a] transition-colors duration-200 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
                Guide
            </button>
            {{-- <button id="restartGuideBtn"
                class="px-4 py-2 rounded-lg text-sm font-semibold bg-green-600 text-white hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Restart Guide
            </button> --}}
        </div>
    </div>

    <!-- Stats Cards - TETAP SAMA, tidak diubah -->
    <div class="w-full lg:flex gap-7">
        <div class="w-full lg:w-1/2 grid grid-cols-1 xl:grid-cols-2 mb-7 lg:mb-0 gap-7" id="statsCards">
            <livewire:component.cards.stats-card :title="'Carbon Emissions'" :value="$data_emisi['total'] ?? 0" :unit="'Kg'" :icon="'wind'"
                :link="url('/distance')" :key="'dashboard-stats-card-carbon-' . $filter" />

            <livewire:component.cards.stats-card :title="'Level of Service'" :value="$data_emisi['los'] ?? '-'" :unit="''" :icon="'arrows-up-from-line'"
                :link="url('/distance')" :key="'dashboard-service-' . $filter" />

            <livewire:component.cards.stats-card :title="'Peak Flow Time'" :value="$data_emisi['peak'] ?? '-'" :unit="''"
                :link="url('/distance')" :icon="'clock'" :key="'dashboard-peak-' . $filter" />

            <livewire:component.cards.stats-card :title="'Total Losses'" :value="$data_emisi['cost'] ?? '-'" :unit="'Ribu Rp'"
                :link="url('/distance')" :icon="'circle-dollar-sign'" :key="'dashboard-cost-' . $filter" />

        </div>

        <!-- Placeholder card -->
        <div class="w-full lg:w-1/2 rounded-2xl p-3 bg-white" id="graficVehicle">
            <div class="text-gray-700 font-semibold text-lg">{{ $titleBar }}</div>
            <div class="grid grid-cols-2 gap-5">
                <div class="w-full flex my-auto">
                    @livewire(
                        'component.cards.bar-vehicle',
                        [
                            'id' => 'bar-chart-incoming-' . uniqid(),
                            'positionText' => true,
                            'chartData' => $vehicleData['incomingVehicles'] ?? [],
                        ],
                        key('bar-vehicle-incoming')
                    )
                </div>
                <div class="w-full flex my-auto">
                    @livewire(
                        'component.cards.bar-vehicle',
                        [
                            'id' => 'bar-chart-outgoing-' . uniqid(),
                            'positionText' => false,
                            'chartData' => $vehicleData['outgoingVehicles'] ?? [],
                        ],
                        key('bar-vehicle-outgoing')
                    )
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section - INDEPENDENT dari stats -->
    <div class="w-full lg:flex flex-row-reverse gap-7">
        <div class="w-full lg:w-1/2 mb-7 lg:mb-0" id="barVehicle">
            @livewire(
                'component.cards.charts-card',
                [
                    'chartType' => 'line',
                    'title' => 'Trend Data Analysis',
                    'filter' => $filter,
                ],
                key('charts-card')
            )
        </div>
        <div class="w-full lg:w-1/2 flex justify-center h-92 rounded-xl bg-black items-center" id="cameraPreview">
            @livewire(
                'component.cards.video-player',
                [
                    'src' => 'https://cctvjss.jogjakota.go.id/atcs/ATCS_jlagran.stream/chunklist_w464847900.m3u8',
                    'poster' => 'https://wallpapers.com/images/hd/plain-black-desktop-2560-x-1440-ugpl0479gu0vuwnl.jpg',
                ],
                key('video')
            )
        </div>
    </div>
</div>

{{-- lucide.replace(); --}}
{{-- {
    element: null, // welcome
    title: "Welcome",
    desc: "{{ $descriptionWelcome }}",
    position: "center"
}, --}}
<script>
    document.addEventListener("livewire:init", () => {
        Livewire.on("vehicleDataUpdated", (vehicleData) => {
            console.log("Vehicle Data:", vehicleData);
        });
    });
</script>

<script>
    window.addEventListener('emisiData', event => {
        // console.log("Data Emisi:", event.detail[0].data[0].total_emisi_co2_kg_hari_ini);
        console.log("Data Emisi:", event.detail);
    });

    document.addEventListener("DOMContentLoaded", function() {
        const steps = [{
                element: document.getElementById('filterButtons'),
                title: "Filter Data",
                desc: "Use this filter button to select data based on Today, Week, or Month.",
                position: "bottom-left"
            },
            {
                element: document.getElementById('statsCards'),
                title: "Statistics Overview",
                desc: "This section display summary of impact analysis from intersection level of service.",
                position: "bottom-left"
            },
            {
                element: document.getElementById('graficVehicle'),
                title: "Trend Data Analyst Vehicle",
                desc: "This section display vehicle counting classification to show vehicle type distribution that impact intersection.",
                position: "bottom-right"
            },
            {
                element: document.getElementById('cameraPreview'),
                title: "Camera CCTV Preview",
                desc: "This section display Intersection Condition from CCTV to show how video analytics counts vehicle.",
                position: "top-left"
            },
            {
                element: document.getElementById('barVehicle'),
                title: "Trend Data Analyst Vehicle",
                desc: "This section display vehicle counting time period to show traffic pattern.",
                position: "top-right"
            }
        ];

        let currentStep = 0;
        const modal = document.getElementById('guideModal');
        const dialog = document.getElementById('guideDialog');
        const overlay = document.getElementById('guideOverlay');
        const titleEl = document.getElementById('guideTitle');
        const descEl = document.getElementById('guideDesc');
        const btnNext = document.getElementById('guideNext');
        const btnPrev = document.getElementById('guidePrev');
        const btnClose = document.getElementById('guideClose');

        function showStep(stepIndex) {
            const step = steps[stepIndex];
            modal.classList.remove('hidden');
            overlay.classList.remove('hidden');

            titleEl.textContent = step.title;
            descEl.textContent = step.desc;

            // clear highlight lama
            document.querySelectorAll('.highlighted-step')
                .forEach(el => el.classList.remove('highlighted-step', 'relative', 'z-50'));

            if (step.element) {
                step.element.classList.add('highlighted-step', 'relative', 'z-50');
                const rect = step.element.getBoundingClientRect();
                const scrollY = window.scrollY || window.pageYOffset;
                const scrollX = window.scrollX || window.pageXOffset;

                let top, left;
                switch (step.position) {
                    case "top":
                        top = rect.top + scrollY - dialog.offsetHeight - 10;
                        left = rect.left + scrollX + rect.width / 2 - dialog.offsetWidth / 2;
                        break;
                    case "top-left":
                        top = rect.top + scrollY - dialog.offsetHeight - 10;
                        left = rect.left + scrollX;
                        break;
                    case "top-right":
                        top = rect.top + scrollY - dialog.offsetHeight - 10;
                        left = rect.right + scrollX - dialog.offsetWidth;
                        break;
                    case "bottom":
                        top = rect.bottom + scrollY + 10;
                        left = rect.left + scrollX;
                        break;
                    case "bottom-left":
                        top = rect.bottom + scrollY + 10;
                        left = rect.left + scrollX;
                        break;
                    case "bottom-right":
                        top = rect.bottom + scrollY + 10;
                        left = rect.right + scrollX - dialog.offsetWidth;
                        break;
                    case "left":
                        top = rect.top + scrollY + rect.height / 2 - dialog.offsetHeight / 2;
                        left = rect.left + scrollX - dialog.offsetWidth - 10;
                        break;
                    case "right":
                        top = rect.top + scrollY + rect.height / 2 - dialog.offsetHeight / 2;
                        left = rect.right + scrollX + 10;
                        break;
                    case "center":
                        top = window.innerHeight / 2 - dialog.offsetHeight / 2 + scrollY;
                        left = window.innerWidth / 2 - dialog.offsetWidth / 2 + scrollX;
                        break;
                    default:
                        top = rect.bottom + scrollY + 10;
                        left = rect.left + scrollX + rect.width / 2 - dialog.offsetWidth / 2;
                }

                modal.style.top = `${top}px`;
                modal.style.left = `${left}px`;
            } else {
                // step welcome tanpa target element
                modal.style.top = window.innerHeight / 2 - dialog.offsetHeight / 2 + 'px';
                modal.style.left = window.innerWidth / 2 - dialog.offsetWidth / 2 + 'px';
            }

            // Update button states
            btnPrev.disabled = stepIndex === 0;
            btnPrev.classList.toggle('cursor-not-allowed', stepIndex === 0);
            btnNext.textContent = (stepIndex === steps.length - 1) ? "Finish" : "Next";

            console.log(`Showing step ${stepIndex + 1}/${steps.length}: ${step.title}`);
        }

        btnNext.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            } else {
                closeGuide();
            }
        });

        btnPrev.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        btnClose.addEventListener('click', closeGuide);

        function closeGuide() {
            modal.classList.add('hidden');
            overlay.classList.add('hidden');
            document.querySelectorAll('.highlighted-step')
                .forEach(el => el.classList.remove('highlighted-step', 'relative', 'z-50'));

            // RESET currentStep ketika guide ditutup
            currentStep = 0;
            console.log('Guide closed and reset');
        }

        function startGuide() {
            console.log('Starting guide...'); // Debug
            // RESET currentStep setiap kali guide dimulai
            currentStep = 0;
            showStep(currentStep);
        }

        // Handle welcome modal close dan mulai guide
        @if (session('welcome'))
            // Modifikasi tombol close welcome modal
            const welcomeCloseBtn = document.querySelector('#welcomeModal button');
            if (welcomeCloseBtn) {
                // Remove existing onclick
                welcomeCloseBtn.removeAttribute('onclick');

                // Add new event listener
                welcomeCloseBtn.addEventListener('click', function() {
                    const welcomeModal = document.getElementById('welcomeModal');
                    welcomeModal.classList.add('opacity-0');

                    setTimeout(() => {
                        welcomeModal.remove();
                        // Mulai guide setelah welcome modal tertutup
                        setTimeout(() => {
                            startGuide();
                        }, 300); // Delay sedikit untuk memastikan DOM siap
                    }, 300);
                });
            }
        @endif

        // Event listeners untuk guide buttons
        const startGuideBtn = document.getElementById('startGuideBtn');
        const restartGuideBtn = document.getElementById('restartGuideBtn');

        if (startGuideBtn) {
            startGuideBtn.addEventListener('click', function() {
                console.log('Guide button clicked');
                startGuide();
            });
        }

        if (restartGuideBtn) {
            restartGuideBtn.addEventListener('click', function() {
                console.log('Restart guide button clicked');
                startGuide(); // startGuide sudah reset currentStep ke 0
            });
        }

        // Expose function untuk debugging
        window.startGuide = startGuide;
        window.closeGuide = closeGuide;
        window.resetGuide = function() {
            currentStep = 0;
            console.log('Guide manually reset');
        };
        window.getCurrentStep = function() {
            return currentStep;
        };
    });
</script>


{{-- @if (session('welcome')) --}}
{{-- @endif --}}
