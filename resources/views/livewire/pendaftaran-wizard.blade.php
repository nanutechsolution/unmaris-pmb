<div class="max-w-4xl mx-auto py-6 md:py-10 font-sans px-4 md:px-0">

    <!-- CSS FIX: HILANGKAN SPINNER NUMBER INPUT -->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <!-- HEADER BRANDED -->
    <div class="text-center mb-8 md:mb-10 animate-fade-in-down">
        <!-- Logo Placeholder with Theme Color -->
        <div class="inline-block relative">
            <div class="absolute inset-0 bg-unmaris-yellow rounded-full blur-xl opacity-50"></div>
            <img src="{{ asset('images/logo.png') }}"
                onerror="this.src='https://ui-avatars.com/api/?name=UNMARIS&background=1e3a8a&color=facc15&size=128'"
                class="h-20 w-20 md:h-24 md:w-24 mx-auto relative z-10 drop-shadow-lg transform hover:scale-110 transition duration-300">
        </div>

        <h1 class="text-2xl md:text-4xl font-black text-unmaris-blue tracking-tight uppercase mt-4"
            style="text-shadow: 2px 2px 0px #FACC15;">
            PMB UNMARIS 2026
        </h1>
        <p
            class="text-unmaris-blue font-bold mt-2 text-sm md:text-lg bg-unmaris-yellow inline-block px-4 md:px-6 py-2 transform -rotate-1 border-2 border-unmaris-blue shadow-neo rounded-lg">
            Formulir Pendaftaran Online
        </p>
    </div>

    <!-- PROGRESS BAR (Responsive) -->
    <div class="mb-8 md:mb-12 px-2 md:px-4">
        <div class="relative">
            <!-- Line Background -->
            <div
                class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-3 md:h-4 bg-gray-200 border-2 border-unmaris-blue rounded-full -z-10">
            </div>
            <!-- Active Line (Blue) -->
            <div class="bg-unmaris-blue h-3 md:h-4 absolute left-0 top-1/2 transform -translate-y-1/2 border-y-2 border-l-2 border-unmaris-blue rounded-l-full transition-all duration-500 ease-out"
                style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>

            <div class="flex justify-between w-full">
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 1 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        1
                    </div>
                    <span
                        class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Biodata</span>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 2 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        2
                    </div>
                    <span
                        class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Akademik</span>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 3 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        3
                    </div>
                    <span
                        class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Berkas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD CONTAINER -->
    <div class="bg-white p-5 md:p-8 border-4 border-unmaris-blue shadow-neo-lg rounded-3xl relative overflow-hidden">

        <!-- Decoration Dots -->
        <div class="absolute top-4 right-4 flex gap-2">
            <div class="w-2 h-2 md:w-3 md:h-3 rounded-full border-2 border-unmaris-blue bg-unmaris-blue"></div>
            <div class="w-2 h-2 md:w-3 md:h-3 rounded-full border-2 border-unmaris-blue bg-unmaris-yellow"></div>
            <div class="w-2 h-2 md:w-3 md:h-3 rounded-full border-2 border-unmaris-blue bg-unmaris-green"></div>
        </div>

        <div class="mb-6">
            <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded border border-red-200">
                (*) Wajib Diisi
            </span>
        </div>

        <!-- ==================================================== -->
        <!-- STEP 1: BIODATA DIRI & JALUR                         -->
        <!-- ==================================================== -->
        @if ($currentStep == 1)
            <div class="animate-fade-in-up">
                <h2
                    class="text-lg md:text-2xl font-black mb-6 text-unmaris-blue uppercase bg-unmaris-yellow inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform -rotate-1 shadow-neo">
                    Langkah 1: Identitas & Jalur
                </h2>

                <!-- Jalur Pendaftaran -->
                <div class="mb-6 bg-blue-50 p-4 md:p-5 rounded-xl border-2 border-unmaris-blue shadow-neo">
                    <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase tracking-wide">
                        Pilih Jalur Pendaftaran <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="jalur_pendaftaran"
                        class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:outline-none focus:ring-0 focus:shadow-neo transition-all font-bold cursor-pointer text-unmaris-blue text-sm md:text-base">
                        <option value="reguler">🔥 Reguler (Umum)</option>
                        <option value="beasiswa">🎓 Jalur Beasiswa</option>
                        <option value="pindahan">🔄 Pindahan (Transfer)</option>
                        <option value="asing">🌍 International Student</option>
                    </select>

                    <!-- PILIHAN PROGRAM BEASISWA (Muncul jika pilih 'beasiswa') -->
                    @if ($jalur_pendaftaran == 'beasiswa')
                        <div class="mt-4 bg-yellow-100 p-4 border-2 border-yellow-500 rounded-lg animate-fade-in-down">
                            <label class="block text-sm font-black text-yellow-900 mb-2">
                                Pilih Program Beasiswa <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="scholarship_id"
                                class="w-full bg-white border-2 border-yellow-600 rounded-lg py-3 px-4 font-bold text-gray-800">
                                <option value="">-- Pilih Beasiswa --</option>
                                @foreach (\App\Models\Scholarship::where('is_active', true)->get() as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} (Sisa Kuota:
                                        {{ max(0, $s->quota - $s->pendaftars_count) }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs font-bold text-yellow-800 mt-2">
                                ℹ️ Anda wajib mengunggah berkas persyaratan (SKTM/Rapor/Sertifikat) di <strong>Langkah
                                    3</strong>.
                            </p>
                            @error('scholarship_id')
                                <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- NISN -->
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-sm font-bold text-unmaris-blue">
                                NISN (Nomor Induk Siswa Nasional)
                            </label>
                            <a href="https://nisn.data.kemdikbud.go.id/index.php/Cindex/senc" target="_blank"
                                class="text-[10px] md:text-xs font-bold text-unmaris-blue hover:text-unmaris-blue-light underline bg-blue-100 px-2 py-0.5 rounded border border-unmaris-blue">
                                Lupa NISN? Cek Disini
                            </a>
                        </div>
                        <!-- FIX: Gunakan type="text" inputmode="numeric" untuk mencegah scroll wheel -->
                        <input type="text" inputmode="numeric" wire:model="nisn"
                            placeholder="Boleh dikosongkan jika lupa"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base {{ $jalur_pendaftaran != 'reguler' ? 'bg-gray-200 cursor-not-allowed text-gray-400' : '' }}"
                            {{ $jalur_pendaftaran != 'reguler' ? 'disabled' : '' }}>
                        @error('nisn')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            NIK (Sesuai KTP/Kartu Keluarga) <span class="text-red-500">*</span>
                        </label>
                        <!-- FIX: inputmode numeric -->
                        <input type="text" inputmode="numeric" maxlength="16" wire:model="nik"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('nik')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="tempat_lahir"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('tempat_lahir')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tgl Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="tgl_lahir"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('tgl_lahir')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="jenis_kelamin"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue cursor-pointer text-sm md:text-base">
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
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Agama <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="agama"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue cursor-pointer text-sm md:text-base">
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

                    <!-- Nomor HP (BARU) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            No. HP / WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <!-- FIX: inputmode numeric & Placeholder +62 -->
                        <input type="tel" wire:model="nomor_hp" placeholder="Contoh: 081234567890"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('nomor_hp')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Alamat Lengkap (Sesuai KTP) <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="alamat" rows="3" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base"></textarea>
                        @error('alamat')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️
                                {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <!-- FIX: Loading Indicator agar tidak double submit -->
                    <button wire:click="validateStep1" wire:loading.attr="disabled"
                        class="w-full md:w-auto bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover transition-all transform uppercase tracking-wider flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="validateStep1">Lanjut ke Akademik 👉</span>
                        <span wire:loading wire:target="validateStep1">Memproses... ⏳</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- ==================================================== -->
        <!-- STEP 2: SEKOLAH & PRODI                              -->
        <!-- ==================================================== -->
        @if ($currentStep == 2)
            <div class="animate-fade-in-up">
                <h2
                    class="text-lg md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-blue inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 2: Data Akademik
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Sekolah -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Nama Asal Sekolah (SMA/SMK/MA) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="asal_sekolah" placeholder="Contoh: SMA Katolik Anda Luri"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('asal_sekolah')
                            <span class="text-red-600 text-xs font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Tahun Lulus <span class="text-red-500">*</span>
                        </label>
                        <input type="text" inputmode="numeric" maxlength="4" wire:model="tahun_lulus"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('tahun_lulus')
                            <span class="text-red-600 text-xs font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2 border-t-2 border-dashed border-gray-300 my-2"></div>

                    <!-- Prodi -->
                    <div class="md:col-span-2 bg-blue-50 p-4 border-2 border-unmaris-blue rounded-xl">
                        <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">
                            Pilihan Program Studi Utama (Prioritas 1) <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="pilihan_prodi_1"
                            class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue">
                            <option value="">-- PILIH PRODI UTAMA --</option>
                            @foreach (\App\Models\StudyProgram::all() as $p)
                                <option value="{{ $p->name }}">{{ $p->name }} ({{ $p->degree }})
                                </option>
                            @endforeach
                        </select>
                        @error('pilihan_prodi_1')
                            <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span>
                        @enderror

                        <div class="mt-4">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Pilihan Kedua
                                (Opsional)</label>
                            <select wire:model="pilihan_prodi_2"
                                class="w-full bg-white border-2 border-gray-300 rounded-lg py-2 px-3 text-sm">
                                <option value="">-- Tidak Memilih --</option>
                                @foreach (\App\Models\StudyProgram::all() as $p)
                                    <!-- FIX: Jangan tampilkan prodi yang sudah dipilih di Pilihan 1 -->
                                    @if ($p->name != $pilihan_prodi_1)
                                        <option value="{{ $p->name }}">{{ $p->name }}
                                            ({{ $p->degree }})</option>
                                    @endif
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilihan kedua akan digunakan jika kuota pilihan utama
                                penuh.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col-reverse md:flex-row justify-between gap-3">
                    <button wire:click="back(1)"
                        class="w-full md:w-auto bg-white border-2 border-unmaris-blue font-bold py-3 px-6 rounded-lg">👈
                        Kembali</button>

                    <!-- FIX: Loading state -->
                    <button wire:click="validateStep2" wire:loading.attr="disabled"
                        class="w-full md:w-auto bg-unmaris-yellow text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-none transition-all flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="validateStep2">Lanjut ke Berkas 👉</span>
                        <span wire:loading wire:target="validateStep2">Memproses... ⏳</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- STEP 3: ORTU & UPLOAD -->
        @if ($currentStep == 3)
            <div x-data="{ agreed: false }" class="animate-fade-in-up">
                <h2
                    class="text-lg md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-green inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 3: Orang Tua & Berkas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Ortu -->
                    <div class="md:col-span-2 bg-green-50 p-4 border-2 border-unmaris-blue border-dashed rounded-xl">
                        <h3 class="font-black text-unmaris-green mb-4">👨‍👩‍👧 Data Orang Tua</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">
                                    Nama Ayah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="nama_ayah"
                                    class="w-full border-2 border-unmaris-blue rounded px-3 py-2 text-sm">
                                @error('nama_ayah')
                                    <span class="text-red-600 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">
                                    Nama Ibu <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="nama_ibu"
                                    class="w-full border-2 border-unmaris-blue rounded px-3 py-2 text-sm">
                                @error('nama_ibu')
                                    <span class="text-red-600 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD SECTION -->
                    @if ($jalur_pendaftaran == 'beasiswa')
                        <div
                            class="md:col-span-2 bg-yellow-100 p-6 border-4 border-yellow-500 rounded-xl relative shadow-sm">
                            <div
                                class="absolute -top-3 left-6 bg-yellow-500 text-white px-3 py-1 font-black text-xs uppercase rounded">
                                Syarat Beasiswa</div>
                            <label class="block text-lg font-black text-yellow-900 mb-1">
                                Upload Berkas Pendukung <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs font-bold text-yellow-800 mb-4">Gabungkan semua syarat (KIP/SKTM/Rapor)
                                menjadi <strong>1 File PDF</strong>.</p>

                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-yellow-500 border-dashed rounded-lg cursor-pointer bg-yellow-50 hover:bg-white transition relative">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6"
                                        wire:loading.remove wire:target="file_beasiswa">
                                        <span class="text-3xl">📁</span>
                                        <p class="mb-2 text-sm text-yellow-700 font-bold"><span
                                                class="font-black">Klik upload</span> PDF (Max 5MB)</p>
                                    </div>
                                    <!-- Loading State Upload -->
                                    <div class="absolute inset-0 flex items-center justify-center bg-white/80"
                                        wire:loading wire:target="file_beasiswa">
                                        <span class="font-bold text-yellow-600">Mengupload... ⏳</span>
                                    </div>
                                    <input type="file" wire:model="file_beasiswa" class="hidden"
                                        accept=".pdf" />
                                </label>
                            </div>

                            @if ($file_beasiswa)
                                <p class="text-xs font-bold text-green-600 mt-2">File terpilih:
                                    {{ $file_beasiswa->getClientOriginalName() }}</p>
                            @elseif($existingFileBeasiswaPath)
                                <p class="text-xs font-bold text-green-600 mt-2">✅ Berkas sudah tersimpan.</p>
                            @endif
                            @error('file_beasiswa')
                                <span class="text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Upload Foto -->
                    <div
                        class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center hover:bg-yellow-50 transition shadow-neo group relative">
                        <label class="block text-lg font-black text-unmaris-blue mb-1">
                            Pas Foto Resmi <span class="text-red-500">*</span>
                        </label>

                        <!-- INSTRUKSI WARNA DINAMIS -->
                        <div
                            class="mb-4 inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider text-white bg-black animate-pulse">
                            WAJIB LATAR: {{ $this->warnaLatar }}
                        </div>

                        <div class="mt-2 flex justify-center relative">
                            <!-- Loading Overlay Foto -->
                            <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-20"
                                wire:loading wire:target="foto">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-unmaris-blue"></div>
                            </div>

                            @if ($foto)
                                <img src="{{ $foto->temporaryUrl() }}"
                                    class="h-32 w-32 object-cover rounded-full border-4 border-unmaris-blue shadow-sm">
                            @elseif($existingFotoPath)
                                <img src="{{ asset('storage/' . $existingFotoPath) }}"
                                    class="h-32 w-32 object-cover rounded-full border-4 border-unmaris-blue">
                            @else
                                <div
                                    class="w-32 h-32 bg-gray-200 rounded-full border-4 border-unmaris-blue border-dashed flex items-center justify-center text-4xl">
                                    📸</div>
                            @endif
                        </div>

                        <input type="file" wire:model="foto"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        @error('foto')
                            <span class="text-red-600 font-bold text-xs block mt-2">{{ $message }}</span>
                        @enderror
                        <p class="text-[10px] text-gray-500 mt-2">Format: JPG/PNG, Max 2MB. Wajah harus terlihat jelas.
                        </p>
                    </div>

                    <!-- Upload Ijazah -->
                    <div
                        class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center shadow-neo relative group">
                        <label class="block text-lg font-black text-unmaris-blue mb-1">
                            Ijazah / SKL <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs font-bold text-gray-400 block mb-4">PDF / JPG</span>

                        <div class="mt-2 flex justify-center relative">
                            <!-- Loading Overlay Ijazah -->
                            <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-20"
                                wire:loading wire:target="ijazah">
                                <span class="text-xs font-bold">Uploading...</span>
                            </div>

                            @if ($ijazah)
                                <div
                                    class="flex flex-col items-center justify-center p-4 bg-blue-50 border-2 border-unmaris-blue rounded-lg">
                                    <span class="text-2xl">📄</span><span
                                        class="text-xs font-bold mt-1 text-unmaris-blue">Siap Upload</span></div>
                            @elseif($existingIjazahPath)
                                <div
                                    class="flex flex-col items-center justify-center p-4 bg-green-100 border-2 border-unmaris-blue rounded-lg">
                                    <span class="text-2xl">✅</span><span
                                        class="text-xs font-black mt-1 text-green-900">TERSIMPAN</span></div>
                            @else
                                <div
                                    class="w-32 h-32 bg-gray-200 rounded-lg border-4 border-unmaris-blue border-dashed flex items-center justify-center">
                                    <span class="text-xs text-gray-500 font-bold px-2">Klik Upload</span></div>
                            @endif
                        </div>

                        <input type="file" wire:model="ijazah"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        @error('ijazah')
                            <span class="text-red-600 font-bold text-xs block mt-2">{{ $message }}</span>
                        @enderror
                        <p class="text-[10px] text-gray-500 mt-2">Scan asli berwarna, tulisan harus terbaca.</p>
                    </div>
                </div>

                <!-- Checkbox Persetujuan -->
                <div class="mt-6 flex items-center bg-yellow-50 p-4 rounded-xl border-2 border-unmaris-blue">
                    <input type="checkbox" x-model="agreed"
                        class="w-6 h-6 text-unmaris-blue border-2 border-unmaris-blue rounded focus:ring-0 cursor-pointer flex-shrink-0">
                    <span class="ml-3 text-xs md:text-sm font-bold text-unmaris-blue">
                        Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan.
                    </span>
                </div>

                <div class="mt-8 flex flex-col-reverse md:flex-row justify-between items-center gap-3">
                    <button wire:click="back(2)"
                        class="w-full md:w-auto bg-white border-2 border-unmaris-blue font-bold py-3 px-6 rounded-lg">👈
                        Kembali</button>

                    <button wire:click="submit" :disabled="!agreed"
                        :class="{ 'opacity-50 cursor-not-allowed': !
                            agreed, 'bg-unmaris-green hover:bg-green-600 hover:shadow-none': agreed }"
                        wire:loading.attr="disabled"
                        class="w-full md:w-auto bg-gray-300 text-white font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo transition-all transform uppercase tracking-wider flex justify-center items-center">
                        <span wire:loading.remove wire:target="submit">KIRIM PENDAFTARAN 🚀</span>
                        <span wire:loading wire:target="submit">MENYIMPAN...</span>
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>
