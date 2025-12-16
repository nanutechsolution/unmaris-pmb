<div class="max-w-4xl mx-auto py-10 font-sans">

    <!-- HEADER BRANDED -->
    <div class="text-center mb-10 animate-fade-in-down">
        <!-- Logo Placeholder with Theme Color -->
        <div class="inline-block relative">
            <div class="absolute inset-0 bg-unmaris-yellow rounded-full blur-xl opacity-50"></div>
            <img src="{{ asset('images/logo.png') }}"
                onerror="this.src='https://ui-avatars.com/api/?name=UNMARIS&background=1e3a8a&color=facc15&size=128'"
                class="h-24 w-24 mx-auto relative z-10 drop-shadow-lg transform hover:scale-110 transition duration-300">
        </div>

        <h1 class="text-3xl md:text-4xl font-black text-unmaris-blue tracking-tight uppercase mt-4"
            style="text-shadow: 2px 2px 0px #FACC15;">
            Penerimaan Mahasiswa Baru 2026
        </h1>
        <p
            class="text-unmaris-blue font-bold mt-2 text-lg bg-unmaris-yellow inline-block px-6 py-2 transform -rotate-1 border-2 border-unmaris-blue shadow-neo rounded-lg">
            Formulir Pendaftaran Online
        </p>
    </div>

    <!-- PROGRESS BAR (Themed) -->
    <div class="mb-12 px-4">
        <div class="relative">
            <!-- Line Background -->
            <div
                class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-4 bg-gray-200 border-2 border-unmaris-blue rounded-full -z-10">
            </div>
            <!-- Active Line (Blue) -->
            <div class="{{ $currentStep >= 1 ? 'bg-unmaris-blue' : 'bg-gray-200' }} h-4 absolute left-0 top-1/2 transform -translate-y-1/2 border-y-2 border-l-2 border-unmaris-blue rounded-l-full transition-all duration-300"
                style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>

            <div class="flex justify-between w-full">
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 1 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-12 h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-xl z-10 transition-all duration-200">
                        1
                    </div>
                    <span
                        class="mt-3 font-bold text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Biodata</span>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 2 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-12 h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-xl z-10 transition-all duration-200">
                        2
                    </div>
                    <span
                        class="mt-3 font-bold text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Berkas</span>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 3 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-12 h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-xl z-10 transition-all duration-200">
                        3
                    </div>
                    <span
                        class="mt-3 font-bold text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Prodi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER -->
    <div class="bg-white p-6 md:p-8 border-4 border-unmaris-blue shadow-neo-lg rounded-3xl relative overflow-hidden">

        <!-- Decoration Dots (Using Theme Colors) -->
        <div class="absolute top-4 right-4 flex gap-2">
            <div class="w-3 h-3 rounded-full border-2 border-unmaris-blue bg-unmaris-blue"></div>
            <div class="w-3 h-3 rounded-full border-2 border-unmaris-blue bg-unmaris-yellow"></div>
            <div class="w-3 h-3 rounded-full border-2 border-unmaris-blue bg-unmaris-green"></div>
        </div>

        <!-- ==================================================== -->
        <!-- STEP 1: BIODATA DIRI                                 -->
        <!-- ==================================================== -->
        @if ($currentStep == 1)
            <div class="animate-fade-in-up">
                <!-- Header -->
                <h2
                    class="text-xl md:text-2xl font-black mb-6 text-unmaris-blue uppercase bg-unmaris-yellow inline-block px-4 py-2 border-2 border-unmaris-blue transform -rotate-1 shadow-neo">
                    Langkah 1: Identitas Diri
                </h2>

                <!-- Jalur Pendaftaran -->
                <div class="mb-6 bg-blue-50 p-5 rounded-xl border-2 border-unmaris-blue shadow-neo">
                    <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase tracking-wide">Pilih Jalur
                        Pendaftaran</label>
                    <select wire:model.live="jalur_pendaftaran"
                        class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:outline-none focus:ring-0 focus:shadow-neo transition-all font-bold cursor-pointer text-unmaris-blue">
                        <option value="reguler">Reguler (Lulusan SMA/SMK/MA)</option>
                        <option value="pindahan">Pindahan (Transfer dari Kampus Lain)</option>
                        <option value="asing">Mahasiswa Asing (International)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NISN -->
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-sm font-bold text-unmaris-blue">NISN (Nomor Induk Siswa
                                Nasional)</label>
                            <a href="https://nisn.data.kemdikbud.go.id/index.php/Cindex/senc" target="_blank"
                                class="text-xs font-bold text-unmaris-blue hover:text-unmaris-blue-light underline bg-blue-100 px-2 py-0.5 rounded border border-unmaris-blue">
                                Lupa NISN? Cek Disini
                            </a>
                        </div>
                        <input type="number" wire:model="nisn" placeholder="Boleh dikosongkan jika lupa"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue {{ $jalur_pendaftaran != 'reguler' ? 'bg-gray-200 cursor-not-allowed text-gray-400' : '' }}"
                            {{ $jalur_pendaftaran != 'reguler' ? 'disabled' : '' }}>
                        @error('nisn')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">NIK (Sesuai KTP/Kartu
                            Keluarga)</label>
                        <input type="number" wire:model="nik"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue">
                        @error('nik')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Tempat Lahir</label>
                        <input type="text" wire:model="tempat_lahir"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue">
                        @error('tempat_lahir')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tgl Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Tanggal Lahir</label>
                        <input type="date" wire:model="tgl_lahir"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue">
                        @error('tgl_lahir')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue cursor-pointer">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Agama</label>
                        <select wire:model="agama"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue cursor-pointer">
                            <option value="">-- Pilih Agama --</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Islam">Islam</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                        @error('agama')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Alamat Lengkap (Sesuai
                            KTP)</label>
                        <textarea wire:model="alamat" rows="3"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue"></textarea>
                        @error('alamat')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <!-- Tombol Primary -->
                    <button wire:click="validateStep1"
                        class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all transform uppercase tracking-wider flex items-center">
                        Lanjut ke Data Sekolah 👉
                    </button>
                </div>
            </div>
        @endif

        <!-- ==================================================== -->
        <!-- STEP 2: SEKOLAH, ORTU & UPLOAD                       -->
        <!-- ==================================================== -->
        @if ($currentStep == 2)
            <div class="animate-fade-in-up">
                <h2
                    class="text-xl md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-blue inline-block px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 2: Sekolah & Berkas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Sekolah -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Nama Asal Sekolah
                            (SMA/SMK/MA)</label>
                        <input type="text" wire:model="asal_sekolah" placeholder="Contoh: SMA Katolik Anda Luri"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue">
                        @error('asal_sekolah')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Tahun Lulus</label>
                        <input type="number" wire:model="tahun_lulus"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue">
                        @error('tahun_lulus')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="hidden md:block"></div>

                    <!-- Orang Tua Container -->
                    <div
                        class="md:col-span-2 bg-green-50 p-5 border-2 border-unmaris-blue border-dashed rounded-xl mt-2">
                        <h3 class="font-black text-lg mb-4 text-unmaris-green flex items-center">
                            👨‍👩‍👧 Data Orang Tua / Wali
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-unmaris-blue mb-1">Nama Ayah</label>
                                <input type="text" wire:model="nama_ayah"
                                    class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-2 px-3 focus:outline-none focus:shadow-neo-sm transition-all text-unmaris-blue">
                                @error('nama_ayah')
                                    <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-unmaris-blue mb-1">Pekerjaan Ayah</label>
                                <input type="text" wire:model="pekerjaan_ayah"
                                    class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-2 px-3 focus:outline-none focus:shadow-neo-sm transition-all text-unmaris-blue">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-unmaris-blue mb-1">Nama Ibu</label>
                                <input type="text" wire:model="nama_ibu"
                                    class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-2 px-3 focus:outline-none focus:shadow-neo-sm transition-all text-unmaris-blue">
                                @error('nama_ibu')
                                    <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-unmaris-blue mb-1">Pekerjaan Ibu</label>
                                <input type="text" wire:model="pekerjaan_ibu"
                                    class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-2 px-3 focus:outline-none focus:shadow-neo-sm transition-all text-unmaris-blue">
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD SECTION -->
                    <div class="md:col-span-2 mt-4">
                        <div
                            class="bg-unmaris-blue text-unmaris-yellow px-4 py-2 text-sm font-black inline-block transform -rotate-1 border border-unmaris-yellow">
                            AREA UNGGAH DOKUMEN
                        </div>
                    </div>

                    <!-- Upload Foto -->
                    <div
                        class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center hover:bg-yellow-50 transition shadow-neo relative overflow-hidden group">
                        <label
                            class="block text-lg font-black text-unmaris-blue mb-1 cursor-pointer group-hover:underline">Pas
                            Foto Resmi</label>
                        <span class="text-xs font-bold bg-unmaris-blue text-white px-2 py-0.5 rounded">Format:
                            JPG/PNG</span>

                        <div class="mt-4 flex justify-center">
                            @if ($foto)
                                @if (in_array(strtolower($foto->extension()), ['jpg', 'jpeg', 'png']))
                                    <div class="relative">
                                        <img src="{{ $foto->temporaryUrl() }}"
                                            class="h-32 w-32 object-cover rounded-full border-4 border-unmaris-blue shadow-sm">
                                        <div
                                            class="absolute bottom-0 right-0 bg-unmaris-green border-2 border-unmaris-blue rounded-full p-1 w-6 h-6">
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="text-sm font-bold text-red-500 bg-red-100 p-2 border border-red-500 rounded">
                                        Format file salah!</div>
                                @endif
                            @elseif(auth()->user()->pendaftar && auth()->user()->pendaftar->foto_path)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . auth()->user()->pendaftar->foto_path) }}"
                                        class="h-32 w-32 object-cover rounded-full border-4 border-unmaris-blue">
                                    <span
                                        class="absolute -bottom-2 -right-2 bg-unmaris-green text-unmaris-blue text-xs font-black px-2 py-1 border-2 border-unmaris-blue rounded transform rotate-3">SUDAH
                                        DIUNGGAH</span>
                                </div>
                            @else
                                <div
                                    class="w-32 h-32 bg-gray-200 rounded-full border-4 border-unmaris-blue border-dashed flex items-center justify-center">
                                    <span class="text-xs text-gray-500 font-bold px-2">Klik Untuk Upload</span>
                                </div>
                            @endif
                        </div>

                        <input type="file" wire:model="foto" accept="image/png, image/jpeg"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        <div wire:loading wire:target="foto"
                            class="absolute inset-0 bg-white/80 flex items-center justify-center font-black text-unmaris-blue">
                            SEDANG MENGUNGGAH...</div>
                        @error('foto')
                            <span class="text-red-600 font-bold text-xs block mt-2">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Upload Ijazah -->
                    <div
                        class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center hover:bg-yellow-50 transition shadow-neo relative overflow-hidden group">
                        <label
                            class="block text-lg font-black text-unmaris-blue mb-1 cursor-pointer group-hover:underline">Scan
                            Ijazah / SKL</label>
                        <span class="text-xs font-bold bg-unmaris-blue text-white px-2 py-0.5 rounded">Format:
                            PDF/JPG/PNG</span>

                        <div class="mt-4 flex justify-center">
                            @if ($ijazah)
                                <div
                                    class="flex flex-col items-center justify-center p-4 bg-blue-50 border-2 border-unmaris-blue rounded-lg">
                                    <span class="text-2xl">📄</span>
                                    <span class="text-xs font-bold mt-1 text-unmaris-blue">Siap Diunggah</span>
                                </div>
                            @elseif(auth()->user()->pendaftar && auth()->user()->pendaftar->ijazah_path)
                                <div
                                    class="flex flex-col items-center justify-center p-4 bg-green-100 border-2 border-unmaris-blue rounded-lg transform -rotate-2">
                                    <span class="text-2xl">✅</span>
                                    <span class="text-xs font-black mt-1 text-green-900">BERKAS TERSIMPAN</span>
                                </div>
                            @else
                                <div
                                    class="w-32 h-32 bg-gray-200 rounded-lg border-4 border-unmaris-blue border-dashed flex items-center justify-center">
                                    <span class="text-xs text-gray-500 font-bold px-2">Klik Untuk Upload</span>
                                </div>
                            @endif
                        </div>

                        <input type="file" wire:model="ijazah" accept=".pdf,.jpg,.jpeg,.png"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        <div wire:loading wire:target="ijazah"
                            class="absolute inset-0 bg-white/80 flex items-center justify-center font-black text-unmaris-blue">
                            SEDANG MENGUNGGAH...</div>
                        @error('ijazah')
                            <span class="text-red-600 font-bold text-xs block mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <button wire:click="back(1)"
                        class="bg-white hover:bg-gray-100 text-unmaris-blue font-black py-3 px-6 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all transform flex items-center">
                        👈 Kembali
                    </button>
                    <button wire:click="validateStep2"
                        class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all transform uppercase tracking-wider flex items-center">
                        Lanjut ke Pilihan Prodi 👉
                    </button>
                </div>
            </div>
        @endif

        <!-- ==================================================== -->
        <!-- STEP 3: PILIHAN PRODI                                -->
        <!-- ==================================================== -->
        @if ($currentStep == 3)
            <div x-data="{ agreed: false }" class="animate-fade-in-up">
                <!-- Header: Hijau Aksen -->
                <h2
                    class="text-xl md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-green inline-block px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 3: Pilih Program Studi
                </h2>

                <div class="bg-blue-100 text-unmaris-blue p-4 border-2 border-unmaris-blue rounded-xl mb-6 shadow-neo">
                    <p class="font-bold flex items-center">
                        <span class="text-2xl mr-3">💡</span>
                        Silakan pilih Program Studi sesuai dengan minat dan bakat Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">Pilihan Utama
                            (Prioritas 1)</label>
                        <select wire:model="pilihan_prodi_1"
                            class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-4 px-4 focus:outline-none focus:ring-0 focus:shadow-neo transition-all font-bold text-lg cursor-pointer hover:bg-yellow-50 text-unmaris-blue">
                            <option value="">-- SILAKAN PILIH PRODI --</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Agroteknologi">Agroteknologi</option>
                            <option value="Manajemen">Manajemen</option>
                            <option value="Akuntansi">Akuntansi</option>
                        </select>
                        @error('pilihan_prodi_1')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">Pilihan Kedua
                            (Cadangan/Opsional)</label>
                        <select wire:model="pilihan_prodi_2"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:outline-none focus:ring-0 focus:shadow-neo transition-all font-medium cursor-pointer text-unmaris-blue">
                            <option value="">-- Boleh Dikosongkan --</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Agroteknologi">Agroteknologi</option>
                            <option value="Manajemen">Manajemen</option>
                            <option value="Akuntansi">Akuntansi</option>
                        </select>
                    </div>

                    <div class="mt-6 flex items-center bg-yellow-50 p-4 rounded-xl border-2 border-unmaris-blue">
                        <input type="checkbox" x-model="agreed"
                            class="w-6 h-6 text-unmaris-blue border-2 border-unmaris-blue rounded focus:ring-0 cursor-pointer">
                        <span class="ml-3 text-sm font-bold text-unmaris-blue">
                            Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan.
                        </span>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <button wire:click="back(2)"
                        class="bg-white hover:bg-gray-100 text-unmaris-blue font-black py-3 px-6 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all transform flex items-center">
                        👈 Kembali
                    </button>

                    <button wire:click="submit" :disabled="!agreed"
                        :class="{ 'opacity-50 cursor-not-allowed bg-gray-300': !
                            agreed, 'bg-unmaris-green hover:bg-green-600 hover:shadow-neo-hover hover:translate-x-[4px] hover:translate-y-[4px]': agreed }"
                        wire:loading.attr="disabled"
                        class="text-white font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo transition-all transform uppercase tracking-wider text-lg flex items-center">
                        <span wire:loading.remove>KIRIM PENDAFTARAN</span>
                        <span wire:loading>SEDANG MENYIMPAN...</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
