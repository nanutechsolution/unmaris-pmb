<div class="space-y-4 sm:space-y-6 pb-24" 
     x-data="{ 
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
    
    <div class="flex flex-col lg:flex-row justify-between gap-4 bg-white p-4 rounded-xl border-2 border-unmaris-blue shadow-sm">
        
        <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-1/2">
            <div class="w-full relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-unmaris-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NISN..." 
                       class="w-full pl-10 border-2 border-gray-300 rounded-lg py-2 font-bold text-gray-700 focus:outline-none focus:border-unmaris-blue focus:ring-1 focus:ring-unmaris-blue transition-all text-sm">
            </div>

            <div class="flex gap-2 shrink-0">
                <button @click="toggleMonitor()" 
                        :class="monitoring ? 'bg-red-100 text-red-600 border-red-200' : 'bg-gray-100 text-gray-500 border-gray-200'"
                        class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-3 py-2 rounded-lg border-2 font-bold text-xs whitespace-nowrap transition-all">
                    <span class="relative flex h-2 w-2">
                        <span x-show="monitoring" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span :class="monitoring ? 'bg-red-500' : 'bg-gray-400'" class="relative inline-flex rounded-full h-2 w-2"></span>
                    </span>
                    <span x-text="monitoring ? 'LIVE' : 'OFF'"></span>
                </button>

                <a href="{{ route('admin.export') }}" target="_blank" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-3 py-2 rounded-lg border-2 border-green-600 bg-green-100 text-green-700 font-bold text-xs whitespace-nowrap hover:bg-green-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    XLSX
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:flex sm:flex-wrap lg:justify-end gap-2 w-full lg:w-1/2">
            <div class="col-span-2 sm:col-span-1 sm:w-auto">
                <select wire:model.live="filterSync" class="w-full border-2 border-indigo-200 bg-indigo-50 rounded-lg py-2 px-3 font-bold text-indigo-700 text-xs sm:text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">🔄 Status SIAKAD</option>
                    <option value="0">⏳ Belum Sync</option>
                    <option value="1">✅ Sudah Sync</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <select wire:model.live="filterStatus" class="w-full border-2 border-gray-300 rounded-lg py-2 px-3 font-bold text-gray-600 text-xs sm:text-sm focus:outline-none focus:border-unmaris-blue">
                    <option value="">📂 Status Daftar</option>
                    <option value="lulus">✅ Hanya Lulus</option>
                    <option value="verifikasi">🔍 Verifikasi</option>
                    <option value="draft">📝 Draft</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <select wire:model.live="filterPembayaran" class="w-full border-2 border-unmaris-blue bg-blue-50 rounded-lg py-2 px-3 font-bold text-unmaris-blue text-xs sm:text-sm focus:outline-none">
                    <option value="">💰 Pembayaran</option>
                    <option value="menunggu_verifikasi">⏳ Butuh Cek</option>
                    <option value="lunas">✅ Lunas</option>
                </select>
            </div>
        </div>
    </div>

    <div class="space-y-2">
        @if (session()->has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm font-bold animate-fade-in-down shadow-sm">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm font-bold animate-fade-in-down shadow-sm">{{ session('error') }}</div>
        @endif
    </div>

    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden relative min-h-[300px]">
        
        <div wire:loading.flex wire:target="search, filterStatus, filterPembayaran, filterSync, previousPage, nextPage, gotoPage, deleteBulk, syncToSiakadBulk" 
             class="absolute inset-0 bg-white/80 z-20 flex-col items-center justify-center backdrop-blur-[2px]">
            <div class="animate-spin rounded-full h-10 w-10 border-b-4 border-unmaris-blue mb-3"></div>
            <span class="font-black text-unmaris-blue animate-pulse text-sm">MEMPROSES DATA...</span>
        </div>

        <div class="block md:hidden bg-gray-50 p-4 space-y-4">
            @forelse($pendaftars as $p)
                <div class="bg-white p-4 rounded-lg border-2 {{ in_array($p->id, $selected) ? 'border-unmaris-blue bg-blue-50' : 'border-gray-200' }} shadow-sm relative">
                    <div class="absolute top-4 right-4">
                         <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-6 h-6 text-unmaris-blue border-2 border-gray-300 rounded focus:ring-0">
                    </div>

                    <div class="pr-8"> <div class="font-black text-unmaris-blue text-lg">{{ $p->user->name }}</div>
                        <div class="text-xs font-bold text-gray-500 uppercase mt-1">
                            {{ $p->jalur_pendaftaran }} • {{ $p->nisn ?? '-' }}
                        </div>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($p->status_pendaftaran == 'lulus')
                            <span class="px-2 py-1 rounded text-[10px] font-black bg-green-100 text-green-800 border border-green-300">LULUS</span>
                        @elseif($p->status_pendaftaran == 'verifikasi')
                            <span class="px-2 py-1 rounded text-[10px] font-black bg-yellow-50 text-yellow-600 border border-yellow-200">VERIFIKASI</span>
                        @else
                            <span class="px-2 py-1 rounded text-[10px] font-black bg-gray-100 text-gray-600 border border-gray-300">{{ strtoupper($p->status_pendaftaran) }}</span>
                        @endif

                        @if($p->status_pembayaran == 'lunas')
                            <span class="px-2 py-1 rounded text-[10px] font-black bg-green-100 text-green-700 border border-green-300">💰 LUNAS</span>
                        @elseif($p->status_pembayaran == 'menunggu_verifikasi')
                            <span class="px-2 py-1 rounded text-[10px] font-black bg-yellow-100 text-yellow-700 border border-yellow-300 animate-pulse">⏳ CEK BAYAR</span>
                        @endif

                        @if($p->is_synced)
                            <span class="px-2 py-1 rounded text-[10px] font-black bg-indigo-50 text-indigo-600 border border-indigo-200">✅ SYNCED</span>
                        @endif
                    </div>

                    <div class="mt-3 pt-3 border-t border-dashed border-gray-200 text-sm text-gray-700">
                        <div class="font-bold flex items-center mb-1"><span class="w-4 h-4 rounded-full bg-unmaris-blue text-white text-[10px] flex items-center justify-center mr-2">1</span> {{ $p->pilihan_prodi_1 }}</div>
                        @if($p->pilihan_prodi_2)
                        <div class="text-xs text-gray-500 flex items-center"><span class="w-4 h-4 rounded-full bg-gray-400 text-white text-[10px] flex items-center justify-center mr-2">2</span> {{ $p->pilihan_prodi_2 }}</div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="block w-full text-center bg-white text-unmaris-blue border-2 border-unmaris-blue px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-unmaris-blue hover:text-white transition-colors">
                            Lihat Detail 🔎
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 font-bold italic">
                    🍃 Tidak ada data ditemukan.
                </div>
            @endforelse
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-unmaris-blue text-white">
                    <tr>
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-unmaris-blue border-2 border-white rounded focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Identitas</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Prodi</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm text-center">Status</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($pendaftars as $p)
                        <tr class="hover:bg-yellow-50 transition {{ in_array($p->id, $selected) ? 'bg-blue-50' : '' }}">
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-unmaris-blue border-2 border-gray-300 rounded focus:ring-0 cursor-pointer">
                            </td>
                            <td class="p-4 align-top">
                                <div class="font-black text-unmaris-blue text-lg">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wide mt-1 mb-2">
                                    {{ $p->jalur_pendaftaran }} • {{ $p->nisn ?? '-' }}
                                </div>
                                @if($p->status_pembayaran == 'lunas')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-green-100 text-green-700 border border-green-300">✅ LUNAS</span>
                                @elseif($p->status_pembayaran == 'menunggu_verifikasi')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-yellow-100 text-yellow-700 border border-yellow-300 animate-pulse">⏳ CEK BAYAR</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm font-bold text-gray-700 align-top">
                                <div class="mb-1 flex items-center">
                                    <span class="text-xs bg-unmaris-blue text-white px-1.5 rounded mr-2">1</span> {{ $p->pilihan_prodi_1 }}
                                </div>
                                @if($p->pilihan_prodi_2)
                                <div class="text-xs text-gray-500 flex items-center">
                                    <span class="text-[10px] bg-gray-400 text-white px-1.5 rounded mr-2">2</span> {{ $p->pilihan_prodi_2 }}
                                </div>
                                @endif
                            </td>
                            <td class="p-4 text-center align-top">
                                @if($p->status_pendaftaran == 'lulus')
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-black border-2 bg-green-100 text-green-800 border-green-500">LULUS</span>
                                    @if($p->is_synced)
                                        <div class="mt-1 text-[10px] font-bold text-indigo-600 bg-indigo-50 px-1 rounded border border-indigo-200">✅ SIAKAD SYNCED</div>
                                    @else
                                        <div class="mt-1 text-[10px] font-bold text-gray-400">⏳ Belum Sync</div>
                                    @endif
                                @elseif($p->status_pendaftaran == 'verifikasi')
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-black border-2 bg-yellow-50 text-yellow-600 border-yellow-200">VERIFIKASI</span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-black border-2 bg-gray-100 text-gray-600 border-gray-400">{{ strtoupper($p->status_pendaftaran) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right align-top">
                                <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="inline-flex items-center bg-white text-unmaris-blue border-2 border-unmaris-blue px-3 py-1 rounded-lg font-bold shadow-sm hover:bg-unmaris-yellow transition-all text-xs">
                                    Detail 🔎
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 font-bold italic">
                                🍃 Belum ada data pendaftar yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t-4 border-unmaris-blue bg-gray-50">
            {{ $pendaftars->links() }}
        </div>
    </div>

    <div class="fixed bottom-4 left-4 right-4 md:left-1/2 md:right-auto md:transform md:-translate-x-1/2 md:w-11/12 md:max-w-4xl z-50 transition-all duration-300 {{ count($selected) > 0 ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0 pointer-events-none' }}">
        <div class="bg-indigo-900 text-white p-3 md:p-4 rounded-xl border-4 border-white shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-3">
            
            <div class="flex items-center justify-between w-full sm:w-auto gap-3">
                <span class="bg-yellow-400 text-black font-black px-3 py-1 rounded border shadow-sm text-sm">
                    {{ count($selected) }} Item
                </span>
                <span class="font-bold text-sm sm:hidden text-indigo-200">Dipilih</span>
                <span class="font-bold text-sm hidden sm:block">Pilih Aksi Massal:</span>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button wire:click="deleteBulk" 
                        wire:confirm="Hapus data terpilih?"
                        wire:loading.attr="disabled" 
                        class="flex-1 sm:flex-none justify-center bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2 rounded-lg border-2 border-white/50 shadow-sm text-xs sm:text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Hapus</span>
                </button>

                <button wire:click="syncToSiakadBulk" wire:loading.attr="disabled" class="flex-[2] sm:flex-none justify-center bg-green-500 hover:bg-green-600 text-white font-black px-4 sm:px-6 py-2 rounded-lg border-2 border-white shadow-lg uppercase text-xs sm:text-sm flex items-center gap-2">
                    <span wire:loading.remove>KIRIM SIAKAD</span>
                    <span wire:loading>PROSES...</span>
                </button>
            </div>
        </div>
    </div>
</div>