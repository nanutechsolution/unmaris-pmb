<div class="space-y-6">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black">
        <h2 class="text-white font-black text-xl uppercase tracking-wider">
            🎤 Tes Wawancara
        </h2>
        <div class="w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Camaba..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <!-- TABEL -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <table class="min-w-full text-left">
            <thead class="bg-orange-400 text-unmaris-blue border-b-4 border-unmaris-blue">
                <tr>
                    <th class="p-4 font-black uppercase">Peserta</th>
                    <th class="p-4 font-black uppercase">Jadwal Wawancara</th>
                    <th class="p-4 font-black uppercase">Pewawancara</th>
                    <th class="p-4 font-black uppercase text-center">Skor</th>
                    <th class="p-4 font-black uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y-2 divide-gray-100">
                @forelse($peserta as $p)
                    <tr class="hover:bg-orange-50 transition">
                        <td class="p-4">
                            <div class="font-black text-unmaris-blue">{{ $p->user->name }}</div>
                            <div class="text-xs font-bold text-gray-500">{{ $p->pilihan_prodi_1 }}</div>
                        </td>
                        <td class="p-4">
                            @if($p->jadwal_wawancara)
                                <div class="font-bold text-unmaris-blue bg-blue-100 px-2 py-1 rounded inline-block border border-blue-200">
                                    🕒 {{ $p->jadwal_wawancara->format('d M, H:i') }}
                                </div>
                            @else
                                <span class="text-xs font-bold text-red-400 bg-red-50 px-2 py-1 rounded">Belum Ada</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-gray-600">
                            {{ $p->pewawancara ?? '-' }}
                        </td>
                        <td class="p-4 text-center">
                            @if($p->nilai_wawancara > 0)
                                <span class="text-xl font-black {{ $p->nilai_wawancara >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $p->nilai_wawancara }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <button wire:click="edit({{ $p->id }})" class="bg-unmaris-blue text-white px-4 py-2 rounded-lg font-bold border-2 border-black shadow-neo-sm hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all text-xs">
                                ⚙️ ATUR
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-gray-400 font-bold">Data kosong.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">{{ $peserta->links() }}</div>
    </div>

    <!-- MODAL -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up">
        <div class="bg-white w-full max-w-lg rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden">
            <div class="bg-orange-400 p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-unmaris-blue text-lg uppercase">🎤 Jadwal & Nilai Wawancara</h3>
                <button wire:click="closeModal" class="text-unmaris-blue font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-unmaris-blue mb-1">📅 Jadwal Wawancara</label>
                    <input type="datetime-local" wire:model="jadwal_wawancara" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-unmaris-blue mb-1">👨‍🏫 Pewawancara (Dosen)</label>
                    <input type="text" wire:model="pewawancara" placeholder="Nama Dosen..." class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold">
                </div>
                <div class="border-t-2 border-dashed border-gray-300 my-2"></div>
                <div class="flex gap-4">
                    <div class="w-1/3">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">💯 Nilai</label>
                        <input type="number" wire:model="nilai_wawancara" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-black text-center text-xl">
                    </div>
                    <div class="w-2/3">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">📝 Catatan</label>
                        <input type="text" wire:model="catatan_wawancara" placeholder="Kesan/Catatan..." class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-medium">
                    </div>
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