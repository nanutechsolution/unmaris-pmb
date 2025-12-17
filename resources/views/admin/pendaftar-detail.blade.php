<x-admin-layout>
    <x-slot name="header">
        🔍 Detail Data Pendaftar
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI: FOTO & STATUS -->
        <div class="space-y-8">
            
            <!-- Pas Foto Card -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-4 bg-unmaris-blue"></div>
                
                @if($pendaftar->foto_path)
                    <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" 
                         class="w-48 h-64 object-cover mx-auto border-4 border-unmaris-blue shadow-sm rounded mb-4 bg-gray-100">
                @else
                    <div class="w-48 h-64 bg-gray-100 border-4 border-unmaris-blue border-dashed mx-auto flex flex-col items-center justify-center mb-4 text-gray-400">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-bold text-xs uppercase">Tidak Ada Foto</span>
                    </div>
                @endif
                
                <div class="font-black text-xl text-unmaris-blue uppercase leading-tight">{{ $pendaftar->user->name }}</div>
                <div class="text-sm font-bold text-gray-500 mt-1">NISN: {{ $pendaftar->nisn ?? '-' }}</div>
            </div>

            <!-- Panel Verifikasi (Action) -->
            <div class="bg-unmaris-yellow border-4 border-unmaris-blue shadow-neo rounded-xl p-6">
                <h3 class="font-black text-unmaris-blue text-lg mb-4 uppercase border-b-2 border-unmaris-blue pb-2 flex items-center">
                    ⚡ Verifikasi Status
                </h3>
                
                <form action="{{ route('admin.pendaftar.update-status', $pendaftar->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <!-- Indikator Status Saat Ini -->
                    <div class="bg-white border-2 border-unmaris-blue p-3 rounded mb-4 text-center">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Status Saat Ini</span>
                        <div class="font-black text-2xl text-unmaris-blue uppercase tracking-wide">
                            {{ $pendaftar->status_pendaftaran }}
                        </div>
                    </div>

                    <p class="text-xs font-bold text-unmaris-blue mb-2">Ubah Status Menjadi:</p>

                    <button type="submit" name="status" value="verifikasi" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-900 border-2 border-unmaris-blue font-bold py-2 rounded shadow-sm hover:shadow-none transition-all text-sm flex justify-center items-center">
                        🔄 Sedang Diverifikasi
                    </button>

                    <!-- TOMBOL LULUS DENGAN GUARD NILAI -->
                    @if($pendaftar->nilai_ujian > 0 && $pendaftar->nilai_wawancara > 0)
                        <button type="submit" name="status" value="lulus" onclick="return confirm('Yakin nyatakan LULUS?')" class="w-full bg-unmaris-green hover:bg-green-600 text-white border-2 border-unmaris-blue font-black py-3 rounded shadow-[2px_2px_0px_0px_#1E3A8A] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex justify-center items-center">
                            ✅ NYATAKAN LULUS
                        </button>
                    @else
                        <button type="button" disabled class="w-full bg-gray-400 text-white border-2 border-gray-600 font-bold py-3 rounded cursor-not-allowed opacity-70 flex justify-center items-center" title="Input Nilai Ujian Tulis & Wawancara Terlebih Dahulu">
                            🔒 LENGKAPI NILAI DULU
                        </button>
                    @endif

                    <button type="submit" name="status" value="gagal" onclick="return confirm('Yakin nyatakan TIDAK LULUS?')" class="w-full bg-red-500 hover:bg-red-600 text-white border-2 border-unmaris-blue font-bold py-2 rounded shadow-[2px_2px_0px_0px_#1E3A8A] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex justify-center items-center">
                        ❌ TOLAK / GAGAL
                    </button>
                    
                    <div class="border-t-2 border-dashed border-gray-300 my-3"></div>

                    <button type="submit" name="status" value="draft" onclick="return confirm('Buka kunci data?')" class="w-full bg-gray-500 hover:bg-gray-600 text-white border-2 border-unmaris-blue font-bold py-2 rounded shadow-sm hover:shadow-none transition-all text-sm flex justify-center items-center">
                        🔓 BUKA KUNCI (EDIT)
                    </button>
                </form>
            </div>
            
            <!-- PANEL PEMBAYARAN LIVEWIRE -->
            @livewire('admin.payment-verifier', ['pendaftar' => $pendaftar])
        </div>

        <!-- KOLOM KANAN: DETAIL DATA -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Card Biodata -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-8 relative">
                <div class="absolute top-4 right-4 text-xs font-black bg-unmaris-blue text-white px-2 py-1 rounded">
                    REG-{{ str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT) }}
                </div>

                <h3 class="font-black text-2xl text-unmaris-blue mb-6 border-b-4 border-unmaris-yellow inline-block pb-1">
                    📝 Biodata Lengkap
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jalur Pendaftaran</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ ucfirst($pendaftar->jalur_pendaftaran) }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">NIK (KTP)</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->nik }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tempat, Tanggal Lahir</label>
                        <div class="font-bold text-lg text-unmaris-blue">
                            {{ $pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftar->tgl_lahir)->format('d F Y') }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jenis Kelamin</label>
                        <div class="font-bold text-lg text-unmaris-blue">
                            {{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Agama</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->agama }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kontak (Email/HP)</label>
                        <div class="font-bold text-lg text-unmaris-blue truncate">{{ $pendaftar->user->email }}</div>
                        <div class="text-sm font-medium text-gray-600">{{ $pendaftar->user->nomor_hp ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap</label>
                        <div class="font-bold text-lg text-unmaris-blue bg-gray-50 p-3 rounded border-2 border-gray-100">
                            {{ $pendaftar->alamat }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Akademik & Nilai -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-8">
                <h3 class="font-black text-xl text-unmaris-blue mb-6 border-b-4 border-unmaris-yellow inline-block pb-1">
                    🏫 Data Akademik & Seleksi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <!-- Sekolah -->
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Asal Sekolah</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->asal_sekolah }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tahun Lulus</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->tahun_lulus }}</div>
                    </div>
                    
                    <div class="md:col-span-2 border-t-2 border-dashed border-gray-300 my-2"></div>

                    <!-- Pilihan Prodi -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pilihan Program Studi</label>
                        <div class="flex flex-col gap-2 mt-1">
                            <div class="flex items-center bg-blue-50 border border-blue-100 p-2 rounded">
                                <span class="bg-unmaris-blue text-white text-xs font-black px-2 py-1 rounded mr-2">1</span>
                                <span class="font-bold text-unmaris-blue">{{ $pendaftar->pilihan_prodi_1 }}</span>
                            </div>
                            @if($pendaftar->pilihan_prodi_2)
                            <div class="flex items-center bg-gray-50 border border-gray-100 p-2 rounded opacity-75">
                                <span class="bg-gray-400 text-white text-xs font-black px-2 py-1 rounded mr-2">2</span>
                                <span class="font-bold text-gray-600">{{ $pendaftar->pilihan_prodi_2 }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- NILAI SELEKSI -->
                    <div class="md:col-span-2 border-t-2 border-dashed border-gray-300 my-2 pt-2">
                        <div class="flex justify-between items-center bg-unmaris-yellow p-3 rounded border-2 border-black">
                            <div>
                                <span class="block text-xs font-bold text-unmaris-blue uppercase">Nilai Ujian Tulis</span>
                                <span class="text-2xl font-black {{ $pendaftar->nilai_ujian > 0 ? 'text-blue-900' : 'text-gray-500' }}">
                                    {{ $pendaftar->nilai_ujian > 0 ? $pendaftar->nilai_ujian : '-' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="block text-xs font-bold text-unmaris-blue uppercase">Nilai Wawancara</span>
                                <span class="text-2xl font-black {{ $pendaftar->nilai_wawancara > 0 ? 'text-blue-900' : 'text-gray-500' }}">
                                    {{ $pendaftar->nilai_wawancara > 0 ? $pendaftar->nilai_wawancara : '-' }}
                                </span>
                            </div>
                        </div>
                        @if($pendaftar->nilai_ujian == 0 || $pendaftar->nilai_wawancara == 0)
                            <p class="text-xs text-red-500 font-bold mt-2 text-center">* Nilai belum lengkap. Harap isi di menu Seleksi & Wawancara.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Dokumen -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-8">
                <h3 class="font-black text-xl text-unmaris-blue mb-6 border-b-4 border-unmaris-yellow inline-block pb-1">
                    📂 Berkas Pendukung
                </h3>
                
                <div class="flex flex-col md:flex-row items-center justify-between bg-gray-50 border-2 border-unmaris-blue rounded-lg p-4 gap-4">
                    <div class="flex items-center w-full">
                        <div class="bg-red-100 text-red-600 p-3 rounded-lg border-2 border-red-200 mr-4 flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-black text-unmaris-blue text-lg">Ijazah / SKL</div>
                            <div class="text-sm font-bold text-gray-500">
                                @if($pendaftar->ijazah_path)
                                    <span class="text-green-600">✓ File Tersedia</span>
                                @else
                                    <span class="text-red-500">⚠ File Tidak Ditemukan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($pendaftar->ijazah_path)
                        <a href="{{ asset('storage/' . $pendaftar->ijazah_path) }}" target="_blank" class="w-full md:w-auto text-center bg-unmaris-blue hover:bg-blue-800 text-white font-black py-3 px-6 rounded-lg border-2 border-black shadow-[2px_2px_0px_0px_#000] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-2">
                            <span>Buka File</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>