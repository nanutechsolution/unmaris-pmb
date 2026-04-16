<div class="min-h-screen bg-gray-50/50 pb-24 lg:pb-12 overflow-x-hidden">
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

        $uploadedDocs = array_filter($documents, fn($d) => !empty($d['file']));
        $uploadedDocsCount = count($uploadedDocs);
        $isVerified = in_array($pendaftar->status_pendaftaran, ['verifikasi', 'lulus']);
        $initialChecked = $isVerified ? array_column($uploadedDocs, 'id') : [];
    @endphp

    <div x-data='{ 
            checkedDocs: @json($initialChecked), 
            totalDocs: {{ $uploadedDocsCount }},
            activeTab: "berkas",
            
            showRejectModal: false,
            rejectDocId: null,
            rejectDocLabel: "",
            rejectReason: "",
            
            showPreviewModal: false,
            previewUrl: "",
            previewType: "",

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
            openPreviewModal(url) {
                this.previewUrl = url;
                let lowerUrl = url.toLowerCase();
                if(lowerUrl.includes(".pdf")) {
                    this.previewType = "pdf";
                } else if(lowerUrl.includes(".jpg") || lowerUrl.includes(".jpeg") || lowerUrl.includes(".png") || lowerUrl.includes(".webp")) {
                    this.previewType = "image";
                } else {
                    this.previewType = "unknown";
                }
                this.showPreviewModal = true;
            }
         }'>
        
        <!-- HEADER (Responsive) -->
        <div class="bg-white border-b border-gray-200 shadow-sm relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-5">
                <div class="flex flex-col gap-4 sm:flex-row justify-between sm:items-center">
                    <div>
                        <div class="flex items-center gap-2 text-[10px] sm:text-xs text-gray-500 mb-1.5 uppercase tracking-wide font-semibold">
                            <a href="{{ route('admin.pendaftar.index') }}" class="hover:text-indigo-600 transition">Data Pendaftar</a>
                            <span>/</span>
                            <span>#{{ $pendaftar->id }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-gray-900 flex items-center gap-3">
                            {{ $pendaftar->user->name }}
                        </h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($pendaftar->is_locked && auth()->user()->role === 'admin')
                            <button wire:click="unlockData" wire:confirm="Yakin ingin mereset keputusan kelulusan anak ini?" class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 rounded-lg bg-red-50 text-red-600 px-4 py-2 text-sm font-bold border border-red-200 hover:bg-red-100 transition shadow-sm">
                                🔓 Buka Kunci (Reset)
                            </button>
                        @endif
                        <button onclick="window.print()" class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                            🖨️ Cetak Detail
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- NOTIFIKASI -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                @if (session()->has('success'))
                    <div class="bg-green-50 px-4 py-3 rounded-xl border border-green-200 text-sm font-bold text-green-700 mb-2 shadow-sm flex items-start sm:items-center gap-3">
                        <span class="text-lg">✅</span> <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-50 px-4 py-3 rounded-xl border border-red-200 text-sm font-bold text-red-700 mb-2 shadow-sm flex items-start sm:items-center gap-3">
                        <span class="text-lg">⚠️</span> <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <!-- MOBILE PROGRESS BAR -->
            <div class="lg:hidden mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="flex justify-between items-end mb-2">
                    <div>
                        <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Tahap {{ $currentStep == 99 ? 'Ditolak' : $currentStep }} dari 5</p>
                        <h3 class="text-sm font-black text-gray-900">{{ $currentStep == 99 ? 'Tidak Lulus Seleksi' : $steps[$currentStep]['label'] }}</h3>
                    </div>
                    @if($currentStep != 99)
                        <span class="text-xs font-bold text-gray-400">{{ ($currentStep/5)*100 }}%</span>
                    @endif
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 ease-out {{ $currentStep == 99 ? 'bg-red-500 w-full' : 'bg-indigo-600' }}" style="width: {{ $currentStep == 99 ? 100 : ($currentStep/5)*100 }}%"></div>
                </div>
            </div>

            <!-- DESKTOP STEPPER -->
            <div class="hidden lg:block mb-8">
                <nav aria-label="Progress">
                    <ol role="list" class="divide-y divide-gray-300 rounded-xl border border-gray-200 bg-white flex shadow-sm">
                        @foreach ($steps as $key => $step)
                            @if($key > 5) @continue @endif
                            <li class="relative flex flex-1">
                                @php $status = ($currentStep > $key) ? 'complete' : (($currentStep == $key) ? 'current' : 'upcoming'); @endphp
                                <div class="group flex w-full items-center">
                                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full transition-all {{ $status == 'complete' ? 'bg-green-500 shadow-md shadow-green-200' : ($status == 'current' ? 'border-2 border-indigo-600 bg-indigo-50' : 'border-2 border-gray-200 bg-gray-50') }}">
                                            @if($status == 'complete') <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" /></svg>
                                            @elseif($status == 'current') <span class="text-indigo-600 font-black text-xs">{{ $key }}</span>
                                            @else <span class="text-gray-400 font-bold text-xs">{{ $key }}</span> @endif
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-xs font-black uppercase tracking-wide {{ $status == 'upcoming' ? 'text-gray-400' : ($status == 'current' ? 'text-indigo-700' : 'text-gray-900') }}">{{ $step['label'] }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $step['desc'] }}</span>
                                        </span>
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>

            <!-- ACTION CENTER CARD -->
            <div class="mb-8">
                @if($currentStep == 1)
                    <div class="bg-gradient-to-r from-white to-gray-50 rounded-2xl shadow-sm border border-gray-200 p-5 lg:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="rounded-xl bg-indigo-100 p-3.5 text-indigo-600 shadow-sm border border-indigo-200 text-2xl">💰</div>
                            <div>
                                <h3 class="text-base lg:text-lg font-black text-gray-900">Status Pembayaran Pendaftaran</h3>
                                <p class="text-sm text-gray-500 font-medium mt-0.5">
                                    @if($pendaftar->status_pembayaran == 'menunggu_verifikasi') Bukti transfer telah diunggah. Silakan verifikasi. @else Menunggu pembayaran dari calon mahasiswa. @endif
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-auto mt-2 md:mt-0">
                            @livewire('admin.payment-verifier', ['pendaftar' => $pendaftar], key('action-payment-'.$pendaftar->id))
                        </div>
                    </div>
                @elseif($currentStep == 2)
                    <div class="rounded-2xl shadow-sm border p-5 lg:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 transition-all duration-300"
                         :class="isComplete() ? 'bg-gradient-to-r from-green-50 to-white border-green-300' : 'bg-white border-yellow-300'">
                        <div class="flex items-center gap-4">
                            <div class="rounded-xl p-3.5 shadow-sm border text-2xl transition-colors" :class="isComplete() ? 'bg-green-100 border-green-200 text-green-600' : 'bg-yellow-50 border-yellow-200 text-yellow-600'">
                                <span x-show="!isComplete()">📂</span><span x-show="isComplete()" style="display: none">✅</span>
                            </div>
                            <div>
                                <h3 class="text-base lg:text-lg font-black text-gray-900">Validasi Dokumen Kelengkapan</h3>
                                <div class="mt-0.5 text-sm font-medium text-gray-600">Dokumen Tervalidasi: <span class="font-black text-lg ml-1" :class="isComplete() ? 'text-green-600' : 'text-red-500'" x-text="checkedDocs.length"></span> <span class="text-gray-400">/ {{ $uploadedDocsCount }}</span></div>
                                <p class="text-xs text-gray-500 mt-1" x-show="!isComplete()">Centang kotak "Valid" pada setiap dokumen di tab <b>Dokumen Berkas</b> bawah.</p>
                            </div>
                        </div>
                        <button type="button" wire:click="updateStatus('verifikasi')" :disabled="!isComplete()"
                                :class="isComplete() ? 'bg-green-600 hover:bg-green-700 shadow-md shadow-green-200 cursor-pointer text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed'"
                                class="w-full md:w-auto inline-flex justify-center items-center gap-2 rounded-xl px-6 py-3.5 text-sm font-black transition-all duration-300">
                            <span x-text="isComplete() ? 'Nyatakan Valid & Lanjutkan' : 'Lengkapi Checklist Dahulu'"></span>
                        </button>
                    </div>
                @elseif($currentStep == 3)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 lg:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-lg font-black text-gray-900">Keputusan Hasil Seleksi</h3>
                                <p class="text-sm text-gray-500 mt-1">Berkas valid. Tentukan kelulusan berdasarkan nilai yang diinput pada tab <b>Akademik</b>.</p>
                            </div>
                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-black border shadow-sm {{ $pendaftar->nilai_ujian > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
                                {{ $pendaftar->nilai_ujian > 0 ? 'Skor Ujian: ' . $pendaftar->nilai_ujian : '⚠️ Nilai Belum Diinput' }}
                            </span>
                        </div>

                        <!-- GRID KELULUSAN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                            @if($pendaftar->nilai_ujian > 0)
                                <button type="button" wire:click="lulusPilihan(1)" wire:confirm="Yakin luluskan pendaftar ini di pilihan 1?" class="group block w-full rounded-xl border-2 border-gray-200 bg-gray-50 p-4 hover:border-green-500 hover:bg-green-50 text-left transition-all duration-300 shadow-sm">
                                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 group-hover:text-green-600 transition">Lulus Pilihan 1</span>
                                    <span class="block text-sm font-black text-gray-900 group-hover:text-green-800">{{ $pendaftar->pilihan_prodi_1 }}</span>
                                </button>
                                
                                @if($pendaftar->pilihan_prodi_2)
                                <button type="button" wire:click="lulusPilihan(2)" wire:confirm="Yakin luluskan pendaftar ini di pilihan 2?" class="group block w-full rounded-xl border-2 border-gray-200 bg-gray-50 p-4 hover:border-green-500 hover:bg-green-50 text-left transition-all duration-300 shadow-sm">
                                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 group-hover:text-green-600 transition">Lulus Pilihan 2</span>
                                    <span class="block text-sm font-black text-gray-900 group-hover:text-green-800">{{ $pendaftar->pilihan_prodi_2 }}</span>
                                </button>
                                @endif

                                @if($pendaftar->rekomendasi_prodi)
                                <button type="button" wire:click="lulusRekomendasi()" wire:confirm="Yakin luluskan pendaftar di prodi rekomendasi ({{ $pendaftar->rekomendasi_prodi }})?" class="group block w-full rounded-xl border-2 border-blue-200 bg-blue-50 p-4 hover:border-blue-500 hover:bg-blue-100 text-left transition-all duration-300 shadow-sm">
                                    <span class="block text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1 group-hover:text-blue-700 transition">Lulus Prodi Alternatif</span>
                                    <span class="block text-sm font-black text-blue-900">{{ $pendaftar->rekomendasi_prodi }}</span>
                                </button>
                                @endif
                            @else
                                <div class="col-span-1 md:col-span-2 xl:col-span-3 p-5 bg-yellow-50/80 border border-yellow-200 rounded-xl text-center text-yellow-800 flex flex-col items-center justify-center">
                                    <span class="font-black text-base mb-1">Tombol Kelulusan Terkunci</span>
                                    <span class="text-xs font-medium">Buka Tab <b>Akademik & Nilai</b> di bawah untuk menginput nilai ujian terlebih dahulu.</span>
                                </div>
                            @endif

                            <button type="button" wire:click="updateStatus('gagal')" wire:confirm="Yakin nyatakan pendaftar ini TIDAK LULUS?" class="group block w-full rounded-xl border-2 border-red-100 bg-red-50 p-4 hover:border-red-500 hover:bg-red-100 text-left transition-all duration-300 shadow-sm">
                                <span class="block text-[10px] font-bold text-red-500 uppercase tracking-widest mb-1 group-hover:text-red-700 transition">Tolak Pendaftaran</span>
                                <span class="block text-sm font-black text-red-700 group-hover:text-red-800">Tidak Lulus Seleksi</span>
                            </button>
                        </div>
                    </div>
                @elseif($currentStep == 4)
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-2xl p-5 lg:p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="rounded-xl bg-white p-3.5 text-indigo-600 shadow-sm border border-indigo-100 text-2xl">🚀</div>
                            <div>
                                <h3 class="text-lg font-black text-indigo-900">Pendaftar Lulus & Siap Sinkronisasi</h3>
                                <p class="text-sm font-medium text-indigo-700 mt-1">
                                    Diterima di: <strong class="font-black bg-white px-2 py-0.5 rounded text-indigo-800 shadow-sm">{{ $pendaftar->prodi_diterima }}</strong>
                                </p>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <button wire:click="syncToSiakad" onclick="return confirm('Kirim data mahasiswa ini ke SIAKAD?')" class="w-full md:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-black text-white shadow-md shadow-indigo-200 hover:bg-indigo-700 transition-all">Push Data ke SIAKAD</button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- LAYOUT UTAMA: SIDEBAR KIRI & KONTEN KANAN -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
                
                <!-- SIDEBAR KIRI: PROFIL KARTU -->
                <div class="lg:col-span-4 space-y-6" x-data="{ expandedProfile: false }">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden transition-all duration-300">
                        
                        <!-- MOBILE COMPACT HEADER -->
                        <div class="lg:hidden p-4 flex items-center justify-between cursor-pointer bg-white active:bg-gray-50 transition" @click="expandedProfile = !expandedProfile">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    @if ($pendaftar->foto_path)
                                        <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-100 border-2 border-gray-200 flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                    @endif
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-black text-gray-900 leading-tight truncate">{{ $pendaftar->user->name }}</h3>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-[9px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded uppercase tracking-wider">{{ $pendaftar->jalur_pendaftaran }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-xl text-gray-400 shrink-0 border border-gray-100">
                                <svg :class="expandedProfile ? 'rotate-180 text-indigo-600' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <!-- DESKTOP VIEW & MOBILE EXPANDED CONTENT -->
                        <div :class="expandedProfile ? 'block' : 'hidden lg:block'">
                            <div class="h-28 bg-gradient-to-br from-indigo-600 to-purple-700 relative hidden lg:block">
                                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 16px 16px;"></div>
                            </div>
                            <div class="relative px-5 sm:px-6 pb-6 text-center lg:-mt-14 pt-4 lg:pt-0 border-t border-gray-100 lg:border-t-0 bg-gray-50/50 lg:bg-white">
                                <div class="relative inline-block mb-4 hidden lg:block">
                                    @if ($pendaftar->foto_path)
                                        <div class="relative w-28 h-36 mx-auto rounded-xl shadow-lg overflow-hidden border-4 border-white bg-gray-200 cursor-pointer hover:scale-105 transition duration-300" @click="openPreviewModal('{{ asset('storage/' . $pendaftar->foto_path) }}')">
                                            <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-28 h-36 mx-auto rounded-xl shadow-lg border-4 border-white bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                                            <svg class="h-10 w-10 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-2 -right-2 bg-indigo-600 text-white text-[9px] font-bold px-2 py-1 rounded-md shadow-sm border-2 border-white uppercase hidden lg:inline-block">Calon Mhs</span>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 leading-tight hidden lg:block">{{ $pendaftar->user->name }}</h3>
                                <p class="text-xs text-gray-500 font-semibold mt-1 hidden lg:block">{{ $pendaftar->user->email }}</p>
                                
                                <div class="mt-2 lg:mt-6 lg:border-t border-gray-100 pt-0 lg:pt-5 text-left space-y-4">
                                    <div class="hidden lg:block">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Jalur Pendaftaran</span>
                                        <span class="text-sm font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg inline-block capitalize">{{ $pendaftar->jalur_pendaftaran }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 bg-white lg:bg-transparent p-3 lg:p-0 rounded-xl border border-gray-100 lg:border-none shadow-sm lg:shadow-none">
                                        <div class="bg-green-100 p-2.5 rounded-xl text-green-600 shrink-0">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Kontak WhatsApp</span>
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pendaftar->nomor_hp) }}" target="_blank" class="text-sm font-black text-gray-800 hover:text-green-600 transition truncate block">{{ $pendaftar->nomor_hp ?? '-' }}</a>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 bg-white lg:bg-transparent p-3 lg:p-0 rounded-xl border border-gray-100 lg:border-none shadow-sm lg:shadow-none">
                                        <div class="bg-gray-100 p-2.5 rounded-xl text-gray-500 shrink-0 text-lg leading-none flex items-center justify-center">🏫</div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Asal Sekolah</span>
                                            <span class="text-sm font-black text-gray-800 line-clamp-2 leading-snug" title="{{ $pendaftar->asal_sekolah }}">{{ $pendaftar->asal_sekolah }}</span>
                                        </div>
                                    </div>

                                    @if ($pendaftar->foto_path)
                                    <div class="mt-4 pt-4 border-t border-gray-100 lg:hidden">
                                        <button type="button" @click="openPreviewModal('{{ asset('storage/' . $pendaftar->foto_path) }}')" class="w-full flex justify-center items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-black text-xs py-3 rounded-xl border border-indigo-100 transition shadow-sm">
                                            👁️ Pratinjau Pas Foto
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KONTEN KANAN (TABS) -->
                <div class="lg:col-span-8">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden min-h-[600px] flex flex-col relative z-10">
                        
                        <!-- TAB NAVIGASI PILL-STYLE -->
                        <div class="px-4 py-4 sm:px-6 bg-white border-b border-gray-100">
                            <nav class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide snap-x w-full">
                                <button @click="activeTab = 'berkas'" 
                                        :class="activeTab === 'berkas' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 border-transparent' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
                                        class="snap-start shrink-0 px-4 py-2 rounded-xl text-sm font-black transition-all border">
                                    📂 Dokumen Berkas
                                </button>
                                <button @click="activeTab = 'biodata'" 
                                        :class="activeTab === 'biodata' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 border-transparent' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
                                        class="snap-start shrink-0 px-4 py-2 rounded-xl text-sm font-black transition-all border">
                                    👤 Data Biodata
                                </button>
                                <button @click="activeTab = 'akademik'" 
                                        :class="activeTab === 'akademik' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 border-transparent' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
                                        class="snap-start shrink-0 px-4 py-2 rounded-xl text-sm font-black transition-all border flex items-center gap-2">
                                    📝 Nilai Akademik 
                                    @if($pendaftar->nilai_ujian == 0) <span class="flex h-2.5 w-2.5 rounded-full bg-red-500 animate-pulse"></span> @endif
                                </button>
                                <!-- TAB BARU: BUKTI KEUANGAN -->
                                <button @click="activeTab = 'keuangan'" 
                                        :class="activeTab === 'keuangan' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 border-transparent' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100'" 
                                        class="snap-start shrink-0 px-4 py-2 rounded-xl text-sm font-black transition-all border">
                                    💰 Bukti Keuangan
                                </button>
                            </nav>
                        </div>

                        <!-- TAB CONTENT AREA -->
                        <div class="p-4 sm:p-6 flex-1 bg-gray-50/30">
                            
                            <!-- TAB BERKAS -->
                            <div x-show="activeTab === 'berkas'" x-transition.opacity>
                                @if($uploadedDocsCount > 0 && $currentStep == 2)
                                <div class="mb-5 p-4 bg-blue-50 text-blue-800 rounded-xl border border-blue-100 text-sm font-medium shadow-sm flex items-start gap-3">
                                    <span class="text-xl leading-none">💡</span>
                                    <p>Centang kotak <b class="text-blue-900">"Valid"</b> setelah memastikan dokumen asli terbaca jelas dan asli.</p>
                                </div>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($documents as $doc)
                                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-indigo-200 transition p-4 flex flex-col justify-between h-full">
                                        <div class="flex items-start gap-4">
                                            <div class="text-3xl bg-gray-50 p-3 rounded-xl border border-gray-100">{{ $doc['icon'] }}</div>
                                            <div class="flex-1">
                                                <p class="text-sm font-black text-gray-900 mb-1">{{ $doc['label'] }}</p>
                                                @if(!$doc['file'])
                                                    <span class="inline-block text-[10px] text-red-600 font-bold uppercase tracking-wider bg-red-50 border border-red-100 px-2 py-0.5 rounded-md">Belum Upload</span>
                                                @else
                                                    @if($currentStep == 2)
                                                        <div class="flex flex-col gap-2 mt-2">
                                                            <label class="inline-flex items-center cursor-pointer p-2 bg-green-50 border border-green-100 rounded-lg hover:bg-green-100 transition">
                                                                <input type="checkbox" value="{{ $doc['id'] }}" x-model="checkedDocs" class="rounded border-gray-300 text-green-600 shadow-sm w-5 h-5 focus:ring-green-500">
                                                                <span class="ml-2 text-xs font-black text-green-800">Tandai Valid</span>
                                                            </label>
                                                        </div>
                                                    @else
                                                        <span class="inline-block text-[10px] text-green-600 font-bold uppercase tracking-wider bg-green-50 border border-green-100 px-2 py-0.5 rounded-md">File Tersedia</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                @if($doc['file'] && $currentStep == 2)
                                                    <button type="button" @click="openRejectModal('{{ $doc['id'] }}', '{{ $doc['label'] }}')" class="text-[10px] text-red-600 font-bold hover:bg-red-50 border border-transparent hover:border-red-200 px-2 py-1 rounded-md transition uppercase">Tolak</button>
                                                @endif
                                                <button type="button" wire:click="openUploadModal('{{ $doc['id'] }}', '{{ $doc['label'] }}')" class="text-[10px] font-bold border px-2 py-1 rounded-md transition uppercase shadow-sm {{ $doc['file'] ? 'text-blue-600 hover:bg-blue-50 border-transparent hover:border-blue-200' : 'text-green-700 bg-green-50 border-green-200 hover:bg-green-100' }}">
                                                    {{ $doc['file'] ? 'Ubah File' : 'Upload Berkas' }}
                                                </button>
                                            </div>
                                            @if($doc['file'])
                                                <button type="button" @click="openPreviewModal('{{ asset('storage/'.$doc['file']) }}')" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 border border-indigo-100 px-3 py-1.5 text-xs font-black text-indigo-700 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition shadow-sm">
                                                    👁️ Preview
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- TAB BIODATA -->
                            <div x-show="activeTab === 'biodata'" x-cloak x-transition.opacity>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-16 lg:mb-0 relative">
                                    
                                    <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50 border-b border-gray-200 gap-3">
                                        <div>
                                            <h3 class="text-base font-black text-gray-900">Data Diri & Orang Tua</h3>
                                            <p class="text-xs font-medium text-gray-500 mt-0.5">Verifikasi dan sesuaikan data pendaftar jika ada kesalahan ketik.</p>
                                        </div>
                                        @if(!$isEditingBiodata)
                                            <button wire:click="editBiodata" class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl text-sm font-black shadow-sm hover:bg-gray-50 transition">✏️ Mode Edit Data</button>
                                        @endif
                                    </div>

                                    @if($isEditingBiodata)
                                        <div class="p-5 sm:p-6 space-y-6">
                                            
                                            <!-- QUICK ACCESS DOCUMENT -->
                                            <div class="mb-6 bg-indigo-50/70 border border-indigo-100 p-4 rounded-xl shadow-sm">
                                                <div class="flex items-start gap-3 mb-3">
                                                    <span class="text-xl leading-none">🕵️‍♂️</span>
                                                    <div>
                                                        <h4 class="text-sm font-black text-indigo-900 leading-tight">Side-by-Side Verification</h4>
                                                        <p class="text-[11px] font-medium text-indigo-700 mt-0.5">Klik dokumen untuk membukanya di panel kiri, sehingga Anda bisa mengedit data ini sambil melihat dokumen fisik.</p>
                                                    </div>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($documents as $doc)
                                                        @if($doc['file'])
                                                        <button type="button" @click="openPreviewModal('{{ asset('storage/'.$doc['file']) }}')" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-indigo-200 text-indigo-700 text-xs font-black rounded-lg hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition shadow-sm">
                                                            <span>{{ $doc['icon'] }}</span> Buka {{ $doc['label'] }}
                                                        </button>
                                                        @endif
                                                    @endforeach
                                                    @if($uploadedDocsCount === 0)
                                                        <span class="text-xs font-bold text-red-500 bg-white px-3 py-1.5 rounded-lg border border-red-100">Pendaftar belum mengunggah dokumen satupun.</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Seksi Biodata -->
                                            <div>
                                                <h4 class="text-sm font-black text-indigo-700 mb-4 border-b border-indigo-100 pb-2 uppercase tracking-wide">Informasi Pribadi</h4>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                                        <input type="text" wire:model="edit_name" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_name') border-red-500 bg-red-50 @enderror">
                                                        @error('edit_name') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="edit_nomor_hp" maxlength="15" placeholder="Contoh: 0812..." class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_nomor_hp') border-red-500 bg-red-50 @enderror">
                                                        @error('edit_nomor_hp') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">NIK KTP/KK <span class="text-red-500">*</span></label>
                                                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="edit_nik" maxlength="16" placeholder="16 Digit Angka" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_nik') border-red-500 bg-red-50 @enderror">
                                                        @error('edit_nik') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                                        <select wire:model="edit_jenis_kelamin" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_jenis_kelamin') border-red-500 bg-red-50 @enderror">
                                                            <option value="L">Laki-laki</option>
                                                            <option value="P">Perempuan</option>
                                                        </select>
                                                        @error('edit_jenis_kelamin') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                                                        <input type="text" wire:model="edit_tempat_lahir" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_tempat_lahir') border-red-500 bg-red-50 @enderror">
                                                        @error('edit_tempat_lahir') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                                                        <input type="date" wire:model="edit_tgl_lahir" max="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_tgl_lahir') border-red-500 bg-red-50 @enderror">
                                                        @error('edit_tgl_lahir') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Asal Sekolah (Berdasarkan Ijazah/SKL) <span class="text-red-500">*</span></label>
                                                        <input type="text" wire:model="edit_asal_sekolah" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_asal_sekolah') border-red-500 bg-red-50 @enderror">
                                                        @error('edit_asal_sekolah') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Alamat Domisili Lengkap <span class="text-red-500">*</span></label>
                                                        <textarea wire:model="edit_alamat" rows="2" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_alamat') border-red-500 bg-red-50 @enderror"></textarea>
                                                        @error('edit_alamat') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Seksi Data Orang Tua -->
                                            <div class="pt-2">
                                                <h4 class="text-sm font-black text-indigo-700 mb-4 border-b border-indigo-100 pb-2 uppercase tracking-wide">Data Orang Tua / Wali</h4>
                                                
                                                <div class="mb-5 p-3 sm:p-4 bg-yellow-50 text-yellow-800 text-xs sm:text-sm font-medium rounded-xl border border-yellow-200 flex gap-3 shadow-sm">
                                                    <span class="text-xl leading-none">💡</span>
                                                    <p>Jika status orang tua <strong>Meninggal</strong>, kolom NIK, Pendidikan, dan Pekerjaan <b>boleh dikosongkan</b>. Namun, jika NIK diisi, WAJIB tepat 16 digit.</p>
                                                </div>

                                                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 sm:p-5 mb-5 shadow-sm">
                                                    <span class="text-xs font-black text-gray-800 uppercase tracking-widest block mb-4">Ayah Kandung</span>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                                                            <input type="text" wire:model="edit_nama_ayah" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_nama_ayah') border-red-500 bg-red-50 @enderror">
                                                            @error('edit_nama_ayah') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Status Ayah <span class="text-red-500">*</span></label>
                                                            <select wire:model.live="edit_status_ayah" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_status_ayah') border-red-500 bg-red-50 @enderror">
                                                                <option value="Hidup">Masih Hidup</option>
                                                                <option value="Meninggal">Sudah Meninggal</option>
                                                            </select>
                                                            @error('edit_status_ayah') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                                                                NIK Ayah @if($edit_status_ayah === 'Hidup') <span class="text-red-500">*</span> @endif
                                                            </label>
                                                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="edit_nik_ayah" maxlength="16" placeholder="{{ $edit_status_ayah === 'Hidup' ? 'Harus 16 digit angka' : 'Opsional...' }}" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_nik_ayah') border-red-500 bg-red-50 @enderror">
                                                            @error('edit_nik_ayah') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Pendidikan</label>
                                                            <select wire:model="edit_pendidikan_ayah" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500">
                                                                <option value="">-- Pilih --</option>
                                                                <option value="Tidak Sekolah">Tidak Sekolah</option>
                                                                <option value="SD">SD Sederajat</option>
                                                                <option value="SMP">SMP Sederajat</option>
                                                                <option value="SMA">SMA Sederajat</option>
                                                                <option value="D1/D2/D3">D1/D2/D3</option>
                                                                <option value="S1/D4">S1/D4</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                            </select>
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Pekerjaan Utama</label>
                                                            <input type="text" wire:model="edit_pekerjaan_ayah" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 sm:p-5 shadow-sm">
                                                    <span class="text-xs font-black text-gray-800 uppercase tracking-widest block mb-4">Ibu Kandung</span>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                                                            <input type="text" wire:model="edit_nama_ibu" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_nama_ibu') border-red-500 bg-red-50 @enderror">
                                                            @error('edit_nama_ibu') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Status Ibu <span class="text-red-500">*</span></label>
                                                            <select wire:model.live="edit_status_ibu" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_status_ibu') border-red-500 bg-red-50 @enderror">
                                                                <option value="Hidup">Masih Hidup</option>
                                                                <option value="Meninggal">Sudah Meninggal</option>
                                                            </select>
                                                            @error('edit_status_ibu') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                                                                NIK Ibu @if($edit_status_ibu === 'Hidup') <span class="text-red-500">*</span> @endif
                                                            </label>
                                                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="edit_nik_ibu" maxlength="16" placeholder="{{ $edit_status_ibu === 'Hidup' ? 'Harus 16 digit angka' : 'Opsional...' }}" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 @error('edit_nik_ibu') border-red-500 bg-red-50 @enderror">
                                                            @error('edit_nik_ibu') <span class="text-[10px] font-bold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Pendidikan</label>
                                                            <select wire:model="edit_pendidikan_ibu" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500">
                                                                <option value="">-- Pilih --</option>
                                                                <option value="Tidak Sekolah">Tidak Sekolah</option>
                                                                <option value="SD">SD Sederajat</option>
                                                                <option value="SMP">SMP Sederajat</option>
                                                                <option value="SMA">SMA Sederajat</option>
                                                                <option value="D1/D2/D3">D1/D2/D3</option>
                                                                <option value="S1/D4">S1/D4</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                            </select>
                                                        </div>
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Pekerjaan Utama</label>
                                                            <input type="text" wire:model="edit_pekerjaan_ibu" class="w-full rounded-xl border-gray-300 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.1)] lg:sticky lg:bottom-0 lg:bg-white lg:border-t lg:p-5 z-40 flex justify-end gap-3 flex-col-reverse sm:flex-row">
                                            <button type="button" wire:click="$set('isEditingBiodata', false)" class="w-full sm:w-auto bg-gray-100 text-gray-700 px-6 py-3.5 sm:py-2.5 rounded-xl font-black text-sm hover:bg-gray-200 transition text-center border border-gray-200">Batal Edit</button>
                                            <button type="button" wire:click="simpanBiodata" wire:loading.attr="disabled" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-3.5 sm:py-2.5 rounded-xl font-black text-sm hover:bg-indigo-700 shadow-md shadow-indigo-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2 border border-indigo-700">
                                                <span wire:loading.remove wire:target="simpanBiodata">Simpan Perubahan Data</span>
                                                <span wire:loading wire:target="simpanBiodata">Menyimpan...</span>
                                            </button>
                                        </div>
                                    @else
                                        <!-- TAMPILAN READ ONLY -->
                                        <div class="p-0">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:gap-y-6 sm:p-6 p-2">
                                                <div class="px-4 py-3 sm:px-0 sm:py-0">
                                                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Lengkap</dt>
                                                    <dd class="text-sm font-black text-gray-900">{{ $pendaftar->user->name }}</dd>
                                                </div>
                                                <div class="px-4 py-3 sm:px-0 sm:py-0">
                                                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nomor KTP/NIK</dt>
                                                    <dd class="text-sm font-black text-gray-900 font-mono">{{ $pendaftar->nik }}</dd>
                                                </div>
                                                <div class="px-4 py-3 sm:px-0 sm:py-0">
                                                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tempat, Tanggal Lahir</dt>
                                                    <dd class="text-sm font-bold text-gray-800">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tgl_lahir instanceof \DateTime ? $pendaftar->tgl_lahir->format('d F Y') : $pendaftar->tgl_lahir }}</dd>
                                                </div>
                                                <div class="px-4 py-3 sm:px-0 sm:py-0">
                                                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Jenis Kelamin</dt>
                                                    <dd class="text-sm font-bold text-gray-800">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                                                </div>
                                                <div class="px-4 py-3 sm:px-0 sm:py-0">
                                                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Asal Sekolah</dt>
                                                    <dd class="text-sm font-black text-gray-900">{{ $pendaftar->asal_sekolah }}</dd>
                                                </div>
                                                <div class="px-4 py-3 sm:px-0 sm:py-0">
                                                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Alamat Domisili Lengkap</dt>
                                                    <dd class="text-sm font-bold text-gray-800 leading-relaxed">{{ $pendaftar->alamat }}</dd>
                                                </div>
                                            </div>

                                            <div class="bg-gray-50/80 border-t border-gray-100 p-4 sm:p-6">
                                                <h3 class="text-xs font-black text-indigo-700 uppercase tracking-widest mb-4">👨‍👩‍👧 Info Orang Tua</h3>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span class="text-base font-black text-gray-900">{{ $pendaftar->nama_ayah }}</span>
                                                            @if(isset($pendaftar->status_ayah))
                                                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest uppercase {{ $pendaftar->status_ayah == 'Meninggal' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200' }}">
                                                                    {{ $pendaftar->status_ayah }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-600 space-y-1 font-medium">
                                                            <p><span class="text-gray-400 inline-block w-16">NIK:</span> <span class="font-mono text-gray-800">{{ $pendaftar->nik_ayah ?? '-' }}</span></p>
                                                            <p><span class="text-gray-400 inline-block w-16">Pendidikan:</span> {{ $pendaftar->pendidikan_ayah ?? '-' }}</p>
                                                            <p><span class="text-gray-400 inline-block w-16">Pekerjaan:</span> {{ $pendaftar->pekerjaan_ayah ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span class="text-base font-black text-gray-900">{{ $pendaftar->nama_ibu }}</span>
                                                            @if(isset($pendaftar->status_ibu))
                                                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest uppercase {{ $pendaftar->status_ibu == 'Meninggal' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200' }}">
                                                                    {{ $pendaftar->status_ibu }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-600 space-y-1 font-medium">
                                                            <p><span class="text-gray-400 inline-block w-16">NIK:</span> <span class="font-mono text-gray-800">{{ $pendaftar->nik_ibu ?? '-' }}</span></p>
                                                            <p><span class="text-gray-400 inline-block w-16">Pendidikan:</span> {{ $pendaftar->pendidikan_ibu ?? '-' }}</p>
                                                            <p><span class="text-gray-400 inline-block w-16">Pekerjaan:</span> {{ $pendaftar->pekerjaan_ibu ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- TAB AKADEMIK (INPUT NILAI & REKOMENDASI) -->
                            <div x-show="activeTab === 'akademik'" x-cloak x-transition.opacity>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-16 lg:mb-0">
                                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                        <div>
                                            <h3 class="text-base font-black text-gray-900">Penilaian & Rekomendasi</h3>
                                            <p class="text-xs font-medium text-gray-500 mt-0.5">Input nilai ujian dan berikan rekomendasi prodi jika diperlukan.</p>
                                        </div>
                                    </div>

                                    <form wire:submit.prevent="simpanAkademik" class="p-0">
                                        <!-- Pilihan Prodi Readonly -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 sm:p-6 bg-white border-b border-gray-100">
                                            <div class="p-4 border-2 border-indigo-100 rounded-xl bg-indigo-50 shadow-sm relative overflow-hidden">
                                                <div class="absolute top-0 right-0 bg-indigo-200 w-16 h-16 rounded-bl-full opacity-50 -mr-4 -mt-4"></div>
                                                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest bg-white px-2 py-0.5 rounded-md border border-indigo-100 shadow-sm">Pilihan 1 (Prioritas)</span>
                                                <p class="font-black text-indigo-950 text-base sm:text-lg mt-3">{{ $pendaftar->pilihan_prodi_1 }}</p>
                                            </div>
                                            @if($pendaftar->pilihan_prodi_2)
                                            <div class="p-4 border-2 border-gray-200 rounded-xl bg-gray-50 shadow-sm relative overflow-hidden">
                                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-white px-2 py-0.5 rounded-md border border-gray-200 shadow-sm">Pilihan 2 (Alternatif)</span>
                                                <p class="font-black text-gray-800 text-base sm:text-lg mt-3">{{ $pendaftar->pilihan_prodi_2 }}</p>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 bg-gray-50/50">
                                            <!-- Bagian CBT / Ujian Tulis -->
                                            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                                                <div>
                                                    <h4 class="text-sm font-black text-indigo-700 mb-4 border-b border-indigo-100 pb-2 uppercase tracking-wide">Ujian Tulis / CBT</h4>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Skor Ujian (0-100) <span class="text-red-500">*</span></label>
                                                            <input type="number" wire:model="nilai_ujian" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-black text-lg text-center @error('nilai_ujian') border-red-500 bg-red-50 @enderror" min="0" max="100">
                                                            @error('nilai_ujian') <span class="text-[10px] font-bold text-red-500 mt-1 block text-center">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Jadwal Ujian Dilaksanakan</label>
                                                            <input type="datetime-local" wire:model="jadwal_ujian" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 sm:text-sm text-gray-600 font-medium @error('jadwal_ujian') border-red-500 @enderror">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Lokasi / Ruang Ujian</label>
                                                            <input type="text" wire:model="lokasi_ujian" placeholder="Contoh: Lab Komputer A" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 sm:text-sm font-medium @error('lokasi_ujian') border-red-500 @enderror">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bagian Wawancara & Rekomendasi (Diperbarui) -->
                                            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                                                <div>
                                                    <h4 class="text-sm font-black text-indigo-700 mb-4 border-b border-indigo-100 pb-2 uppercase tracking-wide flex items-center gap-2">
                                                        Tes & Rekomendasi
                                                    </h4>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Skor Wawancara (Opsional)</label>
                                                            <input type="number" wire:model="nilai_wawancara" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-black text-lg text-center text-gray-600 @error('nilai_wawancara') border-red-500 bg-red-50 @enderror" min="0" max="100">
                                                        </div>
                                                        <div class="pt-3 mt-3 border-t border-dashed border-gray-200">
                                                            <label class="block text-[11px] font-bold text-blue-600 uppercase tracking-wide mb-1 flex items-center gap-1"><span>🎯</span> Rekomendasi Prodi (Bila ada)</label>
                                                            <p class="text-[10px] text-gray-500 mb-2">Pilih prodi alternatif jika pendaftar tidak lolos di pilihan utamanya.</p>
                                                            <select wire:model="rekomendasi_prodi" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-bold text-gray-800">
                                                                <option value="">-- Tidak Ada Rekomendasi --</option>
                                                                @foreach($prodiList as $prodi)
                                                                    <option value="{{ $prodi->nama_prodi }}">{{ $prodi->nama_prodi }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Catatan Seleksi / Internal</label>
                                                            <textarea wire:model="catatan_seleksi" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 sm:text-sm font-medium @error('catatan_seleksi') border-red-500 @enderror" placeholder="Isi pertimbangan rekomendasi khusus..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.1)] lg:static lg:bg-transparent lg:border-t-0 lg:p-6 lg:shadow-none lg:pt-0 z-40 flex justify-end gap-3">
                                            <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-3.5 sm:py-3 rounded-xl font-black text-sm hover:bg-indigo-700 shadow-md shadow-indigo-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2 border border-indigo-700">
                                                <span wire:loading.remove wire:target="simpanAkademik">Simpan Nilai & Rekomendasi</span>
                                                <span wire:loading wire:target="simpanAkademik">Menyimpan...</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- TAB BARU: KEUANGAN (Selalu Tampil) -->
                            <div x-show="activeTab === 'keuangan'" x-cloak x-transition.opacity>
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-16 lg:mb-0">
                                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                        <div>
                                            <h3 class="text-base font-black text-gray-900">Keuangan & Pembayaran</h3>
                                            <p class="text-xs font-medium text-gray-500 mt-0.5">Cek bukti transfer dan ubah status pembayaran pendaftar kapan saja.</p>
                                        </div>
                                    </div>
                                    <div class="p-5 sm:p-6 bg-gray-50/50">
                                        <div class="max-w-xl mx-auto">
                                            @livewire('admin.payment-verifier', ['pendaftar' => $pendaftar], key('tab-payment-'.$pendaftar->id))
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MODAL TOLAK DOKUMEN ALPINE -->
        <div x-show="showRejectModal" style="display: none;" class="fixed inset-0 z-[150] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4" x-cloak>
            <div @click.outside="showRejectModal=false" class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-sm transform transition-all">
                <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                    <h3 class="text-lg font-black text-red-600 flex items-center gap-2"><span>⚠️</span> Tolak Dokumen</h3>
                    <button @click="showRejectModal = false" class="text-gray-400 hover:text-gray-900 transition bg-gray-100 hover:bg-gray-200 rounded-full p-1.5 leading-none">✕</button>
                </div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Dokumen Pendaftar</p>
                <p class="text-sm font-black text-gray-900 mb-4 border border-gray-200 bg-gray-50 px-3 py-2 rounded-lg" x-text="rejectDocLabel"></p>
                
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea x-model="rejectReason" class="w-full border-gray-300 rounded-xl text-sm font-medium mb-5 focus:ring-red-500 focus:border-red-500 bg-gray-50" rows="3" placeholder="Tuliskan pesan mengapa dokumen ini ditolak..."></textarea>
                
                <div class="flex justify-end gap-3 flex-col-reverse sm:flex-row">
                    <button type="button" @click="showRejectModal=false" class="w-full sm:w-auto px-5 py-2.5 text-sm text-gray-700 bg-gray-100 border border-gray-200 rounded-xl font-black hover:bg-gray-200 transition">Batal</button>
                    <button type="button" @click="$wire.rejectDocument(rejectDocId, rejectReason); showRejectModal = false;" :disabled="!rejectReason" class="w-full sm:w-auto px-5 py-2.5 text-sm text-white bg-red-600 border border-red-700 rounded-xl font-black hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-red-200">Tolak Sekarang</button>
                </div>
            </div>
        </div>

        <!-- NON-BLOCKING PREVIEW PANEL (SIDE-BY-SIDE UX) -->
        <div x-show="showPreviewModal" 
             style="display: none;" 
             class="fixed inset-y-0 left-0 z-[120] w-full lg:w-[45vw] bg-gray-900 shadow-[20px_0_50px_rgba(0,0,0,0.5)] flex flex-col transform transition-transform duration-300 ease-in-out border-r border-gray-800"
             x-transition:enter="translate-x-[-100%]"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="translate-x-0"
             x-transition:leave-end="translate-x-[-100%]"
             x-cloak>
            
            <div class="flex justify-between items-center p-4 bg-gray-950 border-b border-gray-800 shrink-0">
                <h3 class="text-sm font-black text-white flex items-center gap-2"><span>👁️</span> Pratinjau Dokumen</h3>
                <div class="flex items-center gap-2">
                    <a :href="previewUrl" target="_blank" class="px-3 py-1.5 text-xs bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white font-bold transition border border-gray-700 hidden sm:inline-block">Buka Penuh ↗</a>
                    <button @click="showPreviewModal = false" class="text-white hover:text-red-400 px-3 py-1.5 bg-gray-800 border border-gray-700 rounded-lg transition font-black text-xs shadow-sm">Tutup ✕</button>
                </div>
            </div>

            <div class="flex-1 bg-gray-900 overflow-auto flex items-center justify-center p-2 relative w-full h-full">
                <template x-if="previewType === 'image'">
                    <img :src="previewUrl" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
                </template>
                <template x-if="previewType === 'pdf'">
                    <iframe :src="previewUrl" class="w-full h-full rounded-lg shadow-2xl border border-gray-800 bg-white"></iframe>
                </template>
                <template x-if="previewType === 'unknown'">
                    <div class="text-center text-gray-400 bg-gray-800 p-8 rounded-2xl border border-gray-700">
                        <span class="text-5xl block mb-3 opacity-50">📁</span>
                        <p class="font-black text-lg text-white mb-1">Format Tidak Didukung</p>
                        <p class="text-xs font-medium mb-4">Browser tidak dapat menampilkan pratinjau file ini di dalam panel.</p>
                        <a :href="previewUrl" target="_blank" class="bg-indigo-600 text-white font-black px-6 py-2.5 rounded-xl hover:bg-indigo-500 inline-block shadow-md">Unduh File Asli</a>
                    </div>
                </template>
            </div>
            
            <div class="p-3 bg-gray-950 text-center shrink-0 lg:hidden">
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Geser/Tutup panel ini untuk kembali mengisi form.</p>
            </div>
        </div>

    </div> <!-- END X-DATA -->

    <!-- MODAL GANTI BERKAS LIVEWIRE (DROPZONE STYLE) -->
    @if($showUploadModal)
    <div class="fixed inset-0 z-[150] flex items-end sm:items-center justify-center bg-gray-900/60 backdrop-blur-sm p-0 sm:p-4 transition-all">
        <div class="bg-white p-0 rounded-t-3xl sm:rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all mt-auto sm:mt-0 animate-slide-up sm:animate-none">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex justify-between items-center">
                <h3 class="text-lg font-black text-white">Upload / Ubah Berkas</h3>
                <button wire:click="closeUploadModal" class="bg-white/20 hover:bg-white/30 text-white rounded-full p-1.5 transition text-lg leading-none">✕</button>
            </div>
            
            <form wire:submit.prevent="gantiBerkasAdmin" class="p-6">
                <p class="text-sm font-medium text-gray-600 mb-5 leading-relaxed">Silakan unggah dokumen untuk <strong class="text-gray-900 font-black">{{ $upload_label }}</strong> yang akan diproses oleh sistem.</p>
                
                <div class="relative w-full">
                    <label for="upload-admin-file" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer group shadow-inner">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                            <div class="bg-white p-3 rounded-full shadow-sm mb-3 group-hover:scale-110 transition duration-300 border border-gray-100 group-hover:border-blue-200">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="mb-1 text-sm text-gray-600 group-hover:text-blue-700 transition"><span class="font-black">Klik disini</span> untuk memilih file</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">PDF, JPG, PNG (Max 5MB)</p>
                        </div>
                        <input id="upload-admin-file" type="file" wire:model="upload_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.webp">
                    </label>

                    <div wire:loading wire:target="upload_file" class="absolute inset-0 bg-white/90 backdrop-blur-md flex flex-col items-center justify-center rounded-2xl z-10 border border-gray-100">
                        <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-xs font-black text-blue-800 uppercase tracking-widest animate-pulse">Memproses File...</span>
                    </div>
                </div>

                @if($upload_file)
                    <div class="mt-5 p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-center gap-3 shadow-sm">
                        <div class="bg-white p-2 rounded-lg shadow-sm border border-blue-100 text-xl leading-none">📄</div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-sm font-black text-blue-900 truncate" title="{{ $upload_file->getClientOriginalName() }}">{{ $upload_file->getClientOriginalName() }}</p>
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider mt-0.5 flex items-center gap-1"><span>✨</span> Siap Disimpan</p>
                        </div>
                    </div>
                @endif

                @error('upload_file') <span class="text-[11px] font-black text-red-500 block mt-3 bg-red-50 p-2 rounded-lg border border-red-100 text-center">⚠️ {{ $message }}</span> @enderror
                
                <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100 flex-col-reverse sm:flex-row">
                    <button type="button" wire:click="closeUploadModal" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 text-sm bg-white border border-gray-300 text-gray-700 rounded-xl font-black hover:bg-gray-50 transition shadow-sm">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto px-5 py-3 sm:py-2.5 text-sm text-white bg-blue-600 border border-blue-700 rounded-xl font-black hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-md shadow-blue-200">
                        <span wire:loading.remove wire:target="gantiBerkasAdmin">Upload & Simpan File</span>
                        <span wire:loading wire:target="gantiBerkasAdmin">Mengunggah...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes slide-up { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .animate-slide-up { animation: slide-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</div>