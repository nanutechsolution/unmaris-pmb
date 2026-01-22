<div class="space-y-6">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black gap-4">
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <h2 class="text-white font-black text-xl uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                <span>👥</span> Manajemen Akun
            </h2>
            
            <!-- TABS SWITCHER -->
            <div class="bg-black/20 p-1 rounded-lg flex items-center">
                <button wire:click="$set('activeTab', 'camaba')" 
                        class="px-4 py-1.5 rounded-md text-xs font-black uppercase transition-all {{ $activeTab === 'camaba' ? 'bg-yellow-400 text-black shadow-sm' : 'text-white hover:bg-white/10' }}">
                    🎓 Camaba
                </button>
                <button wire:click="$set('activeTab', 'petugas')" 
                        class="px-4 py-1.5 rounded-md text-xs font-black uppercase transition-all {{ $activeTab === 'petugas' ? 'bg-yellow-400 text-black shadow-sm' : 'text-white hover:bg-white/10' }}">
                    👮 Petugas
                </button>
            </div>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <!-- SEARCH -->
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Nama / Email / No HP..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all text-sm">
            
            <!-- TOMBOL TAMBAH PETUGAS (Hanya di Tab Petugas) -->
            @if($activeTab === 'petugas')
                <button wire:click="create" class="bg-yellow-400 hover:bg-yellow-500 text-black font-black px-4 py-2 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none transition-all flex items-center gap-2 whitespace-nowrap text-sm">
                    <span>+</span> Tambah Petugas
                </button>
            @endif

            <!-- TOMBOL EXPORT & FILTER (Hanya di Tab Camaba) -->
            @if($activeTab === 'camaba')
                <select wire:model.live="filterStatus" class="bg-white text-black font-bold text-sm rounded-lg border-2 border-black focus:shadow-neo transition-all py-2 px-3 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="belum_isi">❌ Belum Isi</option>
                    <option value="sudah_isi">✅ Sudah Isi</option>
                </select>

                <button wire:click="exportFiltered" wire:loading.attr="disabled" class="bg-green-500 hover:bg-green-600 text-white font-black px-4 py-2 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none transition-all flex items-center gap-2 whitespace-nowrap text-sm">
                    <span wire:loading.remove wire:target="exportFiltered">📥 Excel</span>
                    <span wire:loading wire:target="exportFiltered">⏳...</span>
                </button>
            @endif
        </div>

    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-pulse flex items-center gap-2">
            <span>✅</span> {{ session('message') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 font-bold shadow-sm animate-pulse flex items-center gap-2">
            <span>🚫</span> {{ session('error') }}
        </div>
    @endif

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-200 text-black border-b-4 border-unmaris-blue">
                    <tr>
                        <th class="p-4 font-black uppercase text-sm">Nama & Email</th>
                        <th class="p-4 font-black uppercase text-sm">Kontak (WA)</th>
                        <th class="p-4 font-black uppercase text-sm text-center">Role / Status</th>
                        <th class="p-4 font-black uppercase text-right text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition group">
                            
                            <!-- Nama & Email -->
                            <td class="p-4 align-top">
                                <div class="font-black text-unmaris-blue text-base md:text-lg">{{ $user->name }}</div>
                                <div class="text-xs font-bold text-gray-500">{{ $user->email }}</div>
                                <div class="text-[10px] text-gray-400 mt-1">Daftar: {{ $user->created_at->format('d M Y') }}</div>
                            </td>

                            <!-- Kontak (WA) -->
                            <td class="p-4 align-top">
                                @if($user->nomor_hp)
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-700 text-sm">{{ $user->nomor_hp }}</span>
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $user->nomor_hp)) }}?text=Halo%20{{ urlencode($user->name) }}..." 
                                           target="_blank" 
                                           class="bg-green-500 text-white p-1 rounded-full hover:bg-green-600 transition shadow-sm border border-green-700"
                                           title="Chat WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada no HP</span>
                                @endif
                            </td>

                            <!-- Role / Status Form -->
                            <td class="p-4 align-top text-center">
                                @if($activeTab === 'camaba')
                                    @if($user->pendaftar)
                                        @php
                                            $statusClass = match($user->pendaftar->status_pendaftaran) {
                                                'lulus' => 'bg-green-100 text-green-800 border-green-500',
                                                'submit' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
                                                'verifikasi' => 'bg-blue-100 text-blue-800 border-blue-500',
                                                default => 'bg-gray-100 text-gray-800 border-gray-400',
                                            };
                                        @endphp
                                        <a href="{{ route('admin.pendaftar.show', $user->pendaftar->id) }}" 
                                           class="inline-block px-2 py-1 {{ $statusClass }} text-[10px] font-black rounded border uppercase hover:scale-105 transition-transform cursor-pointer"
                                           title="Klik untuk lihat detail pendaftaran">
                                            {{ $user->pendaftar->status_pendaftaran == 'submit' ? 'MENUNGGU VERIF' : $user->pendaftar->status_pendaftaran }} ↗
                                        </a>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-[10px] font-black rounded border border-red-500 uppercase animate-pulse">
                                            BELUM ISI FORM
                                        </span>
                                    @endif
                                @else
                                    <!-- Jika Tab Petugas, tampilkan Role -->
                                    <span class="px-3 py-1 bg-gray-800 text-white text-[10px] font-black rounded border border-black uppercase">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 align-top text-right">
                                <div class="flex flex-col gap-2 justify-end">
                                    <button wire:click="edit({{ $user->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg font-bold border-2 border-black shadow-neo-sm hover:shadow-none transition-all text-xs flex items-center justify-center gap-1">
                                        <span>✏️</span> Edit
                                    </button>
                                    <button wire:click="confirmReset({{ $user->id }})" class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1.5 rounded-lg font-bold border-2 border-black shadow-neo-sm hover:shadow-none transition-all text-xs flex items-center justify-center gap-1">
                                        <span>🔑</span> Reset
                                    </button>
                                    <button wire:click="delete({{ $user->id }})" onclick="return confirm('Hapus akun ini selamanya? Data pendaftaran juga akan hilang.')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg font-bold border-2 border-black shadow-neo-sm hover:shadow-none transition-all text-xs flex items-center justify-center gap-1">
                                        <span>🗑️</span> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center font-bold text-gray-400 bg-gray-50">
                                Tidak ada data pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL EDIT/CREATE USER -->
    @if($isEditModalOpen || $isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-white w-full max-w-md rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden relative">
            <div class="bg-blue-500 p-4 border-b-4 border-unmaris-blue flex justify-between items-center text-white">
                <h3 class="font-black text-lg uppercase">
                    {{ $isCreateModalOpen ? '➕ Tambah Petugas' : '✏️ Edit Data User' }}
                </h3>
                <button wire:click="closeModal" class="font-black hover:text-yellow-300 text-xl">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                    @error('name') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Email</label>
                    <input type="email" wire:model="email" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                    @error('email') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">No. HP (WhatsApp)</label>
                    <input type="text" wire:model="nomor_hp" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                    @error('nomor_hp') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                
                <!-- Role Selection (UPDATED: Sembunyikan Admin) -->
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Role / Hak Akses</label>
                    <select wire:model="role" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none bg-white">
                        <option value="camaba">🎓 Calon Mahasiswa</option>
                        <!-- Opsi Admin disembunyikan agar tidak sembarangan dibuat -->
                        {{-- <option value="admin">👮 Super Admin</option> --}} 
                        <option value="keuangan">💸 Staf Keuangan</option>
                        <option value="akademik">🏫 Staf Akademik</option>
                    </select>
                    @error('role') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password (Only for Create) -->
                @if($isCreateModalOpen)
                <div>
                    <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Password Awal</label>
                    <input type="text" wire:model="password" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                    @error('password') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>

            <div class="p-4 bg-gray-100 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800 text-sm">Batal</button>
                <button wire:click="{{ $isCreateModalOpen ? 'store' : 'update' }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-700 transition-all text-sm uppercase">
                    {{ $isCreateModalOpen ? 'Simpan Baru' : 'Simpan Perubahan' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL RESET PASSWORD (Tetap Sama) -->
    @if($confirmingUserReset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up" x-data @keydown.escape.window="$wire.closeModal()">
        <div class="bg-white w-full max-w-md rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden relative">
            <div class="bg-yellow-400 p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-black text-lg uppercase">🔑 Reset Password User</h3>
                <button wire:click="closeModal" class="text-black font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm font-bold text-gray-600 mb-4 bg-blue-50 p-3 rounded border border-blue-200">
                    Masukkan password baru untuk user ini. <br>
                    <span class="text-red-500">Penting:</span> Harap dicatat sebelum disimpan karena tidak bisa dilihat lagi.
                </p>
                <label class="block text-xs font-black text-unmaris-blue mb-1 uppercase">Password Baru</label>
                <input type="text" wire:model="newPassword" placeholder="Min. 8 Karakter" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none text-gray-800">
                @error('newPassword') <span class="text-red-600 text-xs font-bold block mt-1 bg-red-50 p-1 rounded">{{ $message }}</span> @enderror
            </div>
            <div class="p-4 bg-gray-100 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800 text-sm">Batal</button>
                <button wire:click="resetPassword" class="bg-unmaris-blue text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-800 transition-all text-sm uppercase">
                    Simpan Password
                </button>
            </div>
        </div>
    </div>
    @endif

</div>