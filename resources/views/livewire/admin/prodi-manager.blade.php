<div class="p-6 font-sans">
    
    <!-- Header & Add Button -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-xl font-black text-unmaris-blue uppercase">Daftar Jurusan</h3>
            <p class="text-sm text-gray-500 font-bold">Kelola Program Studi & Biaya Kuliah</p>
        </div>
        <button wire:click="create" class="bg-unmaris-yellow hover:bg-yellow-400 text-black font-black py-2 px-4 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none transition-all flex items-center gap-2">
            <span>+</span> Tambah Prodi
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 font-bold shadow-sm animate-pulse">
            ✅ {{ session('message') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y-2 divide-gray-100">
                <thead class="bg-gray-200 text-black border-b-4 border-unmaris-blue">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">Nama Prodi</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">Jenjang</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider">Biaya (Rp)</th>
                        <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($prodis as $prodi)
                    <tr class="hover:bg-blue-50 transition group">
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-unmaris-blue">{{ $prodi->name }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase mt-1">Kode: {{ $prodi->code ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-black rounded border-2 border-black shadow-sm {{ $prodi->degree == 'S1' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $prodi->degree }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                            Rp {{ number_format($prodi->tuition_fee, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 inline-flex text-[10px] font-black uppercase rounded-full border {{ $prodi->is_active ? 'bg-green-100 text-green-800 border-green-500' : 'bg-red-100 text-red-800 border-red-500' }}">
                                {{ $prodi->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $prodi->id }})" class="text-blue-600 hover:text-blue-900 font-bold mr-3 hover:underline">Edit</button>
                            <button wire:click="delete({{ $prodi->id }})" onclick="return confirm('Yakin hapus prodi ini?')" class="text-red-600 hover:text-red-900 font-bold hover:underline">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 font-bold italic">Belum ada data program studi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">
            {{ $prodis->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-white w-full max-w-md rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden relative">
            
            <div class="bg-unmaris-yellow p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-unmaris-blue text-lg uppercase">{{ $prodi_id ? '✏️ Edit Prodi' : '➕ Tambah Prodi' }}</h3>
                <button wire:click="closeModal" class="text-black font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Nama Program Studi</label>
                    <input type="text" wire:model="name" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo outline-none transition-all">
                    @error('name') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Jenjang</label>
                        <select wire:model="degree" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo outline-none bg-white">
                            <option value="S1">S1 (Sarjana)</option>
                            <option value="D3">D3 (Diploma)</option>
                        </select>
                        @error('degree') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Biaya (Rp)</label>
                        <input type="number" wire:model="tuition_fee" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo outline-none">
                        @error('tuition_fee') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Status Pendaftaran</label>
                    <select wire:model="is_active" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo outline-none bg-white">
                        <option value="1">✅ Aktif (Buka)</option>
                        <option value="0">⛔ Non-Aktif (Tutup)</option>
                    </select>
                </div>
            </div>

            <div class="p-4 bg-gray-100 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800 text-sm">Batal</button>
                <button wire:click="store" class="bg-unmaris-blue text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-800 transition-all text-sm uppercase">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
    @endif
</div>