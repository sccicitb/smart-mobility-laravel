<div x-data="{ openModal: false, show: false, message: 'init', type: 'success' }"
    x-on:notify.window="
        console.log('Event diterima:', $event);
        type = $event.detail[0].type;
        message = $event.detail[0].message;
        show = true;
        setTimeout(() => show = false, 3000);
    "
    x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons() })" class="p-4">
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
    <div x-show="show" x-cloak x-transition.opacity
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[99999] flex items-center justify-center">
        <!-- Card Notif -->
        <div class="rounded-2xl px-6 py-4 flex items-center gap-4 shadow-2xl w-[320px]"
            :class="type === 'success' ? 'bg-white' : 'bg-white'">
            <!-- Icon -->
            <div class="flex items-center justify-center rounded-full w-12 h-12"
                :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'">
                <i data-lucide="check" x-show="type === 'success'" class="w-7 h-7 text-white"></i>
                <i data-lucide="x" x-show="type !== 'success'" class="w-7 h-7 text-white"></i>
            </div>

            <!-- Text -->
            <div class="flex-1">
                <p class="text-gray-800 font-semibold text-lg" x-text="type === 'success' ? 'Berhasil!' : 'Gagal!'"></p>
                <p class="text-gray-600 text-sm" x-text="message"></p>
            </div>
        </div>
    </div>


    <div class="lg:flex justify-between mb-4 w-full items-center">
        {{-- <h3 class="text-2xl font-bold text-white w-full">Data Jarak & Analisis Simpang</h3> --}}
        <h3 class="text-2xl font-bold text-white w-full">Jarak Simpang & Indikator Lalu Lintas</h3>
        <div class="flex flex-col lg:flex-row w-full gap-2 justify-end my-2">
            <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari simpang, arah, ruas..."
                class="px-3 py-1 border rounded shadow-xs w-64" />

            <button @click="openModal = true; $wire.create()"
                class="px-3 py-1 bg-green-500 text-white rounded shadow-xs">
                Tambah Data
            </button>
        </div>

    </div>

    <!-- Card Info Carbon Calculation -->
    <div x-data="{ openModal: false, activeAccordion: null }">

        <!-- Accordion Info Cards -->
        <div class="space-y-3 mb-4">
            <!-- Summary Card (Always Visible) -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 border-l-4 border-gray-500 p-4 rounded-r-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-2xl mr-2">📊</span>
                        <h4 class="font-bold text-gray-800">Integrasi Indikator Analisis Lalu Lintas</h4>
                    </div>
                    <button @click="activeAccordion = activeAccordion === 'all' ? null : 'all'"
                        class="text-sm px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        <span x-text="activeAccordion === 'all' ? 'Tutup Semua' : 'Lihat Detail Formula'"></span>
                    </button>
                </div>

                <div class="grid md:grid-cols-3 gap-3 text-sm mt-3">
                    <div class="bg-white p-3 rounded border-l-2 border-green-400 cursor-pointer hover:shadow-md transition"
                        @click="activeAccordion = activeAccordion === 'emisi' ? null : 'emisi'">
                        <strong class="text-green-700">🌱 Emisi CO2</strong>
                        <p class="mt-1 text-gray-600">Dampak lingkungan dari volume kendaraan</p>
                    </div>
                    <div class="bg-white p-3 rounded border-l-2 border-orange-400 cursor-pointer hover:shadow-md transition"
                        @click="activeAccordion = activeAccordion === 'cost' ? null : 'cost'">
                        <strong class="text-orange-700">💰 Economic Cost</strong>
                        <p class="mt-1 text-gray-600">Beban biaya operasional pengguna jalan</p>
                    </div>
                    <div class="bg-white p-3 rounded border-l-2 border-purple-400 cursor-pointer hover:shadow-md transition"
                        @click="activeAccordion = activeAccordion === 'los' ? null : 'los'">
                        <strong class="text-purple-700">📊 Level of Service</strong>
                        <p class="mt-1 text-gray-600">Kualitas pelayanan & efisiensi jalan</p>
                    </div>
                </div>
            </div>

            <!-- Carbon Emissions Detail -->
            <div x-show="activeAccordion === 'emisi' || activeAccordion === 'all'" x-transition.duration.300ms
                class="bg-gradient-to-r from-green-50 to-blue-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center mb-3">
                    <span class="text-2xl mr-2">🌱</span>
                    <h4 class="font-bold text-green-800">Carbon Emissions (Emisi CO2)</h4>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-md">
                    <div>
                        <p class="text-green-700 mb-2 font-semibold">Formula:</p>
                        <div class="bg-white/70 p-3 rounded border border-green-200">
                            <code class="text-md">CO2 = (Volume × Jarak × Faktor Emisi) ÷ Lebar Jalan</code>
                        </div>
                        <p class="text-green-600 mt-2 text-sm">
                            Menghitung total emisi karbon per ruas jalan berdasarkan volume kendaraan, jarak tempuh,
                            dan faktor emisi masing-masing jenis kendaraan.
                        </p>
                    </div>

                    <div class="bg-white/70 p-3 rounded">
                        <p class="font-semibold text-gray-700 mb-2">Faktor Emisi (gram CO2/km):</p>
                        <div class="text-sm text-gray-600 grid grid-cols-2 gap-x-3 gap-y-1">
                            <div>• Sepeda Motor: <strong>80g</strong></div>
                            <div>• Mobil: <strong>180g</strong></div>
                            <div>• Angk. Umum: <strong>350g</strong></div>
                            <div>• Truk Ringan: <strong>250g</strong></div>
                            <div>• Bus Sedang: <strong>800g</strong></div>
                            <div>• Truk Sedang: <strong>400g</strong></div>
                            <div>• Truk Berat: <strong>600g</strong></div>
                            <div>• Bus Besar: <strong>1200g</strong></div>
                        </div>
                    </div>

                    <div class="bg-white/70 p-3 rounded">
                        <p class="font-semibold text-gray-700 mb-2">Contoh Perhitungan:</p>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div>Motor: (100 × 2km × 80g) ÷ 8m = 2.000g</div>
                            <div>Mobil: (50 × 2km × 180g) ÷ 8m = 2.250g</div>
                            <div class="border-t pt-1 font-medium text-green-700">Total = 4.25 kg CO2</div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600">
                        <p class="font-semibold text-green-700 mb-1">Sumber Referensi:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>IPCC Guidelines for Transport</li>
                            <li>Kementerian Lingkungan Hidup RI</li>
                            <li>BPPT - Penelitian Emisi Transportasi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Economic Cost Detail -->
            <div x-show="activeAccordion === 'cost' || activeAccordion === 'all'" x-transition.duration.300ms
                class="bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-500 p-4 rounded-r-lg">
                <div class="flex items-center mb-3">
                    <span class="text-2xl mr-2">💰</span>
                    <h4 class="font-bold text-orange-800">Economic Cost (Kerugian Ekonomi)</h4>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-md">
                    <div>
                        <p class="text-orange-700 mb-2 font-semibold">Formula:</p>
                        <div class="bg-white/70 p-3 rounded border border-orange-200">
                            <code class="text-md">Cost = (Jumlah × Biaya/km) × Jarak</code>
                        </div>
                        <p class="text-orange-600 mt-2 text-sm">
                            Total kerugian ekonomi akibat biaya operasional kendaraan saat melewati ruas jalan.
                        </p>
                    </div>

                    <div class="bg-white/70 p-3 rounded">
                        <p class="font-semibold text-gray-700 mb-2">Biaya Operasional per Km:</p>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex justify-between">
                                <span>Sepeda Motor:</span><strong>Rp 10.000</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Mobil Penumpang:</span><strong>Rp 20.000</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Angkutan Umum:</span><strong>Rp 40.000</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Truk/Bus:</span><strong>Rp 40.000</strong>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/70 p-3 rounded">
                        <p class="font-semibold text-gray-700 mb-2">Contoh Perhitungan:</p>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div>Motor: 100 × Rp10rb × 2km = Rp 2.000.000</div>
                            <div>Mobil: 50 × Rp20rb × 2km = Rp 2.000.000</div>
                            <div>Truk: 10 × Rp40rb × 2km = Rp 800.000</div>
                            <div class="border-t pt-1 font-medium text-orange-700">Total = Rp 4.800.000</div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600">
                        <p class="font-semibold text-orange-700 mb-1">Komponen Biaya:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Bahan bakar</li>
                            <li>Depresiasi kendaraan</li>
                            <li>Maintenance & perawatan</li>
                            <li>Biaya waktu (time value)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Level of Service Detail -->
            <div x-show="activeAccordion === 'los' || activeAccordion === 'all'" x-transition.duration.300ms
                class="bg-gradient-to-r from-purple-50 to-indigo-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
                <div class="flex items-center mb-3">
                    <span class="text-2xl mr-2">📊</span>
                    <h4 class="font-bold text-purple-800">Level of Service (LOS)</h4>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-md">
                    <div>
                        <p class="text-purple-700 mb-2 font-semibold">Formula:</p>
                        <div class="bg-white/70 p-3 rounded border border-purple-200">
                            <code class="text-md">V/C Ratio = Volume ÷ Kapasitas</code>
                        </div>
                        <div class="mt-2 text-sm space-y-1">
                            <div><strong>Kapasitas (C):</strong> Lebar × 1600 smp/jam/m</div>
                            <div><strong>Volume (V):</strong> SM×1.0 + MP×1.0 + Berat×1.3</div>
                        </div>
                    </div>

                    <div class="bg-white/70 p-3 rounded">
                        <p class="font-semibold text-gray-700 mb-2">Kategori LOS:</p>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2 py-0.5 bg-green-100 text-green-800 rounded font-bold text-sm">A</span>
                                <span>≤0.60 - Arus bebas</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-bold text-sm">B</span>
                                <span>0.60-0.70 - Stabil</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded font-bold text-sm">C</span>
                                <span>0.70-0.80 - Terbatas</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2 py-0.5 bg-orange-100 text-orange-800 rounded font-bold text-sm">D</span>
                                <span>0.80-0.90 - Tidak stabil</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded font-bold text-sm">E-F</span>
                                <span>>0.90 - Macet</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/70 p-3 rounded col-span-2">
                        <p class="font-semibold text-gray-700 mb-2">Interpretasi:</p>
                        <div class="grid md:grid-cols-2 gap-2 text-sm text-gray-600">
                            <div><strong>LOS A-B:</strong> Kondisi ideal, lancar</div>
                            <div><strong>LOS C:</strong> Stabil tapi terbatas</div>
                            <div><strong>LOS D:</strong> Kepadatan tinggi</div>
                            <div><strong>LOS E-F:</strong> Kemacetan, perlu intervensi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal (unchanged) --}}
        <div x-show="openModal" x-transition.opacity
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display:none">
            <div class="bg-white w-2/3 rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold mb-4">
                    {{ $isEdit ? 'Edit Jarak Simpang' : 'Tambah Jarak Simpang' }}
                </h3>
                <form wire:submit.prevent="store" class="space-y-3">
                    <!-- Form fields unchanged -->
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="openModal = false"
                            class="px-3 py-1 bg-gray-400 text-white rounded">Batal</button>
                        <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded"
                            @click="openModal = false">
                            {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal pakai Alpine --}}{{-- Modal pakai Alpine --}}
    <div x-show="openModal" x-transition.opacity
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display:none"
        wire:ignore.self>
        <div class="bg-white w-2/3 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">
                {{ $isEdit ? 'Edit Jarak Simpang' : 'Tambah Jarak Simpang' }}
            </h3>

            <form wire:submit.prevent="store" class="space-y-3">
                <div>
                    <label>Simpang</label>
                    <select wire:model="ID_Simpang" class="w-full border rounded p-1">
                        <option value="">-- Pilih Simpang --</option>
                        @foreach ($simpangs as $s)
                            <option value="{{ $s->id }}">{{ $s->Nama_Simpang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label>Dari Arah</label>
                        <input type="text" wire:model="dari_arah" class="w-full border rounded p-1">
                    </div>
                    <div>
                        <label>Ke Arah</label>
                        <input type="text" wire:model="ke_arah" class="w-full border rounded p-1">
                    </div>
                </div>

                <div>
                    <label>Jarak (km)</label>
                    <input type="number" step="0.01" wire:model="jarak_km" class="w-full border rounded p-1">
                </div>

                <div>
                    <label>Lebar Jalan (meter)</label>
                    <input type="number" step="0.01" wire:model="lebar_jalan" class="w-full border rounded p-1">
                </div>

                <div>
                    <label>Nama Ruas</label>
                    <input type="text" wire:model="nama_ruas" class="w-full border rounded p-1">
                </div>

                <div>
                    <label>Keterangan</label>
                    <textarea wire:model="keterangan" class="w-full border rounded p-1"></textarea>
                </div>

                <div>
                    <label>Status</label>
                    <select wire:model="status" class="w-full border rounded p-1">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @click="openModal = false"
                        class="px-3 py-1 bg-gray-400 text-white rounded">Batal</button>
                    <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded"
                        @click="openModal = false">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="w-full rounded-lg p-4 bg-white/90 mt-4">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border bg-white/95">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2 text-center">No</th>
                        <th class="border p-2 text-center">Simpang</th>
                        <th class="border p-2 text-center">Dari Arah</th>
                        <th class="border p-2 text-center">Ke Arah</th>
                        <th class="border p-2 text-center">Jarak (km)</th>
                        <th class="border p-2 text-center">Lebar Jalan (m)</th>
                        <th class="border p-2 text-center">Nama Ruas</th>
                        <th class="border p-2 text-center">Status</th>
                        <th class="border p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td class="border p-2 text-center">{{ $item->id }}</td>
                            <td class="border p-2">{{ $item->simpang->Nama_Simpang ?? '-' }}</td>
                            <td class="border p-2">{{ $item->dari_arah }}</td>
                            <td class="border p-2">{{ $item->ke_arah }}</td>
                            <td class="border p-2 text-center">{{ $item->jarak_km }}</td>
                            <td class="border p-2 text-center">{{ $item->lebar_jalan ?? '-' }}</td>
                            <td class="border p-2">{{ $item->nama_ruas ?? '-' }}</td>
                            <td class="border p-2 text-center">
                                <span
                                    class="px-2 py-1 rounded text-md {{ $item->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="border p-2 justify-center flex gap-2">
                                <button @click="openModal = true; $dispatch('edit-item', { id: {{ $item->id }} })"
                                    class="px-2 py-1 bg-yellow-400 rounded text-md">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Yakin hapus data ini?')"
                                    class="px-2 py-1 bg-red-500 shadow-xs text-white rounded text-md">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border p-4 text-center text-gray-500">
                                @if ($search)
                                    Tidak ada data yang cocok dengan pencarian "{{ $search }}"
                                @else
                                    Belum ada data jarak simpang
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('notify', e => console.log('Notif event:', e.detail));
</script>
