<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black gap-4">
        <h2 class="text-white font-black text-xl uppercase tracking-wider flex items-center gap-2">
            <span>🎤</span> Jadwal Wawancara
        </h2>
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Peserta..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all text-sm">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-pulse flex items-center gap-2">
            <span>✅</span> {{ session('message') }}
        </div>
    @endif

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden relative">
        <div class="overflow-x-auto pb-20"> <!-- Padding bottom extra untuk floating bar -->
            <table class="min-w-full text-left">
                <thead class="bg-gray-200 text-black border-b-4 border-unmaris-blue">
                    <tr>
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-unmaris-blue border-2 border-black rounded focus:ring-0 cursor-pointer">
                        </th>
                        <th class="p-4 font-black uppercase text-sm">Nama Peserta</th>
                        <th class="p-4 font-black uppercase text-sm">Jadwal & Pewawancara</th>
                        <th class="p-4 font-black uppercase text-center text-sm">Nilai</th>
                        <th class="p-4 font-black uppercase text-right text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($peserta as $p)
                        <tr class="hover:bg-blue-50 transition group {{ in_array($p->id, $selected) ? 'bg-blue-100' : '' }}">
                            
                            <!-- Checkbox -->
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selected" value="{{ $p->id }}" class="w-5 h-5 text-unmaris-blue border-2 border-gray-400 rounded focus:ring-0 cursor-pointer">
                            </td>

                            <!-- Nama -->
                            <td class="p-4 align-top">
                                <div class="font-black text-unmaris-blue text-base">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500">{{ $p->pilihan_prodi_1 }}</div>
                                
                                @if($p->nomor_hp)
                                    <div class="mt-1 flex items-center gap-1">
                                        <span class="text-[10px] bg-green-100 text-green-800 px-1 rounded border border-green-200 font-bold">WA: {{ $p->nomor_hp }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Jadwal Status -->
                            <td class="p-4 align-top">
                                @if($p->jadwal_wawancara)
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 rounded border border-blue-300 text-xs font-black w-fit">
                                            📅 {{ \Carbon\Carbon::parse($p->jadwal_wawancara)->format('d M Y, H:i') }}
                                        </span>
                                        <span class="text-xs font-bold text-gray-500">
                                            Oleh: <span class="text-black">{{ $p->pewawancara ?? '-' }}</span>
                                        </span>
                                        
                                        <!-- Tombol WA Notifikasi -->
                                        @if($p->nomor_hp)
                                            @php
                                                $waText = "Halo " . $p->user->name . ", berikut jadwal wawancara PMB UNMARIS Anda:%0a%0a📅 Tanggal: " . \Carbon\Carbon::parse($p->jadwal_wawancara)->format('d-m-Y') . "%0a⏰ Jam: " . \Carbon\Carbon::parse($p->jadwal_wawancara)->format('H:i') . "%0a👤 Pewawancara: " . ($p->pewawancara ?? 'Panitia') . "%0a%0aHarap hadir tepat waktu. Terima kasih.";
                                                $waLink = "https://wa.me/" . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->nomor_hp)) . "?text=" . $waText;
                                            @endphp
                                            <a href="{{ $waLink }}" target="_blank" class="mt-1 flex items-center gap-1 text-[10px] font-bold text-green-600 hover:underline hover:text-green-800">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                Kirim Jadwal via WA
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-black rounded border border-red-200 uppercase animate-pulse">
                                        BELUM DIJADWALKAN
                                    </span>
                                @endif
                            </td>

                            <!-- Nilai -->
                            <td class="p-4 align-top text-center">
                                @if($p->nilai_wawancara > 0)
                                    <span class="text-2xl font-black text-unmaris-blue">{{ $p->nilai_wawancara }}</span>
                                @else
                                    <span class="text-xl font-bold text-gray-300">-</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 align-top text-right">
                                <button wire:click="edit({{ $p->id }})" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg font-bold border-2 border-black shadow-neo-sm hover:shadow-none transition-all text-xs">
                                    ⚙️ Atur
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center font-bold text-gray-400 bg-gray-50">
                                Tidak ada peserta yang perlu dijadwalkan saat ini.
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

    <!-- FLOATING ACTION BAR (Untuk Bulk Schedule) -->
    <div x-data="{ count: @entangle('selected').live }" 
         x-show="count.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-40 w-11/12 max-w-4xl bg-black text-white p-4 rounded-2xl shadow-2xl border-4 border-unmaris-yellow flex flex-col md:flex-row items-center justify-between gap-4">
        
        <div class="flex items-center gap-3">
            <span class="bg-unmaris-yellow text-black font-black w-8 h-8 flex items-center justify-center rounded-full" x-text="count.length"></span>
            <span class="font-bold text-sm uppercase">Peserta Dipilih</span>
        </div>

        <!-- Form Inline Sederhana -->
        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
            <input type="datetime-local" wire:model="bulk_jadwal_wawancara" class="bg-gray-800 border border-gray-600 text-white text-sm rounded px-3 py-2 focus:ring-unmaris-yellow">
            <input type="text" wire:model="bulk_pewawancara" placeholder="Nama Pewawancara..." class="bg-gray-800 border border-gray-600 text-white text-sm rounded px-3 py-2 focus:ring-unmaris-yellow">
        </div>

        <button wire:click="applyBulkSchedule" wire:loading.attr="disabled" class="bg-unmaris-yellow hover:bg-yellow-400 text-black font-black px-6 py-2 rounded-lg uppercase text-sm shadow-neo transition-transform hover:-translate-y-1">
            <span wire:loading.remove>🚀 Simpan Jadwal Massal</span>
            <span wire:loading>Memproses...</span>
        </button>
    </div>

    <!-- MODAL SINGLE EDIT -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-white w-full max-w-md rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden relative">
            <div class="bg-unmaris-blue p-4 border-b-4 border-black flex justify-between items-center text-white">
                <h3 class="font-black text-lg uppercase">⚙️ Atur Wawancara</h3>
                <button wire:click="closeModal" class="font-black hover:text-yellow-300 text-xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                
                <!-- Jadwal -->
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Jadwal Wawancara</label>
                    <input type="datetime-local" wire:model="jadwal_wawancara" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                    @error('jadwal_wawancara') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pewawancara -->
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Nama Pewawancara</label>
                    <input type="text" wire:model="pewawancara" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                </div>

                <hr class="border-dashed border-gray-300">

                <!-- Input Nilai (Jika sudah wawancara) -->
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Nilai (0-100)</label>
                    <input type="number" wire:model="nilai_wawancara" min="0" max="100" class="w-full border-2 border-black rounded px-3 py-2 font-bold text-center text-2xl focus:shadow-neo transition-all outline-none">
                    <p class="text-[10px] text-gray-400 mt-1">Kosongkan/0 jika belum dinilai.</p>
                </div>

                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Catatan</label>
                    <textarea wire:model="catatan_wawancara" rows="2" class="w-full border-2 border-black rounded px-3 py-2 font-medium focus:shadow-neo transition-all outline-none"></textarea>
                </div>

            </div>

            <div class="p-4 bg-gray-100 border-t-4 border-black flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800 text-sm">Batal</button>
                <button wire:click="update" class="bg-green-600 text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-green-700 transition-all text-sm uppercase">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
    @endif

</div>