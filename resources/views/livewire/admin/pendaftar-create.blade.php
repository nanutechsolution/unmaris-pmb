<div class="min-h-screen bg-gray-50/50 pb-24 lg:pb-12 overflow-x-hidden">
    <!-- HEADER MODERN -->
    <div class="bg-white border-b border-gray-200 shadow-sm relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col gap-4 sm:flex-row justify-between sm:items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-100 hidden sm:block">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Registrasi Manual</h1>
                        <p class="text-sm text-gray-500 font-medium">Input data calon mahasiswa dari jalur offline atau bypass.</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.pendaftar.index') }}" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form wire:submit.prevent="save" class="space-y-8">
            
            <!-- SECTION: KREDENSIAL AKUN -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="p-2 bg-white rounded-lg shadow-sm border border-gray-100 text-lg">🔑</span>
                    <h3 class="text-base font-black text-gray-800 uppercase tracking-wide">Akses Login Sistem</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Lengkap Sesuai Identitas <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('name') border-red-500 bg-red-50 @enderror" placeholder="Contoh: MUHAMMAD FARHAN">
                        @error('name') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Alamat Email Aktif <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('email') border-red-500 bg-red-50 @enderror" placeholder="mhs@gmail.com">
                        @error('email') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Password Akun</label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" wire:model.live="auto_generate_password" class="rounded text-indigo-600 focus:ring-0 w-4 h-4 border-gray-300">
                                <span class="text-[10px] font-black text-indigo-600 group-hover:text-indigo-800 transition uppercase tracking-tighter">Auto-Acak</span>
                            </label>
                        </div>
                        <input type="text" wire:model="password" {{ $auto_generate_password ? 'disabled' : '' }} class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 disabled:bg-gray-100 disabled:text-gray-400 @error('password') border-red-500 bg-red-50 @enderror" placeholder="{{ $auto_generate_password ? 'Dibuat otomatis oleh sistem...' : 'Minimal 8 karakter...' }}">
                        @error('password') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION: DATA IDENTITAS -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="p-2 bg-white rounded-lg shadow-sm border border-gray-100 text-lg">👤</span>
                    <h3 class="text-base font-black text-gray-800 uppercase tracking-wide">Identitas Kependudukan</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="nik" maxlength="16" class="w-full rounded-2xl border-gray-200 text-sm font-black tracking-widest focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('nik') border-red-500 bg-red-50 @enderror" placeholder="Wajib 16 digit angka">
                        @error('nik') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">No. WhatsApp / HP Aktif <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold text-sm">+62</span>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="nomor_hp" maxlength="15" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 pl-12 @error('nomor_hp') border-red-500 bg-red-50 @enderror" placeholder="81234...">
                        </div>
                        @error('nomor_hp') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex-1 relative cursor-pointer group">
                                <input type="radio" wire:model="jenis_kelamin" value="L" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-100 rounded-2xl bg-gray-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:shadow-md transition-all flex items-center justify-center gap-3">
                                    <span class="text-xl">👨</span>
                                    <span class="text-sm font-black text-gray-700 peer-checked:text-indigo-900">Laki-laki</span>
                                </div>
                            </label>
                            <label class="flex-1 relative cursor-pointer group">
                                <input type="radio" wire:model="jenis_kelamin" value="P" class="sr-only peer">
                                <div class="p-4 border-2 border-gray-100 rounded-2xl bg-gray-50 peer-checked:border-pink-600 peer-checked:bg-pink-50 peer-checked:shadow-md transition-all flex items-center justify-center gap-3">
                                    <span class="text-xl">👩</span>
                                    <span class="text-sm font-black text-gray-700 peer-checked:text-pink-900">Perempuan</span>
                                </div>
                            </label>
                        </div>
                        @error('jenis_kelamin') <span class="text-[10px] font-bold text-red-500 mt-3 block text-center italic">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION: AKADEMIK & BYPASS -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 border-b border-indigo-700 flex items-center gap-3 shadow-[0_4px_20px_-5px_rgba(79,70,229,0.4)]">
                    <span class="p-2 bg-white/10 rounded-lg text-white text-lg">🎓</span>
                    <h3 class="text-base font-black text-white uppercase tracking-wide">Pengaturan Jalur & Akademik</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Gelombang Masuk <span class="text-red-500">*</span></label>
                        <select wire:model="gelombang_id" class="w-full rounded-2xl border-gray-200 text-sm font-black text-gray-800 focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('gelombang_id') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Pilih Gelombang --</option>
                            @foreach($gelombangs as $gel)
                                <option value="{{ $gel->id }}">{{ $gel->nama_gelombang }} (TA {{ $gel->tahun_akademik }})</option>
                            @endforeach
                        </select>
                        @error('gelombang_id') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Jalur Pendaftaran <span class="text-red-500">*</span></label>
                        <select wire:model="jalur_pendaftaran" class="w-full rounded-2xl border-gray-200 text-sm font-black text-gray-800 focus:ring-indigo-500 focus:border-indigo-500 py-3">
                            <option value="reguler">Reguler / Umum</option>
                            <option value="prestasi">Prestasi Akademik/Non</option>
                            <option value="beasiswa">Beasiswa Penuh / KIP-K</option>
                            <option value="karyawan">Kelas Karyawan / Sore</option>
                            <option value="pindahan">Pindahan / Lanjutan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Program Studi Pilihan <span class="text-red-500">*</span></label>
                        <select wire:model="pilihan_prodi_1" class="w-full rounded-2xl border-gray-200 text-sm font-black text-indigo-700 bg-indigo-50/30 focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('pilihan_prodi_1') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Cari Program Studi --</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->name }}">{{ $prodi->degree }} - {{ $prodi->name }}</option>
                            @endforeach
                        </select>
                        @error('pilihan_prodi_1') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Status Pembayaran <span class="text-red-500">*</span></label>
                        <select wire:model="status_pembayaran" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('status_pembayaran') border-red-500 @enderror {{ $status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-white text-gray-800' }}">
                            <option value="menunggu_pembayaran">🔴 WAJIB BAYAR (Status Default)</option>
                            <option value="lunas">🟢 LUNAS / BYPASS (Jalur Beasiswa/Khusus)</option>
                        </select>
                        @error('status_pembayaran') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                        @if($status_pembayaran === 'lunas')
                            <p class="text-[10px] text-green-600 font-black mt-2 flex items-center gap-1 bg-green-50 p-2 rounded-lg border border-green-100">
                                <span>🚀</span> AKUN AKAN LANGSUNG AKTIF TANPA VERIFIKASI PEMBAYARAN.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- STICKY ACTION BAR -->
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-gray-200 shadow-[0_-10px_30px_-15px_rgba(0,0,0,0.1)] lg:sticky lg:bottom-4 lg:bg-transparent lg:border-t-0 lg:p-0 lg:shadow-none z-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="w-full sm:w-auto bg-white/50 backdrop-blur px-4 py-2 rounded-2xl border border-gray-200 shadow-sm">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" wire:model="send_notification" class="rounded-lg text-indigo-600 shadow-sm focus:ring-0 w-6 h-6 border-gray-300">
                        <div class="min-w-0">
                            <span class="text-sm font-black text-gray-800 block">Kirim Akses ke Pendaftar</span>
                            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Via WhatsApp & Email Otomatis</span>
                        </div>
                    </label>
                </div>

                <button type="submit" 
                        wire:loading.attr="disabled" 
                        class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-sm lg:text-base hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-3 border-b-4 border-indigo-800">
                    <span wire:loading.remove wire:target="save">Daftarkan & Simpan Data</span>
                    <div wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Sedang Memproses...</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>