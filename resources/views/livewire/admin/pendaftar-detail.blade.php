<div class="min-h-screen bg-gray-50/50 pb-20">
  

    <!-- DATA PREPARATION -->
    @php
        $steps = [
            1 => ['label' => 'Pembayaran', 'desc' => 'Verifikasi Adm'],
            2 => ['label' => 'Verifikasi Berkas', 'desc' => 'Cek Dokumen'],
            3 => ['label' => 'Seleksi & Ujian', 'desc' => 'Penilaian'],
            4 => ['label' => 'Integrasi', 'desc' => 'Push Siakad'],
            5 => ['label' => 'Selesai', 'desc' => 'Finalisasi']
        ];

        $currentStep = 1;
        
        if ($pendaftar->status_pembayaran == 'lunas') $currentStep = 2;
        if ($pendaftar->status_pembayaran == 'lunas' && $pendaftar->status_pendaftaran == 'verifikasi') $currentStep = 3;
        if ($pendaftar->status_pendaftaran == 'lulus') $currentStep = 4;
        if ($pendaftar->is_synced) $currentStep = 5;
        if ($pendaftar->status_pendaftaran == 'gagal') $currentStep = 99; 
        if ($pendaftar->status_pendaftaran == 'perbaikan') $currentStep = 2; 

        // LIST DOKUMEN
        $documents = [
            ['id' => 'ktp', 'label' => 'Kartu Identitas (KTP/KK)', 'file' => $pendaftar->ktp_path, 'icon' => '🪪'],
            ['id' => 'akta', 'label' => 'Akta Kelahiran', 'file' => $pendaftar->akta_path, 'icon' => '👶'],
            ['id' => 'ijazah', 'label' => 'Ijazah / SKL', 'file' => $pendaftar->ijazah_path, 'icon' => '🎓'],
            ['id' => 'transkrip', 'label' => 'Transkrip Nilai', 'file' => $pendaftar->transkrip_path, 'icon' => '📊'],
        ];
        
        if($pendaftar->file_beasiswa) {
            $documents[] = ['id' => 'beasiswa', 'label' => 'Berkas Beasiswa', 'file' => $pendaftar->file_beasiswa, 'icon' => '💸'];
        }

        // Hitung file yang benar-benar ada
        $uploadedDocs = array_filter($documents, fn($d) => !empty($d['file']));
        $uploadedDocsCount = count($uploadedDocs);

        // Jika sudah diverifikasi/lulus, anggap semua terceklis
        $isVerified = in_array($pendaftar->status_pendaftaran, ['verifikasi', 'lulus']);
        $initialChecked = $isVerified ? array_column($uploadedDocs, 'id') : [];

        // Ambil data rejection history
        $docStatus = $pendaftar->doc_status ?? [];
    @endphp

    <div x-data='{ 
            checkedDocs: @json($initialChecked), 
            totalDocs: {{ $uploadedDocsCount }},
            activeTab: "berkas",
            
            // Modal Tolak
            showRejectModal: false,
            rejectDocId: null,
            rejectDocLabel: "",
            rejectReason: "",

            // Modal Lulus
            showLulusModal: false,
            lulusChoice: null,
            lulusProdiName: "",

            isComplete() {
                if (this.totalDocs === 0) return true;
                return this.checkedDocs.length >= this.totalDocs;
            },
            openRejectModal(id, label) {
                this.rejectDocId = id;
                this.rejectDocLabel = label;
                this.rejectReason = "";
                this.showRejectModal = true;
            },
            openLulusModal(choice, prodiName) {
                this.lulusChoice = choice;
                this.lulusProdiName = prodiName;
                this.showLulusModal = true;
            }
         }'>
        
        <!-- HEADER STICKY -->
        <div class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Left -->
                    <div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                            <a href="{{ route('admin.pendaftar.index') }}" class="hover:text-gray-900">Data Pendaftar</a>
                            <span>/</span>
                            <span>#{{ $pendaftar->id }}</span>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            {{ $pendaftar->user->name }}
                            @if($currentStep == 99)
                                <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-700">TIDAK LULUS</span>
                            @elseif($pendaftar->status_pendaftaran == 'perbaikan')
                                <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-bold text-orange-700 border border-orange-200">⏳ MENUNGGU PERBAIKAN</span>
                            @else
                                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700">{{ $steps[$currentStep]['label'] ?? 'Proses' }}</span>
                            @endif
                        </h1>
                    </div>
                    <!-- Right -->
                    <div>
                        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            🖨️ Cetak
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- NOTIFIKASI -->
            @if (session()->has('success'))
                <div class="bg-green-100 px-4 py-2 text-center text-sm font-bold text-green-700">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 px-4 py-2 text-center text-sm font-bold text-red-700">
                    ⚠️ {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- STEPPER -->
            <div class="hidden lg:block mb-8">
                <nav aria-label="Progress">
                    <ol role="list" class="divide-y divide-gray-300 rounded-xl border border-gray-200 bg-white md:flex md:divide-y-0 shadow-sm">
                        @foreach ($steps as $key => $step)
                            @if($key > 5) @continue @endif
                            <li class="relative md:flex md:flex-1">
                                @php
                                    $status = ($currentStep > $key) ? 'complete' : (($currentStep == $key) ? 'current' : 'upcoming');
                                    if($pendaftar->status_pendaftaran == 'perbaikan' && $key == 2) $status = 'current';
                                @endphp
                                <a href="#" class="group flex w-full items-center">
                                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full transition {{ $status == 'complete' ? 'bg-green-600' : ($status == 'current' ? 'border-2 border-indigo-600' : 'border-2 border-gray-300') }}">
                                            @if($status == 'complete')
                                                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" /></svg>
                                            @elseif($status == 'current')
                                                <span class="text-indigo-600 font-bold text-xs">{{ $key }}</span>
                                            @else
                                                <span class="text-gray-500 text-xs">{{ $key }}</span>
                                            @endif
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-xs font-bold uppercase tracking-wide {{ $status == 'upcoming' ? 'text-gray-400' : ($status == 'current' ? 'text-indigo-600' : 'text-gray-900') }}">{{ $step['label'] }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $step['desc'] }}</span>
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>

            <!-- ACTION CENTER -->
            <div class="mb-8">
                @if($currentStep == 1)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="rounded-full bg-gray-100 p-3 text-gray-600">💰</div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Status Pembayaran</h3>
                                <p class="text-sm text-gray-500">
                                    @if($pendaftar->status_pembayaran == 'menunggu_verifikasi') Bukti masuk. Verifikasi sekarang. @else Menunggu pembayaran. @endif
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-auto">
                            @livewire('admin.payment-verifier', ['pendaftar' => $pendaftar], key($pendaftar->id))
                        </div>
                    </div>

                @elseif($currentStep == 2)
                    <!-- STEP 2: VERIFIKASI BERKAS -->
                    @if($pendaftar->status_pendaftaran == 'perbaikan')
                        <div class="bg-orange-50 rounded-xl shadow-sm border border-orange-200 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex items-start gap-4">
                                <div class="rounded-full bg-orange-100 p-3 text-orange-600">⚠️</div>
                                <div>
                                    <h3 class="text-base font-bold text-orange-900">Menunggu Perbaikan Peserta</h3>
                                    <p class="text-sm text-orange-800">Menunggu peserta mengunggah ulang dokumen.</p>
                                </div>
                            </div>
                            <div class="w-full md:w-auto">
                                <button disabled class="bg-gray-300 text-white font-bold py-2 px-4 rounded cursor-not-allowed text-sm">Menunggu Upload...</button>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row items-center justify-between gap-6 transition-all duration-300"
                             :class="isComplete() ? 'border-green-400 ring-1 ring-green-100' : 'border-yellow-300'">
                            
                            <div class="flex items-start gap-4">
                                <div class="rounded-full p-3 transition-colors" :class="isComplete() ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600'">
                                    <span x-show="!isComplete()">📂</span>
                                    <span x-show="isComplete()" style="display: none">✅</span>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">Validasi Dokumen</h3>
                                    <div class="mt-1 text-sm text-gray-600">
                                        Checklist: <span class="font-bold" :class="isComplete() ? 'text-green-600' : 'text-red-500'" x-text="checkedDocs.length"></span> / {{ $uploadedDocsCount }}
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1" x-show="!isComplete()">Centang semua dokumen "Valid" di bawah.</p>
                                </div>
                            </div>

                            <button type="button"
                                    wire:click="updateStatus('verifikasi')" 
                                    :disabled="!isComplete()"
                                    wire:loading.attr="disabled"
                                    :class="isComplete() ? 'bg-green-600 hover:bg-green-700 shadow-sm cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                                    class="inline-flex items-center gap-2 rounded-lg px-6 py-3 text-sm font-bold text-white transition-all disabled:opacity-50">
                                <span wire:loading.remove wire:target="updateStatus('verifikasi')" x-text="isComplete() ? 'Nyatakan Valid & Lanjut' : 'Lengkapi Checklist Dulu'"></span>
                                <span wire:loading wire:target="updateStatus('verifikasi')">Memproses...</span>
                            </button>
                        </div>
                    @endif

                @elseif($currentStep == 3)
                    <!-- STEP 3: SELEKSI -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Keputusan Seleksi</h3>
                                <p class="text-sm text-gray-500">Berkas valid. Tentukan kelulusan berdasarkan nilai.</p>
                            </div>
                            
                            <!-- NILAI TULIS & WAWANCARA -->
                            <div class="flex gap-3">
                                <div class="text-right">
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Ujian Tulis</span>
                                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-sm font-black {{ $pendaftar->nilai_ujian > 0 ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-gray-100 text-gray-400 border border-gray-200' }}">
                                        {{ $pendaftar->nilai_ujian > 0 ? $pendaftar->nilai_ujian : '-' }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Wawancara</span>
                                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-sm font-black {{ $pendaftar->nilai_wawancara > 0 ? 'bg-orange-50 text-orange-700 border border-orange-100' : 'bg-gray-100 text-gray-400 border border-gray-200' }}">
                                        {{ $pendaftar->nilai_wawancara > 0 ? $pendaftar->nilai_wawancara : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ openGagal: false }">
                            @if($pendaftar->nilai_ujian > 0)
                                <button type="button" @click="openLulusModal(1, '{{ $pendaftar->pilihan_prodi_1 }}')" class="block w-full rounded-lg border-2 border-dashed border-gray-300 p-4 hover:border-green-500 hover:bg-green-50 text-left transition group">
                                    <span class="block text-sm font-semibold text-gray-900 group-hover:text-green-700">Lulus Pilihan 1</span>
                                    <span class="block text-xs text-gray-500">{{ $pendaftar->pilihan_prodi_1 }}</span>
                                </button>

                                @if($pendaftar->pilihan_prodi_2)
                                <button type="button" @click="openLulusModal(2, '{{ $pendaftar->pilihan_prodi_2 }}')" class="block w-full rounded-lg border-2 border-dashed border-gray-300 p-4 hover:border-green-500 hover:bg-green-50 text-left transition group">
                                    <span class="block text-sm font-semibold text-gray-900 group-hover:text-green-700">Lulus Pilihan 2</span>
                                    <span class="block text-xs text-gray-500">{{ $pendaftar->pilihan_prodi_2 }}</span>
                                </button>
                                @endif
                                
                                <!-- OPSI REKOMENDASI -->
                                @if($pendaftar->rekomendasi_prodi)
                                <button type="button" @click="openLulusModal(3, '{{ $pendaftar->rekomendasi_prodi }}')" class="block w-full rounded-lg border-2 border-dashed border-purple-300 bg-purple-50 p-4 hover:border-purple-500 hover:bg-purple-100 text-left transition group">
                                    <span class="block text-sm font-bold text-purple-900 group-hover:text-purple-700">⭐ Lulus Rekomendasi</span>
                                    <span class="block text-xs text-purple-600">{{ $pendaftar->rekomendasi_prodi }}</span>
                                </button>
                                @endif

                            @else
                                <div class="col-span-2 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center text-sm text-gray-500">
                                    ⚠️ Input <strong>Nilai Ujian</strong> di tab "Akademik" di bawah untuk membuka opsi kelulusan.
                                </div>
                            @endif

                            <button type="button" wire:click="updateStatus('gagal')" wire:confirm="Yakin nyatakan TIDAK LULUS?" class="block w-full rounded-lg border-2 border-dashed border-gray-300 p-4 hover:border-red-500 hover:bg-red-50 text-left transition">
                                <span class="block text-sm font-semibold text-red-700">Tidak Lulus</span>
                                <span class="block text-xs text-gray-500">Tolak Pendaftaran</span>
                            </button>
                        </div>

                        <!-- FORM INPUT REKOMENDASI (DI BAWAH GRID) -->
                        @if($pendaftar->nilai_ujian > 0)
                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-5">
                            <h4 class="text-sm font-bold text-yellow-900 mb-3 flex items-center gap-2">
                                <span>💡</span> Opsi Alternatif (Rekomendasi Prodi)
                            </h4>
                            <form wire:submit.prevent="simpanRekomendasi" class="flex flex-col md:flex-row gap-4 items-end">
                                <div class="w-full">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1">Pilih Prodi Rekomendasi</label>
                                    <select wire:model="rekomendasi_prodi" class="w-full text-sm border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500">
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach($prodiList as $prodi) 
                                            <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase mb-1">Catatan Panitia</label>
                                    <input type="text" wire:model="catatan_seleksi" class="w-full text-sm border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500" placeholder="Alasan rekomendasi...">
                                </div>
                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-md text-sm shadow-sm whitespace-nowrap transition">
                                    Simpan
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                @elseif($currentStep == 4)
                    <!-- STEP 4: INTEGRASI -->
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                        <div class="flex items-start gap-4">
                            <div class="rounded-full bg-indigo-100 p-3 text-indigo-600">🚀</div>
                            <div>
                                <h3 class="text-lg font-bold text-indigo-900">Siap Sinkronisasi</h3>
                                <p class="text-sm text-indigo-700">Lulus di <strong>{{ $pendaftar->prodi_diterima }}</strong>. 
                                @if(auth()->user()->role === 'admin') Kirim data ke SIAKAD. @else Menunggu Admin Pusat. @endif</p>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <form wire:submit.prevent="syncToSiakad">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition" onclick="return confirm('Kirim sekarang?')">Push ke SIAKAD</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 3. DATA CONTENT (GRID LAYOUT) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- LEFT: PROFILE -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white shadow-lg border border-gray-100 rounded-2xl overflow-hidden group">
                        <!-- Decorative Header -->
                        <div class="h-24 bg-gradient-to-br from-indigo-500 to-purple-600 relative"><div class="absolute inset-0 bg-white/10"></div></div>
                        <div class="relative px-6 pb-6 text-center -mt-12">
                            <div class="relative inline-block mb-4">
                                @if ($pendaftar->foto_path)
                                    <div class="relative w-32 h-40 mx-auto rounded-lg shadow-xl overflow-hidden border-4 border-white bg-gray-200 group-hover:scale-105 transition duration-300 cursor-zoom-in" onclick="window.open('{{ asset('storage/' . $pendaftar->foto_path) }}', '_blank')">
                                         <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-32 h-40 mx-auto rounded-lg shadow-xl border-4 border-white bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                                        <span class="text-[10px] font-bold uppercase tracking-wider">No Foto</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $pendaftar->user->name }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ $pendaftar->user->email }}</p>
                            
                            <div class="mt-6 border-t border-gray-100 pt-4 text-left space-y-3">
                                <div class="flex justify-between items-center group/item hover:bg-gray-50 p-2 rounded-lg transition -mx-2">
                                    <span class="text-xs font-medium text-gray-500">Jalur Masuk</span>
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full capitalize">{{ $pendaftar->jalur_pendaftaran }}</span>
                                </div>
                                <div class="flex justify-between items-center group/item hover:bg-gray-50 p-2 rounded-lg transition -mx-2">
                                    <span class="text-xs font-medium text-gray-500">WhatsApp</span>
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pendaftar->nomor_hp) }}" target="_blank" class="text-xs font-bold text-green-600 hover:text-green-700 flex items-center gap-1">
                                        {{ $pendaftar->nomor_hp ?? '-' }} 
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    </a>
                                </div>
                                <div>
                                    <span class="text-xs font-medium text-gray-500 block mb-1">Asal Sekolah</span>
                                    <span class="text-sm font-bold text-gray-800 line-clamp-2" title="{{ $pendaftar->asal_sekolah }}">{{ $pendaftar->asal_sekolah }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: TABS -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden min-h-[500px]">
                        <div class="border-b border-gray-200 px-4">
                            <nav class="-mb-px flex space-x-8">
                                <button @click="activeTab = 'berkas'" :class="activeTab === 'berkas' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-4 px-1 border-b-2 font-medium text-sm">Dokumen</button>
                                <button @click="activeTab = 'biodata'" :class="activeTab === 'biodata' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-4 px-1 border-b-2 font-medium text-sm">Biodata</button>
                                <button @click="activeTab = 'akademik'" :class="activeTab === 'akademik' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-4 px-1 border-b-2 font-medium text-sm">Akademik</button>
                            </nav>
                        </div>

                        <div class="p-6">
                            <!-- TAB BERKAS (CHECKLIST FIX) -->
                            <div x-show="activeTab === 'berkas'">
                                @if($uploadedDocsCount > 0 && $currentStep == 2 && $pendaftar->status_pendaftaran != 'perbaikan')
                                <div class="mb-4 p-3 bg-blue-50 text-blue-700 rounded-md border border-blue-100 text-xs flex items-center gap-2">
                                    <span>ℹ️ Centang kotak "Valid" setelah memeriksa dokumen asli.</span>
                                </div>
                                @endif

                                <ul role="list" class="divide-y divide-gray-100 rounded-lg border border-gray-100">
                                    @foreach($documents as $doc)
                                        @php
                                            // FIX LOGIKA REJECT: Cek histori penolakan
                                            $isRejected = isset($docStatus[$doc['id']]) && $docStatus[$doc['id']]['status'] == 'rejected';
                                            $rejectReason = $isRejected ? $docStatus[$doc['id']]['reason'] : '';
                                        @endphp
                                    <li class="flex items-center justify-between py-4 px-4 hover:bg-gray-50 transition">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xl">{{ $doc['icon'] }}</span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $doc['label'] }}</p>
                                                @if(!$doc['file'])
                                                    <p class="text-[10px] text-red-500 font-bold uppercase">Belum Upload</p>
                                                @else
                                                    @if($isRejected && $pendaftar->status_pendaftaran == 'perbaikan')
                                                        <p class="text-[10px] text-red-600 font-bold uppercase mt-1">⚠️ Revisi: {{ $rejectReason }}</p>
                                                    @else
                                                        @if($isRejected)
                                                            <div class="flex items-center gap-2 mt-1 mb-1">
                                                                <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">REVISI MASUK</span>
                                                            </div>
                                                        @endif

                                                        @if($currentStep == 2 && $pendaftar->status_pendaftaran != 'perbaikan')
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="checkbox" value="{{ $doc['id'] }}" x-model="checkedDocs" class="rounded border-gray-300 text-indigo-600 shadow-sm w-4 h-4">
                                                                    <span class="ml-2 text-xs font-bold text-gray-500">Valid</span>
                                                                </label>
                                                            </div>
                                                            <!-- TOMBOL TOLAK -->
                                                            <button type="button" @click="openRejectModal('{{ $doc['id'] }}', '{{ $doc['label'] }}')" class="text-[10px] text-red-500 font-bold hover:text-red-700 hover:underline mt-1 block">
                                                                {{ $isRejected ? 'Tolak Lagi?' : 'Tolak / Bermasalah?' }}
                                                            </button>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($doc['file'])
                                                <a href="{{ asset('storage/'.$doc['file']) }}" target="_blank" class="inline-flex items-center gap-1 rounded bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-100">Lihat ↗</a>
                                            @else
                                                <span class="text-gray-300 text-xs italic">--</span>
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- TAB BIODATA -->
                            <div x-show="activeTab === 'biodata'" x-cloak>
                                <div class="px-4 py-5 sm:px-6"><h3 class="text-base font-semibold leading-6 text-gray-900">Informasi Pribadi</h3></div>
                                <div class="border-t border-gray-100">
                                    <dl class="divide-y divide-gray-100">
                                        <!-- Pas Foto -->
                                        @if($pendaftar->foto_path)
                                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-gray-50/50 transition">
                                            <dt class="text-sm font-medium text-gray-500">Pas Foto</dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" class="h-16 w-12 object-cover rounded border border-gray-200 shadow-sm">
                                                    <a href="{{ asset('storage/' . $pendaftar->foto_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 font-medium text-xs">Lihat Ukuran Penuh ↗</a>
                                                </div>
                                            </dd>
                                        </div>
                                        @endif
                                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"><dt class="text-sm font-medium text-gray-500">NIK</dt><dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $pendaftar->nik }}</dd></div>
                                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"><dt class="text-sm font-medium text-gray-500">TTL</dt><dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tgl_lahir instanceof \DateTime ? $pendaftar->tgl_lahir->format('d F Y') : $pendaftar->tgl_lahir }}</dd></div>
                                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"><dt class="text-sm font-medium text-gray-500">Alamat</dt><dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $pendaftar->alamat }}</dd></div>
                                    </dl>
                                </div>
                                <div class="px-4 py-5 sm:px-6 mt-6 border-t-4 border-gray-50"><h3 class="text-base font-semibold leading-6 text-gray-900">Data Orang Tua</h3></div>
                                <div class="border-t border-gray-100">
                                    <dl class="divide-y divide-gray-100">
                                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"><dt class="text-sm font-medium text-gray-500">Ayah</dt><dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $pendaftar->nama_ayah }}</dd></div>
                                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"><dt class="text-sm font-medium text-gray-500">Ibu</dt><dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $pendaftar->nama_ibu }}</dd></div>
                                    </dl>
                                </div>
                            </div>
                            
                            <!-- TAB AKADEMIK -->
                            <div x-show="activeTab === 'akademik'" x-cloak>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 border rounded-lg bg-gray-50"><span class="text-xs text-gray-500 uppercase">Pilihan 1</span><p class="font-bold">{{ $pendaftar->pilihan_prodi_1 }}</p></div>
                                    @if($pendaftar->pilihan_prodi_2)
                                    <div class="p-4 border rounded-lg bg-gray-50"><span class="text-xs text-gray-500 uppercase">Pilihan 2</span><p class="font-bold">{{ $pendaftar->pilihan_prodi_2 }}</p></div>
                                    @endif
                                </div>
                                <!-- TAMBAHAN: RINCIAN NILAI DI TAB AKADEMIK -->
                                <div class="mt-6 border-t border-gray-100 pt-6">
                                    <h4 class="text-sm font-bold text-gray-900 mb-4">Hasil Seleksi</h4>
                                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500">Nilai Ujian Tulis</dt>
                                            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $pendaftar->nilai_ujian > 0 ? $pendaftar->nilai_ujian : '-' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500">Nilai Wawancara</dt>
                                            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $pendaftar->nilai_wawancara > 0 ? $pendaftar->nilai_wawancara : '-' }}</dd>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <dt class="text-xs font-medium text-gray-500">Catatan Pewawancara</dt>
                                            <dd class="mt-1 text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 italic">
                                                {{ $pendaftar->catatan_wawancara ?? 'Tidak ada catatan.' }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MODAL TOLAK -->
        <div x-show="showRejectModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm transform transition-all">
                <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-bold text-red-600">Tolak Dokumen</h3><button @click="showRejectModal = false" class="text-gray-400 hover:text-gray-600">✕</button></div>
                <p class="text-sm text-gray-700 mb-2">Dokumen: <strong x-text="rejectDocLabel"></strong></p>
                <p class="text-xs text-gray-500 mb-3">Jelaskan alasan penolakan.</p>
                <textarea x-model="rejectReason" class="w-full border-gray-300 rounded-lg text-sm mb-4" rows="3" placeholder="Contoh: Tulisan blur..."></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showRejectModal=false" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg font-bold hover:bg-gray-200">Batal</button>
                    <button type="button" wire:click="rejectDocument(rejectDocId, rejectReason)" @click="showRejectModal = false" :disabled="!rejectReason" class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg font-bold hover:bg-red-700 disabled:opacity-50">Kirim Penolakan</button>
                </div>
            </div>
        </div>

        <!-- MODAL KONFIRMASI LULUS -->
        <div x-show="showLulusModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white p-6 rounded-xl shadow-2xl w-full max-w-sm transform transition-all">
                <div class="text-center mb-6">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mb-4">
                        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi Kelulusan</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Apakah Anda yakin meluluskan peserta ini pada Program Studi:
                    </p>
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <span class="block text-lg font-black text-green-800" x-text="lulusProdiName"></span>
                    </div>
                </div>
                
                <div class="flex justify-center gap-3">
                    <button type="button" @click="showLulusModal=false" class="px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                    
                    <!-- FIX: Gunakan @click dan $wire untuk logika dinamis -->
                    <button type="button" 
                            @click="
                                if (lulusChoice === 3) {
                                    $wire.lulusRekomendasi();
                                } else {
                                    $wire.lulusPilihan(lulusChoice);
                                }
                                showLulusModal = false;
                            "
                            class="px-6 py-2 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Ya, Luluskan!
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>