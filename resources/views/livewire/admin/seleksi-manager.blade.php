<div class="space-y-6 pb-24"> <!-- Padding bottom extra biar floating bar tidak menutupi -->
    
    <!-- HEADER & SEARCH -->
    <div class="flex justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black">
        <h2 class="text-white font-black text-xl uppercase tracking-wider">
            🎯 Seleksi Ujian Tulis (Offline)
        </h2>
        <div class="w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Peserta..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all">
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-yellow-400 text-unmaris-blue border-b-4 border-unmaris-blue">
                    <tr>
                        <!-- CHECKBOX ALL -->
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-4 font-black uppercase">Peserta</th>
                        <th class="p-4 font-black uppercase">Jadwal & Ruang</th>
                        <th class="p-4 font-black uppercase text-center">Nilai Ujian</th>
                        <th class="p-4 font-black uppercase text-center">Status</th>
                        <th class="p-4 font-black uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($peserta as $p)
                        <tr class="hover:bg-blue-50 transition {{ in_array($p->id, $selected) ? 'bg-yellow-50' : '' }}">
                            <!-- CHECKBOX ROW -->
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                            </td>

                            <td class="p-4">
                                <div class="font-black text-unmaris-blue">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500">{{ $p->pilihan_prodi_1 }}</div>
                            </td>

                            <td class="p-4">
                                @if($p->jadwal_ujian)
                                    <div class="font-bold text-unmaris-blue bg-blue-100 px-2 py-1 rounded inline-block border border-blue-200">
                                        📅 {{ $p->jadwal_ujian->format('d M / H:i') }} WITA
                                    </div>
                                    <div class="text-xs font-bold text-gray-500 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ Str::limit($p->lokasi_ujian, 30) }}
                                    </div>
                                @else
                                    <span class="text-xs font-bold text-red-400 bg-red-50 px-2 py-1 rounded border border-red-100">Belum Ada Jadwal</span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                @if($p->nilai_ujian > 0)
                                    <span class="text-xl font-black {{ $p->nilai_ujian >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $p->nilai_ujian }}
                                    </span>
                                @else
                                    <span class="text-gray-300 font-bold text-2xl">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-center">
                                <span class="px-2 py-1 text-[10px] font-black rounded border-2 uppercase
                                    {{ $p->status_pendaftaran == 'lulus' ? 'bg-green-100 text-green-800 border-green-600' : '' }}
                                    {{ $p->status_pendaftaran == 'gagal' ? 'bg-red-100 text-red-800 border-red-600' : '' }}
                                    {{ $p->status_pendaftaran == 'verifikasi' ? 'bg-yellow-100 text-yellow-800 border-yellow-500' : '' }}">
                                    {{ $p->status_pendaftaran }}
                                </span>
                            </td>

                            <td class="p-4 text-right">
                                <button wire:click="edit({{ $p->id }})" class="bg-white text-unmaris-blue px-3 py-1 rounded font-bold border-2 border-unmaris-blue shadow-neo-sm hover:shadow-none transition-all text-xs">
                                    ⚙️ Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center font-bold text-gray-400">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">
            {{ $peserta->links() }}
        </div>
    </div>

    <!-- FLOATING ACTION BAR (AKSI MASSAL) -->
    <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 w-11/12 max-w-4xl z-50 transition-all duration-300 {{ count($selected) > 0 ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0 pointer-events-none' }}">
        <div class="bg-unmaris-blue text-white p-4 rounded-xl border-4 border-black shadow-neo-lg flex flex-col md:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <span class="bg-unmaris-yellow text-unmaris-blue font-black px-3 py-1 rounded border-2 border-black shadow-sm">
                    {{ count($selected) }} Terpilih
                </span>
                <span class="font-bold text-sm hidden md:inline">Atur Jadwal Sekaligus:</span>
            </div>

            <div class="flex flex-1 gap-2 w-full md:w-auto">
                <input type="datetime-local" wire:model="bulk_jadwal_ujian" class="flex-1 text-black font-bold rounded border-2 border-black focus:shadow-neo outline-none px-2 py-1 text-sm">
                <!-- Placeholder disesuaikan untuk lokasi offline -->
                <input type="text" wire:model="bulk_lokasi_ujian" placeholder="Ruang Ujian (misal: R.101)" class="flex-1 text-black font-bold rounded border-2 border-black focus:shadow-neo outline-none px-2 py-1 text-sm">
            </div>

            <button wire:click="applyBulkSchedule" wire:loading.attr="disabled" class="bg-green-500 hover:bg-green-600 text-white font-black px-6 py-2 rounded-lg border-2 border-black shadow-[2px_2px_0px_0px_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase text-sm">
                <span wire:loading.remove>TERAPKAN 🚀</span>
                <span wire:loading>LOADING...</span>
            </button>
        </div>
    </div>

    <!-- MODAL EDIT SINGLE -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up">
        <div class="bg-white w-full max-w-lg rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden">
            <div class="bg-unmaris-yellow p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-unmaris-blue text-lg uppercase">⚙️ Update Data Seleksi</h3>
                <button wire:click="closeModal" class="text-unmaris-blue font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-unmaris-blue mb-1">📅 Tanggal & Jam Ujian</label>
                    <input type="datetime-local" wire:model="jadwal_ujian" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold">
                    @error('jadwal_ujian') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-unmaris-blue mb-1">📍 Lokasi Ruang Ujian</label>
                    <input type="text" wire:model="lokasi_ujian" placeholder="Contoh: Gedung A, Ruang 101" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold">
                    <p class="text-xs text-gray-500 mt-1 font-bold">*) Masukkan nama gedung atau nomor ruang.</p>
                </div>
                <div class="border-t-2 border-dashed border-gray-300 my-2"></div>
                <div class="flex gap-4">
                    <div class="w-1/3">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">💯 Skor Ujian</label>
                        <input type="number" wire:model="nilai_ujian" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-black text-center text-xl">
                    </div>
                    <div class="w-2/3">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">📝 Catatan Penguji</label>
                        <input type="text" wire:model="catatan_penguji" placeholder="Optional..." class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-medium">
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-100 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800">Batal</button>
                <button wire:click="update" class="bg-unmaris-blue text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-800 transition-all">
                    SIMPAN
                </button>
            </div>
        </div>
    </div>
    @endif

</div>