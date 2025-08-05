<div class="position-relative maps-content">
    <div id="map" style="width: 100%; height: 80vh; border-radius: 10px;" wire:ignore></div>

    <!-- Traffic Control Panel -->
    <div class="position-absolute top-0 end-0 sub-maps bg-white rounded shadow-sm"
        style="z-index: 1000; width: 250px; max-height: 80vh; overflow-y: auto;">
        <!-- Threshold Settings -->
        <div class="mb-2">
            <h6 class="mb-2" style="font-size: 0.9rem;">Threshold Settings</h6>
            <div class="row g-1">
                <div class="col-6">
                    <label class="form-label small" style="font-size: 0.8rem;">Low (0 - <span
                            x-text="$wire.thresholds.low"></span>)</label>
                    <input type="number" class="form-control form-control-sm py-1" wire:model.live="thresholds.low"
                        min="0">
                </div>
                <div class="col-6">
                    <label class="form-label small" style="font-size: 0.8rem;">Medium (<span
                            x-text="$wire.thresholds.low + 1"></span> - <span
                            x-text="$wire.thresholds.medium"></span>)</label>
                    <input type="number" class="form-control form-control-sm py-1" wire:model.live="thresholds.medium"
                        :min="$wire.thresholds.low + 1">
                </div>
                <div class="col-12">
                    <small class="text-muted" style="font-size: 0.75rem;">High: Above <span
                            x-text="$wire.thresholds.medium"></span></small>
                </div>
            </div>
        </div>

        <!-- Traffic Count Input -->
        <form wire:submit="updateAllDirections">
            @foreach (['north' => 'Utara', 'south' => 'Selatan', 'east' => 'Timur', 'west' => 'Barat'] as $key => $label)
                <div class="mb-2">
                    <h6 class="border-bottom pb-1 mb-2" style="font-size: 0.9rem;">{{ $label }}</h6>
                    <div class="row g-1">
                        <!-- Incoming Traffic -->
                        <div class="col-6">
                            <label class="form-label d-flex justify-content-between align-items-center mb-1">
                                <small style="font-size: 0.75rem;">Masuk</small>
                                <span class="badge" style="font-size: 0.7rem;"
                                    :class="{
                                        'bg-success': $wire.vehicleCounts.{{ $key }}.in <= $wire.thresholds
                                            .low,
                                        'bg-warning': $wire.vehicleCounts.{{ $key }}.in > $wire.thresholds
                                            .low && $wire.vehicleCounts.{{ $key }}.in <= $wire.thresholds
                                            .medium,
                                        'bg-danger': $wire.vehicleCounts.{{ $key }}.in > $wire.thresholds
                                            .medium
                                    }">{{ $vehicleCounts[$key]['in'] }}</span>
                            </label>
                            <input type="number" class="form-control form-control-sm py-1" min="0"
                                wire:model.live="vehicleCounts.{{ $key }}.in"
                                wire:change="updateVehicleCount('{{ $key }}', 'in', $event.target.value)">
                        </div>
                        <!-- Outgoing Traffic -->
                        <div class="col-6">
                            <label class="form-label d-flex justify-content-between align-items-center mb-1">
                                <small style="font-size: 0.75rem;">Keluar</small>
                                <span class="badge" style="font-size: 0.7rem;"
                                    :class="{
                                        'bg-success': $wire.vehicleCounts.{{ $key }}.out <= $wire.thresholds
                                            .low,
                                        'bg-warning': $wire.vehicleCounts.{{ $key }}.out > $wire.thresholds
                                            .low && $wire.vehicleCounts.{{ $key }}.out <= $wire.thresholds
                                            .medium,
                                        'bg-danger': $wire.vehicleCounts.{{ $key }}.out > $wire.thresholds
                                            .medium
                                    }">{{ $vehicleCounts[$key]['out'] }}</span>
                            </label>
                            <input type="number" class="form-control form-control-sm py-1" min="0"
                                wire:model.live="vehicleCounts.{{ $key }}.out"
                                wire:change="updateVehicleCount('{{ $key }}', 'out', $event.target.value)">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-grid mt-2">
                <button type="submit" class="btn btn-sm btn-primary">Update Traffic</button>
            </div>
        </form>
    </div>

    @push('styles')
        <link href='https://unpkg.com/maplibre-gl@3.3.1/dist/maplibre-gl.css' rel='stylesheet' />
    @endpush

    <script src='https://unpkg.com/maplibre-gl@3.3.1/dist/maplibre-gl.js'></script>
    <script>
        let map;

        function initMap() {
            map = new maplibregl.Map({
                container: 'map',
                style: {
                    version: 8,
                    sources: {
                        'osm': {
                            type: 'raster',
                            tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
                            tileSize: 256,
                            attribution: '&copy; OpenStreetMap Contributors',
                        },
                        'custom-tiles': {
                            type: 'raster',
                            tiles: ['http://localhost:3000/tile/{z}/{x}/{y}.png'],
                            tileSize: 256,
                            attribution: 'Custom Tiles API',
                        },

                        'custom-tiles-2': {
                            type: 'raster',
                            tiles: ['http://localhost:3000/point-tile/{z}/{x}/{y}.png'],
                            tileSize: 256,
                            attribution: 'Custom Tiles API Point',
                        },
                    },
                    layers: [
                    {
                        id: 'osm',
                        type: 'raster',
                        source: 'osm',
                        minzoom: 0,
                        maxzoom: 19
                    },
                    {
                        id: 'custom-tiles',
                        type: 'raster',
                        source: 'custom-tiles',
                        minzoom: 0,
                        maxzoom: 19,
                        paint: {
                            'raster-opacity': 1 // Transparansi untuk custom tiles
                        }
                    },
                    {
                        id: 'custom-tiles-point',
                        type: 'raster',
                        source: 'custom-tiles-2',
                        minzoom: 0,
                        maxzoom: 19,
                        paint: {
                            'raster-opacity': 0.8 // Transparansi untuk custom tiles
                        }
                    },
                ]
                },
                center: [107.64190, -6.94545],
                zoom: 17,
                pitch: 0,
                bearing: 0
            });

            map.on('load', () => {
                const swatches = document.createElement('div');
                swatches.className = 'position-absolute top-0 start-0 m-3 p-2 bg-white rounded shadow-sm';
                swatches.style.zIndex = 1000;

                const colors = {
                    'Default': {
                        low: '#28a745',
                        medium: '#ffc107',
                        high: '#dc3545'
                    },
                    'Blue Scale': {
                        low: '#63B3ED',
                        medium: '#4299E1',
                        high: '#2B6CB0'
                    },
                    'Purple Scale': {
                        low: '#9F7AEA',
                        medium: '#805AD5',
                        high: '#553C9A'
                    },
                    'Orange Scale': {
                        low: '#F6AD55',
                        medium: '#ED8936',
                        high: '#C05621'
                    }
                };

                for (const [name, scheme] of Object.entries(colors)) {
                    const swatch = document.createElement('button');
                    swatch.className = 'btn btn-sm d-block mb-1';
                    swatch.style.backgroundColor = scheme.low;
                    swatch.style.width = '120px';
                    swatch.style.height = '25px';
                    swatch.textContent = name;

                    swatch.addEventListener('click', () => {
                        currentColorScheme = scheme;
                        updateLineColors();
                    });

                    swatches.appendChild(swatch);
                }

                map.getContainer().appendChild(swatches);

                let currentColorScheme = colors['Default'];

                function updateLineColors() {
                    map.setPaintProperty('traffic-lines', 'line-color', [
                        'match',
                        ['get', 'trafficLevel'],
                        'low', currentColorScheme.low,
                        'medium', currentColorScheme.medium,
                        'high', currentColorScheme.high,
                        currentColorScheme.low
                    ]);
                }

                // map.addSource('traffic-lines', {
                //     'type': 'geojson',
                //     'data': {
                //         "type": "FeatureCollection",
                //         "features": [{
                //                 "type": "Feature",
                //                 "properties": {
                //                     "direction": "west",
                //                     "trafficLevel": "low",
                //                     "label": "Barat"
                //                 },
                //                 "geometry": {
                //                     "coordinates": [
                //                         [107.64180081519584, -6.945399455415483],
                //                         [107.6417099058109, -6.945434858153604]
                //                     ],
                //                     "type": "LineString"
                //                 }
                //             },
                //             {
                //                 "type": "Feature",
                //                 "properties": {
                //                     "direction": "east",
                //                     "trafficLevel": "low",
                //                     "label": "Timur"
                //                 },
                //                 "geometry": {
                //                     "coordinates": [
                //                         [107.64198053605571, -6.945527877099721],
                //                         [107.64207214474408, -6.945496639395515]
                //                     ],
                //                     "type": "LineString"
                //                 }
                //             },
                //             {
                //                 "type": "Feature",
                //                 "properties": {
                //                     "direction": "north",
                //                     "trafficLevel": "low",
                //                     "label": "Utara"
                //                 },
                //                 "geometry": {
                //                     "coordinates": [
                //                         [107.64192948694006, -6.945286999641425],
                //                         [107.64193578066664, -6.945180097213537]
                //                     ],
                //                     "type": "LineString"
                //                 }
                //             },
                //             {
                //                 "type": "Feature",
                //                 "properties": {
                //                     "direction": "south",
                //                     "trafficLevel": "low",
                //                     "label": "Selatan"
                //                 },
                //                 "geometry": {
                //                     "coordinates": [
                //                         [107.64181550055713, -6.945625755224697],
                //                         [107.64181200404221, -6.945721550816373]
                //                     ],
                //                     "type": "LineString"
                //                 }
                //             }
                //         ]
                //     }
                // });

                // map.addSource('jalan', {
                //     type: 'geojson',
                //     data: '/JALAN_LN_25K_geojson.json' // Ganti dengan path file GeoJSON Anda
                // });

                // function updateAllLines() {
                //     const features = map.getSource('traffic-lines')._data.features;
                //     features.forEach(feature => {
                //         const direction = feature.properties.direction;
                //         const counts = @this.vehicleCounts[direction];
                //         const totalCount = counts.in + counts.out;

                //         let level = 'low';
                //         if (totalCount > @this.thresholds.medium) {
                //             level = 'high';
                //         } else if (totalCount > @this.thresholds.low) {
                //             level = 'medium';
                //         }

                //         feature.properties.trafficLevel = level;
                //     });

                //     map.getSource('traffic-lines').setData({
                //         type: 'FeatureCollection',
                //         features: features
                //     });
                // }

                document.querySelector('form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    updateAllLines();
                });

                // map.addLayer({
                //     'id': 'traffic-lines',
                //     'type': 'line',
                //     'source': 'traffic-lines',
                //     'paint': {
                //         'line-color': [
                //             'match',
                //             ['get', 'trafficLevel'],
                //             'low', '#28a745',
                //             'medium', '#ffc107',
                //             'high', '#dc3545',
                //             '#28a745'
                //         ],
                //         'line-width': 12,
                //         'line-opacity': 1,
                //         'line-blur': 0.5,
                //         'line-gap-width': 1
                //     }
                // });

                // map.addLayer({
                //     'id': 'traffic-lines-border',
                //     'type': 'line',
                //     'source': 'traffic-lines',
                //     'paint': {
                //         'line-color': '#ffffff',
                //         'line-width': 14,
                //         'line-opacity': 0.5,
                //         'line-blur': 1
                //     }
                // }, 'traffic-lines');


                // map.addLayer({
                //     'id': 'direction-labels',
                //     'type': 'symbol',
                //     'source': 'traffic-lines',
                //     'layout': {
                //         'text-field': ['get', 'label'],
                //         'text-size': 12,
                //         'text-offset': [0, -1],
                //         'text-anchor': 'center',
                //         'symbol-placement': 'line-center'
                //     },
                //     'paint': {
                //         'text-color': '#000000',
                //         'text-halo-color': '#ffffff',
                //         'text-halo-width': 2
                //     }
                // });

                // map.addLayer({
                //     id: 'jalan-layer',
                //     type: 'line',
                //     source: 'jalan',
                //     paint: {
                //         'line-color': '#0000FF', // Warna garis hijau
                //         'line-width': 2
                //     }
                // });
                Livewire.on('traffic-updated', (data) => {
                    if (!map.getSource('traffic-lines')) return;

                    console.log('Received traffic update:', data); // Debug log

                    const features = map.getSource('traffic-lines')._data.features;
                    const feature = features.find(f => f.properties.direction === data.direction);

                    if (feature) {

                        feature.properties.trafficLevel = data.level;
                        console.log(`Updating ${data.direction} to level ${data.level}`); // Debug log


                        map.getSource('traffic-lines').setData({
                            type: 'FeatureCollection',
                            features: features
                        });


                        map.setPaintProperty('traffic-lines', 'line-color', [
                            'match',
                            ['get', 'trafficLevel'],
                            'low', currentColorScheme.low,
                            'medium', currentColorScheme.medium,
                            'high', currentColorScheme.high,
                            currentColorScheme.low
                        ]);
                    }
                });


                Livewire.on('thresholds-updated', (thresholds) => {
                    updateLineColors();
                });

                const style = document.createElement('style');
                style.textContent = `
                    .btn-sm { 
                        color: white;
                        border: none;
                        margin-bottom: 5px;
                        text-shadow: 0 0 2px rgba(0,0,0,0.5);
                        font-size: 0.8rem;
                    }
                    .btn-sm:hover {
                        opacity: 0.8;
                        color: white;
                    }
                `;
                document.head.appendChild(style);
            });

            let currentMarker = null; // Variabel untuk menyimpan marker yang sudah ada

            // Event listener untuk klik pada peta
            map.on('click', (e) => {
                // Pastikan lngLat ada
                if (!e.lngLat) {
                    console.error('Error: lngLat is undefined');
                    return;
                }

                const lat = e.lngLat.lat;
                const lng = e.lngLat.lng;

                console.log(`Latitude: ${lat}, Longitude: ${lng}`);
                
                // Cek apakah marker sudah ada di lokasi ini
                if (currentMarker && currentMarker.getLngLat().lat.toFixed(5) === lat.toFixed(5) && currentMarker.getLngLat().lng.toFixed(5) === lng.toFixed(5)) {
                    // Jika marker ada, buka popupnya
                    currentMarker.togglePopup();
                    return;
                }

                // Panggil API Node.js
                fetch(`http://localhost:3000/api/detect-direction?lat=${lat}&lng=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        alert(`Arah: ${data.direction} (Sudut: ${data.angleDeg}°)`);

                        // // Tambahkan marker di lokasi klik
                        // new maplibregl.Marker()
                        //     .setLngLat([lng, lat])
                        //     .setPopup(new maplibregl.Popup().setHTML(`<b>Arah: ${data.direction}</b>`))
                        //     .addTo(map);
                        window.location.href = "http://localhost:8000/intersections";
                    })
                    .catch(err => console.error('Error:', err));
            });
        }

        document.addEventListener('livewire:initialized', () => {
            if (!map) {
                initMap();
            }
        });
    </script>
</div>
