<!-- Tambahkan event listener @validation-error di div utama -->
<div class="max-w-4xl mx-auto py-6 md:py-10 font-sans px-4 md:px-0" 
     x-data="{ showConfirmModal: false }"
     @validation-error.window="showConfirmModal = false">
    
    <!-- DEV TOOL: AUTO FILL BUTTON (Hanya muncul di environment local) -->
    @if(app()->environment('local'))
    <button type="button" 
            title="Klik untuk isi data dummy otomatis"
            class="fixed bottom-4 left-4 z-50 bg-gray-900 text-white px-4 py-3 rounded-full shadow-2xl border-2 border-yellow-400 font-bold text-xs flex items-center gap-2 opacity-75 hover:opacity-100 transition-opacity"
            @click="
                // STEP 1
                $wire.set('jalur_pendaftaran', 'reguler');
                $wire.set('nisn', '0051234567');
                $wire.set('nik', '5301012301050001');
                $wire.set('tempat_lahir', 'Tambolaka');
                $wire.set('tgl_lahir', '2005-05-20');
                $wire.set('jenis_kelamin', 'L');
                $wire.set('agama', 'Katolik');
                $wire.set('nomor_hp', '081234567890');
                $wire.set('alamat', 'Jl. Testing Developer No. 404, Localhost');
                
                // STEP 2
                $wire.set('asal_sekolah', 'SMA Negeri 1 Testing');
                $wire.set('tahun_lulus', '2024');
                $wire.set('pilihan_prodi_1', 'Teknik Informatika');
                $wire.set('pilihan_prodi_2', 'Bisnis Digital');

                // STEP 3
                $wire.set('nama_ayah', 'Bapak Developer');
                $wire.set('nama_ibu', 'Ibu Developer');
                $wire.set('jenis_dokumen', 'skl');
            ">
        <span>🛠️</span> AUTO-FILL FORM
    </button>
    @endif

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
        /* Modal Animation */
        [x-cloak] { display: none !important; }
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
        <p class="text-unmaris-blue font-bold mt-2 text-sm md:text-lg bg-unmaris-yellow inline-block px-4 md:px-6 py-2 transform -rotate-1 border-2 border-unmaris-blue shadow-neo rounded-lg">
            Formulir Pendaftaran Online
        </p>
    </div>

    <!-- PROGRESS BAR (Responsive) -->
    <div class="mb-8 md:mb-12 px-2 md:px-4">
        <div class="relative">
            <!-- Line Background -->
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-3 md:h-4 bg-gray-200 border-2 border-unmaris-blue rounded-full -z-10"></div>
            <!-- Active Line (Blue) -->
            <div class="bg-unmaris-blue h-3 md:h-4 absolute left-0 top-1/2 transform -translate-y-1/2 border-y-2 border-l-2 border-unmaris-blue rounded-l-full transition-all duration-500 ease-out"
                style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>

            <div class="flex justify-between w-full">
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center group">
                    <div class="{{ $currentStep >= 1 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        1
                    </div>
                    <span class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Biodata</span>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center group">
                    <div class="{{ $currentStep >= 2 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        2
                    </div>
                    <span class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Akademik</span>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center group">
                    <div class="{{ $currentStep >= 3 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        3
                    </div>
                    <span class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Berkas</span>
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
                <h2 class="text-lg md:text-2xl font-black mb-6 text-unmaris-blue uppercase bg-unmaris-yellow inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform -rotate-1 shadow-neo">
                    Langkah 1: Identitas & Jalur
                </h2>

                <!-- ALERT PENTING -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-black text-red-700">
                                PENTING: Data Tempat dan Tanggal Lahir WAJIB SESUAI DENGAN IJAZAH. Kesalahan data akan menghambat proses kelulusan nanti.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Jalur Pendaftaran -->
                <div class="mb-6 bg-blue-50 p-4 md:p-5 rounded-xl border-2 border-unmaris-blue shadow-neo">
                    <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase tracking-wide">
                        Pilih Jalur Pendaftaran <span class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="jalur_pendaftaran" class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:outline-none focus:ring-0 focus:shadow-neo transition-all font-bold cursor-pointer text-unmaris-blue text-sm md:text-base">
                        <option value="reguler">🔥 Reguler (Umum)</option>
                        <option value="pindahan">🔄 Pindahan (Transfer)</option>
                    </select>

                    <!-- PILIHAN PROGRAM BEASISWA (Muncul jika pilih 'beasiswa') -->
                    @if($jalur_pendaftaran == 'beasiswa')
                        <div class="mt-4 bg-yellow-100 p-4 border-2 border-yellow-500 rounded-lg animate-fade-in-down">
                            <label class="block text-sm font-black text-yellow-900 mb-2">
                                Pilih Program Beasiswa <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="scholarship_id" class="w-full bg-white border-2 border-yellow-600 rounded-lg py-3 px-4 font-bold text-gray-800">
                                <option value="">-- Pilih Beasiswa --</option>
                                @foreach(\App\Models\Scholarship::where('is_active', true)->get() as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} (Sisa Kuota: {{ max(0, $s->quota - $s->pendaftars_count) }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs font-bold text-yellow-800 mt-2">
                                ℹ️ Anda wajib mengunggah berkas persyaratan (SKTM/Rapor/Sertifikat) di <strong>Langkah 3</strong>.
                            </p>
                            @error('scholarship_id') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
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
                        <input type="text" inputmode="numeric" wire:model="nisn" placeholder="Boleh dikosongkan jika lupa"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base {{ $jalur_pendaftaran != 'reguler' ? 'bg-gray-200 cursor-not-allowed text-gray-400' : '' }}"
                            {{ $jalur_pendaftaran != 'reguler' ? 'disabled' : '' }}>
                        @error('nisn') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            NIK (Sesuai KTP/Kartu Keluarga) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" inputmode="numeric" maxlength="16" wire:model="nik" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('nik') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Tempat Lahir <span class="text-red-500 font-black text-xs bg-red-100 px-1 rounded ml-1">(SESUAI IJAZAH) *</span>
                        </label>
                        <input type="text" wire:model="tempat_lahir" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('tempat_lahir') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>

                    <!-- Tgl Lahir -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Tanggal Lahir <span class="text-red-500 font-black text-xs bg-red-100 px-1 rounded ml-1">(SESUAI IJAZAH) *</span>
                        </label>
                        <input type="date" wire:model="tgl_lahir" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('tgl_lahir') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="jenis_kelamin" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue cursor-pointer text-sm md:text-base">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Agama <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="agama" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue cursor-pointer text-sm md:text-base">
                            <option value="">-- Pilih Agama --</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Islam">Islam</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                        @error('agama') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Nomor HP (BARU) -->
                    <div class="md:col-span-2">
                         <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            No. HP / WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" wire:model="nomor_hp" placeholder="Contoh: 081234567890" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('nomor_hp') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Alamat Lengkap (Sesuai KTP) <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="alamat" rows="3" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base"></textarea>
                        @error('alamat') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">⚠️ {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button wire:click="validateStep1" wire:loading.attr="disabled" class="w-full md:w-auto bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover transition-all transform uppercase tracking-wider flex items-center justify-center gap-2">
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
                <h2 class="text-lg md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-blue inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 2: Data Akademik
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Sekolah -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Nama Asal Sekolah (SMA/SMK/MA) <span class="text-red-500 font-black text-xs bg-red-100 px-1 rounded ml-1">(SESUAI IJAZAH) *</span>
                        </label>
                        <input type="text" wire:model="asal_sekolah" placeholder="Contoh: SMA Katolik Anda Luri" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('asal_sekolah') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">
                            Tahun Lulus <span class="text-red-500 font-black text-xs bg-red-100 px-1 rounded ml-1">(SESUAI IJAZAH) *</span>
                        </label>
                        <input type="text" inputmode="numeric" maxlength="4" wire:model="tahun_lulus" class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 focus:bg-white focus:outline-none focus:shadow-neo transition-all font-medium text-unmaris-blue text-sm md:text-base">
                        @error('tahun_lulus') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 border-t-2 border-dashed border-gray-300 my-2"></div>

                    <!-- Prodi -->
                    <div class="md:col-span-2 bg-blue-50 p-4 border-2 border-unmaris-blue rounded-xl">
                        <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">
                            Pilihan Program Studi Utama (Prioritas 1) <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="pilihan_prodi_1" class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue">
                            <option value="">-- PILIH PRODI UTAMA --</option>
                            @foreach(\App\Models\StudyProgram::all() as $p)
                                <option value="{{ $p->name }}">{{ $p->name }} ({{ $p->degree }})</option>
                            @endforeach
                        </select>
                        @error('pilihan_prodi_1') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                        
                        <div class="mt-4">
                            <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">
                                Pilihan Program Studi Kedua (Prioritas 2) <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="pilihan_prodi_2" class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue text-sm">
                                <option value="">-- PILIH PRODI KEDUA --</option>
                                @foreach(\App\Models\StudyProgram::all() as $p)
                                    @if($p->name != $pilihan_prodi_1)
                                        <option value="{{ $p->name }}">{{ $p->name }} ({{ $p->degree }})</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('pilihan_prodi_2') <span class="text-red-600 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Pilihan kedua akan digunakan jika kuota pilihan utama penuh.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col-reverse md:flex-row justify-between gap-3">
                    <button wire:click="back(1)" class="w-full md:w-auto bg-white border-2 border-unmaris-blue font-bold py-3 px-6 rounded-lg">👈 Kembali</button>
                    
                    <button wire:click="validateStep2" wire:loading.attr="disabled" class="w-full md:w-auto bg-unmaris-yellow text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-none transition-all flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="validateStep2">Lanjut ke Berkas 👉</span>
                        <span wire:loading wire:target="validateStep2">Memproses... ⏳</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- STEP 3: ORTU & UPLOAD -->
        @if($currentStep == 3)
            <div x-data="{ agreed: false }" class="animate-fade-in-up">
                <h2 class="text-lg md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-green inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 3: Orang Tua & Berkas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Ortu -->
                    <div class="md:col-span-2 bg-green-50 p-4 border-2 border-unmaris-blue border-dashed rounded-xl">
                        <h3 class="font-black text-unmaris-green mb-4">👨‍👩‍👧 Data Orang Tua</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">
                                    Nama Ayah <span class="text-red-500 font-black text-xs bg-red-100 px-1 rounded ml-1">(SESUAI KK) *</span>
                                </label>
                                <input type="text" wire:model="nama_ayah" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 text-sm">
                                @error('nama_ayah') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">
                                    Nama Ibu <span class="text-red-500 font-black text-xs bg-red-100 px-1 rounded ml-1">(SESUAI KK) *</span>
                                </label>
                                <input type="text" wire:model="nama_ibu" class="w-full border-2 border-unmaris-blue rounded px-3 py-2 text-sm">
                                @error('nama_ibu') <span class="text-red-600 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- UPLOAD SECTION -->
                    @if($jalur_pendaftaran == 'beasiswa')
                    <div class="md:col-span-2 bg-yellow-100 p-6 border-4 border-yellow-500 rounded-xl relative shadow-sm">
                        <div class="absolute -top-3 left-6 bg-yellow-500 text-white px-3 py-1 font-black text-xs uppercase rounded">Syarat Beasiswa</div>
                        <label class="block text-lg font-black text-yellow-900 mb-1">
                            Upload Berkas Pendukung <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs font-bold text-yellow-800 mb-4">Gabungkan semua syarat (KIP/SKTM/Rapor) menjadi <strong>1 File PDF</strong>.</p>

                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-yellow-500 border-dashed rounded-lg cursor-pointer bg-yellow-50 hover:bg-white transition relative">
                                <!-- PREVIEW FILE BEASISWA -->
                                @if($file_beasiswa)
                                    @php
                                        try {
                                            $mime = $file_beasiswa->getMimeType();
                                            $isImage = str_starts_with($mime, 'image/');
                                        } catch (\Exception $e) { $isImage = false; }
                                    @endphp
                                    <div class="flex flex-col items-center justify-center p-2 text-center w-full h-full">
                                        @if($isImage)
                                            <img src="{{ $file_beasiswa->temporaryUrl() }}" class="h-24 w-auto object-contain rounded mb-1 shadow-sm border border-gray-200">
                                        @else
                                            <span class="text-4xl text-red-500">📄</span>
                                        @endif
                                        <p class="text-xs font-bold text-green-700 mt-1 truncate w-48 bg-green-50 px-2 py-1 rounded">{{ $file_beasiswa->getClientOriginalName() }}</p>
                                        <span class="text-[9px] text-gray-400 mt-1">Klik untuk ganti</span>
                                    </div>
                                @elseif($existingFileBeasiswaPath)
                                    <div class="flex flex-col items-center justify-center p-2 text-center w-full h-full bg-green-50">
                                        <span class="text-3xl text-green-600">✅</span>
                                        <p class="text-xs font-black text-green-800 mt-2">BERKAS TERSIMPAN</p>
                                        <span class="text-[9px] text-gray-400 mt-1">Klik untuk ubah</span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" wire:loading.remove wire:target="file_beasiswa">
                                        <span class="text-3xl">📁</span>
                                        <p class="mb-2 text-sm text-yellow-700 font-bold"><span class="font-black">Klik upload</span> PDF (Max 5MB)</p>
                                    </div>
                                @endif

                                <div class="absolute inset-0 flex items-center justify-center bg-white/80 z-10" wire:loading wire:target="file_beasiswa">
                                    <span class="font-bold text-yellow-600 animate-pulse">Mengupload... ⏳</span>
                                </div>
                                <input type="file" wire:model="file_beasiswa" class="hidden" accept=".pdf,.jpg,.jpeg,.png" />
                            </label>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1 text-center">Format: PDF/JPG/PNG. Max: 5MB.</p>
                        @error('file_beasiswa') <span class="text-red-600 font-bold text-xs mt-1 block bg-red-50 p-1 border border-red-200 rounded text-center">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Upload Foto (Latar Biru) -->
                    <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center hover:bg-yellow-50 transition shadow-neo group relative">
                        <label class="block text-lg font-black text-unmaris-blue mb-1">
                            Pas Foto Resmi <span class="text-red-500">*</span>
                        </label>
                        <div class="mb-4 inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider text-white bg-black animate-pulse">
                            WAJIB LATAR: {{ $this->warnaLatar }} (BIRU)
                        </div>

                        <div class="mt-2 flex justify-center relative">
                            <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-20" wire:loading wire:target="foto">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-unmaris-blue"></div>
                            </div>

                            @if ($foto)
                                <!-- LOGIKA PENGECEKAN TIPE FILE (FIX CRASH PDF) -->
                                @php
                                    try {
                                        $mime = $foto->getMimeType();
                                        $isImage = str_starts_with($mime, 'image/');
                                    } catch (\Exception $e) { 
                                        $isImage = false; 
                                    }
                                @endphp

                                @if($isImage)
                                    <img src="{{ $foto->temporaryUrl() }}" class="h-32 w-32 object-cover rounded-full border-4 border-unmaris-blue shadow-sm">
                                @else
                                    <!-- Fallback jika user salah upload PDF/Dokumen -->
                                    <div class="h-32 w-32 bg-red-50 rounded-full border-4 border-red-300 flex flex-col items-center justify-center text-red-500">
                                        <span class="text-3xl">⚠️</span>
                                        <span class="text-[8px] font-bold mt-1 uppercase text-center px-2">Bukan Gambar</span>
                                    </div>
                                @endif

                            @elseif($existingFotoPath)
                                <img src="{{ asset('storage/'.$existingFotoPath) }}" class="h-32 w-32 object-cover rounded-full border-4 border-unmaris-blue">
                            @else
                                <div class="w-32 h-32 bg-gray-200 rounded-full border-4 border-unmaris-blue border-dashed flex items-center justify-center text-4xl">📸</div>
                            @endif
                        </div>

                        <input type="file" wire:model="foto" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        
                        <!-- Pesan Error -->
                        @error('foto') 
                            <span class="text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">{{ $message }}</span> 
                        @enderror

                        <!-- Tampilkan peringatan tambahan jika upload bukan gambar -->
                        @if ($foto && isset($isImage) && !$isImage)
                            <span class="text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">
                                File harus berupa gambar (JPG/PNG)!
                            </span>
                        @endif

                        <p class="text-[10px] text-gray-500 mt-2">Format: JPG/PNG. Max: 2MB. Wajah harus terlihat jelas.</p>
                    </div>

                    <!-- Upload KTP / KK (BARU) -->
                    <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center shadow-neo relative group">
                        <label class="block text-lg font-black text-unmaris-blue mb-1">
                            Scan KTP / Kartu Keluarga <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs font-bold text-gray-400 block mb-4">PDF / JPG</span>
                        
                        <div class="mt-2 flex justify-center relative w-full h-32">
                             <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-20" wire:loading wire:target="file_ktp">
                                <span class="text-xs font-bold text-unmaris-blue animate-pulse">Uploading...</span>
                            </div>

                            @if ($file_ktp)
                                @php
                                    try {
                                        $mime = $file_ktp->getMimeType();
                                        $isImage = str_starts_with($mime, 'image/');
                                    } catch (\Exception $e) { $isImage = false; }
                                @endphp
                                <div class="flex flex-col items-center justify-center p-2 text-center w-full h-full border-2 border-blue-200 border-dashed rounded-lg bg-blue-50">
                                    @if($isImage)
                                        <img src="{{ $file_ktp->temporaryUrl() }}" class="h-20 w-auto object-contain rounded mb-1 shadow-sm border border-gray-200">
                                    @else
                                        <span class="text-3xl">🪪</span>
                                    @endif
                                    <p class="text-[10px] font-bold text-unmaris-blue mt-1 truncate max-w-[150px] bg-white px-2 py-0.5 rounded">{{ $file_ktp->getClientOriginalName() }}</p>
                                </div>
                            @elseif($existingKtpPath)
                                <div class="flex flex-col items-center justify-center p-4 bg-green-100 border-2 border-unmaris-blue rounded-lg w-full h-full">
                                    <span class="text-3xl text-green-600">✅</span>
                                    <span class="text-xs font-black mt-1 text-green-900">TERSIMPAN</span>
                                </div>
                            @else
                                <div class="w-full h-full bg-gray-50 rounded-lg border-2 border-unmaris-blue border-dashed flex flex-col items-center justify-center hover:bg-blue-50 transition">
                                    <span class="text-2xl mb-1">📂</span>
                                    <span class="text-xs text-gray-500 font-bold px-2">Klik Upload</span>
                                </div>
                            @endif
                            
                            <input type="file" wire:model="file_ktp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        @error('file_ktp') <span class="text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-gray-500 mt-2">Scan Asli KTP atau Kartu Keluarga. <br>Format: PDF/JPG/PNG. Max: 2MB.</p>
                    </div>

                    <!-- Upload Akta Kelahiran (BARU - OPSIONAL) -->
                    <div class="md:col-span-2 bg-yellow-50 border-2 border-yellow-500 border-dashed rounded-xl p-6 text-center relative group">
                        <label class="block text-lg font-black text-yellow-800 mb-1">
                            Scan Akta Kelahiran
                        </label>
                        <span class="text-xs font-bold text-green-600 block mb-4 bg-white px-2 py-1 inline-block rounded border border-green-500 uppercase">Opsional (Jika Ada)</span>
                        
                        <div class="mt-2 flex justify-center relative w-full h-32">
                             <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-20" wire:loading wire:target="file_akta">
                                <span class="text-xs font-bold text-yellow-800 animate-pulse">Uploading...</span>
                            </div>

                            @if ($file_akta)
                                @php
                                    try {
                                        $mime = $file_akta->getMimeType();
                                        $isImage = str_starts_with($mime, 'image/');
                                    } catch (\Exception $e) { $isImage = false; }
                                @endphp
                                <div class="flex flex-col items-center justify-center p-2 text-center w-full h-full border-2 border-yellow-300 border-dashed rounded-lg bg-white">
                                    @if($isImage)
                                        <img src="{{ $file_akta->temporaryUrl() }}" class="h-20 w-auto object-contain rounded mb-1 shadow-sm border border-gray-200">
                                    @else
                                        <span class="text-3xl">👶</span>
                                    @endif
                                    <p class="text-[10px] font-bold text-yellow-800 mt-1 truncate max-w-[200px] bg-yellow-50 px-2 py-0.5 rounded">{{ $file_akta->getClientOriginalName() }}</p>
                                </div>
                            @elseif($existingAktaPath)
                                <div class="flex flex-col items-center justify-center p-4 bg-green-100 border-2 border-green-600 rounded-lg h-full w-full">
                                    <span class="text-3xl text-green-600">✅</span>
                                    <span class="text-xs font-black mt-1 text-green-900">TERSIMPAN</span>
                                </div>
                            @else
                                <div class="w-full h-full bg-white rounded-lg border-2 border-yellow-600 border-dashed flex flex-col items-center justify-center hover:bg-yellow-100 transition">
                                    <span class="text-2xl mb-1">📂</span>
                                    <span class="text-sm text-yellow-800 font-bold px-2">Klik untuk Upload Akta</span>
                                </div>
                            @endif
                            
                            <input type="file" wire:model="file_akta" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        @error('file_akta') <span class="text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG/PNG. Max: 2MB.</p>
                    </div>

                    <!-- Upload Ijazah / SKL (LOGIKA BARU) -->
                    <div class="md:col-span-2 bg-blue-50 border-4 border-unmaris-blue rounded-xl p-6 relative">
                        <h4 class="font-black text-unmaris-blue mb-4 border-b-2 border-blue-200 pb-2 flex items-center gap-2">
                            <span>🎓</span> Dokumen Kelulusan
                        </h4>

                        <!-- Pilihan Dokumen -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dokumen apa yang Anda miliki saat ini? <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded border-2 border-blue-200 hover:border-unmaris-blue">
                                    <input type="radio" wire:model.live="jenis_dokumen" value="ijazah" class="text-unmaris-blue focus:ring-unmaris-blue">
                                    <span class="font-bold text-sm">Ijazah Asli</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded border-2 border-blue-200 hover:border-unmaris-blue">
                                    <input type="radio" wire:model.live="jenis_dokumen" value="skl" class="text-unmaris-blue focus:ring-unmaris-blue">
                                    <span class="font-bold text-sm">Surat Keterangan Lulus (SKL)</span>
                                </label>
                            </div>
                            @error('jenis_dokumen') <span class="text-red-600 font-bold text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- 1. Upload File Utama (Ijazah/SKL) -->
                            <div class="bg-white border-2 border-gray-300 border-dashed rounded-xl p-4 text-center relative hover:border-unmaris-blue transition">
                                <label class="block text-sm font-bold text-gray-700 mb-1">
                                    Upload {{ $jenis_dokumen == 'skl' ? 'Surat Keterangan Lulus (SKL)' : 'Ijazah Asli' }} <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] text-gray-400 block mb-2">PDF / JPG</span>
                                
                                <div class="flex justify-center h-32 relative">
                                    <div class="absolute inset-0 flex items-center justify-center bg-white/80 z-20" wire:loading wire:target="ijazah">
                                        <span class="text-xs font-bold text-unmaris-blue animate-pulse">Uploading...</span>
                                    </div>

                                    @if ($ijazah)
                                        @php
                                            try {
                                                $mime = $ijazah->getMimeType();
                                                $isImage = str_starts_with($mime, 'image/');
                                            } catch (\Exception $e) { $isImage = false; }
                                        @endphp
                                        <div class="flex flex-col items-center justify-center p-2 text-center w-full h-full bg-blue-50 rounded-lg">
                                            @if($isImage)
                                                <img src="{{ $ijazah->temporaryUrl() }}" class="h-20 w-auto object-contain rounded mb-1 shadow-sm border border-gray-200">
                                            @else
                                                <span class="text-3xl">📄</span>
                                            @endif
                                            <p class="text-[10px] font-bold text-unmaris-blue mt-1 truncate max-w-[150px] bg-white px-2 py-0.5 rounded">{{ $ijazah->getClientOriginalName() }}</p>
                                        </div>
                                    @elseif($existingIjazahPath)
                                        <div class="flex flex-col items-center justify-center p-4 bg-green-100 border-2 border-unmaris-blue rounded-lg w-full h-full">
                                            <span class="text-3xl text-green-600">✅</span>
                                            <span class="text-xs font-black mt-1 text-green-900">TERSIMPAN</span>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center">
                                            <span class="text-3xl mb-1">📄</span>
                                            <p class="text-[10px] text-unmaris-blue font-bold">Klik untuk Upload</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <input type="file" wire:model="ijazah" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                                @error('ijazah') <span class="text-red-600 font-bold text-xs block mt-1 bg-red-50 p-1 border border-red-200 rounded">{{ $message }}</span> @enderror
                                <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG/PNG. Max: 2MB.</p>
                            </div>

                            <!-- 2. Upload Transkrip (WAJIB JIKA IJAZAH) -->
                            @if($jenis_dokumen == 'ijazah')
                            <div class="bg-white border-2 border-dashed border-red-400 bg-red-50 rounded-xl p-4 text-center relative transition-all animate-fade-in-up">
                                <label class="block text-sm font-bold text-gray-700 mb-1">
                                    Transkrip Nilai
                                    <span class="text-red-600 bg-red-100 px-1 rounded text-[10px] uppercase ml-1">Wajib</span> 
                                </label>
                                <span class="text-[10px] text-gray-400 block mb-2">Halaman nilai di belakang Ijazah</span>
                                
                                <div class="flex justify-center h-32 relative">
                                    <div class="absolute inset-0 flex items-center justify-center bg-white/80 z-20" wire:loading wire:target="transkrip">
                                        <span class="text-xs font-bold text-red-600 animate-pulse">Uploading...</span>
                                    </div>

                                    @if ($transkrip)
                                        @php
                                            try {
                                                $mime = $transkrip->getMimeType();
                                                $isImage = str_starts_with($mime, 'image/');
                                            } catch (\Exception $e) { $isImage = false; }
                                        @endphp
                                        <div class="flex flex-col items-center justify-center p-2 text-center w-full h-full bg-white border border-red-200 rounded-lg">
                                            @if($isImage)
                                                <img src="{{ $transkrip->temporaryUrl() }}" class="h-20 w-auto object-contain rounded mb-1 shadow-sm border border-gray-200">
                                            @else
                                                <span class="text-3xl">📊</span>
                                            @endif
                                            <p class="text-[10px] font-bold text-green-600 mt-1 truncate max-w-[150px] bg-green-50 px-2 py-0.5 rounded">{{ $transkrip->getClientOriginalName() }}</p>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center">
                                            <span class="text-3xl mb-1">📊</span>
                                            <p class="text-[10px] text-gray-500 font-bold">Klik untuk Upload</p>
                                        </div>
                                    @endif
                                </div>

                                <input type="file" wire:model="transkrip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                                @error('transkrip') <span class="text-red-600 font-bold text-xs block mt-1 bg-red-100 p-1 border border-red-300 rounded">{{ $message }}</span> @enderror
                                <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG/PNG. Max: 2MB.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Checkbox Persetujuan (High Visibility) -->
                <div class="mt-8 p-5 rounded-xl border-4 transition-colors duration-300 shadow-neo"
                     :class="agreed ? 'bg-green-100 border-green-600' : 'bg-yellow-100 border-unmaris-blue'">
                    
                    <label class="flex items-start gap-4 cursor-pointer group">
                        <div class="relative flex-shrink-0 mt-1">
                            <input type="checkbox" x-model="agreed" 
                                class="w-8 h-8 text-unmaris-blue border-4 border-black rounded focus:ring-0 cursor-pointer relative z-10 transition-transform group-hover:scale-110">
                            
                            <!-- Animasi Ping agar mencolok jika belum dicentang -->
                            <span x-show="!agreed" class="animate-ping absolute inset-0 rounded-md bg-yellow-600 opacity-75"></span>
                        </div>
                        
                        <div>
                            <div class="font-black text-xs uppercase tracking-widest mb-1" 
                                 :class="agreed ? 'text-green-800' : 'text-red-500'">
                                <span x-show="!agreed">⚠️ WAJIB DICENTANG</span>
                                <span x-show="agreed" style="display: none;">✅ TERIMA KASIH</span>
                            </div>
                            <span class="text-sm md:text-base font-bold text-gray-900 leading-tight">
                                Saya menyatakan bahwa seluruh data yang saya isi adalah <span class="underline decoration-2 decoration-red-500">BENAR</span> dan dapat dipertanggungjawabkan.
                            </span>
                        </div>
                    </label>
                </div>

                <div class="mt-8 flex flex-col-reverse md:flex-row justify-between items-center gap-3">
                    <button wire:click="back(2)" class="w-full md:w-auto bg-white border-2 border-unmaris-blue font-bold py-3 px-6 rounded-lg">👈 Kembali</button>
                    
                    <!-- TOMBOL KIRIM (MEMUNCULKAN MODAL DULU) -->
                    <button @click="if(agreed) showConfirmModal = true" 
                            :disabled="!agreed" 
                            :class="{'opacity-50 cursor-not-allowed': !agreed, 'bg-unmaris-green hover:bg-green-600 hover:shadow-none': agreed}" 
                            class="w-full md:w-auto bg-gray-300 text-white font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo transition-all transform uppercase tracking-wider flex justify-center items-center">
                        KIRIM PENDAFTARAN 🚀
                    </button>
                </div>
            </div>
        @endif

    </div>

    <!-- MODAL KONFIRMASI (USER FRIENDLY) -->
    <div x-show="showConfirmModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
        
        <div class="bg-white border-4 border-unmaris-blue rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
            <!-- Icon -->
            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-yellow-400 border-4 border-black rounded-full p-4 shadow-neo">
                <span class="text-4xl">🤔</span>
            </div>

            <div class="mt-8 text-center">
                <h3 class="text-2xl font-black text-unmaris-blue uppercase mb-2">Sudah Yakin, Kawan?</h3>
                <p class="text-gray-600 font-medium text-sm mb-6">
                    
                    Pastikan semua data (terutama <strong>Nama, NIK, & Pilihan Jurusan</strong>) sudah benar ya! <br>Data tidak bisa diubah setelah dikirim.
                </p>

                <div class="flex flex-col gap-3">
                    <button wire:click="submit" 
                            wire:loading.attr="disabled"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-3 rounded-xl border-2 border-black shadow-neo hover:shadow-none transition-all uppercase flex justify-center items-center gap-2">
                        <span wire:loading.remove>✅ Ya, Kirim Data Saya!</span>
                        <span wire:loading>Menyimpan... ⏳</span>
                    </button>
                    
                    <button @click="showConfirmModal = false" 
                            class="w-full bg-white hover:bg-gray-100 text-gray-700 font-bold py-3 rounded-xl border-2 border-gray-300 transition-all uppercase">
                        🔍 Cek Lagi Deh
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- AUTO-SCROLL SCRIPT -->
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.hook('request', ({ fail }) => {
            // Handle 422 (Validation Error)
            fail(({ status, preventDefault }) => {
                if (status === 422) {
                    setTimeout(() => {
                        // Cari error yang valid (span dengan text-red-600)
                        const firstError = document.querySelector('span.text-red-600');
                        if (firstError) {
                            window.dispatchEvent(new CustomEvent('validation-error')); // Add this
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }, 150);
                }
            })
        })
        
        // Handle standard response that might contain errors
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                setTimeout(() => {
                    const firstError = document.querySelector('span.text-red-600');
                    if (firstError) {
                        window.dispatchEvent(new CustomEvent('validation-error')); // Add this
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 150);
            })
        })
    });
</script>