<div class="min-h-screen bg-gray-50/50 pb-24 lg:pb-12 overflow-x-hidden">
    <!-- HEADER MODERN -->
    <div class="bg-white border-b border-gray-200 shadow-sm relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col gap-4 sm:flex-row justify-between sm:items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-100 hidden sm:block">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Form Registrasi Offline</h1>
                        <p class="text-sm text-gray-500 font-medium flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 text-blue-700 uppercase tracking-widest">Input Manual</span>
                            Sesuai dengan lembar formulir pendaftaran fisik mahasiswa.
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.pendaftar.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Batal & Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- SECTION: KREDENSIAL AKUN -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="p-2 bg-white rounded-lg shadow-sm border border-gray-100 text-lg">🔑</span>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Akses Login (Otomatis Diverifikasi)</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Calon Mahasiswa <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 uppercase @error('name') border-red-500 bg-red-50 @enderror" placeholder="Sesuai Formulir Fisik">
                        @error('name') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Email Pendaftar <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('email') border-red-500 bg-red-50 @enderror" placeholder="email@gmail.com">
                        @error('email') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Kata Sandi (Password)</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.live="auto_generate_password" class="rounded text-indigo-600 focus:ring-0 w-4 h-4 border-gray-300">
                                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-tighter">Acak Otomatis</span>
                            </label>
                        </div>
                        <input type="text" wire:model="password" {{ $auto_generate_password ? 'disabled' : '' }} class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 disabled:bg-gray-100 disabled:text-gray-400 @error('password') border-red-500 bg-red-50 @enderror" placeholder="{{ $auto_generate_password ? 'Dibuat otomatis oleh sistem...' : 'Minimal 8 karakter...' }}">
                        @error('password') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION B: DATA CALON MAHASISWA -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="p-2 bg-white rounded-lg shadow-sm border border-gray-100 text-lg">👤</span>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">B. Data Calon Mahasiswa</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">2. NIK KTP/KK <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="nik" maxlength="16" class="w-full rounded-2xl border-gray-200 text-sm font-black tracking-widest focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('nik') border-red-500 bg-red-50 @enderror" placeholder="16 Digit Angka NIK">
                        @error('nik') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">3. Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="tempat_lahir" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('tempat_lahir') border-red-500 bg-red-50 @enderror" placeholder="Kota Kelahiran">
                        @error('tempat_lahir') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tgl. Lahir <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="tgl_lahir" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('tgl_lahir') border-red-500 bg-red-50 @enderror">
                        @error('tgl_lahir') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">4. Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select wire:model="jenis_kelamin" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Agama <span class="text-red-500">*</span></label>
                        <select wire:model="agama" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('agama') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Pilih Agama --</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen Protestan</option>
                            <option value="Katolik">Kristen Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Buddha</option>
                            <option value="Khonghucu">Khonghucu</option>
                        </select>
                        @error('agama') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">No. HP / WA <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="nomor_hp" maxlength="15" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('nomor_hp') border-red-500 bg-red-50 @enderror" placeholder="081234...">
                        @error('nomor_hp') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">5. Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea wire:model="alamat" rows="2" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 focus:border-indigo-500 @error('alamat') border-red-500 bg-red-50 @enderror" placeholder="Alamat jalan, RT/RW, Desa, Kecamatan, Kab/Kota"></textarea>
                        @error('alamat') <span class="text-[10px] font-bold text-red-500 mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">7. Asal Sekolah / NPSN <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="asal_sekolah" class="w-full rounded-2xl border-gray-200 text-sm font-black py-3 focus:ring-indigo-500 @error('asal_sekolah') border-red-500 bg-red-50 @enderror" placeholder="Contoh: SMA Negeri 1 Tambolaka">
                        @error('asal_sekolah') <span class="text-[10px] font-bold text-red-500 mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">8. NISN (Opsional)</label>
                        <input type="text" inputmode="numeric" wire:model="nisn" class="w-full rounded-2xl border-gray-200 text-sm font-black py-3 focus:ring-indigo-500 @error('nisn') border-red-500 bg-red-50 @enderror" placeholder="Nomor Induk Siswa Nasional">
                        @error('nisn') <span class="text-[10px] font-bold text-red-500 mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 11: DATA ORANG TUA -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="p-2 bg-white rounded-lg shadow-sm border border-gray-100 text-lg">👨‍👩‍👦</span>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">11. Data Orang Tua</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Seksi Ayah -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">1. Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_ayah" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500 @error('nama_ayah') border-red-500 @enderror" placeholder="Nama Lengkap Ayah">
                            @error('nama_ayah') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">2. NIK Ayah</label>
                                <input type="text" inputmode="numeric" maxlength="16" wire:model="nik_ayah" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500 @error('nik_ayah') border-red-500 @enderror" placeholder="16 Digit">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">4. Pend. Ayah</label>
                                <select wire:model="pendidikan_ayah" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500">
                                    <option value="">- Pilih -</option>
                                    <option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA">SMA</option><option value="S1">S1/S2</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">3. Pekerjaan Ayah</label>
                            <input type="text" wire:model="pekerjaan_ayah" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500" placeholder="Contoh: Petani, PNS, Wiraswasta">
                        </div>
                    </div>

                    <!-- Seksi Ibu -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">5. Nama Ibu <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_ibu" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500 @error('nama_ibu') border-red-500 @enderror" placeholder="Nama Lengkap Ibu">
                            @error('nama_ibu') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">6. NIK Ibu</label>
                                <input type="text" inputmode="numeric" maxlength="16" wire:model="nik_ibu" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500 @error('nik_ibu') border-red-500 @enderror" placeholder="16 Digit">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">8. Pend. Ibu</label>
                                <select wire:model="pendidikan_ibu" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500">
                                    <option value="">- Pilih -</option>
                                    <option value="SD">SD</option><option value="SMP">SMP</option><option value="SMA">SMA</option><option value="S1">S1/S2</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">7. Pekerjaan Ibu</label>
                            <input type="text" wire:model="pekerjaan_ibu" class="w-full rounded-2xl border-gray-200 text-sm font-black py-2.5 focus:ring-indigo-500" placeholder="Contoh: Ibu Rumah Tangga, Guru">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION A & 10: JALUR & PRODI -->
            <div class="bg-indigo-50 rounded-3xl shadow-sm border-2 border-indigo-100 overflow-hidden">
                <div class="bg-indigo-600 px-6 py-4 flex items-center gap-3">
                    <span class="text-lg">🎓</span>
                    <h3 class="text-sm font-black text-white uppercase tracking-widest">A. Pilihan Kelas & 10. Program Studi</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1.5">Gelombang PMB <span class="text-red-500">*</span></label>
                        <select wire:model="gelombang_id" class="w-full rounded-2xl border-indigo-200 text-sm font-black text-gray-800 focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('gelombang_id') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Pilih Gelombang --</option>
                            @foreach($gelombangs as $gel)
                                <option value="{{ $gel->id }}">{{ $gel->nama_gelombang }} ({{ $gel->tahun_akademik }})</option>
                            @endforeach
                        </select>
                        @error('gelombang_id') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1.5">Pilihan Kelas (Jalur) <span class="text-red-500">*</span></label>
                        <select wire:model="jalur_pendaftaran" class="w-full rounded-2xl border-indigo-200 text-sm font-black focus:ring-indigo-500 py-3">
                            <option value="reguler">1) Reguler / Umum</option>
                            <option value="karyawan">2) Non Reguler / Ext</option>
                            <option value="pindahan">3) Transfer / Pindahan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1.5">10. Program Studi Pilihan 1 <span class="text-red-500">*</span></label>
                        <select wire:model="pilihan_prodi_1" class="w-full rounded-2xl border-indigo-200 text-sm font-black text-indigo-800 bg-white focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('pilihan_prodi_1') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Prodi Utama --</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->name }}">{{ $prodi->degree }} - {{ $prodi->name }}</option>
                            @endforeach
                        </select>
                        @error('pilihan_prodi_1') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1.5">Program Studi Pilihan 2 (Opsional)</label>
                        <select wire:model="pilihan_prodi_2" class="w-full rounded-2xl border-indigo-200 text-sm font-black text-gray-700 bg-white focus:ring-indigo-500 focus:border-indigo-500 py-3 @error('pilihan_prodi_2') border-red-500 bg-red-50 @enderror">
                            <option value="">-- Prodi Alternatif --</option>
                            @foreach($prodiList as $prodi)
                                <option value="{{ $prodi->name }}">{{ $prodi->degree }} - {{ $prodi->name }}</option>
                            @endforeach
                        </select>
                        @error('pilihan_prodi_2') <span class="text-[10px] font-bold text-red-500 mt-2 block ml-1 italic">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION REFERRAL (GUNTING DISINI) -->
            <div class="bg-white rounded-3xl shadow-sm border-2 border-dashed border-gray-400 overflow-hidden relative mt-8">
                <div class="absolute -top-3 right-6 bg-gray-50 px-2 text-xs font-bold text-gray-500 italic">✂️ Gunting Disini (Data Referral)</div>
                <div class="bg-gray-100/50 px-6 py-4 flex items-center gap-3">
                    <span class="text-lg">🤝</span>
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Informasi Prospek / Referral</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nama Prospek (Referral)</label>
                        <input type="text" wire:model="nama_referensi" class="w-full rounded-2xl border-gray-200 text-sm font-black py-3 focus:ring-indigo-500" placeholder="Siapa yang merekomendasikan?">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">No. HP Prospek</label>
                        <input type="text" inputmode="numeric" wire:model="nomor_hp_referensi" class="w-full rounded-2xl border-gray-200 text-sm font-black py-3 focus:ring-indigo-500" placeholder="08...">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Status Pembayaran Calon Mahasiswa <span class="text-red-500">*</span></label>
                        <select wire:model="status_pembayaran" class="w-full rounded-2xl border-gray-200 text-sm font-black focus:ring-indigo-500 py-3 @if($status_pembayaran === 'lunas') bg-green-50 text-green-700 border-green-200 @endif">
                            <option value="belum_bayar">Belum Bayar (Status Default)</option>
                            <option value="lunas">✅ LUNAS / Bypass Verifikasi</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- STICKY ACTION BAR -->
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 backdrop-blur-md border-t border-gray-200 shadow-[0_-10px_30px_-15px_rgba(0,0,0,0.1)] lg:sticky lg:bottom-4 lg:bg-transparent lg:border-t-0 lg:p-0 lg:shadow-none z-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="w-full sm:w-auto bg-white/50 backdrop-blur px-4 py-2 rounded-2xl border border-gray-200 shadow-sm">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" wire:model="send_notification" class="rounded-lg text-indigo-600 shadow-sm focus:ring-0 w-6 h-6 border-gray-300">
                        <div class="min-w-0">
                            <span class="text-sm font-black text-gray-800 block leading-none mb-1">Kirim Akses Portal</span>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter">Via WhatsApp & Email Otomatis</span>
                        </div>
                    </label>
                </div>

                <button type="submit" 
                        wire:loading.attr="disabled" 
                        class="w-full sm:w-auto bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-sm lg:text-base hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-3 border-b-4 border-indigo-800">
                    <span wire:loading.remove wire:target="save">Simpan Formulir Offline</span>
                    <div wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Menyimpan Data...</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>