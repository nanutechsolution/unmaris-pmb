<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-unmaris-blue uppercase tracking-tight">
                    🔍 Detail Calon Mahasiswa
                </h2>
                <p class="text-sm text-gray-500 font-bold">
                    Reg. ID: <span class="bg-yellow-200 px-2 rounded text-black">#{{ str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT) }}</span>
                    &bull; {{ $pendaftar->created_at->format('d M Y H:i') }}
                </p>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.pendaftar.index') }}" class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg text-gray-600 font-bold hover:bg-gray-100 transition shadow-sm text-sm">
                    ← Kembali
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-unmaris-blue text-white rounded-lg font-bold hover:bg-blue-800 transition shadow-neo text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Data
                </button>
            </div>
        </div>
    </x-slot>

    <!-- NOTIFIKASI SYSTEM -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 font-bold shadow-sm rounded-r flex items-center animate-fade-in-down" x-data="{show: true}" x-show="show">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-green-900 hover:text-green-500 font-black ml-4">✕</button>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 font-bold shadow-sm rounded-r flex items-center animate-fade-in-down" x-data="{show: true}" x-show="show">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="flex-1">{{ session('error') }}</span>
            <button @click="show = false" class="text-red-900 hover:text-red-500 font-black ml-4">✕</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-8 pb-20">

        <!-- SIDEBAR KIRI: PROFILE SUMMARY -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- PAS FOTO & STATUS UTAMA -->
            <div class="bg-white border-2 border-gray-200 shadow-lg rounded-xl p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-unmaris-blue to-blue-600"></div>
                
                <div class="relative inline-block mt-8 mb-4">
                    @if ($pendaftar->foto_path)
                        <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" 
                             class="w-32 h-40 md:w-40 md:h-48 object-cover mx-auto border-4 border-white shadow-md rounded-lg bg-gray-100 cursor-pointer hover:scale-105 transition-transform"
                             onclick="window.open('{{ asset('storage/' . $pendaftar->foto_path) }}', '_blank')">
                    @else
                        <div class="w-32 h-40 md:w-40 md:h-48 bg-gray-100 border-4 border-white border-dashed mx-auto flex flex-col items-center justify-center text-gray-400 shadow-md rounded-lg">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-bold text-[10px] uppercase">No Photo</span>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'lulus' => 'bg-green-500',
                                'verifikasi' => 'bg-blue-500',
                                'submit' => 'bg-yellow-400 text-black',
                                'gagal' => 'bg-red-500',
                                'draft' => 'bg-gray-400',
                            ];
                            $color = $statusColors[$pendaftar->status_pendaftaran] ?? 'bg-gray-500';
                            $text = $pendaftar->status_pendaftaran == 'submit' ? 'MENUNGGU VERIF' : $pendaftar->status_pendaftaran;
                        @endphp
                        <span class="px-3 py-1 text-xs font-black rounded-full border-2 border-white shadow-sm uppercase text-white {{ $color }}">
                            {{ $text }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="font-black text-lg text-gray-800 leading-tight">{{ $pendaftar->user->name }}</h3>
                    <p class="text-xs font-bold text-gray-500 mt-1">{{ $pendaftar->user->email }}</p>
                    <p class="text-xs font-bold text-gray-500">{{ $pendaftar->nomor_hp ?? '-' }}</p>
                </div>

                <div class="mt-6 border-t pt-4 text-left space-y-2">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Jalur Masuk</span>
                        <p class="text-sm font-black text-unmaris-blue">{{ ucfirst($pendaftar->jalur_pendaftaran) }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Asal Sekolah</span>
                        <p class="text-sm font-black text-unmaris-blue truncate" title="{{ $pendaftar->asal_sekolah }}">{{ $pendaftar->asal_sekolah }}</p>
                    </div>
                </div>
            </div>

            <!-- CARD STATUS PEMBAYARAN -->
            @livewire('admin.payment-verifier', ['pendaftar' => $pendaftar])

        </div>

        <!-- MAIN CONTENT -->
        <div class="lg:col-span-3 space-y-6">

            <!-- BANNER STATUS UTAMA (PENTING AGAR ADMIN TAHU STATUSNYA APA) -->
            @if($pendaftar->status_pendaftaran == 'lulus')
                <div class="bg-green-100 border-l-8 border-green-600 p-6 rounded-r-xl shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-green-600 text-white rounded-full p-3 shadow-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-2xl text-green-900 uppercase">DITERIMA / LULUS</h3>
                            <p class="text-sm font-bold text-green-700">
                                Mahasiswa ini telah resmi diterima pada Program Studi:
                            </p>
                            <div class="mt-2 bg-white px-4 py-2 rounded border border-green-300 inline-block">
                                <span class="text-lg font-black text-green-800">{{ $pendaftar->prodi_diterima }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Info Integrasi -->
                    <div class="text-right">
                        @if($pendaftar->is_synced)
                            <span class="bg-blue-100 text-blue-800 text-xs font-black px-3 py-1 rounded-full border border-blue-200">DATA TERSINKRON KE SIAKAD</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-black px-3 py-1 rounded-full border border-yellow-200 animate-pulse">BELUM SINKRON KE SIAKAD</span>
                        @endif
                    </div>
                </div>
            @elseif($pendaftar->status_pendaftaran == 'gagal')
                <div class="bg-red-100 border-l-8 border-red-600 p-6 rounded-r-xl shadow-sm flex items-center gap-4">
                    <div class="bg-red-600 text-white rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-2xl text-red-900 uppercase">TIDAK LULUS</h3>
                        <p class="text-sm font-bold text-red-700">Mohon maaf, peserta belum memenuhi kriteria penerimaan.</p>
                    </div>
                </div>
            @endif

            <!-- TABS NAVIGATION -->
            <div x-data="{ tab: 'berkas' }" class="bg-white border-2 border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="flex border-b-2 border-gray-100 bg-gray-50 overflow-x-auto">
                    <button @click="tab = 'berkas'" :class="tab === 'berkas' ? 'bg-white border-b-4 border-unmaris-blue text-unmaris-blue' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-4 font-black text-sm uppercase transition-all whitespace-nowrap">
                        📂 Dokumen & Berkas
                    </button>
                    <button @click="tab = 'biodata'" :class="tab === 'biodata' ? 'bg-white border-b-4 border-unmaris-blue text-unmaris-blue' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-4 font-black text-sm uppercase transition-all whitespace-nowrap">
                        📝 Biodata Lengkap
                    </button>
                    <button @click="tab = 'akademik'" :class="tab === 'akademik' ? 'bg-white border-b-4 border-unmaris-blue text-unmaris-blue' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-4 font-black text-sm uppercase transition-all whitespace-nowrap">
                        🎓 Pilihan Prodi
                    </button>
                    @if($pendaftar->status_pendaftaran == 'lulus')
                    <button @click="tab = 'siakad'" :class="tab === 'siakad' ? 'bg-white border-b-4 border-indigo-600 text-indigo-700' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-4 font-black text-sm uppercase transition-all whitespace-nowrap">
                        🚀 Integrasi SIAKAD
                    </button>
                    @endif
                </div>

                <!-- TAB CONTENT -->
                <div class="p-6">
                    
                    <!-- 1. TAB BERKAS -->
                    <div x-show="tab === 'berkas'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        @if($pendaftar->status_pendaftaran == 'submit')
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg flex items-start gap-3">
                                <span class="text-2xl">⚠️</span>
                                <div>
                                    <h4 class="font-black text-yellow-800 text-sm uppercase">Perlu Verifikasi</h4>
                                    <p class="text-xs text-yellow-700 mt-1">Silakan periksa kelengkapan dokumen sebelum melanjutkan ke seleksi.</p>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- KTP -->
                            <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-unmaris-blue transition bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl">🪪</div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">KTP / Kartu Keluarga</p>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $pendaftar->ktp_path ? 'Ada File' : 'Tidak Ada' }}</p>
                                    </div>
                                </div>
                                @if($pendaftar->ktp_path)
                                    <a href="{{ asset('storage/'.$pendaftar->ktp_path) }}" target="_blank" class="px-3 py-1 bg-white border border-gray-300 rounded text-xs font-bold text-gray-600 hover:bg-unmaris-blue hover:text-white hover:border-unmaris-blue transition">LIHAT</a>
                                @endif
                            </div>

                            <!-- AKTA -->
                            <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-unmaris-blue transition bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xl">👶</div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">Akta Kelahiran</p>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $pendaftar->akta_path ? 'Ada File' : 'Tidak Ada (Opsional)' }}</p>
                                    </div>
                                </div>
                                @if($pendaftar->akta_path)
                                    <a href="{{ asset('storage/'.$pendaftar->akta_path) }}" target="_blank" class="px-3 py-1 bg-white border border-gray-300 rounded text-xs font-bold text-gray-600 hover:bg-unmaris-blue hover:text-white hover:border-unmaris-blue transition">LIHAT</a>
                                @endif
                            </div>

                            <!-- IJAZAH / SKL -->
                            <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-unmaris-blue transition bg-white shadow-sm ring-1 ring-black/5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl">🎓</div>
                                    <div>
                                        <p class="font-black text-gray-800 text-sm">{{ $pendaftar->jenis_dokumen == 'skl' ? 'SKL (Sementara)' : 'Ijazah Asli' }}</p>
                                        <p class="text-[10px] text-green-600 font-bold uppercase">Dokumen Utama</p>
                                    </div>
                                </div>
                                @if($pendaftar->ijazah_path)
                                    <a href="{{ asset('storage/'.$pendaftar->ijazah_path) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded text-xs font-black hover:bg-green-700 transition shadow-sm">PERIKSA</a>
                                @else
                                    <span class="text-xs font-bold text-red-500 bg-red-100 px-2 py-1 rounded">MISSING</span>
                                @endif
                            </div>

                            <!-- TRANSKRIP -->
                            <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-unmaris-blue transition bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl">📊</div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">Transkrip Nilai</p>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $pendaftar->transkrip_path ? 'Ada File' : '-' }}</p>
                                    </div>
                                </div>
                                @if($pendaftar->transkrip_path)
                                    <a href="{{ asset('storage/'.$pendaftar->transkrip_path) }}" target="_blank" class="px-3 py-1 bg-white border border-gray-300 rounded text-xs font-bold text-gray-600 hover:bg-unmaris-blue hover:text-white hover:border-unmaris-blue transition">LIHAT</a>
                                @endif
                            </div>

                            <!-- BEASISWA -->
                            @if($pendaftar->file_beasiswa)
                            <div class="col-span-1 md:col-span-2 flex items-center justify-between p-4 border-2 border-yellow-400 bg-yellow-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-yellow-500 text-white rounded-full flex items-center justify-center text-xl border-2 border-black">💸</div>
                                    <div>
                                        <p class="font-black text-yellow-900 text-sm">Pengajuan Beasiswa</p>
                                        <p class="text-[10px] text-yellow-700 font-bold">KIP / SKTM / Rapor</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/'.$pendaftar->file_beasiswa) }}" target="_blank" class="px-4 py-2 bg-yellow-400 border-2 border-black text-black rounded text-xs font-black hover:bg-yellow-500 transition shadow-sm">DOWNLOAD BERKAS</a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. TAB BIODATA -->
                    <div x-show="tab === 'biodata'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm">
                        <div class="space-y-4">
                            <h4 class="font-black text-unmaris-blue uppercase text-xs border-b border-gray-200 pb-1 mb-2">Data Pribadi</h4>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">NIK (KTP)</span><span class="font-bold text-gray-900">{{ $pendaftar->nik }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">TTL</span><span class="font-bold text-gray-900">{{ $pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftar->tgl_lahir)->format('d M Y') }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">JK</span><span class="font-bold text-gray-900">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">Agama</span><span class="font-bold text-gray-900">{{ $pendaftar->agama }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">Alamat</span><span class="font-bold text-gray-900">{{ $pendaftar->alamat }}</span></div>
                        </div>
                        <div class="space-y-4">
                            <h4 class="font-black text-unmaris-blue uppercase text-xs border-b border-gray-200 pb-1 mb-2">Data Orang Tua</h4>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">Ayah</span><span class="font-bold text-gray-900">{{ $pendaftar->nama_ayah }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">Pekerjaan</span><span class="font-bold text-gray-900">{{ $pendaftar->pekerjaan_ayah ?? '-' }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">Ibu</span><span class="font-bold text-gray-900">{{ $pendaftar->nama_ibu }}</span></div>
                            <div class="grid grid-cols-2"><span class="text-gray-500 font-bold text-xs">Pekerjaan</span><span class="font-bold text-gray-900">{{ $pendaftar->pekerjaan_ibu ?? '-' }}</span></div>
                        </div>
                    </div>

                    <!-- 3. TAB AKADEMIK -->
                    <div x-show="tab === 'akademik'" x-cloak>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-1 space-y-4">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <span class="text-[10px] font-black text-blue-500 uppercase">Pilihan Utama (Prioritas 1)</span>
                                    <p class="text-lg font-black text-unmaris-blue">{{ $pendaftar->pilihan_prodi_1 }}</p>
                                </div>
                                @if($pendaftar->pilihan_prodi_2)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <span class="text-[10px] font-black text-gray-500 uppercase">Pilihan Kedua (Opsional)</span>
                                    <p class="text-lg font-bold text-gray-700">{{ $pendaftar->pilihan_prodi_2 }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 bg-yellow-50 p-6 rounded-xl border-2 border-yellow-400">
                                <h4 class="font-black text-yellow-900 uppercase text-sm mb-4 border-b border-yellow-300 pb-2">Nilai Seleksi</h4>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-xs font-bold text-gray-600 uppercase">Nilai Ujian Tulis</span>
                                    <span class="text-2xl font-black text-unmaris-blue">{{ $pendaftar->nilai_ujian > 0 ? $pendaftar->nilai_ujian : '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-600 uppercase">Nilai Wawancara</span>
                                    <span class="text-2xl font-black text-unmaris-blue">{{ $pendaftar->nilai_wawancara > 0 ? $pendaftar->nilai_wawancara : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. TAB SIAKAD -->
                    @if($pendaftar->status_pendaftaran == 'lulus')
                    <div x-show="tab === 'siakad'" x-cloak>
                        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-xl p-6 text-center">
                             @if ($pendaftar->is_synced)
                                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border-4 border-white shadow-sm">✅</div>
                                <h4 class="font-black text-xl text-green-800">DATA TERSINKRONISASI</h4>
                                <p class="text-sm text-green-700 mt-2 font-bold">Mahasiswa ini sudah terdaftar di database Akademik.</p>
                            @else
                                <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border-4 border-white shadow-sm">🚀</div>
                                <h4 class="font-black text-xl text-indigo-900">PUSH DATA KE SIAKAD</h4>
                                <p class="text-sm text-indigo-700 mt-2 mb-6">Pastikan data sudah benar sebelum mengirim ke sistem Akademik.</p>
                                <form action="{{ route('admin.pendaftar.sync', $pendaftar->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-8 rounded-lg shadow-neo transition uppercase tracking-wider" onclick="return confirm('Kirim sekarang?')">Mulai Sinkronisasi</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- ACTION BAR (STICKY BOTTOM - ADMIN CONTROL) -->
            <!-- Logika: HILANG JIKA SUDAH LULUS/GAGAL -->
            @if (!in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']))
                <div class="bg-white border-t-4 border-unmaris-blue p-6 rounded-xl shadow-neo relative" x-data="{ openP1: false, openP2: false, openRek: false }">
                    <div class="absolute -top-4 left-6 bg-unmaris-blue text-white px-4 py-1 font-black text-xs uppercase rounded shadow-sm">
                        ADMIN ACTION ZONE
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        
                        <!-- 1. VERIFIKASI BERKAS -->
                        <div class="w-full md:w-1/3">
                            <h5 class="font-bold text-gray-500 text-xs uppercase mb-2">Langkah 1: Cek Berkas</h5>
                            @if($pendaftar->status_pembayaran == 'lunas')
                                @if($pendaftar->status_pendaftaran == 'submit')
                                    <form action="{{ route('admin.pendaftar.update-status', $pendaftar->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button name="status" value="verifikasi" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-sm text-sm animate-pulse">
                                            🔍 VERIFIKASI BERKAS SEKARANG
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center gap-2 text-green-700 bg-green-50 p-3 rounded-lg border border-green-200">
                                        <span class="text-xl">✅</span>
                                        <span class="text-xs font-black uppercase">Berkas Valid & Terverifikasi</span>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center gap-2 text-red-600 bg-red-50 p-3 rounded-lg border border-red-200 opacity-75">
                                    <span class="text-xl">⏳</span>
                                    <span class="text-xs font-black uppercase">Menunggu Pembayaran</span>
                                </div>
                            @endif
                        </div>

                        <div class="hidden md:block text-gray-300">➜</div>

                        <!-- 2. KEPUTUSAN KELULUSAN -->
                        <div class="w-full md:w-2/3 {{ ($pendaftar->status_pendaftaran != 'verifikasi' || $pendaftar->nilai_ujian == 0) ? 'opacity-50 grayscale pointer-events-none' : '' }}">
                            <h5 class="font-bold text-gray-500 text-xs uppercase mb-2">Langkah 2: Putusan Akhir</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <button @click="openP1=true" class="bg-green-600 hover:bg-green-700 text-white font-black py-3 rounded-lg shadow-sm text-sm uppercase">Lulus Pilihan 1</button>
                                @if($pendaftar->pilihan_prodi_2)
                                    <button @click="openP2=true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 rounded-lg shadow-sm text-sm uppercase">Lulus Pilihan 2</button>
                                @endif
                                @if($pendaftar->rekomendasi_prodi)
                                    <button @click="openRek=true" class="bg-purple-600 hover:bg-purple-700 text-white font-black py-3 rounded-lg shadow-sm text-sm uppercase md:col-span-2">⭐ Lulus Rekomendasi</button>
                                @endif
                            </div>
                            @if($pendaftar->nilai_ujian == 0 && $pendaftar->status_pendaftaran == 'verifikasi')
                                <p class="text-[10px] text-red-500 font-bold mt-1 text-center">Input nilai seleksi dulu.</p>
                            @endif
                        </div>

                    </div>

                    <!-- MODAL CONFIRMATION (HIDDEN) -->
                    <x-modal-confirm show="openP1" title="Lulus Pilihan 1" color="green" prodi="{{ $pendaftar->pilihan_prodi_1 }}"   :pilihan="1" action="{{ route('admin.pendaftar.lulus-pilihan', $pendaftar->id) }}" x-cloak><input type="hidden" name="pilihan" value="1" /></x-modal-confirm>
                    <x-modal-confirm show="openP2" title="Lulus Pilihan 2" color="emerald" prodi="{{ $pendaftar->pilihan_prodi_2 }}"   :pilihan="2" action="{{ route('admin.pendaftar.lulus-pilihan', $pendaftar->id) }}" x-cloak><input type="hidden" name="pilihan" value="2" /></x-modal-confirm>
                    <x-modal-confirm show="openRek" title="Lulus Rekomendasi" color="purple" prodi="{{ $pendaftar->rekomendasi_prodi }}" action="{{ route('admin.pendaftar.lulus-rekomendasi', $pendaftar->id) }}" x-cloak>
                        <p class="text-xs font-bold text-gray-500 mb-1 uppercase">Catatan Panitia:</p>
                        <div class="bg-gray-100 p-3 rounded border border-gray-300 italic text-sm">{{ $pendaftar->catatan_seleksi ?? 'Tidak ada catatan.' }}</div>
                    </x-modal-confirm>
                </div>
            @endif

            <!-- REKOMENDASI PANEL (OPTIONAL) -->
            @if($pendaftar->status_pendaftaran != 'lulus' && $pendaftar->status_pendaftaran == 'verifikasi' && $pendaftar->nilai_ujian > 0)
            <div class="bg-yellow-50 border-2 border-yellow-200 p-6 rounded-xl mt-6">
                <h5 class="font-black text-yellow-800 uppercase text-sm mb-4">Opsi Alternatif (Rekomendasi)</h5>
                <form action="{{ route('admin.pendaftar.rekomendasi', $pendaftar->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf @method('PATCH')
                    <div class="flex-1 w-full">
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Rekomendasi Prodi Lain</label>
                        <select name="rekomendasi_prodi" class="w-full border-gray-300 rounded-lg text-sm">
                            <option value="">-- Pilih Prodi --</option>
                            @foreach(\App\Models\StudyProgram::all() as $prodi)
                                <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Catatan Panitia</label>
                        <input type="text" name="catatan_seleksi" value="{{ $pendaftar->catatan_seleksi }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Alasan rekomendasi...">
                    </div>
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-2 px-4 rounded-lg shadow-sm text-sm">Simpan</button>
                </form>
            </div>
            @endif

        </div>

    </div>
</x-admin-layout>