<div class="space-y-6">
    
    <!-- HEADER -->
    <div class="bg-unmaris-blue p-6 rounded-xl border-4 border-black shadow-neo">
        <h2 class="text-white font-black text-2xl uppercase tracking-wider">
            📢 Pusat Informasi (Broadcast)
        </h2>
        <p class="text-blue-200 font-bold mt-1">Buat pengumuman yang akan muncul di Dashboard Mahasiswa.</p>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- FORM INPUT (KIRI) -->
        <div class="md:col-span-1">
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6">
                <h3 class="font-black text-unmaris-blue text-lg mb-4 uppercase">✍️ Tulis Berita</h3>
                
                <form wire:submit.prevent="store" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Judul</label>
                        <input type="text" wire:model="title" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Isi Pesan</label>
                        <textarea wire:model="content" rows="4" class="w-full border-2 border-black rounded px-3 py-2 font-medium focus:shadow-neo transition-all"></textarea>
                        @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tipe Info</label>
                        <div class="flex gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="type" value="info" class="peer sr-only">
                                <div class="px-3 py-1 rounded border-2 border-blue-500 text-blue-600 font-bold peer-checked:bg-blue-500 peer-checked:text-white transition-all">ℹ️ Info</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="type" value="warning" class="peer sr-only">
                                <div class="px-3 py-1 rounded border-2 border-yellow-500 text-yellow-600 font-bold peer-checked:bg-yellow-500 peer-checked:text-white transition-all">⚠️ Penting</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="type" value="danger" class="peer sr-only">
                                <div class="px-3 py-1 rounded border-2 border-red-500 text-red-600 font-bold peer-checked:bg-red-500 peer-checked:text-white transition-all">🚨 Darurat</div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 rounded-lg border-2 border-black shadow-neo hover:shadow-none transition-all uppercase tracking-wider">
                        POSTING SEKARANG
                    </button>
                </form>
            </div>
        </div>

        <!-- LIST BERITA (KANAN) -->
        <div class="md:col-span-2 space-y-4">
            @foreach($announcements as $a)
                <div class="bg-white border-2 border-black rounded-xl p-4 flex justify-between items-start shadow-sm hover:shadow-md transition-all">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            @if($a->type == 'info') <span class="bg-blue-100 text-blue-800 text-[10px] font-black px-2 py-0.5 rounded border border-blue-300">INFO</span>
                            @elseif($a->type == 'warning') <span class="bg-yellow-100 text-yellow-800 text-[10px] font-black px-2 py-0.5 rounded border border-yellow-300">PENTING</span>
                            @else <span class="bg-red-100 text-red-800 text-[10px] font-black px-2 py-0.5 rounded border border-red-300">DARURAT</span>
                            @endif
                            
                            <span class="text-xs font-bold text-gray-400">{{ $a->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-black text-lg text-gray-800">{{ $a->title }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($a->content, 100) }}</p>
                    </div>

                    <div class="flex flex-col gap-2">
                        <button wire:click="toggleStatus({{ $a->id }})" class="text-xs font-bold px-3 py-1 rounded border-2 {{ $a->is_active ? 'bg-green-100 text-green-700 border-green-500' : 'bg-gray-200 text-gray-500 border-gray-400' }}">
                            {{ $a->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                        </button>
                        <button wire:click="delete({{ $a->id }})" onclick="return confirm('Hapus pengumuman ini?')" class="text-xs font-bold px-3 py-1 rounded border-2 bg-red-100 text-red-700 border-red-500 hover:bg-red-200">
                            HAPUS
                        </button>
                    </div>
                </div>
            @endforeach

            @if($announcements->isEmpty())
                <div class="text-center p-8 text-gray-400 font-bold italic border-2 border-dashed border-gray-300 rounded-xl">
                    Belum ada pengumuman yang dibuat.
                </div>
            @endif
        </div>

    </div>
</div>