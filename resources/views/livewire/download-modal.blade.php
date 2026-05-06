<div>
    @if($showModal)
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998]" wire:click="hide"></div>

    {{-- Modal --}}
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#7c2d2d]/10 flex items-center justify-center">
                        <i data-lucide="download" class="w-4 h-4 text-[#7c2d2d]"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-800">Unduh Data</h2>
                </div>
                <button wire:click="hide"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 flex flex-col gap-5">

                {{-- Date range --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600">Dari Tanggal</label>
                        <input type="date" wire:model.live="startDate"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#7c2d2d]/30 focus:border-[#7c2d2d]">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-gray-600">Sampai Tanggal</label>
                        <input type="date" wire:model.live="endDate"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#7c2d2d]/30 focus:border-[#7c2d2d]">
                    </div>
                </div>

                {{-- Surveyor --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-600">Pilih Surveyor</label>
                    <div class="flex gap-2">
                        @foreach(['VIANA', 'Manual', 'Semua'] as $opt)
                        <button class="flex-1 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600
                            hover:bg-[#7c2d2d] hover:text-white hover:border-[#7c2d2d] transition-colors">
                            {{ $opt }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Waktu Interval --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-600">Pilih Waktu Interval</label>
                    <div class="flex gap-2">
                        @foreach(['5 menit', '10 menit', '15 menit', 'Jam', 'Semua'] as $opt)
                        <button class="flex-1 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600
                            hover:bg-[#7c2d2d] hover:text-white hover:border-[#7c2d2d] transition-colors">
                            {{ $opt }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Pendekat Simpang --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-600">Pilih Pendekat Simpang</label>
                    <div class="flex gap-2">
                        @foreach(['Utara', 'Selatan', 'Timur', 'Barat', 'Semua'] as $opt)
                        <button class="flex-1 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600
                            hover:bg-[#7c2d2d] hover:text-white hover:border-[#7c2d2d] transition-colors">
                            {{ $opt }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Arah Pergerakan --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-600">Pilih Arah Pergerakan</label>
                    <div class="flex gap-2">
                        @foreach(['Belok Kiri', 'Lurus', 'Belok Kanan', 'Semua'] as $opt)
                        <button class="flex-1 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600
                            hover:bg-[#7c2d2d] hover:text-white hover:border-[#7c2d2d] transition-colors">
                            {{ $opt }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Jenis Klasifikasi --}}
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-600">Pilih Jenis Klasifikasi</label>
                    <div class="flex gap-2">
                        @foreach(['PKJI 2023 Luar Kota', 'PKJI 2023 Dalam Kota', 'Tipikal'] as $opt)
                        <button class="flex-1 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600
                            hover:bg-[#7c2d2d] hover:text-white hover:border-[#7c2d2d] transition-colors">
                            {{ $opt }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex gap-3 px-6 py-4 border-t border-gray-100">
                <button wire:click="hide"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button wire:click="downloadData"
                    class="flex-1 py-2.5 rounded-xl bg-[#7c2d2d] text-white text-sm font-semibold hover:bg-[#6b2424] transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Unduh Data
                </button>
            </div>

        </div>
    </div>
    @endif
</div>
