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
                    <button wire:click="create" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-black font-black px-4 py-3 rounded-xl border-4 border-black shadow-neo-sm active:shadow-none transition-all flex items-center justify-center gap-2 whitespace-nowrap text-xs uppercase">
                        <span>+</span> Camaba
                    </button>

                    <select wire:model.live="filterStatus" class="bg-white text-black font-black text-xs rounded-xl border-4 border-black focus:shadow-neo transition-all py-3 px-3 cursor-pointer outline-none">
                        <option value="">Semua Status</option>
                        <option value="belum_isi">❌ Belum Isi</option>
                        <option value="sudah_isi">✅ Sudah Isi</option>
                        <option value="belum_verifikasi">⚠️ Belum Verif</option>
                    </select>

                    <button wire:click="exportFiltered" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-black px-4 py-3 rounded-xl border-4 border-black shadow-neo-sm active:shadow-none transition-all flex items-center justify-center gap-2 whitespace-nowrap text-xs uppercase text-center">
                        📥 Excel
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- BULK ACTION BAR (POWERFUL) -->
    @if(count($selectedUsers) > 0)
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col md:flex-row items-center gap-4 bg-yellow-400 border-4 border-black p-4 rounded-3xl shadow-neo-lg animate-fade-in-up w-[90%] md:w-auto">
        <div class="flex items-center gap-3">
            <span class="bg-black text-white w-10 h-10 flex items-center justify-center rounded-full font-black">{{ count($selectedUsers) }}</span>
            <span class="font-black uppercase text-xs tracking-tighter">Akun Terpilih</span>
        </div>
        <div class="h-1 w-full md:h-8 md:w-1 bg-black/20 rounded"></div>
        <div class="flex gap-2">
            <button wire:click="verifySelected" wire:confirm="Verifikasi manual semua akun terpilih?" class="bg-blue-600 text-white px-6 py-2 rounded-xl border-2 border-black font-black text-xs uppercase shadow-neo-sm active:shadow-none transition-all hover:bg-blue-700">
                ⚡ Verifikasi Masal
            </button>
            <button wire:click="$set('selectedUsers', [])" class="bg-white text-black px-4 py-2 rounded-xl border-2 border-black font-black text-xs uppercase">
                Batal
            </button>
        </div>
    </div>
    @endif

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
                        <th class="p-4 text-center w-10">
                            <input type="checkbox" wire:model.live="selectAll" class="w-6 h-6 border-4 border-black rounded cursor-pointer accent-unmaris-blue">
                        </th>
                        <th class="p-4 font-black uppercase text-[10px] tracking-widest text-left">Detail User</th>
                        <th class="p-4 font-black uppercase text-[10px] tracking-widest text-center hidden md:table-cell">Kontak</th>
                        <th class="p-4 font-black uppercase text-[10px] tracking-widest text-center">Verifikasi</th>
                        <th class="p-4 font-black uppercase text-[10px] tracking-widest text-center">Status/Role</th>
                        <th class="p-4 font-black uppercase text-[10px] tracking-widest text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-200 bg-white">
                    @forelse($users as $user)
                        <tr class="{{ !$user->email_verified_at ? 'bg-red-50/50' : 'hover:bg-blue-50' }} transition-colors">
                            <td class="p-4 text-center">
                                <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="w-5 h-5 border-2 border-black rounded cursor-pointer accent-unmaris-blue">
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <span class="font-black {{ !$user->email_verified_at ? 'text-red-600' : 'text-unmaris-blue' }} text-sm md:text-base leading-none">
                                            {{ $user->name }}
                                        </span>
                                        @if($user->email_verified_at)
                                            <span class="text-blue-500" title="Terverifikasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-500 mt-1 italic">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-center hidden md:table-cell">
                                @if($user->nomor_hp)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $user->nomor_hp) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-400 border-2 border-black px-3 py-1 rounded-lg font-black text-[10px] shadow-neo-sm hover:shadow-none transition-all">
                                        📞 {{ $user->nomor_hp }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-[10px] italic">N/A</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($user->email_verified_at)
                                    <button wire:click="unverifyEmail({{ $user->id }})" wire:confirm="Batalkan verifikasi email ini?" class="group relative">
                                        <span class="px-3 py-1 bg-blue-100 border-2 border-blue-500 text-blue-700 font-black text-[9px] uppercase rounded-full">OK</span>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-black text-white text-[8px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">Klik untuk Cabut Verif</span>
                                    </button>
                                @else
                                    <button wire:click="verifyEmail({{ $user->id }})" wire:confirm="Verifikasi email ini secara manual?" class="px-3 py-1 bg-red-500 border-2 border-black text-white font-black text-[9px] uppercase rounded-full animate-pulse hover:animate-none">
                                        ⚠️ Verif Sekarang
                                    </button>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($activeTab === 'camaba')
                                    @if($user->pendaftar)
                                        <a href="{{ route('admin.pendaftar.show', $user->pendaftar->id) }}" class="inline-block px-3 py-1 bg-gray-100 border-2 border-black font-black text-[9px] uppercase rounded shadow-neo-sm hover:shadow-none transition-all">
                                            {{ $user->pendaftar->status_pendaftaran }} ↗
                                        </a>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 border-2 border-red-500 text-red-700 font-black text-[9px] uppercase rounded italic">Belum Isi</span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 bg-black text-white font-black text-[9px] uppercase rounded border-2 border-black">
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
                            <td colspan="6" class="p-10 text-center font-black text-gray-400 uppercase tracking-widest bg-gray-50">Data Tidak Ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-black">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL DELETE (CRITICAL WARNING) -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-3xl border-4 border-black shadow-neo-lg overflow-hidden animate-zoom-in">
            <div class="bg-red-500 p-6 text-center border-b-4 border-black">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white border-4 border-black rounded-full shadow-neo-sm text-4xl mb-4">🗑️</div>
                <h3 class="text-white font-black text-xl uppercase italic tracking-tighter">Hapus Akun?</h3>
            </div>
            <div class="p-6 text-center">
                <p class="font-bold text-gray-700 leading-tight">
                    Hapus <span class="bg-yellow-200 px-1 text-black border border-black rounded">{{ $userNameBeingDeleted }}</span>?
                    <span class="block text-red-500 mt-1 text-[10px] uppercase font-black tracking-tighter">DATA PENDAFTARAN AKAN IKUT TERHAPUS!</span>
                </p>
            </div>
            <div class="p-6 pt-0 flex gap-3">
                <button wire:click="closeModal" class="flex-1 py-3 border-4 border-black rounded-2xl font-black uppercase text-sm shadow-neo-sm">Batal</button>
                <button wire:click="delete" class="flex-1 py-3 bg-red-500 border-4 border-black rounded-2xl font-black uppercase text-sm text-white shadow-neo-sm">Hapus!</button>
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
                        @error('nomor_hp') <span class="text-red-600 text-[10px] font-black uppercase ml-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Role</label>
                        <select wire:model="role" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black bg-white outline-none">
                            <option value="camaba">🎓 Camaba</option>
                            <option value="keuangan">💸 Keuangan</option>
                            <option value="akademik">🏫 Akademik</option>
                        </select>
                        @error('role') <span class="text-red-600 text-[10px] font-black uppercase ml-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                @if($isCreateModalOpen)
                <div class="space-y-1">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Password Awal</label>
                    <input type="text" wire:model="password" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none">
                    @error('password') <span class="text-red-600 text-[10px] font-black uppercase ml-1 block">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>

            <div class="p-6 pt-0">
                <button wire:click="{{ $isCreateModalOpen ? 'store' : 'update' }}" class="w-full bg-unmaris-blue text-white py-4 rounded-2xl border-4 border-black font-black uppercase shadow-neo-sm active:translate-y-1 transition-all">
                    Simpan Data
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
                <h3 class="font-black uppercase italic tracking-tight">🔑 Ganti Password</h3>
                <button wire:click="closeModal" class="bg-black text-white w-6 h-6 rounded-full font-black text-xs">&times;</button>
            </div>
            <div class="p-6">
                <label class="text-[10px] font-black uppercase text-gray-400 ml-1">Password Baru</label>
                <input type="text" wire:model="newPassword" placeholder="Min. 8 Karakter" class="w-full border-4 border-black rounded-xl px-4 py-2 font-black focus:bg-yellow-50 outline-none mt-1 text-center">
                @error('newPassword') <span class="text-red-600 text-[10px] font-black uppercase block mt-1 text-center">{{ $message }}</span> @enderror
            </div>
            <div class="p-6 pt-0 flex flex-col gap-2">
                <button wire:click="resetPassword" class="w-full bg-black text-white py-3 rounded-xl border-4 border-black font-black uppercase shadow-neo-sm active:translate-y-1 transition-all">Setel Ulang</button>
            </div>
        </div>
    </div>
    @endif

    <style>
        .shadow-neo { box-shadow: 8px 8px 0px 0px rgba(0,0,0,1); }
        .shadow-neo-sm { box-shadow: 4px 4px 0px 0px rgba(0,0,0,1); }
        .shadow-neo-lg { box-shadow: 12px 12px 0px 0px rgba(0,0,0,1); }
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

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.3s ease-out; }
    </style>

</div>