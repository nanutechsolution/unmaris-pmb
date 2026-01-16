<div class="space-y-6 pb-32"> 
    
    <!-- HEADER, FILTER & SEARCH -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black gap-4">
        
        <!-- Judul & Filter (Sisi Kiri) -->
        <div class="flex items-center gap-4 w-full md:w-auto">
            <h2 class="text-white font-black text-xl uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                <span>🎯</span> Ujian Tulis
            </h2>
            
            <!-- SMART FILTER: Admin kerja lebih fokus -->
            <select wire:model.live="filterStatus" class="bg-white text-unmaris-blue font-black text-sm rounded-lg border-2 border-black focus:shadow-neo transition-all py-2 px-3 cursor-pointer outline-none">
                <option value="belum_jadwal">📅 Belum Dijadwalkan</option>
                <option value="sudah_jadwal">⏳ Menunggu Nilai</option>
                <option value="sudah_nilai">✅ Selesai Dinilai</option>
                <option value="">📂 Semua Data</option>
            </select>
        </div>

        <!-- Search (Sisi Kanan) -->
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Peserta..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all text-sm">
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-fade-in-down flex items-center gap-2">
            <span>✅</span> {{ session('message') }}
        </div>
    @endif

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-yellow-400 text-unmaris-blue border-b-4 border-unmaris-blue">
                    <tr>
                        <th class="p-4 w-10 text-center bg-yellow-500">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-4 font-black uppercase text-sm">Peserta & Prodi</th>
                        <th class="p-4 font-black uppercase text-sm">Jadwal Ujian</th>
                        <th class="p-4 font-black uppercase text-center text-sm">Skor</th>
                        <th class="p-4 font-black uppercase text-right text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($peserta as $p)
                        <tr class="hover:bg-blue-50 transition group {{ in_array($p->id, $selected) ? 'bg-blue-100' : '' }}">
                            
                            <!-- CHECKBOX ROW -->
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                            </td>

                            <!-- IDENTITAS -->
                            <td class="p-4 align-top">
                                <div class="font-black text-unmaris-blue text-base">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500 bg-gray-100 inline-block px-1 rounded border border-gray-300">{{ $p->pilihan_prodi_1 }}</div>
                            </td>

                            <!-- JADWAL -->
                            <td class="p-4 align-top">
                                @if($p->jadwal_ujian)
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-1 font-bold text-sm text-unmaris-blue">
                                            📅 {{ \Carbon\Carbon::parse($p->jadwal_ujian)->format('d M / H:i') }}
                                        </span>
                                        <span class="text-xs text-gray-600 flex items-center gap-1">
                                            📍 {{ Str::limit($p->lokasi_ujian, 25) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-red-400 bg-red-50 px-2 py-1 rounded border border-red-200 uppercase tracking-wide">
                                        Belum Ada Jadwal
                                    </span>
                                @endif
                            </td>

                            <!-- NILAI (VISUAL CUES) -->
                            <td class="p-4 text-center">
                                @if($p->nilai_ujian > 0)
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl font-black {{ $p->nilai_ujian >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $p->nilai_ujian }}
                                        </span>
                                        <!-- Indikator Kelulusan Cepat -->
                                        @if($p->nilai_ujian >= 70)
                                            <span class="text-[9px] font-bold bg-green-100 text-green-800 px-1 rounded border border-green-300">PASS</span>
                                        @else
                                            <span class="text-[9px] font-bold bg-red-100 text-red-800 px-1 rounded border border-red-300">LOW</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 font-bold text-xl">-</span>
                                @endif
                            </td>

                            <!-- AKSI -->
                            <td class="p-4 text-right">
                                <button wire:click="edit({{ $p->id }})" class="bg-white text-unmaris-blue hover:bg-unmaris-blue hover:text-white px-4 py-2 rounded-lg font-black border-2 border-unmaris-blue shadow-neo-sm hover:shadow-none transition-all text-xs uppercase">
                                    {{ $p->nilai_ujian > 0 ? '📝 Edit Nilai' : '⚙️ Atur' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <span class="text-4xl mb-2">📂</span>
                                    <p class="font-bold">Tidak ada data peserta di kategori ini.</p>
                                    <p class="text-xs">Coba ganti filter di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">
            {{ $peserta->links() }}
        </div>
    </div>

    <!-- FLOATING ACTION BAR (Hanya muncul jika ada yang dicentang) -->
    <div x-data="{ count: @entangle('selected').live }" 
         x-show="count.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-40 w-11/12 max-w-4xl bg-unmaris-blue text-white p-4 rounded-2xl shadow-2xl border-4 border-black flex flex-col md:flex-row items-center justify-between gap-4">
        
        <div class="flex items-center gap-3">
            <span class="bg-yellow-400 text-black font-black w-8 h-8 flex items-center justify-center rounded-full border-2 border-black" x-text="count.length"></span>
            <div class="leading-tight">
                <span class="font-bold text-sm uppercase block">Peserta Terpilih</span>
                <span class="text-xs text-blue-200">Atur jadwal masal di sini</span>
            </div>
        </div>

        <!-- Form Inline Sederhana -->
        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
            <input type="datetime-local" wire:model="bulk_jadwal_ujian" class="bg-white text-black font-bold rounded border-2 border-black focus:shadow-neo outline-none px-2 py-2 text-sm">
            <input type="text" wire:model="bulk_lokasi_ujian" placeholder="Contoh: Gedung A, R.101" class="bg-white text-black font-bold rounded border-2 border-black focus:shadow-neo outline-none px-2 py-2 text-sm w-full md:w-64">
        </div>

        <button wire:click="applyBulkSchedule" wire:loading.attr="disabled" class="bg-yellow-400 hover:bg-yellow-500 text-black font-black px-6 py-3 rounded-lg border-2 border-black shadow-[4px_4px_0px_0px_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase text-xs tracking-wider">
            <span wire:loading.remove>🚀 SIMPAN JADWAL</span>
            <span wire:loading>PROSES...</span>
        </button>
    </div>

    <!-- MODAL EDIT SINGLE (SMART MODAL) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm animate-fade-in-up" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-white w-full max-w-lg rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden relative">
            
            <!-- Header Modal -->
            <div class="bg-unmaris-blue p-4 border-b-4 border-black flex justify-between items-center text-white">
                <h3 class="font-black text-lg uppercase flex items-center gap-2">
                    <span>⚙️</span> Update Data Seleksi
                </h3>
                <button wire:click="closeModal" class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded border-2 border-black flex items-center justify-center font-black transition">&times;</button>
            </div>
            
            <div class="p-6 space-y-5">
                
                <!-- Section Jadwal -->
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <h4 class="text-xs font-black text-blue-800 uppercase mb-3 border-b border-blue-200 pb-1">1. Jadwal & Lokasi</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Waktu Ujian</label>
                            <input type="datetime-local" wire:model="jadwal_ujian" class="w-full border-2 border-blue-300 rounded px-3 py-2 font-bold focus:border-unmaris-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Lokasi Ruangan</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="lokasi_ujian" class="w-full border-2 border-blue-300 rounded px-3 py-2 font-bold focus:border-unmaris-blue outline-none" placeholder="Ketik manual...">
                            </div>
                            <!-- SMART PRESETS (Tombol Cepat) -->
                            <div class="flex gap-2 mt-2">
                                <button type="button" wire:click="setQuickLocation('Kampus Utama, R. 101')" class="text-[10px] bg-white border border-gray-300 px-2 py-1 rounded hover:bg-blue-100 transition">🏫 R. 101</button>
                                <button type="button" wire:click="setQuickLocation('Lab Komputer 1')" class="text-[10px] bg-white border border-gray-300 px-2 py-1 rounded hover:bg-blue-100 transition">💻 Lab Komp</button>
                                <button type="button" wire:click="setQuickLocation('Aula St. Alexander')" class="text-[10px] bg-white border border-gray-300 px-2 py-1 rounded hover:bg-blue-100 transition">🏢 Aula</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Nilai -->
                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                    <h4 class="text-xs font-black text-yellow-800 uppercase mb-3 border-b border-yellow-200 pb-1">2. Hasil Ujian</h4>
                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="block text-xs font-bold text-gray-500 mb-1">Skor (0-100)</label>
                            <input type="number" wire:model="nilai_ujian" min="0" max="100" class="w-full border-2 border-yellow-400 rounded px-3 py-2 font-black text-center text-2xl focus:shadow-neo outline-none">
                        </div>
                        <div class="w-2/3">
                            <label class="block text-xs font-bold text-gray-500 mb-1">Catatan Penguji</label>
                            <textarea wire:model="catatan_penguji" rows="2" class="w-full border-2 border-yellow-400 rounded px-3 py-2 font-medium focus:shadow-neo outline-none text-sm" placeholder="Opsional..."></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Modal -->
            <div class="p-4 bg-gray-100 border-t-4 border-black flex justify-between items-center">
                <span class="text-xs font-bold text-gray-400 uppercase">Pastikan data benar</span>
                <div class="flex gap-2">
                    <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800 text-sm">Batal</button>
                    <button wire:click="update" class="bg-green-600 text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-green-700 transition-all text-sm uppercase">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>