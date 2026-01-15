<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-black text-unmaris-blue uppercase">🏢 Manajemen Fasilitas (Slider)</h2>
        <button wire:click="create" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-neo transition">
            + Tambah Fasilitas
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- LIST TABLE -->
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Fasilitas</th>
                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Preview Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-black text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($slides as $slide)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-2xl">{{ $slide->icon }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900">{{ $slide->title }}</div>
                        <div class="text-xs text-gray-500 truncate w-64">{{ $slide->description }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex -space-x-2 overflow-hidden">
                            @if($slide->images)
                                @foreach(array_slice($slide->images, 0, 3) as $img)
                                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover" src="{{ asset('storage/'.$img) }}" alt="">
                                @endforeach
                                @if(count($slide->images) > 3)
                                    <span class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">+{{ count($slide->images)-3 }}</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">No Image</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $slide->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button wire:click="edit({{ $slide->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3 font-bold">Edit</button>
                        <button wire:click="delete({{ $slide->id }})" onclick="return confirm('Yakin hapus fasilitas ini?')" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data fasilitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MODAL FORM (Create/Edit) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-t-8 border-unmaris-blue">
                    <h3 class="text-lg leading-6 font-black text-gray-900 mb-4" id="modal-title">
                        {{ $slideId ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Judul -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nama Fasilitas</label>
                            <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-unmaris-blue focus:ring focus:ring-blue-200 sm:text-sm px-3 py-2">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Icon & Status -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Icon (Emoji)</label>
                                <input type="text" wire:model="icon" placeholder="🏢" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm px-3 py-2">
                                @error('icon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Status</label>
                                <select wire:model="is_active" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm px-3 py-2">
                                    <option value="1">Aktif</option>
                                    <option value="0">Sembunyikan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Deskripsi Singkat</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm px-3 py-2"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- MANAGEMENT FOTO -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Galeri Foto</label>
                            
                            <!-- Foto Lama (Hanya saat Edit) -->
                            @if(!empty($oldImages))
                                <p class="text-xs font-bold text-gray-500 mb-2">Foto Saat Ini:</p>
                                <div class="grid grid-cols-4 gap-2 mb-4">
                                    @foreach($oldImages as $index => $img)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/'.$img) }}" class="h-16 w-16 object-cover rounded border border-gray-300">
                                            <button type="button" wire:click="removePhoto({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-sm hover:bg-red-700">x</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Upload Baru -->
                            <div class="mt-2">
                                <label class="block w-full border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-white hover:border-blue-500 transition cursor-pointer">
                                    <span class="text-gray-500 text-sm font-bold">📄 Klik untuk Upload Foto Baru</span>
                                    <span class="block text-xs text-gray-400">(Bisa pilih banyak sekaligus)</span>
                                    <input type="file" wire:model="newImages" multiple class="hidden">
                                </label>
                                @error('newImages.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Preview Upload Baru -->
                            @if ($newImages)
                                <p class="text-xs font-bold text-green-600 mt-2">Akan diupload:</p>
                                <div class="flex gap-2 mt-1 overflow-x-auto">
                                    @foreach ($newImages as $newImg)
                                        <img src="{{ $newImg->temporaryUrl() }}" class="h-12 w-12 object-cover rounded border border-green-300">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="{{ $slideId ? 'update' : 'store' }}" wire:loading.attr="disabled" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-unmaris-blue text-base font-medium text-white hover:bg-blue-900 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        <span wire:loading.remove>{{ $slideId ? 'Simpan Perubahan' : 'Simpan' }}</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                    <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>