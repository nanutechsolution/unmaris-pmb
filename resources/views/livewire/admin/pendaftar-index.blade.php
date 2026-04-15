<div class="min-h-screen bg-gray-50/50 pb-20 font-sans relative">
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
                        <p class="text-sm text-gray-500">Kelola data calon mahasiswa, verifikasi, sinkronisasi, dan penghapusan.</p>
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
            
            <!-- Notifikasi -->
            @if (session()->has('success'))
                <div class="bg-green-50 px-4 py-3 rounded-xl border border-green-200 text-sm font-bold text-green-700 mb-4 shadow-sm flex items-start gap-3">
                    <span class="text-lg">✅</span> <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-50 px-4 py-3 rounded-xl border border-red-200 text-sm font-bold text-red-700 mb-4 shadow-sm flex items-start gap-3 whitespace-pre-line">
                    <span class="text-lg">⚠️</span> <p>{{ session('error') }}</p>
                </div>
            @endif

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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
                
                <!-- Loading Overlay Table -->
                <div wire:loading class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div class="bg-white p-3 rounded-lg shadow-lg flex items-center gap-3 font-bold text-indigo-600 text-sm">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memuat...
                    </div>
                </div>

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
                                            @if($p->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($p->foto_path))
                                                <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ asset('storage/'.$p->foto_path) }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm border border-indigo-200">
                                                    {{ substr($p->user->name, 0, 1) }}
                                                </div>
                                            @endif
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
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold {{ $regClass }}">
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
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline flex items-center gap-1">
                                            Detail &rarr;
                                        </a>
                                        
                                        <!-- TOMBOL CETAK SATUAN (BARU) -->
                                        <a href="{{ route('admin.pendaftar.cetak', $p->id) }}"class="text-gray-500 hover:text-indigo-600 transition p-1 bg-white hover:bg-indigo-50 rounded-md border border-transparent hover:border-indigo-200" title="Cetak Formulir">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        </a>

                                        <!-- TOMBOL HAPUS SATUAN HANYA UNTUK ADMIN -->
                                        @if(auth()->user()->role === 'admin')
                                        <button wire:click="deletePendaftar({{ $p->id }})" 
                                                wire:confirm="PERINGATAN!\n\nApakah Anda yakin ingin MENGHAPUS PERMANEN data mahasiswa ({{ $p->user->name }}) beserta akun dan semua file uploadnya?\n\nTindakan ini tidak bisa dibatalkan!"
                                                class="text-gray-400 hover:text-red-600 transition p-1 bg-white hover:bg-red-50 rounded-md border border-transparent hover:border-red-200" title="Hapus Permanen">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                        @endif
                                    </div>
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
                 style="display: none;" x-cloak>
                
                <div class="bg-gray-900 text-white rounded-full shadow-2xl px-6 py-3 flex items-center justify-between gap-6 border border-gray-700/50 backdrop-blur-xl bg-opacity-95 flex-col sm:flex-row">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500 text-xs font-bold text-white shrink-0" x-text="$wire.selected.length"></span>
                        <span class="text-sm font-medium">Data Terpilih</span>
                        <div class="flex-1 sm:hidden"></div>
                        <button wire:click="resetSelection" class="sm:hidden px-3 py-1.5 text-xs font-bold text-gray-400 hover:text-white transition">Batal</button>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
                        <button wire:click="resetSelection" class="hidden sm:block px-3 py-1.5 text-xs font-medium text-gray-300 hover:text-white transition">
                            Batal
                        </button>
                        
                        <div class="hidden sm:block h-4 w-px bg-gray-700 mx-1"></div>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <!-- TOMBOL CETAK MASSAL (BARU) -->
                            <button wire:click="redirectCetakMassal" 
                                    wire:loading.attr="disabled"
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 rounded-full bg-white/10 text-white border border-white/20 px-4 py-1.5 text-xs font-bold hover:bg-white hover:text-gray-900 transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="redirectCetakMassal">🖨️ Cetak Massal</span>
                                <span wire:loading wire:target="redirectCetakMassal">Memproses...</span>
                            </button>

                            <!-- TOMBOL BULK HAPUS HANYA UNTUK ADMIN -->
                            @if(auth()->user()->role === 'admin')
                            <button wire:click="bulkDelete" 
                                    wire:confirm="PERINGATAN KRITIS!\n\nAnda yakin ingin MENGHAPUS SEMUA data yang dipilih?\nProses ini menghapus akun login dan SEMUA BERKAS mereka dari server.\n\nTindakan ini bersifat PERMANEN."
                                    wire:loading.attr="disabled"
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 rounded-full bg-red-600/20 text-red-400 border border-red-500/30 px-4 py-1.5 text-xs font-bold hover:bg-red-600 hover:text-white transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="bulkDelete">🗑️ Hapus Massal</span>
                                <span wire:loading wire:target="bulkDelete">Menghapus...</span>
                            </button>
                            @endif

                            <button wire:click="syncToSiakadBulk" 
                                    wire:loading.attr="disabled"
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 rounded-full bg-indigo-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition disabled:opacity-50">
                                <span wire:loading.remove wire:target="syncToSiakadBulk">🚀 Push SIAKAD</span>
                                <span wire:loading wire:target="syncToSiakadBulk">Memproses...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>