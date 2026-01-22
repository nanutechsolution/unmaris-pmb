<div class="max-w-4xl mx-auto py-6 md:py-10 font-sans px-4 md:px-0" x-data="{ showConfirmModal: false }"
    @validation-error.window="showConfirmModal = false">

    <!-- DEV TOOL: AUTO FILL BUTTON (Hanya muncul di environment local) -->
    @if (app()->environment('local'))
        <button type="button" title="Klik untuk isi data dummy otomatis"
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
                $wire.set('sumber_informasi', 'brosur');
                
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
        [x-cloak] {
            display: none !important;
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
            <div
                class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-3 md:h-4 bg-gray-200 border-2 border-unmaris-blue rounded-full -z-10">
            </div>
            <div class="bg-unmaris-blue h-3 md:h-4 absolute left-0 top-1/2 transform -translate-y-1/2 border-y-2 border-l-2 border-unmaris-blue rounded-l-full transition-all duration-500 ease-out"
                style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>

            <div class="flex justify-between w-full">
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 1 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        1</div>
                    <span
                        class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Biodata</span>
                </div>
                <!-- Step 2 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 2 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        2</div>
                    <span
                        class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Akademik</span>
                </div>
                <!-- Step 3 -->
                <div class="relative flex flex-col items-center group">
                    <div
                        class="{{ $currentStep >= 3 ? 'bg-unmaris-yellow text-unmaris-blue translate-x-[-2px] translate-y-[-2px] shadow-neo' : 'bg-white text-gray-400' }} w-10 h-10 md:w-12 md:h-12 border-2 border-unmaris-blue rounded-xl flex items-center justify-center font-black text-lg md:text-xl z-10 transition-all duration-200">
                        3</div>
                    <span
                        class="mt-2 md:mt-3 font-bold text-[10px] md:text-xs uppercase bg-white text-unmaris-blue border-2 border-unmaris-blue px-2 py-1 shadow-neo-sm rounded">Berkas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="bg-white p-5 md:p-8 border-4 border-unmaris-blue shadow-neo-lg rounded-3xl relative overflow-hidden">

        <div class="mb-6">
            <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded border border-red-200">(*) Wajib
                Diisi</span>
        </div>

        <!-- ==================== STEP 1 ==================== -->
        @if ($currentStep == 1)
            <div class="animate-fade-in-up space-y-6">
                <!-- ... existing step 1 content ... -->
                <h2
                    class="text-lg md:text-2xl font-black mb-6 text-unmaris-blue uppercase bg-unmaris-yellow inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform -rotate-1 shadow-neo">
                    Langkah 1: Identitas & Jalur
                </h2>

                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <p class="text-sm font-black text-red-700">PENTING: Data Nama, Tempat, dan Tanggal Lahir WAJIB
                        SESUAI DENGAN IJAZAH.</p>
                </div>

                <!-- Fields Step 1 -->
                <div class="space-y-4">
                    <div class="@error('jalur_pendaftaran') has-error @enderror">
                        <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">Jalur Pendaftaran
                            *</label>
                        <select wire:model.live="jalur_pendaftaran"
                            class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue text-sm md:text-base focus:shadow-neo transition-all">
                            <option value="reguler">🔥 Reguler (Umum)</option>
                            <option value="pindahan">🔄 Ekstensi</option>
                        </select>
                    </div>

                    @if ($jalur_pendaftaran == 'beasiswa')
                        <div
                            class="bg-yellow-100 p-4 border-2 border-yellow-500 rounded-lg @error('scholarship_id') has-error @enderror">
                            <label class="block text-sm font-black text-yellow-900 mb-2">Pilih Program Beasiswa
                                *</label>
                            <select wire:model="scholarship_id"
                                class="w-full bg-white border-2 border-yellow-600 rounded-lg py-3 px-4 font-bold text-gray-800">
                                <option value="">-- Pilih Beasiswa --</option>
                                @foreach (\App\Models\Scholarship::where('is_active', true)->get() as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} (Sisa:
                                        {{ max(0, $s->quota - $s->pendaftars_count) }})</option>
                                @endforeach
                            </select>
                            @error('scholarship_id')
                                <span
                                    class="validation-error text-red-600 text-xs font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="@error('nisn') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">NISN</label>
                            <input type="text" inputmode="numeric" wire:model="nisn"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm"
                                {{ $jalur_pendaftaran != 'reguler' ? 'disabled' : '' }}>
                            @error('nisn')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="@error('nik') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">NIK (KTP) *</label>
                            <input type="text" inputmode="numeric" maxlength="16" wire:model="nik"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                            @error('nik')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="@error('tempat_lahir') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">Tempat Lahir *</label>
                            <input type="text" wire:model="tempat_lahir"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                            @error('tempat_lahir')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="@error('tgl_lahir') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">Tanggal Lahir *</label>
                            <input type="date" wire:model="tgl_lahir"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                            @error('tgl_lahir')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="@error('jenis_kelamin') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">Jenis Kelamin *</label>
                            <select wire:model="jenis_kelamin"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="@error('agama') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">Agama *</label>
                            <select wire:model="agama"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                                <option value="">-- Pilih --</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Islam">Islam</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('agama')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2 @error('nomor_hp') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">No. HP / WA *</label>
                            <input type="tel" wire:model="nomor_hp"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                            @error('nomor_hp')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2 @error('alamat') has-error @enderror">
                            <label class="block text-sm font-bold text-unmaris-blue mb-1">Alamat Lengkap (Sesuai KTP)
                                *</label>
                            <textarea wire:model="alamat" rows="2"
                                class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm"></textarea>
                            @error('alamat')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="md:col-span-2 border-t-2 border-dashed border-unmaris-blue my-4 pt-4">
                        <h3 class="font-black text-unmaris-blue text-sm uppercase mb-4">📢 Sumber Informasi</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="@error('sumber_informasi') has-error @enderror">
                                <label class="block text-sm font-bold text-unmaris-blue mb-1">Tahu UNMARIS dari mana?
                                    *</label>
                                <select wire:model.live="sumber_informasi"
                                    class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue text-sm">
                                    <option value="">-- Pilih Sumber --</option>
                                    <option value="brosur">📄 Brosur / Baliho</option>
                                    <option value="medsos">📱 Facebook / Instagram / TikTok</option>
                                    <option value="sekolah">🏫 Sosialisasi Sekolah</option>
                                    <option value="mahasiswa">🎓 Mahasiswa Aktif UNMARIS</option>
                                    <option value="alumni">🎓 Alumni</option>
                                    <option value="dosen">👨‍🏫 Dosen / Staf UNMARIS</option>
                                    <option value="kerabat">👥 Keluarga / Teman Lainnya</option>
                                </select>
                                @error('sumber_informasi')
                                    <span
                                        class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Input Nama Perekomendasi (Muncul Dinamis) -->
                            @if (in_array($sumber_informasi, ['mahasiswa', 'alumni', 'dosen', 'kerabat']))
                                <div class="animate-fade-in-down @error('nama_referensi') has-error @enderror">
                                    <label class="block text-sm font-bold text-green-700 mb-1">
                                        Nama Perekomendasi * <span
                                            class="text-[10px] text-gray-500 font-normal">(Digunakan untuk pencatatan
                                            internal)</span>
                                    </label>
                                    <input type="text" wire:model="nama_referensi"
                                        placeholder="Masukkan nama lengkap..."
                                        class="w-full bg-green-50 border-2 border-green-500 rounded-lg py-3 px-4 font-bold text-green-900 text-sm focus:ring-green-500 focus:border-green-600">
                                    @error('nama_referensi')
                                        <span
                                            class="validation-error text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button wire:click="validateStep1" wire:loading.attr="disabled"
                            class="w-full md:w-auto bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover transition-all transform uppercase tracking-wider flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="validateStep1">Lanjut ke Akademik 👉</span>
                            <span wire:loading wire:target="validateStep1">Memproses... ⏳</span>
                        </button>
                    </div>
                </div>
        @endif

        <!-- ==================== STEP 2 ==================== -->
        @if ($currentStep == 2)
            <div class="animate-fade-in-up space-y-6">
                <!-- ... existing step 2 content ... -->
                <h2
                    class="text-lg md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-blue inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 2: Data Akademik
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2 @error('asal_sekolah') has-error @enderror">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Nama Asal Sekolah *</label>
                        <input type="text" wire:model="asal_sekolah"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                        @error('asal_sekolah')
                            <span class="validation-error text-red-600 text-xs font-bold block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="@error('tahun_lulus') has-error @enderror">
                        <label class="block text-sm font-bold text-unmaris-blue mb-1">Tahun Lulus *</label>
                        <input type="text" inputmode="numeric" maxlength="4" wire:model="tahun_lulus"
                            class="w-full bg-gray-50 border-2 border-unmaris-blue rounded-lg py-3 px-4 font-medium text-unmaris-blue text-sm">
                        @error('tahun_lulus')
                            <span class="validation-error text-red-600 text-xs font-bold block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2 bg-blue-50 p-4 border-2 border-unmaris-blue rounded-xl space-y-4">
                        <div class="@error('pilihan_prodi_1') has-error @enderror">
                            <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">Pilihan 1 (Utama)
                                *</label>
                            <select wire:model.live="pilihan_prodi_1"
                                class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue">
                                <option value="">-- PILIH PRODI UTAMA --</option>
                                @foreach (\App\Models\StudyProgram::all() as $p)
                                    <option value="{{ $p->name }}">{{ $p->name }} ({{ $p->degree }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pilihan_prodi_1')
                                <span
                                    class="validation-error text-red-600 text-xs font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="@error('pilihan_prodi_2') has-error @enderror">
                            <label class="block text-sm font-black text-unmaris-blue mb-2 uppercase">Pilihan 2 (Wajib)
                                *</label>
                            <select wire:model="pilihan_prodi_2"
                                class="w-full bg-white border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold cursor-pointer text-unmaris-blue text-sm">
                                <option value="">-- PILIH PRODI KEDUA --</option>
                                @foreach (\App\Models\StudyProgram::all() as $p)
                                    @if ($p->name != $pilihan_prodi_1)
                                        <option value="{{ $p->name }}">{{ $p->name }}
                                            ({{ $p->degree }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('pilihan_prodi_2')
                                <span
                                    class="validation-error text-red-600 text-xs font-bold block mt-1">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Pilihan kedua akan digunakan jika kuota pilihan utama
                                penuh.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col-reverse md:flex-row justify-between gap-3">
                    <button wire:click="back(1)"
                        class="w-full md:w-auto bg-white border-2 border-unmaris-blue font-bold py-3 px-6 rounded-lg">👈
                        Kembali</button>
                    <button wire:click="validateStep2" wire:loading.attr="disabled"
                        class="w-full md:w-auto bg-unmaris-yellow text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-none transition-all flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="validateStep2">Lanjut ke Berkas 👉</span>
                        <span wire:loading wire:target="validateStep2">Memproses... ⏳</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- ==================== STEP 3 ==================== -->
        @if ($currentStep == 3)
            <div x-data="{ agreed: false, uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true; progress = 0"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
                class="animate-fade-in-up space-y-6">

                <h2
                    class="text-lg md:text-2xl font-black mb-6 text-white uppercase bg-unmaris-green inline-block px-3 md:px-4 py-2 border-2 border-unmaris-blue transform rotate-1 shadow-neo">
                    Langkah 3: Orang Tua & Berkas
                </h2>

                <!-- PROGRESS BAR GLOBAL -->
                <div x-show="uploading"
                    class="fixed top-0 left-0 w-full z-50 bg-black/80 flex items-center justify-center h-full">
                    <div class="bg-white p-6 rounded-xl w-64 text-center">
                        <div class="mb-2 font-bold text-unmaris-blue animate-pulse">Mengunggah... <span
                                x-text="progress + '%'"></span></div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full transition-all duration-300"
                                :style="'width: ' + progress + '%'"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Mohon tunggu, jangan refresh halaman.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Ortu -->
                    <div class="md:col-span-2 bg-green-50 p-4 border-2 border-unmaris-blue border-dashed rounded-xl">
                        <h3 class="font-black text-unmaris-green mb-4">👨‍👩‍👧 Data Orang Tua</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="@error('nama_ayah') has-error @enderror">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nama Ayah (Sesuai KK)
                                    *</label>
                                <input type="text" wire:model="nama_ayah"
                                    class="w-full border-2 border-unmaris-blue rounded px-3 py-2 text-sm">
                                @error('nama_ayah')
                                    <span
                                        class="validation-error text-red-600 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="@error('nama_ibu') has-error @enderror">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nama Ibu (Sesuai KK)
                                    *</label>
                                <input type="text" wire:model="nama_ibu"
                                    class="w-full border-2 border-unmaris-blue rounded px-3 py-2 text-sm">
                                @error('nama_ibu')
                                    <span
                                        class="validation-error text-red-600 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Uploads -->
                    @if ($jalur_pendaftaran == 'beasiswa')
                        <div
                            class="md:col-span-2 bg-yellow-100 p-6 border-4 border-yellow-500 rounded-xl relative shadow-sm @error('file_beasiswa') has-error @enderror">
                            <label class="block text-lg font-black text-yellow-900 mb-1">Upload Berkas Beasiswa
                                *</label>
                            <p class="text-xs font-bold text-yellow-800 mb-4">Gabungan KIP/SKTM/Rapor (PDF, Max 5MB)
                            </p>
                            <input type="file" wire:model="file_beasiswa" wire:key="beasiswa_input"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition" />
                            @error('file_beasiswa')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs mt-1 block bg-red-50 p-1 border border-red-200 rounded text-center">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div
                        class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center hover:bg-yellow-50 transition shadow-neo group relative @error('foto') has-error @enderror">
                        <label class="block text-lg font-black text-unmaris-blue mb-1">Pas Foto (Latar Biru) *</label>
                        <div
                            class="mb-4 inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider text-white bg-black animate-pulse">
                            WAJIB LATAR: {{ $this->warnaLatar }} (BIRU)
                        </div>

                        <div class="mt-2 flex justify-center relative">
                            @if ($foto && method_exists($foto, 'temporaryUrl') && str_starts_with($foto->getMimeType(), 'image/'))
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
                        <input type="file" wire:model="foto" wire:key="foto_input"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        @error('foto')
                            <span
                                class="validation-error text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">{{ $message }}</span>
                        @enderror

                        <!-- Tampilkan peringatan tambahan jika upload bukan gambar -->
                        @php
                            $isImage = false;
                            try {
                                if ($foto) {
                                    $mime = $foto->getMimeType();
                                    $isImage = str_starts_with($mime, 'image/');
                                }
                            } catch (\Exception $e) {
                                $isImage = false;
                            }
                        @endphp
                        @if ($foto && !$isImage)
                            <span
                                class="text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">
                                File harus berupa gambar (JPG/PNG)!
                            </span>
                        @endif

                        <p class="text-[10px] text-gray-500 mt-2">Format: JPG/PNG. Max: 2MB. Wajah harus terlihat
                            jelas.</p>
                    </div>

                    <div
                        class="bg-white border-2 border-unmaris-blue rounded-xl p-6 text-center shadow-neo relative group @error('file_ktp') has-error @enderror">
                        <label class="block text-lg font-black text-unmaris-blue mb-1">Scan KTP/KK *</label>
                        <span class="text-xs font-bold text-gray-400 block mb-4">PDF / JPG</span>
                        <div class="mt-2 flex justify-center relative h-20 mb-4">
                            @if ($file_ktp)
                                <div
                                    class="bg-green-100 text-green-700 p-2 rounded flex items-center gap-2 border border-green-300">
                                    <span class="text-xl">✅</span> <span
                                        class="text-xs font-bold truncate w-24">{{ $file_ktp->getClientOriginalName() }}</span>
                                </div>
                            @elseif($existingKtpPath)
                                <div
                                    class="bg-blue-100 text-blue-700 p-2 rounded flex items-center gap-2 border border-blue-300">
                                    <span class="text-xl">📂</span> <span class="text-xs font-bold">File
                                        Tersimpan</span>
                                </div>
                            @else
                                <div
                                    class="w-full h-full border-2 border-dashed border-gray-300 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">Klik Upload</span>
                                </div>
                            @endif
                        </div>
                        <input type="file" wire:model="file_ktp" wire:key="ktp_input"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            accept=".pdf,.jpg,.jpeg,.png">
                        @error('file_ktp')
                            <span
                                class="validation-error text-red-600 font-bold text-xs block mt-2 bg-red-50 p-1 border border-red-200 rounded">{{ $message }}</span>
                        @enderror
                        <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG. Max: 2MB.</p>
                    </div>

                    <div
                        class="md:col-span-2 bg-yellow-50 border-2 border-yellow-500 border-dashed rounded-xl p-6 text-center relative group">
                        <label class="block text-lg font-black text-yellow-800 mb-1">Scan Akta Kelahiran
                            (Opsional)</label>
                        <span
                            class="text-xs font-bold text-green-600 block mb-4 bg-white px-2 py-1 inline-block rounded border border-green-500 uppercase">Opsional
                            (Jika Ada)</span>
                        <div class="mt-2 flex justify-center relative h-12 mb-2">
                            @if ($file_akta)
                                <span
                                    class="text-xs font-bold text-green-600 bg-white px-2 py-1 rounded border border-green-500">File
                                    Dipilih: {{ $file_akta->getClientOriginalName() }}</span>
                            @elseif($existingAktaPath)
                                <span
                                    class="text-xs font-bold text-blue-600 bg-white px-2 py-1 rounded border border-blue-500">File
                                    Tersimpan</span>
                            @else
                                <span class="text-xs text-gray-500">Klik area ini untuk upload</span>
                            @endif
                        </div>
                        <input type="file" wire:model="file_akta" wire:key="akta_input"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            accept=".pdf,.jpg,.jpeg,.png">
                        @error('file_akta')
                            <span
                                class="validation-error text-red-600 font-bold text-xs block mt-2">{{ $message }}</span>
                        @enderror
                        <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG/PNG. Max: 2MB.</p>
                    </div>

                    <!-- Dokumen Kelulusan -->
                    <div class="md:col-span-2 bg-blue-50 border-4 border-unmaris-blue rounded-xl p-6 relative">
                        <div class="mb-4 @error('jenis_dokumen') has-error @enderror">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dokumen Kelulusan: *</label>
                            <div class="flex gap-4">
                                <label
                                    class="flex items-center gap-2 bg-white px-4 py-2 rounded border hover:border-unmaris-blue"><input
                                        type="radio" wire:model.live="jenis_dokumen" value="ijazah"><span
                                        class="font-bold text-sm">Ijazah Asli</span></label>
                                <label
                                    class="flex items-center gap-2 bg-white px-4 py-2 rounded border hover:border-unmaris-blue"><input
                                        type="radio" wire:model.live="jenis_dokumen" value="skl"><span
                                        class="font-bold text-sm">📄 SKL (Surat Keterangan Lulus)</span></label>
                            </div>
                            @error('jenis_dokumen')
                                <span
                                    class="validation-error text-red-600 font-bold text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                class="bg-white border-2 border-gray-300 border-dashed rounded-xl p-4 text-center relative @error('ijazah') has-error @enderror">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Upload
                                    {{ $jenis_dokumen == 'skl' ? 'SKL' : 'Ijazah' }} *</label>
                                <span class="text-[10px] text-gray-400 block mb-2">PDF / JPG</span>
                                @if ($ijazah)
                                    <div class="text-xs text-green-600 font-bold my-2">File OK:
                                        {{ $ijazah->getClientOriginalName() }}</div>
                                @elseif($existingIjazahPath)
                                    <div class="text-xs text-blue-600 font-bold my-2">File Tersimpan</div>
                                @endif
                                <input type="file" wire:model="ijazah" wire:key="ijazah_input"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                <div class="mt-8 text-gray-400 text-xs">Klik untuk upload (PDF/JPG)</div>
                                @error('ijazah')
                                    <span
                                        class="validation-error text-red-600 font-bold text-xs block mt-1 bg-red-50 p-1 border border-red-200 rounded relative z-10">{{ $message }}</span>
                                @enderror
                                <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG/PNG. Max: 2MB.</p>
                            </div>

                            @if ($jenis_dokumen == 'ijazah')
                                <div
                                    class="bg-white border-2 border-dashed border-red-400 bg-red-50 rounded-xl p-4 text-center relative @error('transkrip') has-error @enderror">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Transkrip Nilai <span
                                            class="text-red-600 bg-red-100 px-1 rounded text-[10px]">Wajib</span></label>
                                    <span class="text-[10px] text-gray-400 block mb-2">Halaman nilai di belakang
                                        Ijazah</span>
                                    @if ($transkrip)
                                        <div class="text-xs text-green-600 font-bold my-2">File OK:
                                            {{ $transkrip->getClientOriginalName() }}</div>
                                    @elseif($existingTranskripPath)
                                        <div class="text-xs text-blue-600 font-bold my-2">File Tersimpan</div>
                                    @endif
                                    <input type="file" wire:model="transkrip" wire:key="transkrip_input"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                    <div class="mt-8 text-gray-400 text-xs">Klik untuk upload (PDF/JPG)</div>
                                    @error('transkrip')
                                        <span
                                            class="validation-error text-red-600 font-bold text-xs block mt-1 bg-red-100 p-1 border border-red-300 rounded relative z-10">{{ $message }}</span>
                                    @enderror
                                    <p class="text-[10px] text-gray-500 mt-2">Format: PDF/JPG/PNG. Max: 2MB.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Checkbox Persetujuan (Fixed Color Conflict) -->
                <div class="mt-8 p-5 rounded-xl border-4 transition-colors duration-300 shadow-neo"
                    :class="agreed ? 'bg-green-100 border-green-600' : 'bg-yellow-100 border-unmaris-blue'">
                    <label class="flex items-start gap-4 cursor-pointer group">
                        <div class="relative flex-shrink-0 mt-1">
                            <input type="checkbox" x-model="agreed"
                                class="w-8 h-8 text-unmaris-blue border-4 border-black rounded focus:ring-0 cursor-pointer">
                            <span x-show="!agreed"
                                class="animate-ping absolute inset-0 rounded-md bg-yellow-600 opacity-75"></span>
                        </div>
                        <div>
                            <div class="font-black text-xs uppercase tracking-widest mb-1"
                                :class="agreed ? 'text-green-800' : 'text-red-500'"> <!-- Changed to red-500 -->
                                <span x-show="!agreed">⚠️ WAJIB DICENTANG</span>
                                <span x-show="agreed" style="display: none;">✅ TERIMA KASIH</span>
                            </div>
                            <span class="text-sm md:text-base font-bold text-gray-900 leading-tight">Saya menyatakan
                                bahwa data ini benar.</span>
                        </div>
                    </label>
                </div>

                <div class="mt-8 flex flex-col-reverse md:flex-row justify-between items-center gap-3">
                    <button wire:click="back(2)"
                        class="w-full md:w-auto bg-white border-2 border-unmaris-blue font-bold py-3 px-6 rounded-lg">👈
                        Kembali</button>
                    <!-- Trigger Modal Confirmation -->
                    <button @click="if(agreed) showConfirmModal = true" :disabled="!agreed"
                        :class="{
                            'opacity-50 cursor-not-allowed': !
                                agreed,
                            'bg-unmaris-green hover:bg-green-600 hover:shadow-none': agreed
                        }"
                        class="w-full md:w-auto bg-gray-300 text-white font-black py-3 px-8 rounded-lg border-2 border-unmaris-blue shadow-neo transition-all transform uppercase tracking-wider flex justify-center items-center">
                        KIRIM PENDAFTARAN 🚀
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL KONFIRMASI -->
    <div x-show="showConfirmModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <div
            class="bg-white border-4 border-unmaris-blue rounded-3xl p-6 max-w-md w-full shadow-2xl text-center relative">
            <div
                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-yellow-400 border-4 border-black rounded-full p-4 shadow-neo text-4xl">
                🤔</div>
            <div class="mt-8">
                <h3 class="text-2xl font-black text-unmaris-blue uppercase mb-2">Yakin Data Benar?</h3>
                <p class="text-gray-600 text-sm mb-6 font-medium">Data tidak bisa diubah setelah dikirim.</p>
                <div class="flex flex-col gap-3">
                    <!-- Button Submit: Tidak menutup modal manual, biarkan Livewire handle redirect/error -->
                    <button wire:click="submit" wire:loading.attr="disabled"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-3 rounded-xl border-2 border-black shadow-neo hover:shadow-none transition-all uppercase flex justify-center items-center gap-2">
                        <span wire:loading.remove>✅ Ya, Kirim!</span>
                        <span wire:loading>Menyimpan... ⏳</span>
                    </button>
                    <button @click="showConfirmModal = false"
                        class="w-full bg-white hover:bg-gray-100 text-gray-700 font-bold py-3 rounded-xl border-2 border-gray-300 transition-all uppercase">🔍
                        Cek Lagi</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- SMART AUTO-SCROLL SCRIPT -->
<script>
    document.addEventListener('livewire:initialized', () => {
        const scrollToError = () => {
            // Prioritas 1: Scroll ke elemen yang punya class .has-error (Wrapper Input)
            const inputError = document.querySelector('.has-error');
            if (inputError) {
                // Scroll sedikit ke atas agar label terlihat
                const y = inputError.getBoundingClientRect().top + window.scrollY - 100;
                window.scrollTo({
                    top: y,
                    behavior: 'smooth'
                });
                inputError.classList.add('ring-4', 'ring-red-300', 'transition-all');
                setTimeout(() => inputError.classList.remove('ring-4', 'ring-red-300'), 2000);
                return true;
            }
            // Prioritas 2: Scroll ke pesan error teks langsung (Fallback)
            const textError = document.querySelector('.validation-error');
            if (textError) {
                textError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return true;
            }
            return false;
        };

        // Handle error status 422 (jika ada)
        Livewire.hook('request', ({
            fail
        }) => {
            fail(({
                status,
                preventDefault
            }) => {
                if (status === 422) {
                    window.dispatchEvent(new CustomEvent('validation-error'));
                    setTimeout(scrollToError, 200);
                }
            })
        });

        // Handle update sukses (cek apakah muncul error di DOM)
        Livewire.hook('commit', ({
            component,
            commit,
            respond,
            succeed,
            fail
        }) => {
            succeed(({
                snapshot,
                effect
            }) => {
                // FIX: Hapus pengecekan snapshot.memo.errors yang bikin error
                // Langsung saja cek DOM setelah render selesai
                setTimeout(() => {
                    // Coba scroll, jika ditemukan error maka tutup modal (jika ada)
                    if (scrollToError()) {
                        window.dispatchEvent(new CustomEvent('validation-error'));
                    }
                }, 200);
            })
        });
    });
</script>
