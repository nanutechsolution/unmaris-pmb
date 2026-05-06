<div class="space-y-6 pb-24 md:pb-10 max-w-7xl mx-auto font-sans text-gray-800">

    <!-- HEADER SECTION -->
    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col gap-5">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-gray-900 font-bold text-xl md:text-2xl tracking-tight flex items-center gap-2">
                    <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    Manajemen Akun
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data pengguna, petugas, dan calon mahasiswa.</p>
            </div>

            <!-- Segemented Control Tabs -->
            <div class="bg-gray-100/80 p-1 rounded-xl flex w-full md:w-auto overflow-hidden">
                <button wire:click="$set('activeTab', 'camaba')"
                    class="flex-1 md:w-32 py-2 md:py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ $activeTab === 'camaba' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    🎓 Camaba
                </button>
                <button wire:click="$set('activeTab', 'petugas')"
                    class="flex-1 md:w-32 py-2 md:py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ $activeTab === 'petugas' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    👮 Petugas
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 pt-2 border-t border-gray-100">
            <!-- SEARCH -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce="search" type="text" placeholder="Cari Nama / Email / No HP..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
            </div>

            <!-- ACTIONS -->
            <div class="lg:col-span-7 flex flex-wrap sm:flex-nowrap gap-2 w-full">
                @if($activeTab === 'petugas')
                    <button wire:click="create" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Petugas
                    </button>
                @endif

                @if($activeTab === 'camaba')
                    <button wire:click="create" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Camaba
                    </button>

                    <select wire:model.live="filterStatus" class="flex-1 bg-white text-gray-700 font-medium text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all py-2.5 px-3 cursor-pointer outline-none shadow-sm min-w-[140px]">
                        <option value="">Semua Status</option>
                        <option value="belum_isi">❌ Belum Isi</option>
                        <option value="sudah_isi">✅ Sudah Isi</option>
                        <option value="belum_verifikasi">⚠️ Belum Verif</option>
                    </select>

                    <button wire:click="exportFiltered" class="w-full sm:w-auto bg-green-50 text-green-700 hover:bg-green-100 hover:text-green-800 border border-green-200 font-medium px-4 py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export Excel
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if (session()->has('message'))
    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl flex items-center gap-3 text-sm font-medium shadow-sm animate-fade-in-down">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('message') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl flex items-center gap-3 text-sm font-medium shadow-sm animate-fade-in-down">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- TABLE AREA (Mobile Optimized) -->
    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
        <!-- Overflow X auto agar bisa di-scroll ke samping di HP tanpa merusak layout luar -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="p-4 text-center w-12 whitespace-nowrap">
                            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer transition-colors">
                        </th>
                        <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-left whitespace-nowrap">Detail User</th>
                        <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-left hidden md:table-cell whitespace-nowrap">Kontak</th>
                        <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center whitespace-nowrap">Verifikasi</th>
                        <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center whitespace-nowrap">Status/Role</th>
                        <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right whitespace-nowrap">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ !$user->email_verified_at ? 'bg-red-50/30' : '' }}">
                        <td class="p-4 text-center">
                            <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer transition-colors">
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-sm {{ !$user->email_verified_at ? 'text-gray-900' : 'text-gray-900' }}">
                                        {{ $user->name }}
                                    </span>
                                    @if($user->email_verified_at)
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" title="Terverifikasi" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</span>
                                <!-- Tampilkan no HP di mobile saja -->
                                <span class="text-xs text-gray-500 mt-1 md:hidden flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $user->nomor_hp ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4 text-left hidden md:table-cell whitespace-nowrap">
                            @if($user->nomor_hp)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $user->nomor_hp) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-green-600 bg-gray-100 hover:bg-green-50 px-2.5 py-1 rounded-md transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                {{ $user->nomor_hp }}
                            </a>
                            @else
                            <span class="text-gray-400 text-xs italic">N/A</span>
                            @endif
                        </td>
                        <td class="p-4 text-center whitespace-nowrap">
                            @if($user->email_verified_at)
                            <button wire:click="unverifyEmail({{ $user->id }})" wire:confirm="Batalkan verifikasi email ini?" class="group relative px-2.5 py-1 bg-green-50 text-green-700 font-medium text-[11px] uppercase rounded-full hover:bg-red-50 hover:text-red-600 transition-colors">
                                <span class="group-hover:hidden">Terverifikasi</span>
                                <span class="hidden group-hover:block">Cabut Verif</span>
                            </button>
                            @else
                            <button wire:click="verifyEmail({{ $user->id }})" wire:confirm="Verifikasi email ini secara manual?" class="px-2.5 py-1 bg-red-50 text-red-600 hover:bg-red-100 font-medium text-[11px] uppercase rounded-full transition-colors inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Verifikasi
                            </button>
                            @endif
                        </td>
                        <td class="p-4 text-center whitespace-nowrap">
                            @if($activeTab === 'camaba')
                                @if($user->pendaftar)
                                <a href="{{ route('admin.pendaftar.show', $user->pendaftar->id) }}" class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium text-[11px] uppercase rounded-md transition-colors">
                                    {{ str_replace('_', ' ', $user->pendaftar->status_pendaftaran) }} ↗
                                </a>
                                @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 font-medium text-[11px] uppercase rounded-md">Belum Isi</span>
                                @endif
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 font-medium text-[11px] uppercase rounded-md">
                                    {{ $user->role }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $user->id }})" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="confirmReset({{ $user->id }})" class="p-1.5 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Reset Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </button>
                                @if (Auth::user()->role === 'admin')
                                <button wire:click="openDeleteModal({{ $user->id }})" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm font-medium">Data Tidak Ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- BULK ACTION BAR (Floating Bottom Card) -->
    @if(count($selectedUsers) > 0)
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[50] flex flex-col md:flex-row items-center gap-3 bg-gray-900 border border-gray-700 p-3 md:px-5 md:py-3 rounded-2xl shadow-xl animate-fade-in-up w-[90%] md:w-auto text-white">
        <div class="flex items-center gap-3">
            <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm">{{ count($selectedUsers) }}</span>
            <span class="font-medium text-sm text-gray-200">Akun Terpilih</span>
        </div>
        <div class="hidden md:block h-5 w-px bg-gray-700"></div>
        <div class="flex gap-2 w-full md:w-auto mt-2 md:mt-0">
            <button wire:click="verifySelected" wire:confirm="Verifikasi manual semua akun terpilih?" class="flex-1 md:flex-none bg-blue-600 text-white px-4 py-2.5 md:py-2 rounded-xl text-sm font-medium hover:bg-blue-500 transition-colors whitespace-nowrap">
                Verifikasi Masal
            </button>
            <button wire:click="$set('selectedUsers', [])" class="flex-1 md:flex-none bg-gray-800 text-gray-300 px-4 py-2.5 md:py-2 rounded-xl text-sm font-medium hover:bg-gray-700 transition-colors whitespace-nowrap">
                Batal
            </button>
        </div>
    </div>
    @endif

    <!-- MODAL DELETE -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden animate-zoom-in">
            <div class="bg-red-50 p-6 flex flex-col items-center border-b border-red-100 text-center">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-red-800 font-bold text-lg">Hapus Akun?</h3>
            </div>
            <div class="p-6 text-center">
                <p class="text-sm text-gray-600">
                    Anda yakin ingin menghapus <span class="font-bold text-gray-900">{{ $userNameBeingDeleted }}</span>?
                </p>
                <p class="text-xs text-red-500 font-medium mt-2 bg-red-50 py-1 px-2 rounded">Semua data pendaftaran akun ini akan ikut terhapus permanen!</p>
            </div>
            <div class="p-4 bg-gray-50 flex gap-3 border-t border-gray-100">
                <button wire:click="closeModal" class="flex-1 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-50 transition-colors">Batal</button>
                <button wire:click="delete" class="flex-1 py-2.5 bg-red-600 border border-transparent text-white rounded-xl font-medium text-sm hover:bg-red-700 transition-colors">Hapus Permanen</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL CREATE/EDIT -->
    @if($isEditModalOpen || $isCreateModalOpen)
    <div class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl my-auto animate-fade-in-up border border-gray-200">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    @if($isCreateModalOpen)
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Akun Baru
                    @else
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Akun
                    @endif
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">No. HP (WA)</label>
                        <input type="text" wire:model="nomor_hp" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        @error('nomor_hp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Role</label>
                        <select wire:model="role" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white outline-none transition-all">
                            <option value="camaba">🎓 Camaba</option>
                            <option value="keuangan">💸 Keuangan</option>
                            <option value="akademik">🏫 Akademik</option>
                        </select>
                        @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                @if($isCreateModalOpen)
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Password Awal</label>
                    <input type="text" wire:model="password" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                <button wire:click="{{ $isCreateModalOpen ? 'store' : 'update' }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-medium shadow-sm transition-all flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL RESET PASSWORD -->
    @if($confirmingUserReset)
    <div class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm transition-opacity">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden animate-zoom-in border border-gray-200">
            <div class="bg-yellow-50 p-5 border-b border-yellow-100 flex justify-between items-center">
                <h3 class="font-bold text-yellow-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Ganti Password
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide text-center">Password Baru</label>
                <input type="text" wire:model="newPassword" placeholder="Min. 8 Karakter" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none text-center transition-all">
                @error('newPassword') <span class="text-red-500 text-xs mt-2 block text-center">{{ $message }}</span> @enderror
            </div>
            <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex flex-col gap-2">
                <button wire:click="resetPassword" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2.5 rounded-xl font-medium shadow-sm transition-all">Setel Ulang</button>
            </div>
        </div>
    </div>
    @endif

</div>