<x-admin-layout>
    <x-slot name="header">
        🔍 Detail Data Pendaftar
    </x-slot>

    <!-- NOTIFIKASI SYSTEM -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 font-bold shadow-sm rounded-r flex items-center animate-fade-in-down">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 font-bold shadow-sm rounded-r flex items-center animate-fade-in-down">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- KOLOM KIRI (SIDEBAR): FOTO & STATUS -->
        <div class="space-y-6 md:space-y-8">
            
            <!-- PAS FOTO CARD -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 text-center relative overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute top-0 left-0 w-full h-4 bg-unmaris-blue"></div>
                
                @if($pendaftar->foto_path)
                    <div class="relative inline-block mt-4 mb-4">
                        <img src="{{ asset('storage/' . $pendaftar->foto_path) }}" 
                             class="w-40 h-52 md:w-48 md:h-64 object-cover mx-auto border-4 border-unmaris-blue shadow-sm rounded bg-gray-100">
                        <div class="absolute bottom-2 right-2 bg-green-500 w-4 h-4 rounded-full border-2 border-white"></div>
                    </div>
                @else
                    <div class="w-40 h-52 md:w-48 md:h-64 bg-gray-100 border-4 border-unmaris-blue border-dashed mx-auto flex flex-col items-center justify-center mb-4 text-gray-400 mt-4">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-bold text-xs uppercase">No Photo</span>
                    </div>
                @endif
                
                <div class="font-black text-lg md:text-xl text-unmaris-blue uppercase leading-tight">{{ $pendaftar->user->name }}</div>
                <div class="text-xs md:text-sm font-bold text-gray-500 mt-1 bg-gray-100 inline-block px-2 py-1 rounded">
                    NISN: {{ $pendaftar->nisn ?? '-' }}
                </div>
            </div>

            <!-- PANEL VERIFIKASI (ACTION) -->
            <div class="bg-unmaris-yellow border-4 border-unmaris-blue shadow-neo rounded-xl p-5 md:p-6 relative">
                <div class="absolute -top-3 -right-3 bg-red-500 text-white font-black text-xs px-2 py-1 rounded border-2 border-black transform rotate-12 shadow-sm">ADMIN AREA</div>
                
                <h3 class="font-black text-unmaris-blue text-lg mb-4 uppercase border-b-2 border-unmaris-blue pb-2 flex items-center">
                    ⚡ Verifikasi Status
                </h3>
                
                <form action="{{ route('admin.pendaftar.update-status', $pendaftar->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <!-- Indikator Status Saat Ini -->
                    <div class="bg-white border-2 border-unmaris-blue p-3 rounded-lg mb-4 text-center shadow-sm">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Status Saat Ini</span>
                        <div class="font-black text-xl md:text-2xl text-unmaris-blue uppercase tracking-wide">
                            {{ $pendaftar->status_pendaftaran }}
                        </div>
                    </div>

                    <p class="text-xs font-bold text-unmaris-blue mb-2 ml-1">Ubah Status Menjadi:</p>

                    <button type="submit" name="status" value="verifikasi" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-900 border-2 border-unmaris-blue font-bold py-3 rounded-lg shadow-sm hover:shadow-none transition-all text-xs md:text-sm flex justify-center items-center group">
                        <span class="group-hover:animate-spin mr-2">🔄</span> Sedang Diverifikasi
                    </button>

                    <!-- TOMBOL LULUS DENGAN GUARD KETAT -->
                    <!-- Syarat: Nilai Ujian > 0, Nilai Wawancara > 0, DAN SUDAH LUNAS -->
                    @if($pendaftar->nilai_ujian > 0 && $pendaftar->nilai_wawancara > 0 && $pendaftar->status_pembayaran == 'lunas')
                        <button type="submit" name="status" value="lulus" onclick="return confirm('Yakin nyatakan LULUS?')" class="w-full bg-unmaris-green hover:bg-green-600 text-white border-2 border-unmaris-blue font-black py-3 rounded-lg shadow-[2px_2px_0px_0px_#1E3A8A] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex justify-center items-center text-sm md:text-base">
                            ✅ NYATAKAN LULUS
                        </button>
                    @else
                        <!-- Tampilan Tombol Disabled dengan Pesan Error -->
                        @if($pendaftar->status_pembayaran != 'lunas')
                             <button type="button" disabled class="w-full bg-red-100 text-red-500 border-2 border-red-300 font-bold py-3 rounded-lg cursor-not-allowed opacity-80 flex justify-center items-center text-xs md:text-sm" title="Mahasiswa belum melunasi pembayaran">
                                ⛔ BELUM LUNAS (CEK PAYMENT)
                            </button>
                        @else
                             <button type="button" disabled class="w-full bg-gray-400 text-white border-2 border-gray-600 font-bold py-3 rounded-lg cursor-not-allowed opacity-70 flex justify-center items-center text-xs md:text-sm" title="Input Nilai Ujian Tulis & Wawancara Terlebih Dahulu">
                                🔒 LENGKAPI NILAI DULU
                            </button>
                        @endif
                    @endif

                    <button type="submit" name="status" value="gagal" onclick="return confirm('Yakin nyatakan TIDAK LULUS?')" class="w-full bg-red-500 hover:bg-red-600 text-white border-2 border-unmaris-blue font-bold py-3 rounded-lg shadow-[2px_2px_0px_0px_#1E3A8A] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex justify-center items-center text-sm md:text-base">
                        ❌ TOLAK / GAGAL
                    </button>
                    
                    <div class="border-t-2 border-dashed border-unmaris-blue/50 my-4"></div>

                    <button type="submit" name="status" value="draft" onclick="return confirm('Buka kunci data?')" class="w-full bg-gray-600 hover:bg-gray-700 text-white border-2 border-black font-bold py-2 rounded-lg shadow-sm hover:shadow-none transition-all text-xs flex justify-center items-center">
                        🔓 BUKA KUNCI (IZIN EDIT)
                    </button>
                </form>
            </div>
            
            <!-- PANEL PEMBAYARAN (LIVEWIRE) -->
            <!-- Memanggil Component PaymentVerifier -->
            @livewire('admin.payment-verifier', ['pendaftar' => $pendaftar])

            <!-- PANEL INTEGRASI SIAKAD (KHUSUS YANG SUDAH LULUS) -->
            @if($pendaftar->status_pendaftaran == 'lulus')
                <div class="bg-indigo-50 border-4 border-indigo-600 shadow-neo rounded-xl p-5 md:p-6">
                    <h3 class="font-black text-indigo-900 text-lg mb-4 uppercase border-b-2 border-indigo-200 pb-2 flex items-center">
                        🚀 Integrasi SIAKAD
                    </h3>
                    
                    @if($pendaftar->is_synced)
                        <div class="bg-green-100 text-green-800 p-3 rounded-lg border border-green-300 text-center mb-4">
                            <span class="font-black block text-lg">SUDAH DISINKRON</span>
                            <span class="text-xs">Data mahasiswa ini sudah ada di SIAKAD.</span>
                        </div>
                    @else
                        <p class="text-xs font-bold text-indigo-700 mb-3">
                            Kirim data mahasiswa ini ke sistem Akademik untuk penerbitan NIM secara otomatis.
                        </p>
                        <form action="{{ route('admin.pendaftar.sync', $pendaftar->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-neo transition flex items-center justify-center gap-2 text-sm" onclick="return confirm('Kirim ke SIAKAD sekarang?')">
                                📤 KIRIM KE SIAKAD
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>

        <!-- KOLOM KANAN: DETAIL DATA LENGKAP -->
        <div class="lg:col-span-2 space-y-6 md:space-y-8">
            
            <!-- CARD BIODATA -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-4 right-4 text-[10px] md:text-xs font-black bg-unmaris-blue text-white px-2 py-1 rounded uppercase tracking-wider">
                    REG-{{ str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT) }}
                </div>

                <h3 class="font-black text-xl md:text-2xl text-unmaris-blue mb-6 border-b-4 border-unmaris-yellow inline-block pb-1">
                    📝 Biodata Lengkap
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 md:gap-y-6 gap-x-8 text-sm md:text-base">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Jalur Pendaftaran</label>
                        <div class="font-bold text-lg text-unmaris-blue border-b border-gray-100 pb-1">{{ ucfirst($pendaftar->jalur_pendaftaran) }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">NIK (KTP)</label>
                        <div class="font-bold text-lg text-unmaris-blue border-b border-gray-100 pb-1">{{ $pendaftar->nik }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Tempat, Tanggal Lahir</label>
                        <div class="font-bold text-lg text-unmaris-blue border-b border-gray-100 pb-1">
                            {{ $pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftar->tgl_lahir)->format('d F Y') }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Jenis Kelamin</label>
                        <div class="font-bold text-lg text-unmaris-blue border-b border-gray-100 pb-1">
                            {{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Agama</label>
                        <div class="font-bold text-lg text-unmaris-blue border-b border-gray-100 pb-1">{{ $pendaftar->agama }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Kontak (Email/HP)</label>
                        <div class="font-bold text-lg text-unmaris-blue truncate">{{ $pendaftar->user->email }}</div>
                        <div class="text-sm font-medium text-gray-600">{{ $pendaftar->user->nomor_hp ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Alamat Lengkap</label>
                        <div class="font-bold text-lg text-unmaris-blue bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                            {{ $pendaftar->alamat }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD AKADEMIK & ORTU -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 md:p-8">
                <h3 class="font-black text-xl text-unmaris-blue mb-6 border-b-4 border-unmaris-yellow inline-block pb-1">
                    🏫 Data Akademik & Orang Tua
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm md:text-base">
                    <!-- Sekolah -->
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Asal Sekolah</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->asal_sekolah }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Tahun Lulus</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->tahun_lulus }}</div>
                    </div>
                    
                    <div class="md:col-span-2 border-t-2 border-dashed border-gray-300 my-2"></div>

                    <!-- Pilihan Prodi -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Pilihan Program Studi</label>
                        <div class="flex flex-col gap-2 mt-1">
                            <div class="flex items-center bg-blue-50 border border-blue-100 p-3 rounded-lg shadow-sm">
                                <span class="bg-unmaris-blue text-white text-xs font-black px-2 py-1 rounded mr-3 shadow-sm">PRIORITAS 1</span>
                                <span class="font-black text-unmaris-blue text-base md:text-lg">{{ $pendaftar->pilihan_prodi_1 }}</span>
                            </div>
                            @if($pendaftar->pilihan_prodi_2)
                            <div class="flex items-center bg-gray-50 border border-gray-200 p-3 rounded-lg opacity-80">
                                <span class="bg-gray-400 text-white text-xs font-black px-2 py-1 rounded mr-3">OPSIONAL 2</span>
                                <span class="font-bold text-gray-600">{{ $pendaftar->pilihan_prodi_2 }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="md:col-span-2 border-t-2 border-dashed border-gray-300 my-2"></div>

                    <!-- Orang Tua -->
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Ayah</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->nama_ayah }}</div>
                        <div class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">{{ $pendaftar->pekerjaan_ayah ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Ibu</label>
                        <div class="font-bold text-lg text-unmaris-blue">{{ $pendaftar->nama_ibu }}</div>
                        <div class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">{{ $pendaftar->pekerjaan_ibu ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- CARD NILAI SELEKSI -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 md:p-8">
                <h3 class="font-black text-xl text-unmaris-blue mb-6 border-b-4 border-unmaris-yellow inline-block pb-1">
                    📊 Hasil Seleksi
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nilai Tulis -->
                    <div class="bg-blue-50 p-4 rounded-xl border-2 border-blue-200 text-center">
                        <span class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Ujian Tulis</span>
                        @if($pendaftar->nilai_ujian > 0)
                            <span class="block text-4xl font-black text-unmaris-blue">{{ $pendaftar->nilai_ujian }}</span>
                        @else
                            <span class="block text-2xl font-bold text-gray-400 py-2">-</span>
                            <span class="text-[10px] text-red-400 font-bold bg-red-50 px-2 py-1 rounded">BELUM DINILAI</span>
                        @endif
                    </div>

                    <!-- Nilai Wawancara -->
                    <div class="bg-orange-50 p-4 rounded-xl border-2 border-orange-200 text-center">
                        <span class="block text-xs font-bold text-orange-400 uppercase tracking-widest mb-1">Wawancara</span>
                        @if($pendaftar->nilai_wawancara > 0)
                            <span class="block text-4xl font-black text-orange-600">{{ $pendaftar->nilai_wawancara }}</span>
                        @else
                            <span class="block text-2xl font-bold text-gray-400 py-2">-</span>
                            <span class="text-[10px] text-red-400 font-bold bg-red-50 px-2 py-1 rounded">BELUM DINILAI</span>
                        @endif
                    </div>
                </div>

                @if($pendaftar->nilai_ujian == 0 || $pendaftar->nilai_wawancara == 0)
                    <div class="mt-4 text-center">
                        <p class="text-xs text-red-500 font-bold italic bg-red-50 p-2 rounded border border-red-200">
                            * Nilai belum lengkap. Harap input di menu "Seleksi" dan "Wawancara" sebelum melakukan finalisasi kelulusan.
                        </p>
                    </div>
                @endif
            </div>

            <!-- CARD DOKUMEN -->
            <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 md:p-8">
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
                            <div class="text-sm font-bold text-gray-500 mt-1">
                                @if($pendaftar->ijazah_path)
                                    <span class="text-green-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        File Tersedia
                                    </span>
                                @else
                                    <span class="text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        File Tidak Ditemukan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($pendaftar->ijazah_path)
                        <a href="{{ asset('storage/' . $pendaftar->ijazah_path) }}" target="_blank" class="w-full md:w-auto text-center bg-unmaris-blue hover:bg-blue-800 text-white font-black py-3 px-6 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center justify-center gap-2 text-sm">
                            <span>BUKA FILE</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>