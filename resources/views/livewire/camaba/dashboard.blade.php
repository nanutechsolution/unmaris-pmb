<div class="py-4 font-sans">
    <div class="max-w-7xl mx-auto">
        
        <!-- LOGIKA PENUNTUN (NEXT STEP INTELLIGENCE) -->
        @php
            $nextStepTitle = '';
            $nextStepDesc = '';
            $nextStepRoute = '';
            $nextStepBtn = '';
            $showNextStep = false;
            
            // Default Style (Normal)
            $cardColor = 'bg-yellow-400'; 
            $textColor = 'text-black';
            $btnColor = 'bg-white text-black';

            // Cek Detail Penolakan Dokumen & Alasan
            $docRejected = false;
            $rejectionDetails = []; // Array untuk menyimpan detail alasan
            
            if($pendaftar && !empty($pendaftar->doc_status)) {
                foreach($pendaftar->doc_status as $key => $status) {
                    if(isset($status['status']) && $status['status'] == 'rejected') {
                        $docRejected = true;
                        $labels = ['ktp' => 'KTP', 'akta' => 'Akta', 'ijazah' => 'Ijazah', 'transkrip' => 'Transkrip', 'beasiswa' => 'Berkas Beasiswa'];
                        $label = $labels[$key] ?? ucfirst($key);
                        $reason = $status['reason'] ?? 'Tidak valid';
                        
                        // Format pesan: "KTP (Alasan: Foto buram)"
                        $rejectionDetails[] = "<strong>{$label}</strong>: {$reason}";
                    }
                }
            }

            // --- LOGIKA PRIORITAS STATUS (URUTAN PENTING) ---

            if (!$pendaftar) {
                // 1. Belum Daftar
                $showNextStep = true;
                $nextStepTitle = 'Langkah 1: Lengkapi Formulir';
                $nextStepDesc = 'Anda belum mengisi biodata. Silakan isi formulir pendaftaran untuk memulai proses seleksi.';
                $nextStepRoute = route('camaba.formulir');
                $nextStepBtn = '📝 ISI FORMULIR SEKARANG';

            } elseif ($pendaftar->status_pembayaran == 'ditolak') {
                // 2. PEMBAYARAN DITOLAK (URGENT)
                $showNextStep = true;
                $cardColor = 'bg-red-600'; 
                $textColor = 'text-white';
                $btnColor = 'bg-white text-red-600';
                $nextStepTitle = '⚠️ BUKTI BAYAR DITOLAK';
                $nextStepDesc = 'Admin menolak bukti transfer Anda. Mohon cek kembali nominal atau kejelasan foto, lalu unggah ulang.';
                $nextStepRoute = route('camaba.pembayaran');
                $nextStepBtn = '📤 UNGGAH ULANG BUKTI';

            } elseif ($pendaftar->status_pendaftaran == 'perbaikan') {
                // 3. STATUS PERBAIKAN / REVISI (URGENT)
                $showNextStep = true;
                $cardColor = 'bg-red-600';
                $textColor = 'text-white';
                $btnColor = 'bg-white text-red-600';
                $nextStepTitle = '⚠️ REVISI DOKUMEN DIPERLUKAN';
                
                // Tampilkan list alasan jika ada
                if (!empty($rejectionDetails)) {
                    $nextStepDesc = "Mohon perbaiki dokumen berikut:<br><ul class='list-disc ml-5 mt-1 text-sm'>" . implode('', array_map(fn($i) => "<li>$i</li>", $rejectionDetails)) . "</ul><br>Silakan unggah ulang di menu Formulir.";
                } else {
                    $nextStepDesc = "Terdapat kesalahan pada data/berkas Anda. Silakan periksa menu Formulir untuk detail perbaikan.";
                }

                $nextStepRoute = route('camaba.formulir');
                $nextStepBtn = '📂 PERBAIKI & UNGGAH ULANG';

            } elseif ($pendaftar->status_pendaftaran == 'draft') {
                // 4. STATUS DRAFT
                $showNextStep = true;
                $cardColor = 'bg-yellow-400';
                $nextStepTitle = '📝 Formulir Belum Final';
                $nextStepDesc = 'Data Anda masih tersimpan sebagai DRAFT. Lengkapi semua data lalu klik "Simpan Permanen" agar bisa diperiksa Admin.';
                $nextStepRoute = route('camaba.formulir');
                $nextStepBtn = 'LANJUTKAN PENGISIAN';

            } elseif ($pendaftar->status_pendaftaran == 'submit' && $pendaftar->status_pembayaran == 'belum_bayar') {
                // 5. Belum Bayar
                $showNextStep = true;
                $cardColor = 'bg-orange-400';
                $nextStepTitle = 'Langkah 2: Pembayaran';
                $nextStepDesc = 'Data tersimpan! Segera lakukan pembayaran biaya pendaftaran agar berkas Anda diproses admin.';
                $nextStepRoute = route('camaba.pembayaran');
                $nextStepBtn = '💸 BAYAR SEKARANG';

            } elseif ($pendaftar->status_pembayaran == 'menunggu_verifikasi') {
                // 6. Menunggu Verifikasi Admin
                $showNextStep = true;
                $cardColor = 'bg-blue-300';
                $nextStepTitle = '⏳ Sedang Diverifikasi';
                $nextStepDesc = 'Bukti pembayaran Anda sudah diterima. Mohon tunggu 1x24 jam untuk verifikasi Admin.';
                $nextStepRoute = '#';
                $nextStepBtn = ''; 

            } elseif ($pendaftar->status_pembayaran == 'lunas' && !$pendaftar->jadwal_ujian && !$pendaftar->jadwal_wawancara && $pendaftar->nilai_ujian == 0 && $pendaftar->status_pendaftaran != 'lulus' && $pendaftar->status_pendaftaran != 'gagal' && !$docRejected) {
                // 7. Menunggu Jadwal
                $showNextStep = true;
                $cardColor = 'bg-green-300';
                $nextStepTitle = '✅ Menunggu Jadwal Ujian';
                $nextStepDesc = 'Pembayaran & Berkas Lunas! Saat ini Panitia sedang mengatur jadwal ujian seleksi untuk Anda. Cek berkala.';
                $nextStepRoute = '#';
                $nextStepBtn = '';

            } elseif ($pendaftar->jadwal_ujian && $pendaftar->nilai_ujian == 0) {
                // 8. Cetak Kartu & Ujian
                $showNextStep = true;
                $nextStepTitle = 'Langkah 3: Cetak Kartu Ujian';
                $nextStepDesc = 'Jadwal ujian telah keluar! Silakan cetak kartu ujian dan bawa saat pelaksanaan tes.';
                $nextStepRoute = route('camaba.cetak-kartu');
                $nextStepBtn = '🎫 CETAK KARTU';
                $cardColor = 'bg-unmaris-yellow';

            } elseif ($pendaftar->status_pendaftaran == 'lulus') {
                // 9. Lulus
                $showNextStep = true;
                $nextStepTitle = '🎉 SELAMAT! ANDA LULUS';
                $nextStepDesc = 'Selamat bergabung di UNMARIS. Silakan unduh Surat Keterangan Lulus (LoA) di menu Pengumuman.';
                $nextStepRoute = route('camaba.pengumuman');
                $nextStepBtn = '📢 LIHAT PENGUMUMAN';
                $cardColor = 'bg-green-500';
                $textColor = 'text-white';
                
            } elseif ($pendaftar->status_pendaftaran == 'gagal') {
                // 10. Gagal
                $showNextStep = true;
                $nextStepTitle = 'HASIL SELEKSI';
                $nextStepDesc = 'Mohon maaf, Anda belum lulus seleksi tahap ini. Tetap semangat!';
                $nextStepRoute = '#';
                $nextStepBtn = '';
                $cardColor = 'bg-gray-300';
            }
        @endphp

        <!-- 🔥 ACTION CARD UTAMA -->
        @if($showNextStep)
        <div class="{{ $cardColor }} {{ $textColor }} border-4 border-black p-6 rounded-2xl shadow-neo-lg mb-8 flex flex-col md:flex-row items-center justify-between gap-6 animate-fade-in-up relative overflow-hidden">
            <!-- Dekorasi Background -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            
            <div class="flex items-center gap-4 relative z-10 w-full md:w-3/4">
                <div class="bg-white text-black p-3 rounded-full border-2 border-black text-3xl shadow-sm animate-bounce-slight hidden md:block flex-shrink-0 h-16 w-16 flex items-center justify-center">
                    @if(str_contains($cardColor, 'red')) ⚠️ @elseif(str_contains($cardColor, 'green')) 🎉 @else 👇 @endif
                </div>
                <div>
                    @if(str_contains($cardColor, 'red'))
                        <div class="inline-block bg-white text-red-600 text-[10px] font-black px-2 py-0.5 rounded mb-1 uppercase tracking-wider animate-pulse">
                            Perhatian Diperlukan
                        </div>
                    @elseif($pendaftar && $pendaftar->status_pendaftaran == 'draft')
                         <div class="inline-block bg-white text-black text-[10px] font-black px-2 py-0.5 rounded mb-1 uppercase tracking-wider">
                            Draft / Belum Selesai
                        </div>
                    @else
                        <div class="inline-block bg-black text-white text-[10px] font-bold px-2 py-0.5 rounded mb-1 uppercase tracking-wider">
                            Status Terkini
                        </div>
                    @endif
                    <h3 class="font-black text-2xl uppercase leading-none mb-1">{{ $nextStepTitle }}</h3>
                    <!-- Render HTML deskripsi (untuk list error) -->
                    <div class="font-bold opacity-90 text-sm md:text-base leading-snug">
                        {!! $nextStepDesc !!}
                    </div>
                </div>
            </div>
            
            @if($nextStepBtn)
            <div class="w-full md:w-auto relative z-10">
                <a href="{{ $nextStepRoute }}" class="{{ $btnColor }} font-black py-3 px-8 rounded-xl border-4 border-black shadow-sm hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider text-lg whitespace-nowrap text-center flex items-center justify-center gap-2 w-full">
                    {{ $nextStepBtn }} 
                    <span>👉</span>
                </a>
            </div>
            @endif
        </div>
        @endif

        <!-- HERO WELCOME (Status Sekunder) -->
        <div class="bg-unmaris-blue text-white rounded-3xl p-6 mb-8 border-4 border-black shadow-neo relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10 transform translate-x-10 -translate-y-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-block bg-unmaris-yellow text-unmaris-blue font-black px-3 py-1 rounded border-2 border-black shadow-sm transform -rotate-2 mb-2 text-xs uppercase tracking-widest">
                        Student Portal
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black uppercase tracking-tight">
                        Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                    </h1>
                    <p class="text-blue-200 text-sm font-medium mt-1">
                        Selamat datang di dashboard penerimaan mahasiswa baru.
                    </p>
                </div>
            </div>
        </div>

        <!-- JADWAL SAYA (Hanya muncul jika sudah ada jadwal) -->
        @if($pendaftar && ($pendaftar->jadwal_ujian || $pendaftar->jadwal_wawancara))
        <div class="mb-10">
            <h3 class="font-black text-xl text-unmaris-blue mb-4 uppercase flex items-center">
                📅 Jadwal Saya
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($pendaftar->jadwal_ujian)
                <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-5 relative overflow-hidden group hover:bg-blue-50 transition">
                    <div class="absolute top-0 right-0 bg-blue-100 px-3 py-1 rounded-bl-lg font-bold text-xs text-unmaris-blue border-b-2 border-l-2 border-unmaris-blue">UJIAN TULIS</div>
                    <div class="flex items-start gap-4">
                        <div class="bg-blue-100 p-2 rounded-lg border-2 border-unmaris-blue text-xl">📝</div>
                        <div>
                            <h4 class="text-lg font-black text-unmaris-blue">{{ $pendaftar->jadwal_ujian->format('l, d F Y') }}</h4>
                            <p class="text-sm font-bold text-blue-600">Jam {{ $pendaftar->jadwal_ujian->format('H:i') }} WITA</p>
                            <p class="text-xs font-bold text-gray-500 mt-1">📍 {{ $pendaftar->lokasi_ujian }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($pendaftar->jadwal_wawancara)
                <div class="bg-white border-4 border-orange-500 shadow-neo rounded-xl p-5 relative overflow-hidden group hover:bg-orange-50 transition">
                    <div class="absolute top-0 right-0 bg-orange-100 px-3 py-1 rounded-bl-lg font-bold text-xs text-orange-800 border-b-2 border-l-2 border-orange-500">WAWANCARA</div>
                    <div class="flex items-start gap-4">
                        <div class="bg-orange-100 p-2 rounded-lg border-2 border-orange-500 text-xl">🎤</div>
                        <div>
                            <h4 class="text-lg font-black text-orange-900">{{ $pendaftar->jadwal_wawancara->format('l, d F Y') }}</h4>
                            <p class="text-sm font-bold text-orange-600">Jam {{ $pendaftar->jadwal_wawancara->format('H:i') }} WITA</p>
                            <p class="text-xs font-bold text-gray-500 mt-1">👨‍🏫 {{ $pendaftar->pewawancara ?? 'Dosen Penguji' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- MAIN MENU GRID (SHORTCUTS) -->
        <h3 class="font-black text-lg text-unmaris-blue mb-4 uppercase">📂 Menu Akses Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            
            <!-- 1. FORMULIR -->
            <a href="{{ route('camaba.formulir') }}" class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[1px] hover:translate-y-[1px] transition-all relative overflow-hidden {{ ($pendaftar && ($pendaftar->status_pendaftaran == 'perbaikan' || $pendaftar->status_pendaftaran == 'draft')) ? 'ring-4 ring-yellow-400' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $docRejected ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-black' }} rounded-full flex items-center justify-center text-xl border-2 border-black">📝</div>
                    <div>
                        <h3 class="font-black text-base text-unmaris-blue uppercase leading-tight">Formulir</h3>
                        <p class="text-[10px] text-gray-500 font-bold">Biodata & Berkas</p>
                    </div>
                </div>
                @if($pendaftar)
                    @if($pendaftar->status_pendaftaran == 'perbaikan')
                         <div class="absolute top-2 right-2 text-red-500 font-black text-xs animate-pulse">! REVISI</div>
                    @elseif($pendaftar->status_pendaftaran == 'draft')
                         <div class="absolute top-2 right-2 text-yellow-600 font-black text-xs">MODE EDIT</div>
                    @elseif(in_array($pendaftar->status_pendaftaran, ['submit', 'verifikasi', 'lulus']))
                         <div class="absolute top-2 right-2 text-green-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    @endif
                @endif
            </a>

            <!-- 2. PEMBAYARAN -->
            <a href="{{ route('camaba.pembayaran') }}" class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[1px] hover:translate-y-[1px] transition-all relative overflow-hidden {{ ($pendaftar && $pendaftar->status_pembayaran == 'ditolak') ? 'ring-4 ring-red-500' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ ($pendaftar && $pendaftar->status_pembayaran == 'ditolak') ? 'bg-red-100' : 'bg-green-100' }} rounded-full flex items-center justify-center text-xl border-2 border-black">💸</div>
                    <div>
                        <h3 class="font-black text-base text-unmaris-blue uppercase leading-tight">Pembayaran</h3>
                        <p class="text-[10px] text-gray-500 font-bold">Upload Bukti</p>
                    </div>
                </div>
                @if($pendaftar && $pendaftar->status_pembayaran == 'lunas')
                    <div class="absolute top-2 right-2 text-green-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                @elseif($pendaftar && $pendaftar->status_pembayaran == 'ditolak')
                     <div class="absolute top-2 right-2 text-red-500 font-black text-xs animate-pulse">! DITOLAK</div>
                @endif
            </a>

            <!-- 3. KARTU UJIAN -->
            @php $isJadwalReady = $pendaftar && $pendaftar->jadwal_ujian && $pendaftar->status_pembayaran == 'lunas'; @endphp
            <a href="{{ $isJadwalReady ? route('camaba.cetak-kartu') : '#' }}" class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[1px] hover:translate-y-[1px] transition-all relative overflow-hidden {{ !$isJadwalReady ? 'opacity-60 cursor-not-allowed grayscale' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl border-2 border-black">🎫</div>
                    <div>
                        <h3 class="font-black text-base text-unmaris-blue uppercase leading-tight">Kartu Ujian</h3>
                        <p class="text-[10px] text-gray-500 font-bold">Cetak Kartu</p>
                    </div>
                </div>
                @if(!$isJadwalReady)
                    <div class="absolute top-2 right-2 text-gray-400 text-[10px] font-black uppercase border border-gray-400 px-1 rounded">LOCKED</div>
                @endif
            </a>

            <!-- 4. PENGUMUMAN -->
            @php $isResultReady = $pendaftar && in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']); @endphp
            <a href="{{ $isResultReady ? route('camaba.pengumuman') : '#' }}" class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[1px] hover:translate-y-[1px] transition-all relative overflow-hidden {{ !$isResultReady ? 'opacity-60 cursor-not-allowed grayscale' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-xl border-2 border-black">📢</div>
                    <div>
                        <h3 class="font-black text-base text-unmaris-blue uppercase leading-tight">Pengumuman</h3>
                        <p class="text-[10px] text-gray-500 font-bold">Hasil Seleksi</p>
                    </div>
                </div>
                @if(!$isResultReady)
                    <div class="absolute top-2 right-2 text-gray-400 text-[10px] font-black uppercase border border-gray-400 px-1 rounded">LOCKED</div>
                @endif
            </a>

        </div>

    </div>
</div>