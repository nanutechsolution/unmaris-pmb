<div class="space-y-6">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center bg-unmaris-blue p-4 rounded-xl shadow-neo border-2 border-black">
        <h2 class="text-white font-black text-xl uppercase tracking-wider">
            👥 Manajemen Akun Camaba
        </h2>
        <div class="w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Nama / Email..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-200 text-black border-b-4 border-unmaris-blue">
                    <tr>
                        <th class="p-4 font-black uppercase">Nama & Email</th>
                        <th class="p-4 font-black uppercase">Terdaftar Sejak</th>
                        <th class="p-4 font-black uppercase text-center">Status Form</th>
                        <th class="p-4 font-black uppercase text-right">Aksi Akun</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-4">
                                <div class="font-black text-unmaris-blue text-lg">{{ $user->name }}</div>
                                <div class="text-sm font-bold text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="p-4 font-medium text-gray-600">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                @if($user->pendaftar)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-black rounded border border-green-500">
                                        SUDAH ISI
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-black rounded border border-red-500">
                                        BELUM ISI
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="confirmReset({{ $user->id }})" class="bg-yellow-400 text-black px-3 py-1 rounded font-bold border-2 border-black shadow-neo-sm hover:shadow-none transition-all text-xs mr-2">
                                    🔑 Reset Pass
                                </button>
                                <button wire:click="delete({{ $user->id }})" onclick="return confirm('Hapus akun ini selamanya? Data pendaftaran juga akan hilang.')" class="bg-red-500 text-white px-3 py-1 rounded font-bold border-2 border-black shadow-neo-sm hover:shadow-none transition-all text-xs">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center font-bold text-gray-400">
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-unmaris-blue">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    @if($confirmingUserReset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm animate-fade-in-up">
        <div class="bg-white w-full max-w-md rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden">
            <div class="bg-yellow-400 p-4 border-b-4 border-unmaris-blue flex justify-between items-center">
                <h3 class="font-black text-black text-lg uppercase">🔑 Reset Password User</h3>
                <button wire:click="closeModal" class="text-black font-black hover:text-red-600 text-xl">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm font-bold text-gray-600 mb-4">Masukkan password baru untuk user ini. Harap dicatat sebelum disimpan.</p>
                
                <label class="block text-sm font-black text-unmaris-blue mb-1">Password Baru</label>
                <input type="text" wire:model="newPassword" placeholder="Min. 8 Karakter" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 font-bold focus:shadow-neo transition-all outline-none">
                @error('newPassword') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="p-4 bg-gray-100 border-t-4 border-unmaris-blue flex justify-end gap-2">
                <button wire:click="closeModal" class="px-4 py-2 font-bold text-gray-600 hover:text-gray-800">Batal</button>
                <button wire:click="resetPassword" class="bg-unmaris-blue text-white px-6 py-2 rounded-lg font-black border-2 border-black shadow-neo-sm hover:shadow-none hover:bg-blue-800 transition-all">
                    SIMPAN PASSWORD
                </button>
            </div>
        </div>
    </div>
    @endif

</div>