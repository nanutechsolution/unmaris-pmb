<div class="space-y-6 pb-20 md:pb-10">
    
    <!-- HEADER SECTION -->
    <div class="flex flex-col gap-4 bg-unmaris-blue p-4 rounded-2xl shadow-neo border-4 border-black">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-white font-black text-xl uppercase tracking-tighter flex items-center gap-2">
                <span class="bg-yellow-400 p-1.5 rounded-lg border-2 border-black shadow-neo-sm text-black">👥</span> 
                Manajemen Akun
            </h2>

            <div class="bg-black/20 p-1 rounded-xl flex w-full sm:w-auto">
                <button wire:click="$set('activeTab', 'camaba')" 
                        class="flex-1 sm:px-6 py-2 rounded-lg text-xs font-black uppercase transition-all {{ $activeTab === 'camaba' ? 'bg-yellow-400 text-black shadow-neo-sm border-2 border-black' : 'text-white hover:bg-white/10' }}">
                    🎓 Camaba
                </button>
                <button wire:click="$set('activeTab', 'petugas')" 
                        class="flex-1 sm:px-6 py-2 rounded-lg text-xs font-black uppercase transition-all {{ $activeTab === 'petugas' ? 'bg-yellow-400 text-black shadow-neo-sm border-2 border-black' : 'text-white hover:bg-white/10' }}">
                    👮 Petugas
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- SEARCH -->
            <div class="md:col-span-6 lg:col-span-7">
                <input wire:model.live.debounce="search" type="text" placeholder="Cari Nama / Email / No HP..." 
                       class="w-full bg-white border-4 border-black rounded-xl px-4 py-3 font-bold focus:shadow-neo transition-all outline-none text-sm">
            </div>
            
            <!-- ACTIONS -->
            <div class="md:col-span-6 lg:col-span-5 flex gap-2 overflow-x-auto pb-2 md:pb-0">
                @if($activeTab === 'petugas')
                    <button wire:click="create" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-black font-black px-4 py-3 rounded-xl border-4 border-black shadow-neo-sm active:shadow-none transition-all flex items-center justify-center gap-2 whitespace-nowrap text-xs uppercase">
                        <span>+</span> Petugas
                    </button>
                @endif

                @if($activeTab === 'camaba')
                    <select wire:model.live="filterStatus" class="bg-white text-black font-black text-xs rounded-xl border-4 border-black focus:shadow-neo transition-all py-3 px-3 cursor-pointer outline-none">
                        <option value="">Semua Status</option>
                        <option value="belum_isi">❌ Belum Isi</option>
                        <option value="sudah_isi">✅ Sudah Isi</option>
                    </select>

                    <button wire:click="exportFiltered" wire:target="exportFiltered" wire:loading.attr="disabled" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-black px-4 py-3 rounded-xl border-4 border-black shadow-neo-sm active:shadow-none transition-all flex items-center justify-center gap-2 whitespace-nowrap text-xs uppercase">
                        <span wire:loading.remove wire:target="exportFiltered">📥 Excel</span>
                        <span wire:loading wire:target="exportFiltered">⌛...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if (session()->has('message'))
        <div class="bg-green-400 border-4 border-black p-4 font-black shadow-neo-sm animate-bounce-short flex items-center gap-3 rounded-xl">
            <span class="text-2xl">✅</span> {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-400 border-4 border-black p-4 font-black shadow-neo-sm flex items-center gap-3 rounded-xl text-white">
            <span class="text-2xl">🚫</span> {{ session('error') }}
        </div>
    @endif

    <!-- TABLE AREA -->
    <div class="bg-white border-4 border-black shadow-neo rounded-2xl overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="min-w-full divide-y-4 divide-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 font-black uppercase text-xs tracking-widest text-left">User Detail</th>
                        <th class="p-4 font-black uppercase text-xs tracking-widest text-center hidden md:table-cell">Kontak</th>
                        <th class="p-4 font-black uppercase text-xs tracking-widest text-center">Status/Role</th>
                        <th class="p-4 font-black uppercase text-xs tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-200 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="font-black text-unmaris-blue text-sm md:text-base leading-none">{{ $user->name }}</span>
                                    <span class="text-[10px] md:text-xs font-bold text-gray-500 mt-1 italic">{{ $user->email }}</span>
                                    <div class="md:hidden mt-2 flex items-center gap-2">
                                         <span class="text-[10px] font-black bg-gray-200 px-2 py-0.5 rounded border border-black uppercase">{{ $user->nomor_hp ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center hidden md:table-cell">
                                @if($user->nomor_hp)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $user->nomor_hp) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-400 border-2 border-black px-3 py-1 rounded-lg font-black text-xs shadow-neo-sm hover:shadow-none transition-all">
                                        <span>📞</span> {{ $user->nomor_hp }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs italic">N/A</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($activeTab === 'camaba')
                                    @if($user->pendaftar)
                                        @php
                                            $statusClass = match($user->pendaftar->status_pendaftaran) {
                                                'lulus' => 'bg-green-100 border-green-500 text-green-700',
                                                'submit', 'verifikasi' => 'bg-blue-100 border-blue-500 text-blue-700',
                                                'gagal' => 'bg-red-100 border-red-500 text-red-700',
                                                default => 'bg-gray-100 border-gray-400 text-gray-700',
                                            };
                                        @endphp
                                        <a href="{{ route('admin.pendaftar.show', $user->pendaftar->id) }}" class="inline-block px-3 py-1 {{ $statusClass }} border-2 font-black text-[10px] uppercase rounded-lg shadow-sm hover:scale-105 transition-transform">
                                            {{ $user->pendaftar->status_pendaftaran }} ↗
                                        </a>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 border-2 border-red-500 text-red-700 font-black text-[9px] uppercase rounded-lg animate-pulse">Belum Isi</span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 bg-black text-white font-black text-[10px] uppercase rounded border-2 border-black shadow-neo-sm">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-1.5 md:gap-2">
                                    <button wire:click="edit({{ $user->id }})" class="p-2 bg-blue-400 border-2 border-black rounded-lg shadow-neo-sm hover:shadow-none transition-all" title="Edit">
                                        <span>✏️</span>
                                    </button>
                                    <button wire:click="confirmReset({{ $user->id }})" class="p-2 bg-yellow-400 border-2 border-black rounded-lg shadow-neo-sm hover:shadow-none transition-all" title="Reset Password">
                                        <span>🔑</span>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $user->id }})" class="p-2 bg-red-500 border-2 border-black rounded-lg shadow-neo-sm hover:shadow-none transition-all" title="Hapus">
                                        <span>🗑️</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center font-black text-gray-400 uppercase tracking-widest bg-gray-50">Data Tidak Ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-black">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL DELETE (MODAL BARU DENGAN WARNING KRUSIAL) -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-3xl border-4 border-black shadow-neo-lg overflow-hidden animate-zoom-in">
            <div class="bg-red-500 p-6 text-center border-b-4 border-black">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white border-4 border-black rounded-full shadow-neo-sm text-4xl mb-4">
                    🗑️
                </div>
                <h3 class="text-white font-black text-xl uppercase italic tracking-tighter">Konfirmasi Hapus</h3>
            </div>
            <div class="p-6 text-center">
                <p class="font-bold text-gray-700 leading-tight">
                    Hapus akun <span class="bg-yellow-200 px-1 text-black border border-black rounded">{{ $userNameBeingDeleted }}</span> selamanya?
                    <span class="block text-red-500 mt-1 text-[10px] uppercase font-black tracking-tighter">Tindakan ini tidak dapat dibatalkan!</span>
                </p>

                <!-- KRITIKAL WARNING BERDASARKAN STATUS PENDAFTAR -->
                @php
                    $targetUser = \App\Models\User::with('pendaftar')->find($userIdBeingDeleted);
                @endphp

                @if($targetUser && $targetUser->pendaftar)
                    @php
                        $p = $targetUser->pendaftar;
                        $isLulus = $p->status_pendaftaran === 'lulus';
                        $isLunas = $p->status_pembayaran === 'lunas';
                        $hasFiles = $p->foto_path || $p->ktp_path || $p->ijazah_path || $p->bukti_pembayaran;
                    @endphp

                    @if($isLulus || $isLunas || $hasFiles)
                    <div class="mt-4 p-3 bg-yellow-100 border-2 border-yellow-500 rounded-xl text-left animate-pulse">
                        <div class="flex items-center gap-2 text-yellow-700 font-black text-[10px] uppercase mb-1">
                            <span>⚠️</span> Warning Krusial:
                        </div>
                        <ul class="text-[9px] font-bold text-yellow-800 list-disc ml-4 space-y-0.5">
                            @if($isLulus) <li>User ini sudah berstatus <span class="bg-green-500 text-white px-1">LULUS</span></li> @endif
                            @if($isLunas) <li>Sudah melakukan <span class="bg-blue-500 text-white px-1">PEMBAYARAN LUNAS</span></li> @endif
                            @if($hasFiles) <li>Sudah <span class="underline">MENGUNGGAH BERKAS</span> / Bukti Bayar</li> @endif
                        </ul>
                    </div>
                    @endif
                @endif
            </div>
            
            <div class="p-6 pt-0 flex gap-3">
                <button wire:click="closeModal" class="flex-1 py-3 border-4 border-black rounded-2xl font-black uppercase text-sm shadow-neo-sm hover:shadow-none transition-all active:translate-y-1">
                    Batal
                </button>
                <button wire:click="delete" class="flex-1 py-3 bg-red-500 border-4 border-black rounded-2xl font-black uppercase text-sm text-white shadow-neo-sm hover:shadow-none transition-all active:translate-y-1">
                    Hapus!
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL CREATE/EDIT -->
    @if($isEditModalOpen || $isCreateModalOpen)
    <div class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white w-full max-w-md rounded-3xl border-4 border-black shadow-neo-lg my-auto animate-fade-in-up">
            <div class="bg-blue-500 p-4 border-b-4 border-black flex justify-between items-center text-white">
                <h3 class="font-black uppercase italic tracking-tight">{{ $isCreateModalOpen ? '➕ Akun Baru' : '✏️ Edit Akun' }}</h3>
                <button wire:click="closeModal" class="bg-black text-white w-8 h-8 rounded-full border-2 border-white font-black">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none">
                    @error('name') <span class="text-red-600 text-[10px] font-black uppercase ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none">
                    @error('email') <span class="text-red-600 text-[10px] font-black uppercase ml-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-1">No. HP (WA)</label>
                        <input type="text" wire:model="nomor_hp" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none">
                        @error('nomor_hp') <span class="text-red-600 text-[10px] font-black uppercase ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Role</label>
                        <select wire:model="role" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black bg-white outline-none">
                            <option value="camaba">🎓 Camaba</option>
                            <option value="keuangan">💸 Keuangan</option>
                            <option value="akademik">🏫 Akademik</option>
                            @if($isEditModalOpen && $role === 'admin') <option value="admin">👮 Admin</option> @endif
                        </select>
                    </div>
                </div>
                @if($isCreateModalOpen)
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Password Awal</label>
                    <input type="text" wire:model="password" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none">
                    @error('password') <span class="text-red-600 text-[10px] font-black uppercase ml-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>

            <div class="p-6 pt-0">
                <button wire:click="{{ $isCreateModalOpen ? 'store' : 'update' }}" class="w-full bg-unmaris-blue text-white py-4 rounded-2xl border-4 border-black font-black uppercase shadow-neo-sm hover:shadow-none active:translate-y-1 transition-all">
                    {{ $isCreateModalOpen ? 'Simpan Data Baru' : 'Perbarui Data' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL RESET PASSWORD -->
    @if($confirmingUserReset)
    <div class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-3xl border-4 border-black shadow-neo-lg overflow-hidden animate-zoom-in">
            <div class="bg-yellow-400 p-6 border-b-4 border-black flex justify-between items-center">
                <h3 class="font-black uppercase italic italic tracking-tight">🔑 Ganti Password</h3>
                <button wire:click="closeModal" class="bg-black text-white w-6 h-6 rounded-full font-black text-xs">&times;</button>
            </div>
            <div class="p-6">
                <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Password Baru</label>
                <input type="text" wire:model="newPassword" placeholder="Min. 8 Karakter" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none mt-1 text-center">
                @error('newPassword') <span class="text-red-600 text-[10px] font-black uppercase block mt-1 text-center">{{ $message }}</span> @enderror
            </div>
            <div class="p-6 pt-0 flex flex-col gap-2">
                <button wire:click="resetPassword" class="w-full bg-black text-white py-3 rounded-xl border-4 border-black font-black uppercase shadow-neo-sm hover:shadow-none transition-all">
                    Setel Ulang
                </button>
                <button wire:click="closeModal" class="w-full font-bold text-gray-500 text-xs uppercase">Batal</button>
            </div>
        </div>
    </div>
    @endif


<style>
    .shadow-neo { shadow: 8px 8px 0px 0px rgba(0,0,0,1); }
    .shadow-neo-sm { shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
    .shadow-neo-lg { shadow: 12px 12px 0px 0px rgba(0,0,0,1); }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce-short { animation: bounce-short 1s ease-in-out infinite; }
    
    @keyframes zoom-in {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in { animation: zoom-in 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>

</div>
