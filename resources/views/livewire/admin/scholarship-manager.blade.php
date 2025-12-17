<div class="space-y-6">
    
    <!-- HEADER -->
    <div class="bg-unmaris-blue p-6 rounded-xl border-4 border-black shadow-neo flex justify-between items-center">
        <div>
            <h2 class="text-white font-black text-2xl uppercase tracking-wider flex items-center gap-2">
                🎓 Manajemen Beasiswa
            </h2>
            <p class="text-blue-200 font-bold mt-1">Kelola program bantuan biaya pendidikan.</p>
        </div>
        <button wire:click="create" class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue px-6 py-3 rounded-lg font-black border-2 border-black shadow-sm hover:shadow-none transition-all uppercase flex items-center gap-2">
            <span>+ Buat Program</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-fade-in-down">
            {{ session('message') }}
        </div>
    @endif

    <!-- LIST CARD -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($scholarships as $s)
            <div class="bg-white border-4 {{ $s->is_active ? 'border-black' : 'border-gray-300' }} rounded-xl p-6 shadow-neo relative group transition-all hover:-translate-y-1">
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <button wire:click="toggleActive({{ $s->id }})" 
                            class="text-[10px] font-black px-2 py-1 rounded border-2 cursor-pointer uppercase transition-all
                            {{ $s->is_active ? 'bg-green-100 text-green-700 border-green-500' : 'bg-gray-100 text-gray-500 border-gray-400' }}">
                        {{ $s->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                    </button>
                </div>

                <div class="text-4xl mb-3 {{ $s->is_active ? 'grayscale-0' : 'grayscale opacity-50' }}">🎓</div>
                
                <h3 class="font-black text-xl text-unmaris-blue uppercase leading-tight mb-2 {{ $s->is_active ? '' : 'text-gray-400' }}">
                    {{ $s->name }}
                </h3>
                
                <p class="text-xs font-bold text-gray-500 mb-4 line-clamp-2 h-8">
                    {{ $s->description }}
                </p>

                <!-- Stats -->
                <div class="flex items-center gap-2 mb-4 bg-gray-50 p-2 rounded border border-gray-200">
                    <div class="flex-1 text-center border-r border-gray-300">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Kuota</span>
                        <span class="block font-black text-lg text-black">{{ $s->quota }}</span>
                    </div>
                    <div class="flex-1 text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase">Pendaftar</span>
                        <span class="block font-black text-lg {{ $s->pendaftars_count > $s->quota ? 'text-red-500' : 'text-green-500' }}">
                            {{ $s->pendaftars_count }}
                        </span>
                    </div>
                </div>

                <div class="text-[10px] font-bold text-gray-400 mb-4 uppercase text-center">
                    Periode: {{ $s->start_date->format('d M') }} - {{ $s->end_date->format('d M Y') }}
                </div>

                <div class="flex gap-2">
                    <button wire:click="edit({{ $s->id }})" class="flex-1 bg-white hover:bg-gray-50 text-black font-bold py-2 rounded border-2 border-black text-xs uppercase shadow-sm">
                        Edit
                    </button>
                    <button wire:click="delete({{ $s->id }})" onclick="return confirm('Hapus program ini?')" class="flex-1 bg-red-100 hover:bg-red-200 text-red-600 font-bold py-2 rounded border-2 border-red-200 text-xs uppercase">
                        Hapus
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- MODAL FORM -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden">
            <div class="bg-unmaris-yellow p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-unmaris-blue text-lg uppercase">
                    {{ $selectedId ? 'Edit Program' : 'Program Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-unmaris-blue font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Beasiswa</label>
                    <input type="text" wire:model="name" placeholder="Misal: KIP Kuliah 2025" class="w-full border-2 border-black rounded px-3 py-2 font-bold">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kuota Penerima</label>
                    <input type="number" wire:model="quota" class="w-full border-2 border-black rounded px-3 py-2 font-bold">
                    @error('quota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mulai</label>
                        <input type="date" wire:model="start_date" class="w-full border-2 border-black rounded px-3 py-2 font-medium">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Selesai</label>
                        <input type="date" wire:model="end_date" class="w-full border-2 border-black rounded px-3 py-2 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan / Syarat</label>
                    <textarea wire:model="description" rows="3" placeholder="Jelaskan syarat khusus..." class="w-full border-2 border-black rounded px-3 py-2 font-medium"></textarea>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800">Batal</button>
                <button wire:click="store" class="bg-unmaris-blue text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-800 transition-all">SIMPAN</button>
            </div>
        </div>
    </div>
    @endif

</div>