<div class="p-6">
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- FORM INPUT (KIRI) -->
        <div class="w-full md:w-1/3">
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6">
                <h3 class="text-xl font-black text-unmaris-blue mb-4 uppercase">
                    ➕ Buat Gelombang
                </h3>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-bold">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="store">
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Nama Gelombang</label>
                        <input type="text" wire:model="nama_gelombang" placeholder="Contoh: Gelombang 1" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                        @error('nama_gelombang') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Tanggal Mulai</label>
                        <input type="date" wire:model="tgl_mulai" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                        @error('tgl_mulai') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Tanggal Selesai</label>
                        <input type="date" wire:model="tgl_selesai" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                        @error('tgl_selesai') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-2 px-4 rounded border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                        SIMPAN JADWAL
                    </button>
                </form>
            </div>
        </div>

        <!-- LIST GELOMBANG (KANAN) -->
        <div class="w-full md:w-2/3">
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6">
                <h3 class="text-xl font-black text-unmaris-blue mb-4 uppercase">
                    📅 Daftar Jadwal PMB
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-blue-50 border-b-2 border-unmaris-blue text-unmaris-blue">
                                <th class="p-3 font-black uppercase text-sm">Gelombang</th>
                                <th class="p-3 font-black uppercase text-sm">Periode</th>
                                <th class="p-3 font-black uppercase text-sm text-center">Status</th>
                                <th class="p-3 font-black uppercase text-sm text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gelombangs as $g)
                                <tr class="border-b border-gray-200 hover:bg-yellow-50 transition">
                                    <td class="p-3 font-bold text-unmaris-blue">
                                        {{ $g->nama_gelombang }}
                                    </td>
                                    <td class="p-3 text-sm font-medium text-gray-600">
                                        {{ $g->tgl_mulai->format('d M Y') }} <span class="font-bold">-</span> {{ $g->tgl_selesai->format('d M Y') }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <button wire:click="toggleActive({{ $g->id }})" 
                                            class="text-xs font-black px-3 py-1 rounded-full border-2 transition-all transform hover:scale-105
                                            {{ $g->is_active 
                                                ? 'bg-unmaris-green text-white border-green-700 shadow-sm' 
                                                : 'bg-gray-200 text-gray-500 border-gray-400' }}">
                                            {{ $g->is_active ? 'AKTIF (BUKA)' : 'NON-AKTIF' }}
                                        </button>
                                    </td>
                                    <td class="p-3 text-right">
                                        <button wire:click="delete({{ $g->id }})" onclick="return confirm('Hapus gelombang ini?')" class="text-red-500 hover:text-red-700 font-bold text-xs underline">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-400 font-bold italic">
                                        Belum ada jadwal gelombang. Silakan buat baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>