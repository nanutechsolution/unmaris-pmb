    <x-slot name="header">
        Beranda Mahasiswa
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- LOGIKA INTELEGENSIA DASHBOARD -->
            @php
                $state = 'unknown';
                $cardColor = 'bg-gray-100';
                $icon = '🤔';
                $title = 'Memuat Data...';
                $desc = 'Sedang menyinkronkan data Anda.';
                $btnText = '';
                $btnUrl = '#';
                $showBtn = false;
                $isLulus = false; // Flag untuk trigger confetti

                // 1. Belum Daftar
                if (!$pendaftar) {
                    $state = 'daftar';
                    $cardColor = 'bg-unmaris-yellow';
                    $icon = '📝';
                    $title = 'Langkah 1: Isi Formulir';
                    $desc = 'Anda belum terdaftar sebagai calon mahasiswa. Silakan lengkapi biodata untuk memulai.';
                    $btnText = 'ISI FORMULIR SEKARANG';
                    $btnUrl = route('camaba.formulir');
                    $showBtn = true;
                }
                // ... (Status lain sama seperti sebelumnya) ...
                
                // 10. Lulus (DRAMATIS MODE ON)
                elseif ($pendaftar->status_pendaftaran == 'lulus') {
                    $state = 'lulus';
                    $isLulus = true; // Trigger Confetti
                    $cardColor = 'bg-green-500 text-white'; // Ubah warna teks jadi putih biar kontras
                    $icon = '🎓';
                    $title = 'SELAMAT! ANDA DITERIMA';
                    $desc = 'Selamat bergabung menjadi bagian dari Civitas Akademika UNMARIS. Perjalanan masa depanmu dimulai hari ini!';
                    $btnText = 'UNDUH SURAT KELULUSAN';
                    $btnUrl = route('camaba.pengumuman');
                    $showBtn = true;
                }
                // 11. Gagal
                elseif ($pendaftar->status_pendaftaran == 'gagal') {
                    $state = 'gagal';
                    $cardColor = 'bg-gray-300';
                    $icon = '📢';
                    $title = 'Pengumuman Seleksi';
                    $desc = 'Mohon maaf, Anda belum lolos seleksi tahap ini.';
                    $btnText = 'LIHAT DETAIL';
                    $btnUrl = route('camaba.pengumuman');
                    $showBtn = true;
                }
                // ... (Sisa logika status lainnya: draft, bayar, verifikasi, dll - pastikan ada di blok elseif di atas) ...
                 // 2. Draft (Belum Submit)
                elseif ($pendaftar->status_pendaftaran == 'draft') {
                    $state = 'draft';
                    $cardColor = 'bg-yellow-300';
                    $icon = '✍️';
                    $title = 'Lanjutkan Pengisian';
                    $desc = 'Data Anda belum lengkap/belum dikirim. Lanjutkan pengisian formulir.';
                    $btnText = 'LANJUTKAN FORMULIR';
                    $btnUrl = route('camaba.formulir');
                    $showBtn = true;
                }
                // 3. Sudah Submit, Belum Bayar
                elseif ($pendaftar->status_pendaftaran == 'submit' && $pendaftar->status_pembayaran == 'belum_bayar') {
                    $state = 'bayar';
                    $cardColor = 'bg-orange-400';
                    $icon = '💸';
                    $title = 'Langkah 2: Pembayaran';
                    $desc = 'Formulir diterima! Segera lakukan pembayaran agar berkas dapat diverifikasi.';
                    $btnText = 'LAKUKAN PEMBAYARAN';
                    $btnUrl = route('camaba.pembayaran');
                    $showBtn = true;
                }
                // 4. Sudah Upload, Tunggu Verifikasi Pembayaran
                elseif ($pendaftar->status_pembayaran == 'menunggu_verifikasi') {
                    $state = 'tunggu_bayar';
                    $cardColor = 'bg-blue-200';
                    $icon = '⏳';
                    $title = 'Verifikasi Pembayaran';
                    $desc = 'Bukti pembayaran sedang dicek oleh Bagian Keuangan. Estimasi 1x24 Jam.';
                    $showBtn = false;
                }
                // 5. Pembayaran Ditolak
                elseif ($pendaftar->status_pembayaran == 'ditolak') {
                    $state = 'bayar_tolak';
                    $cardColor = 'bg-red-400';
                    $icon = '❌';
                    $title = 'Pembayaran Ditolak';
                    $desc = 'Bukti pembayaran tidak valid. Silakan upload ulang bukti yang benar.';
                    $btnText = 'UPLOAD ULANG';
                    $btnUrl = route('camaba.pembayaran');
                    $showBtn = true;
                }
                // 6. Lunas, Tunggu Verifikasi Berkas (Akademik)
                elseif ($pendaftar->status_pembayaran == 'lunas' && $pendaftar->status_pendaftaran == 'submit') {
                    $state = 'tunggu_berkas';
                    $cardColor = 'bg-blue-300';
                    $icon = '📂';
                    $title = 'Verifikasi Berkas';
                    $desc = 'Pembayaran Lunas! Admin Akademik sedang memvalidasi Ijazah & Nilai Anda.';
                    $showBtn = false;
                }
                // 7. Berkas OK, Tunggu Jadwal
                elseif ($pendaftar->status_pendaftaran == 'verifikasi' && !$pendaftar->jadwal_ujian) {
                    $state = 'tunggu_jadwal';
                    $cardColor = 'bg-purple-200';
                    $icon = '📅';
                    $title = 'Menunggu Jadwal Ujian';
                    $desc = 'Berkas valid! Panitia sedang menyusun jadwal ujian untuk Anda. Cek berkala.';
                    $showBtn = false;
                }
                // 8. Sudah Jadwal, Belum Ujian (Nilai 0)
                elseif ($pendaftar->jadwal_ujian && $pendaftar->nilai_ujian == 0) {
                    $state = 'siap_ujian';
                    $cardColor = 'bg-green-300';
                    $icon = '🎫';
                    $title = 'Siap Ujian Seleksi';
                    $desc = 'Jadwal telah keluar! Cetak Kartu Ujian dan bawa saat pelaksanaan tes.';
                    $btnText = 'CETAK KARTU UJIAN';
                    $btnUrl = route('camaba.cetak-kartu');
                    $showBtn = true;
                }
                // 9. Sudah Ujian, Tunggu Hasil
                elseif ($pendaftar->nilai_ujian > 0 && $pendaftar->status_pendaftaran == 'verifikasi') {
                    $state = 'tunggu_hasil';
                    $cardColor = 'bg-indigo-200';
                    $icon = '🤞';
                    $title = 'Menunggu Hasil Seleksi';
                    $desc = 'Ujian selesai. Panitia sedang memproses hasil kelulusan Anda.';
                    $showBtn = false;
                }
            @endphp

            <!-- 🔥 SMART ACTION CARD (Pemandu Utama) -->
            <div class="{{ $cardColor }} border-4 border-black p-6 md:p-8 rounded-3xl shadow-neo-lg mb-10 relative overflow-hidden transition-all hover:-translate-y-1">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-20 rounded-full blur-2xl"></div>
                
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                    <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 text-center md:text-left">
                        <div class="text-6xl md:text-7xl animate-bounce-slight filter drop-shadow-md">
                            {{ $icon }}
                        </div>
                        <div>
                            <div class="inline-block bg-black text-white text-[10px] font-black px-2 py-1 rounded mb-2 uppercase tracking-widest">
                                Status Terkini
                            </div>
                            <h2 class="font-black text-2xl md:text-4xl {{ $isLulus ? 'text-white' : 'text-black' }} uppercase leading-tight mb-2">
                                {{ $title }}
                            </h2>
                            <p class="font-bold {{ $isLulus ? 'text-white/90' : 'text-black/80' }} text-sm md:text-base max-w-xl leading-relaxed">
                                {{ $desc }}
                            </p>

                            <!-- Jika sedang menunggu, beri saran aktivitas -->
                            @if(!$showBtn)
                                <div class="mt-4 flex flex-col md:flex-row items-center gap-2 justify-center md:justify-start text-xs md:text-sm">
                                    <a href="https://wa.me/6281234567890" target="_blank" class="font-black text-black underline hover:text-white transition-colors">
                                        Butuh Bantuan? Chat Admin
                                    </a>
                                    <span class="text-black/50 hidden md:inline">|</span>
                                    <span class="font-bold text-black">Pantau terus halaman ini.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($showBtn)
                        <a href="{{ $btnUrl }}" class="w-full md:w-auto bg-white text-black font-black py-4 px-8 rounded-xl border-4 border-black shadow-sm hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all uppercase tracking-wider text-sm md:text-lg whitespace-nowrap flex justify-center items-center gap-2 group">
                            <span>{{ $btnText }}</span>
                            <span class="group-hover:translate-x-1 transition-transform">👉</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- JADWAL SAYA (FITUR BARU) -->
            <!-- LOGIKA DIPERBARUI: Hanya muncul jika ada jadwal DAN BELUM LULUS/GAGAL -->
            @if($pendaftar && ($pendaftar->jadwal_ujian || $pendaftar->jadwal_wawancara) && !in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']))
            <div class="mb-10 animate-fade-in-up">
                <h3 class="font-black text-xl text-unmaris-blue mb-4 uppercase flex items-center border-l-8 border-unmaris-yellow pl-3">
                    📅 Agenda & Jadwal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($pendaftar->jadwal_ujian)
                    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 relative overflow-hidden group hover:bg-blue-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 p-3 rounded-lg border-2 border-unmaris-blue text-2xl flex-shrink-0">📝</div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Ujian Tulis</p>
                                <h4 class="text-xl font-black text-unmaris-blue">
                                    {{ $pendaftar->jadwal_ujian->format('l, d F Y') }}
                                </h4>
                                <p class="text-lg font-bold text-blue-600">
                                    {{ $pendaftar->jadwal_ujian->format('H:i') }} WITA
                                </p>
                                <div class="mt-2 text-xs font-bold bg-gray-100 px-2 py-1 rounded inline-block border border-gray-300">
                                    📍 {{ $pendaftar->lokasi_ujian }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendaftar->jadwal_wawancara)
                    <div class="bg-white border-4 border-orange-500 shadow-neo rounded-xl p-6 relative overflow-hidden group hover:bg-orange-50 transition">
                        <div class="flex items-start gap-4">
                            <div class="bg-orange-100 p-3 rounded-lg border-2 border-orange-500 text-2xl flex-shrink-0">🎤</div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">Wawancara</p>
                                <h4 class="text-xl font-black text-orange-900">
                                    {{ $pendaftar->jadwal_wawancara->format('l, d F Y') }}
                                </h4>
                                <p class="text-lg font-bold text-orange-600">
                                    {{ $pendaftar->jadwal_wawancara->format('H:i') }} WITA
                                </p>
                                <div class="mt-2 text-xs font-bold bg-gray-100 px-2 py-1 rounded inline-block border border-gray-300">
                                    👨‍🏫 {{ $pendaftar->pewawancara ?? 'Dosen Penguji' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- NAVIGATION GRID (Menu Pintas) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <!-- Menu 1 -->
                <a href="{{ route('camaba.formulir') }}" class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full">
                    <div class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">📝</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Formulir</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Biodata & Berkas</p>
                        </div>
                    </div>
                    @if($pendaftar)
                        <div class="absolute top-2 right-2 text-green-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    @endif
                </a>
                
                <!-- Menu 2 -->
                <a href="{{ route('camaba.pembayaran') }}" class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full">
                    <div class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">💸</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Pembayaran</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Upload Bukti</p>
                        </div>
                    </div>
                    @if($pendaftar && $pendaftar->status_pembayaran == 'lunas')
                        <div class="absolute top-2 right-2 text-green-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    @endif
                </a>

                <!-- Menu 3 -->
                <a href="{{ ($pendaftar && $pendaftar->jadwal_ujian) ? route('camaba.cetak-kartu') : '#' }}" 
                   class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full {{ (!$pendaftar || !$pendaftar->jadwal_ujian) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <div class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">🎫</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Kartu Ujian</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Cetak PDF</p>
                        </div>
                    </div>
                    @if(!$pendaftar || !$pendaftar->jadwal_ujian)
                        <div class="absolute top-2 right-2 text-gray-400 text-[10px] font-black uppercase border border-gray-400 px-1 rounded">LOCKED</div>
                    @endif
                </a>

                <!-- Menu 4 -->
                <a href="{{ ($pendaftar && in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal'])) ? route('camaba.pengumuman') : '#' }}" 
                   class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full {{ (!$pendaftar || !in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal'])) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <div class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">📢</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Pengumuman</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Hasil Seleksi</p>
                        </div>
                    </div>
                    @if(!$pendaftar || !in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']))
                        <div class="absolute top-2 right-2 text-gray-400 text-[10px] font-black uppercase border border-gray-400 px-1 rounded">LOCKED</div>
                    @endif
                </a>
            </div>

            <!-- PROFILE SUMMARY -->
            <div class="bg-white border-4 border-black rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-unmaris-yellow border-2 border-black overflow-hidden flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=facc15&color=1e3a8a" alt="User">
                    </div>
                    <div>
                        <h4 class="font-black text-unmaris-blue text-lg">{{ Auth::user()->name }}</h4>
                        <p class="text-xs font-bold text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <form method="POST" action="#">
                    @csrf
                    <button type="submit" class="text-red-500 font-black text-xs uppercase hover:underline border-2 border-red-500 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors w-full md:w-auto">
                        Keluar Akun
                    </button>
                </form>
            </div>

        </div>
    </div>
    
    <!-- KONFETI SCRIPT (Hanya Load Jika Lulus) -->
    @if($isLulus)
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var duration = 3000;
                var end = Date.now() + duration;

                (function frame() {
                    confetti({
                        particleCount: 5,
                        angle: 60,
                        spread: 55,
                        origin: { x: 0 },
                        colors: ['#FACC15', '#1E3A8A', '#16A34A'] // Warna Tema UNMARIS
                    });
                    confetti({
                        particleCount: 5,
                        angle: 120,
                        spread: 55,
                        origin: { x: 1 },
                        colors: ['#FACC15', '#1E3A8A', '#16A34A']
                    });

                    if (Date.now() < end) {
                        requestAnimationFrame(frame);
                    }
                }());
            });
        </script>
    @endif
