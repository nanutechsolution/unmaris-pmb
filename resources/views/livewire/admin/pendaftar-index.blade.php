<div class="space-y-6 pb-24 md:pb-10 font-sans" 
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
    
    <!-- HEADER & CONTROLS -->
    <div class="flex flex-col gap-5 bg-white p-5 rounded-3xl shadow-neo border-4 border-black relative overflow-hidden">
        
        <!-- Top Row: Title & Monitor -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 z-10 border-b-2 border-dashed border-gray-200 pb-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-unmaris-blue text-white flex items-center justify-center rounded-2xl border-4 border-black shadow-sm text-2xl relative">
                    📂
                    <!-- Notification Dot (Visual Only) -->
                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                </div>
                <div>
                    <h2 class="font-black text-2xl uppercase tracking-tighter text-black">
                        Data Pendaftar
                    </h2>
                    <p class="text-gray-500 text-xs font-bold mt-0.5">
                        Warna <span class="bg-orange-100 text-orange-800 px-1 border border-orange-300 rounded">KUNING</span> menandakan data butuh verifikasi/cek bayar.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 w-full lg:w-auto">
                <!-- LIVE MONITOR BUTTON -->
                <button @click="toggleMonitor()" 
                        :class="monitoring ? 'bg-red-500 text-white border-red-700 animate-pulse' : 'bg-gray-100 text-gray-500 border-gray-300'"
                        class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-4 font-black text-xs uppercase transition-all shadow-sm">
                    <span class="relative flex h-3 w-3">
                        <span x-show="monitoring" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span :class="monitoring ? 'bg-white' : 'bg-gray-400'" class="relative inline-flex rounded-full h-3 w-3"></span>
                    </span>
                    <span x-text="monitoring ? 'LIVE MONITOR ON' : 'MONITOR OFF'"></span>
                </button>

                <!-- EXPORT BUTTON -->
                <a href="{{ route('admin.export') }}" target="_blank" class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-4 border-green-600 bg-green-500 text-white font-black text-xs uppercase hover:bg-green-600 shadow-neo-sm active:translate-y-1 active:shadow-none transition-all">
                    <span>📊 Export Excel</span>
                </a>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 z-10">
            <!-- Search -->
            <div class="md:col-span-12 lg:col-span-4 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400 text-lg group-focus-within:text-black transition-colors">🔍</span>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NISN..." 
                       class="w-full bg-white border-4 border-black rounded-xl pl-12 pr-4 py-3 font-bold focus:shadow-neo focus:bg-yellow-50 transition-all outline-none text-sm placeholder-gray-300">
            </div>
            
            <!-- Filter Dropdowns -->
            <div class="md:col-span-12 lg:col-span-8 flex flex-col sm:flex-row gap-2 overflow-x-auto">
                <select wire:model.live="filterStatus" class="flex-1 border-4 border-black rounded-xl py-3 px-3 font-bold text-xs uppercase bg-white focus:shadow-neo outline-none cursor-pointer">
                    <option value="">📂 Semua Status Daftar</option>
                    <option value="lulus">🎉 Lulus Seleksi</option>
                    <option value="verifikasi">🔍 Perlu Verifikasi</option>
                    <option value="draft">📝 Masih Draft</option>
                </select>

                <select wire:model.live="filterPembayaran" class="flex-1 border-4 border-black rounded-xl py-3 px-3 font-bold text-xs uppercase bg-white focus:shadow-neo outline-none cursor-pointer">
                    <option value="">💰 Semua Pembayaran</option>
                    <option value="menunggu_verifikasi">⏳ Cek Bukti Bayar</option>
                    <option value="lunas">✅ Lunas</option>
                </select>

                <select wire:model.live="filterSync" class="flex-1 border-4 border-black rounded-xl py-3 px-3 font-bold text-xs uppercase bg-white focus:shadow-neo outline-none cursor-pointer">
                    <option value="">🔄 Status Siakad</option>
                    <option value="0">⏳ Belum Sync</option>
                    <option value="1">✅ Sudah Sync</option>
                </select>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    <div class="space-y-2">
        @if (session()->has('success'))
            <div class="bg-green-100 border-l-8 border-green-500 bg-white p-4 font-bold shadow-sm rounded-r-xl flex items-center gap-3 animate-fade-in-down">
                <span class="text-2xl">✅</span> {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border-l-8 border-red-500 bg-white p-4 font-bold shadow-sm rounded-r-xl flex items-center gap-3 animate-fade-in-down">
                <span class="text-2xl">🚫</span> <span class="whitespace-pre-line">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- DATA CONTENT -->
    <div class="bg-white border-4 border-black shadow-neo rounded-3xl overflow-hidden relative min-h-[300px]">
        
        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search, filterStatus, filterPembayaran, filterSync, previousPage, nextPage, gotoPage, deleteBulk, syncToSiakadBulk" 
             class="absolute inset-0 bg-white/80 z-50 flex-col items-center justify-center backdrop-blur-[2px]">
            <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-unmaris-blue mb-3"></div>
            <span class="font-black text-unmaris-blue animate-pulse text-sm uppercase tracking-widest">Memproses Data...</span>
        </div>

        <!-- MOBILE VIEW (CARDS) -->
        <div class="block md:hidden bg-gray-50 p-4 space-y-4">
            @forelse($pendaftars as $p)
                @php
                    $isUrgent = $p->status_pendaftaran == 'verifikasi' || $p->status_pembayaran == 'menunggu_verifikasi';
                    $cardClass = $isUrgent ? 'border-orange-500 bg-orange-50 ring-4 ring-orange-100' : 'border-black bg-white';
                    if (in_array($p->id, $selected)) $cardClass = 'border-unmaris-blue bg-blue-50';
                @endphp

                <div class="border-4 {{ $cardClass }} rounded-2xl p-5 shadow-sm relative overflow-hidden group transition-all">
                    
                    @if($isUrgent)
                        <div class="absolute top-0 right-0 bg-orange-500 text-white text-[9px] font-black px-3 py-1 rounded-bl-xl uppercase tracking-wider animate-pulse">
                            ⚠️ Butuh Proses
                        </div>
                    @endif

                    <!-- Checkbox Absolute -->
                    <div class="absolute top-0 left-0 p-0 z-20">
                        <label class="flex items-center justify-center w-10 h-10 border-r-2 border-b-2 cursor-pointer rounded-br-xl hover:bg-yellow-200 {{ $isUrgent ? 'bg-orange-100 border-orange-300' : 'bg-gray-100 border-gray-300' }}">
                             <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-unmaris-blue border-2 border-gray-400 rounded focus:ring-0">
                        </label>
                    </div>

                    <!-- Status Badges Row -->
                    <div class="flex justify-end gap-1 mb-3 pl-10 pt-4 flex-wrap">
                        <!-- Status Daftar -->
                        @php
                            $statusClass = match($p->status_pendaftaran) {
                                'lulus' => 'bg-green-100 text-green-800 border-green-500',
                                'verifikasi' => 'bg-orange-100 text-orange-800 border-orange-500 font-extrabold',
                                'draft' => 'bg-gray-100 text-gray-600 border-gray-400',
                                default => 'bg-gray-100 text-gray-600 border-gray-400'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-md text-[9px] font-black border uppercase {{ $statusClass }}">
                            {{ $p->status_pendaftaran }}
                        </span>

                        <!-- Status Bayar -->
                        @if($p->status_pembayaran == 'lunas')
                            <span class="px-2 py-1 rounded-md text-[9px] font-black border bg-blue-100 text-blue-800 border-blue-500 uppercase">Lunas</span>
                        @elseif($p->status_pembayaran == 'menunggu_verifikasi')
                             <span class="px-2 py-1 rounded-md text-[9px] font-black border bg-red-100 text-red-800 border-red-500 uppercase animate-pulse">Cek Bayar</span>
                        @endif

                        <!-- Sync Status -->
                        @if($p->is_synced)
                             <span class="px-2 py-1 rounded-md text-[9px] font-black border bg-indigo-100 text-indigo-800 border-indigo-500 uppercase">Synced</span>
                        @endif
                    </div>

                    <div class="pl-2">
                        <h3 class="font-black text-lg text-gray-900 leading-tight">{{ $p->user->name }}</h3>
                        <p class="text-xs font-bold text-gray-500 uppercase mt-1 tracking-wide">
                            {{ $p->jalur_pendaftaran }} • NISN: {{ $p->nisn ?? '-' }}
                        </p>
                        
                        <div class="mt-3 pt-3 border-t-2 border-dashed border-gray-300 text-xs font-medium text-gray-600 space-y-1">
                            <div class="flex items-start gap-2">
                                <span class="bg-black text-white px-1.5 rounded text-[10px] font-bold mt-0.5">1</span>
                                <span>{{ $p->pilihan_prodi_1 }}</span>
                            </div>
                            @if($p->pilihan_prodi_2)
                            <div class="flex items-start gap-2 opacity-75">
                                <span class="bg-gray-400 text-white px-1.5 rounded text-[10px] font-bold mt-0.5">2</span>
                                <span>{{ $p->pilihan_prodi_2 }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t-2 {{ $isUrgent ? 'border-orange-300' : 'border-black' }}">
                        <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="block w-full text-center {{ $isUrgent ? 'bg-orange-500 hover:bg-orange-600' : 'bg-unmaris-blue hover:bg-blue-900' }} text-white py-2 rounded-xl font-black text-xs uppercase shadow-neo-sm hover:shadow-none active:translate-y-1 transition-all">
                            {{ $isUrgent ? '⚠️ Segera Proses Data' : 'Lihat Detail & Proses 🔎' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <span class="text-4xl block mb-2">🍃</span>
                    <p class="font-bold text-gray-400">Tidak ada data ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW (TABLE) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-100 border-b-4 border-black">
                    <tr>
                        <th class="p-4 w-14 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-black border-2 border-black rounded focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-4 font-black uppercase tracking-wider text-xs text-gray-600">Identitas Calon MHS</th>
                        <th class="p-4 font-black uppercase tracking-wider text-xs text-gray-600">Pilihan Prodi</th>
                        <th class="p-4 font-black uppercase tracking-wider text-xs text-center text-gray-600">Status</th>
                        <th class="p-4 font-black uppercase tracking-wider text-xs text-right text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($pendaftars as $p)
                        @php
                            $isUrgentRow = $p->status_pendaftaran == 'verifikasi' || $p->status_pembayaran == 'menunggu_verifikasi';
                            $rowClass = $isUrgentRow ? 'bg-orange-50 hover:bg-orange-100 border-l-4 border-orange-500' : 'hover:bg-yellow-50';
                            if (in_array($p->id, $selected)) $rowClass = 'bg-blue-50';
                        @endphp
                        
                        <tr class="transition group {{ $rowClass }}">
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-black border-2 border-gray-300 rounded focus:ring-0 cursor-pointer">
                            </td>
                            <td class="p-4 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $isUrgentRow ? 'bg-orange-500' : 'bg-unmaris-blue' }} text-white flex items-center justify-center font-bold text-sm border-2 border-black shadow-sm">
                                        {{ substr($p->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 text-sm">{{ $p->user->name }}</div>
                                        <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mt-0.5">
                                            {{ $p->jalur_pendaftaran }} • NISN: {{ $p->nisn ?? '-' }}
                                        </div>
                                        @if($isUrgentRow)
                                            <div class="text-[9px] font-black text-orange-600 uppercase mt-1 animate-pulse">⚠️ Perlu Tindakan</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black bg-black text-white px-1.5 py-0.5 rounded">1</span>
                                        <span class="text-xs font-bold text-gray-700">{{ $p->pilihan_prodi_1 }}</span>
                                    </div>
                                    @if($p->pilihan_prodi_2)
                                    <div class="flex items-center gap-2 opacity-60">
                                        <span class="text-[10px] font-black bg-gray-400 text-white px-1.5 py-0.5 rounded">2</span>
                                        <span class="text-xs font-bold text-gray-700">{{ $p->pilihan_prodi_2 }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="flex flex-col gap-1 items-center">
                                    <!-- Status Daftar -->
                                    @php
                                        $statusStyle = match($p->status_pendaftaran) {
                                            'lulus' => 'bg-green-100 text-green-800 border-green-400',
                                            'verifikasi' => 'bg-orange-100 text-orange-800 border-orange-400 font-bold',
                                            default => 'bg-gray-100 text-gray-600 border-gray-300'
                                        };
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black border uppercase {{ $statusStyle }}">
                                        {{ $p->status_pendaftaran }}
                                    </span>

                                    <!-- Status Bayar -->
                                    @if($p->status_pembayaran == 'lunas')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black border bg-blue-100 text-blue-800 border-blue-400 uppercase">💰 Lunas</span>
                                    @elseif($p->status_pembayaran == 'menunggu_verifikasi')
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black border bg-red-100 text-red-800 border-red-400 uppercase animate-pulse">⏳ Cek Bayar</span>
                                    @endif

                                    <!-- Sync -->
                                    @if($p->is_synced)
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black border bg-indigo-100 text-indigo-800 border-indigo-400 uppercase" title="Tersinkron dengan SIAKAD">🔄 Synced</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 align-middle text-right">
                                <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="inline-flex items-center {{ $isUrgentRow ? 'bg-orange-500 text-white border-orange-700 hover:bg-orange-600' : 'bg-white text-black border-black hover:bg-yellow-400' }} border-2 px-4 py-1.5 rounded-lg font-black shadow-sm transition-all text-xs uppercase transform hover:-translate-y-0.5">
                                    {{ $isUrgentRow ? 'Proses ⚡' : 'Detail 🔎' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 font-bold italic bg-gray-50">
                                <div class="flex flex-col items-center justify-center opacity-50">
                                    <span class="text-4xl mb-2">🍃</span>
                                    <span>Belum ada data pendaftar yang sesuai filter.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t-4 border-black bg-gray-50">
            {{ $pendaftars->links() }}
        </div>
    </div>

    <!-- FLOATING BULK ACTIONS BAR -->
    <div class="fixed bottom-4 left-4 right-4 md:left-1/2 md:right-auto md:transform md:-translate-x-1/2 md:w-11/12 md:max-w-4xl z-[90] transition-all duration-300 {{ count($selected) > 0 ? 'translate-y-0 opacity-100' : 'translate-y-32 opacity-0 pointer-events-none' }}">
        <div class="bg-indigo-900 text-white p-3 md:p-4 rounded-2xl border-4 border-white shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-3 relative">
            
            <!-- Badge Count -->
            <div class="absolute -top-3 -left-3 bg-yellow-400 text-black font-black w-8 h-8 flex items-center justify-center rounded-full border-2 border-black shadow-sm z-10 animate-bounce">
                {{ count($selected) }}
            </div>

            <div class="flex items-center gap-3 pl-4">
                <span class="font-bold text-sm hidden sm:block text-indigo-100">Item Terpilih:</span>
                <span class="font-black text-sm text-yellow-300 sm:hidden"> {{ count($selected) }} Data Dipilih</span>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button wire:click="deleteBulk" 
                        wire:confirm="Yakin ingin menghapus data yang dipilih? Data yang sudah LULUS/LUNAS sebaiknya jangan dihapus."
                        wire:loading.attr="disabled" 
                        class="flex-1 sm:flex-none justify-center bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2.5 rounded-xl border-2 border-white/20 shadow-sm text-xs uppercase tracking-wide flex items-center gap-2 transition-transform active:scale-95">
                    <span>🗑️ Hapus</span>
                </button>

                <button wire:click="syncToSiakadBulk" wire:loading.attr="disabled" class="flex-[2] sm:flex-none justify-center bg-green-500 hover:bg-green-600 text-white font-black px-6 py-2.5 rounded-xl border-2 border-white shadow-lg uppercase text-xs tracking-wide flex items-center gap-2 transition-transform active:scale-95 group">
                    <span wire:loading.remove class="group-hover:animate-pulse">🚀 Kirim ke Siakad</span>
                    <span wire:loading>⏳ Memproses...</span>
                </button>
            </div>
        </div>
    </div>

<style>
    /* Neo-Brutalism Utilities */
    .shadow-neo { box-shadow: 8px 8px 0px 0px rgba(0,0,0,1); }
    .shadow-neo-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
    .animate-fade-in-down { animation: fade-in-down 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    
    @keyframes fade-in-down {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

</div>
