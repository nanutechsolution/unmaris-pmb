<div class="min-h-screen bg-gray-50/50 pb-20 font-sans">
    <!-- Alpine & Header Logic -->
    <div x-data="{
            monitoring: false,
            monitorInterval: null,
            toggleMonitor() {
                this.monitoring = !this.monitoring;
                if (this.monitoring) {
                    this.monitorInterval = setInterval(() => { $wire.$refresh() }, 5000);
                } else {
                    clearInterval(this.monitorInterval);
                }
            }
         }"
         x-on:livewire:navigated.window="clearInterval(monitorInterval)">

        <!-- PAGE HEADER -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Pendaftar</h1>
                        <p class="text-sm text-gray-500">Kelola data calon mahasiswa, verifikasi, dan sinkronisasi.</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                         <!-- Live Monitor Button -->
                        <button @click="toggleMonitor()" 
                                :class="monitoring ? 'bg-red-50 text-red-600 ring-red-200' : 'bg-white text-gray-600 ring-gray-300'"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-bold uppercase ring-1 ring-inset transition-all hover:bg-gray-50">
                            <span class="relative flex h-2 w-2">
                                <span x-show="monitoring" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span :class="monitoring ? 'bg-red-500' : 'bg-gray-400'" class="relative inline-flex rounded-full h-2 w-2"></span>
                            </span>
                            <span x-text="monitoring ? 'Live Monitor: ON' : 'Live Monitor: OFF'"></span>
                        </button>

                        <!-- Export -->
                        <a href="{{ route('admin.export') }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-xs font-bold uppercase text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS & CONTENT -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Filter Bar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                <!-- Search -->
                <div class="md:col-span-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" 
                           class="block w-full rounded-lg border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                           placeholder="Cari Nama, NISN...">
                </div>

                <!-- Filters -->
                <div class="md:col-span-8 flex flex-col sm:flex-row gap-2 justify-end">
                    <select wire:model.live="filterStatus" class="rounded-lg border-0 py-2.5 pl-3 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">📂 Semua Status</option>
                        <option value="draft">📝 Draft</option>
                        <option value="submit">📩 Submit</option>
                        <option value="verifikasi">🔍 Verifikasi</option>
                        <option value="lulus">🎉 Lulus</option>
                        <option value="gagal">🚫 Gagal</option>
                        <option value="perbaikan">⚠️ Perbaikan</option>
                    </select>

                    <select wire:model.live="filterPembayaran" class="rounded-lg border-0 py-2.5 pl-3 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">💰 Pembayaran</option>
                        <option value="belum_bayar">❌ Belum Bayar</option>
                        <option value="menunggu_verifikasi">⏳ Cek Bukti</option>
                        <option value="lunas">✅ Lunas</option>
                        <option value="ditolak">🚫 Ditolak</option>
                    </select>

                    <select wire:model.live="filterSync" class="rounded-lg border-0 py-2.5 pl-3 pr-8 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">🔄 Siakad</option>
                        <option value="0">Belum Sync</option>
                        <option value="1">Sudah Sync</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-10">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 h-4 w-4">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Calon Mahasiswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prodi Pilihan</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($pendaftars as $p)
                            <tr class="hover:bg-gray-50 transition {{ in_array($p->id, $selected) ? 'bg-indigo-50/50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 h-4 w-4">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm border border-indigo-200">
                                                {{ substr($p->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $p->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $p->jalur_pendaftaran }} • NISN: {{ $p->nisn ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            <span class="w-4 h-4 flex items-center justify-center bg-gray-600 text-white rounded-full text-[9px]">1</span>
                                            {{ $p->pilihan_prodi_1 }}
                                        </span>
                                        @if($p->pilihan_prodi_2)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium text-gray-500">
                                            <span class="w-4 h-4 flex items-center justify-center bg-gray-300 text-white rounded-full text-[9px]">2</span>
                                            {{ $p->pilihan_prodi_2 }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col gap-1.5 items-center">
                                        <!-- Registration Status -->
                                        @php
                                            $regColors = [
                                                'draft' => 'bg-gray-100 text-gray-600',
                                                'submit' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
                                                'verifikasi' => 'bg-yellow-50 text-yellow-800 ring-1 ring-inset ring-yellow-600/20',
                                                'lulus' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                                'gagal' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10',
                                                'perbaikan' => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/10',
                                            ];
                                            $regClass = $regColors[$p->status_pendaftaran] ?? $regColors['draft'];
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $regClass }}">
                                            {{ strtoupper($p->status_pendaftaran) }}
                                        </span>

                                        <!-- Payment Status -->
                                        @if($p->status_pembayaran == 'lunas')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Lunas
                                            </span>
                                        @elseif($p->status_pembayaran == 'menunggu_verifikasi')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-yellow-600 animate-pulse">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Cek Bayar
                                            </span>
                                        @endif

                                        <!-- Sync Status -->
                                        @if($p->is_synced)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600" title="Tersinkron ke SIAKAD">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Synced
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        <p class="text-sm font-semibold">Tidak ada data ditemukan</p>
                                        <p class="text-xs">Coba sesuaikan filter pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $pendaftars->links() }}
                </div>
            </div>

            <!-- BULK ACTION BAR (Floating) -->
            <div x-show="$wire.selected.length > 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 opacity-100"
                 x-transition:leave-end="translate-y-full opacity-0"
                 class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-3xl px-4"
                 style="display: none;">
                
                <div class="bg-gray-900 text-white rounded-full shadow-2xl px-6 py-3 flex items-center justify-between gap-6 border border-gray-700/50 backdrop-blur-xl bg-opacity-95">
                    <div class="flex items-center gap-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500 text-xs font-bold text-white" x-text="$wire.selected.length"></span>
                        <span class="text-sm font-medium">Item Terpilih</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button wire:click="resetSelection" class="px-3 py-1.5 text-xs font-medium text-gray-300 hover:text-white transition">
                            Batal
                        </button>
                        <div class="h-4 w-px bg-gray-700 mx-1"></div>
                        <button wire:click="syncToSiakadBulk" 
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="syncToSiakadBulk">🚀 Kirim ke SIAKAD</span>
                            <span wire:loading wire:target="syncToSiakadBulk">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>