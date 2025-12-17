<div class="space-y-6 pb-24"> <!-- Padding bottom agar tidak ketutup floating bar -->
    
    <!-- HEADER & SEARCH -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black gap-4">
        <h2 class="text-white font-black text-xl uppercase tracking-wider flex items-center gap-2">
            🎤 Tes Wawancara
        </h2>
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Camaba..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold animate-pulse shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-orange-400 text-unmaris-blue border-b-4 border-unmaris-blue">
                    <tr>
                        <!-- Checkbox All -->
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-4 font-black uppercase whitespace-nowrap">Peserta</th>
                        <th class="p-4 font-black uppercase whitespace-nowrap">Jadwal Wawancara</th>
                        <th class="p-4 font-black uppercase whitespace-nowrap">Pewawancara</th>
                        <th class="p-4 font-black uppercase text-center whitespace-nowrap">Skor (Tulis + Wawancara)</th>
                        <th class="p-4 font-black uppercase text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($peserta as $p)
                        <tr class="hover:bg-orange-50 transition {{ in_array($p->id, $selected) ? 'bg-yellow-50' : '' }}">
                            <!-- Checkbox Row -->
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                <div class="font-black text-unmaris-blue">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500">{{ $p->pilihan_prodi_1 }}</div>
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                @if($p->jadwal_wawancara)
                                    <div class="font-bold text-unmaris-blue bg-blue-100 px-2 py-1 rounded inline-block border border-blue-200 text-sm">
                                        🕒 {{ $p->jadwal_wawancara->format('d M, H:i') }}
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-red-400 bg-red-50 px-2 py-1 rounded border border-red-100">Belum Ada</span>
                                @endif
                            </td>

                            <td class="p-4 font-bold text-gray-600 whitespace-nowrap">
                                {{ $p->pewawancara ?? '-' }}
                            </td>

                            <td class="p-4 text-center">
                                <div class="flex flex-col gap-1 items-center">
                                    <!-- Nilai Tulis -->
                                    <span class="text-xs font-bold {{ $p->nilai_ujian > 0 ? 'text-blue-600' : 'text-red-400' }}">
                                        Tulis: {{ $p->nilai_ujian > 0 ? $p->nilai_ujian : '-' }}
                                    </span>
                                    <!-- Nilai Wawancara -->
                                    <span class="text-xs font-bold {{ $p->nilai_wawancara > 0 ? 'text-green-600' : 'text-red-400' }}">
                                        Wwcr: {{ $p->nilai_wawancara > 0 ? $p->nilai_wawancara : '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <!-- Tombol Input Nilai -->
                                    <button wire:click="edit({{ $p->id }})" class="bg-white text-unmaris-blue px-3 py-1 rounded-lg font-bold border-2 border-unmaris-blue shadow-neo-sm hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all text-xs flex items-center gap-1">
                                        ⚙️ <span class="hidden md:inline">ATUR</span>
                                    </button>
                                    
                                    <!-- Tombol Shortcut ke Detail (Finalisasi Status) -->
                                    <!-- GUARD: Cek KEDUA nilai harus ada -->
                                    @if($p->nilai_wawancara > 0 && $p->nilai_ujian > 0)
                                        <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="bg-green-500 text-white px-3 py-1 rounded-lg font-bold border-2 border-black shadow-neo-sm hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all text-xs flex items-center gap-1" title="Finalisasi Kelulusan">
                                            ✅ <span class="hidden md:inline">FINAL</span>
                                        </a>
                                    @else
                                        <!-- Tombol Disabled (Terkunci) -->
                                        <button disabled class="bg-gray-300 text-gray-500 px-3 py-1 rounded-lg font-bold border-2 border-gray-400 cursor-not-allowed text-xs flex items-center gap-1 opacity-60" title="Nilai Tulis & Wawancara harus diisi dulu">
                                            🔒 <span class="hidden md:inline">LENGKAPI</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-gray-400 font-bold italic">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">{{ $peserta->links() }}</div>
    </div>

    <!-- FLOATING ACTION BAR (AKSI MASSAL) -->
    <!-- Muncul di bawah hanya jika ada yang dicentang -->
    <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 w-11/12 max-w-4xl z-50 transition-all duration-300 {{ count($selected) > 0 ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0 pointer-events-none' }}">
        <div class="bg-unmaris-blue text-white p-4 rounded-xl border-4 border-black shadow-neo-lg flex flex-col md:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <span class="bg-orange-400 text-unmaris-blue font-black px-3 py-1 rounded border-2 border-black shadow-sm whitespace-nowrap text-sm">
                    {{ count($selected) }} Terpilih
                </span>
                <span class="font-bold text-sm hidden md:inline">Atur Jadwal Sekaligus:</span>
            </div>
            
            <div class="flex flex-col md:flex-row flex-1 gap-2 w-full md:w-auto">
                 <input type="datetime-local" wire:model="bulk_jadwal_wawancara" class="flex-1 text-black font-bold rounded border-2 border-black focus:shadow-neo outline-none px-2 py-1 text-sm">
                 <input type="text" wire:model="bulk_pewawancara" placeholder="Nama Pewawancara" class="flex-1 text-black font-bold rounded border-2 border-black focus:shadow-neo outline-none px-2 py-1 text-sm">
            </div>

            <button wire:click="applyBulkSchedule" wire:loading.attr="disabled" class="w-full md:w-auto bg-green-500 hover:bg-green-600 text-white font-black px-6 py-2 rounded-lg border-2 border-black shadow-[2px_2px_0px_0px_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase text-sm">
                <span wire:loading.remove>TERAPKAN 🚀</span>
                <span wire:loading>LOADING...</span>
            </button>
        </div>
    </div>

    <!-- MODAL EDIT SINGLE -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up px-4">
        <div class="bg-white w-full max-w-lg rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden">
            <div class="bg-orange-400 p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-unmaris-blue text-lg uppercase">🎤 Jadwal & Nilai</h3>
                <button wire:click="closeModal" class="text-unmaris-blue font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-unmaris-blue mb-1">📅 Jadwal Wawancara</label>
                    <input type="datetime-local" wire:model="jadwal_wawancara" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold">
                    @error('jadwal_wawancara') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-unmaris-blue mb-1">👨‍🏫 Pewawancara (Dosen)</label>
                    <input type="text" wire:model="pewawancara" placeholder="Nama Dosen..." class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold">
                </div>
                <div class="border-t-2 border-dashed border-gray-300 my-2"></div>
                <div class="flex gap-4">
                    <div class="w-1/3">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">💯 Skor</label>
                        <input type="number" wire:model="nilai_wawancara" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-black text-center text-xl">
                    </div>
                    <div class="w-2/3">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">📝 Catatan</label>
                        <input type="text" wire:model="catatan_wawancara" placeholder="Kesan/Catatan..." class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-medium">
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 p-3 rounded text-xs text-blue-800">
                    <strong>Info:</strong> Pastikan Anda juga sudah menginput Nilai Ujian Tulis di menu Seleksi sebelum melakukan finalisasi.
                </div>
            </div>
            <div class="p-4 bg-gray-100 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800">Batal</button>
                <button wire:click="update" class="bg-unmaris-blue text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-800 transition-all">SIMPAN</button>
            </div>
        </div>
    </div>
    @endif
</div>