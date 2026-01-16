    <x-slot name="header">
        Beranda Mahasiswa
    </x-slot>

    <div class="py-6 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- 1. WIDGET PENGUMUMAN (BROADCAST SYSTEM) -->
            <!-- Muncul paling atas agar info penting (misal: perubahan jadwal) langsung terbaca -->
            @php
                $announcements = \App\Models\Announcement::where('is_active', true)->latest()->get();
            @endphp

            @if ($announcements->count() > 0)
                <div class="mb-8 space-y-4">
                    @foreach ($announcements as $ann)
                        @php
                            $colors = [
                                'info' => 'bg-blue-100 border-blue-500 text-blue-900',
                                'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-900',
                                'danger' => 'bg-red-100 border-red-500 text-red-900',
                            ];
                            $theme = $colors[$ann->type] ?? $colors['info'];
                            $icon = match ($ann->type) {
                                'info' => 'ℹ️',
                                'warning' => '⚠️',
                                'danger' => '🚨',
                                default => '📢',
                            };
                        @endphp

                        <div
                            class="{{ $theme }} border-l-8 p-4 rounded-r-xl shadow-sm flex items-start gap-4 animate-fade-in-down">
                            <div class="text-2xl">{{ $icon }}</div>
                            <div>
                                <h4 class="font-black text-lg uppercase">{{ $ann->title }}</h4>
                                <p class="text-sm font-medium opacity-90 leading-relaxed">{{ $ann->content }}</p>
                                <p class="text-[10px] font-bold mt-2 opacity-70 uppercase">
                                    {{ $ann->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- 2. LOGIKA INTELEGENSIA DASHBOARD (STATUS) -->
            @php
                $state = 'unknown';
                $cardColor = 'bg-gray-100';
                $icon = '🤔';
                $title = 'Memuat Data...';
                $desc = 'Sedang menyinkronkan data Anda.';
                $btnText = '';
                $btnUrl = '#';
                $showBtn = false;
                $isLulus = false;
                $showCredentialBox = false; // Flag baru untuk kotak kredensial
                $showChecklist = false; // Flag baru untuk checklist pasca-lulus

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
                } elseif ($pendaftar->status_pendaftaran == 'draft') {
                    $state = 'draft';
                    $cardColor = 'bg-yellow-300';
                    $icon = '✍️';
                    $title = 'Lanjutkan Pengisian';
                    $desc = 'Data Anda belum lengkap/belum dikirim. Lanjutkan pengisian formulir.';
                    $btnText = 'LANJUTKAN FORMULIR';
                    $btnUrl = route('camaba.formulir');
                    $showBtn = true;
                } elseif (
                    $pendaftar->status_pendaftaran == 'submit' &&
                    $pendaftar->status_pembayaran == 'belum_bayar'
                ) {
                    $state = 'bayar';
                    $cardColor = 'bg-orange-400';
                    $icon = '💸';
                    $title = 'Langkah 2: Pembayaran';
                    $desc = 'Formulir diterima! Segera lakukan pembayaran agar berkas dapat diverifikasi.';
                    $btnText = 'LAKUKAN PEMBAYARAN';
                    $btnUrl = route('camaba.pembayaran');
                    $showBtn = true;
                } elseif ($pendaftar->status_pembayaran == 'menunggu_verifikasi') {
                    $state = 'tunggu_bayar';
                    $cardColor = 'bg-blue-200';
                    $icon = '⏳';
                    $title = 'Verifikasi Pembayaran';
                    $desc = 'Bukti pembayaran sedang dicek oleh Bagian Keuangan. Estimasi 1x24 Jam.';
                    $showBtn = false;
                } elseif ($pendaftar->status_pembayaran == 'ditolak') {
                    $state = 'bayar_tolak';
                    $cardColor = 'bg-red-400';
                    $icon = '❌';
                    $title = 'Pembayaran Ditolak';
                    $desc = 'Bukti pembayaran tidak valid. Silakan upload ulang bukti yang benar.';
                    $btnText = 'UPLOAD ULANG';
                    $btnUrl = route('camaba.pembayaran');
                    $showBtn = true;
                } elseif ($pendaftar->status_pembayaran == 'lunas' && $pendaftar->status_pendaftaran == 'submit') {
                    $state = 'tunggu_berkas';
                    $cardColor = 'bg-blue-300';
                    $icon = '📂';
                    $title = 'Verifikasi Berkas';
                    $desc = 'Pembayaran Lunas! Admin Akademik sedang memvalidasi Ijazah & Nilai Anda.';
                    $showBtn = false;
                } elseif (
                    $pendaftar->status_pendaftaran == 'verifikasi' &&
                    !$pendaftar->jadwal_ujian &&
                    $pendaftar->status_pembayaran == 'lunas'
                ) {
                    $state = 'tunggu_jadwal';
                    $cardColor = 'bg-purple-200';
                    $icon = '📅';
                    $title = 'Menunggu Jadwal Ujian';
                    $desc = 'Berkas valid! Panitia sedang menyusun jadwal ujian untuk Anda. Cek berkala.';
                    $showBtn = false;
                } elseif ($pendaftar->jadwal_ujian && $pendaftar->nilai_ujian == 0) {
                    $state = 'siap_ujian';
                    $cardColor = 'bg-green-300';
                    $icon = '🎫';
                    $title = 'Siap Ujian Seleksi';
                    $desc = 'Jadwal telah keluar! Cetak Kartu Ujian dan bawa saat pelaksanaan tes.';
                    $btnText = 'CETAK KARTU UJIAN';
                    $btnUrl = route('camaba.cetak-kartu');
                    $showBtn = true;
                } elseif ($pendaftar->nilai_ujian > 0 && $pendaftar->status_pendaftaran == 'verifikasi') {
                    $state = 'tunggu_hasil';
                    $cardColor = 'bg-indigo-200';
                    $icon = '🤞';
                    $title = 'Menunggu Hasil Seleksi';
                    $desc = 'Ujian selesai. Panitia sedang memproses hasil kelulusan Anda.';
                    $showBtn = false;
                }

                // 10. Lulus (LOGIKA DIPERBARUI)
                elseif ($pendaftar->status_pendaftaran == 'lulus') {
                    $state = 'lulus';
                    $isLulus = true; // Trigger Confetti

                    if ($pendaftar->is_synced) {
                        // SUDAH SYNC KE SIAKAD
                        $cardColor = 'bg-indigo-900 text-white'; // Warna Lebih Elegan
                        $icon = '🚀';
                        $title = 'SELAMAT! ANDA RESMI MENJADI MAHASISWA';
                        $desc =
                            'Anda telah dinyatakan LULUS dan data Anda sudah aktif di Sistem Akademik (SIAKAD). Silakan gunakan kartu akses di bawah ini untuk login pertama kali.';
                        $btnText = 'MASUK KE SIAKAD';
                        $btnUrl = 'https://siakad.unmarissumba.ac.id'; // Link SIAKAD
                        $showCredentialBox = true; // Tampilkan Kotak Password
                    } else {
                        // BELUM SYNC
                        $cardColor = 'bg-green-500 text-white';
                        $icon = '🎓';
                        $title = 'SELAMAT! ANDA LULUS SELEKSI';
                        $desc =
                            'Selamat bergabung di UNMARIS! Saat ini Admin sedang memproses Akun SIAKAD Anda. Mohon tunggu dalam 1x24 jam.';
                        $btnText = 'UNDUH SURAT KELULUSAN';
                        $btnUrl = route('camaba.pengumuman');
                        $showChecklist = true; // Tampilkan Checklist Pasca-Lulus
                    }
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
                // 12 jika berkas sudah diverivikasi tapi belum bayar
                elseif (
                    $pendaftar->status_pendaftaran == 'verifikasi' &&
                    $pendaftar->status_pembayaran == 'belum_bayar'
                ) {
                    $state = 'verif_belum_bayar';
                    $cardColor = 'bg-yellow-400';
                    $icon = '💰';
                    $title = 'Segera Lakukan Pembayaran';
                    $desc = 'Berkas Anda sudah diverifikasi. Silakan lanjutkan ke tahap pembayaran.';
                    $btnText = 'LANJUTKAN PEMBAYARAN';
                    $btnUrl = route('camaba.pembayaran');
                    $showBtn = true;
                }

            @endphp

            <!-- 🔥 SMART ACTION CARD -->
            <div
                class="{{ $cardColor }} border-4 border-black p-6 md:p-8 rounded-3xl shadow-neo-lg mb-8 relative overflow-hidden transition-all hover:-translate-y-1">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-20 rounded-full blur-2xl">
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                    <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 text-center md:text-left">
                        <div class="text-6xl md:text-7xl animate-bounce-slight filter drop-shadow-md">
                            {{ $icon }}
                        </div>
                        <div>
                            <div
                                class="inline-block bg-black text-white text-[10px] font-black px-2 py-1 rounded mb-2 uppercase tracking-widest">
                                Status Terkini
                            </div>
                            <h2
                                class="font-black text-2xl md:text-3xl {{ $isLulus ? 'text-white' : 'text-black' }} uppercase leading-tight mb-2">
                                {{ $title }}
                            </h2>
                            <p
                                class="font-bold {{ $isLulus ? 'text-white/90' : 'text-black/80' }} text-sm md:text-base max-w-xl leading-relaxed">
                                {{ $desc }}
                            </p>
                        </div>
                    </div>

                    @if ($showBtn)
                        <div class="flex flex-col gap-2">
                            <a href="{{ $btnUrl }}"
                                class="w-full md:w-auto bg-white text-black font-black py-4 px-8 rounded-xl border-4 border-black shadow-sm hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all uppercase tracking-wider text-sm md:text-lg whitespace-nowrap flex justify-center items-center gap-2 group">
                                <span>{{ $btnText }}</span>
                                <span class="group-hover:translate-x-1 transition-transform">👉</span>
                            </a>

                            @if ($isLulus && !$pendaftar->is_synced)
                                <a href="https://chat.whatsapp.com/invitelink" target="_blank"
                                    class="text-center text-xs font-bold text-white underline hover:no-underline">
                                    Gabung Grup WA Mahasiswa Baru 2025
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- ✅ CHECKLIST PASCA-LULUS (AGAR TIDAK BINGUNG) -->
            @if ($showChecklist)
                <div class="mb-10 bg-white border-4 border-black rounded-3xl p-6 shadow-neo animate-fade-in-up"
                    x-data="{ open: true }">
                    <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                        <h3 class="font-black text-lg text-unmaris-blue uppercase flex items-center gap-2">
                            📋 Apa yang harus dilakukan selanjutnya?
                        </h3>
                        <span x-text="open ? '➖' : '➕'" class="font-bold text-xl"></span>
                    </div>

                    <div x-show="open" class="mt-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="bg-green-100 p-1 rounded text-green-600">✅</div>
                            <p class="text-sm font-bold text-gray-600">Unduh & Cetak Surat Kelulusan (LoA) dari tombol
                                di atas.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="bg-yellow-100 p-1 rounded text-yellow-600">⚠️</div>
                            <p class="text-sm font-bold text-gray-600">Siapkan Pas Foto 4x6 Berwarna (4 lembar) &
                                Fotokopi
                                Ijazah/SKL Legalisir.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-100 p-1 rounded text-blue-600">📍</div>
                            <p class="text-sm font-bold text-gray-600">Datang ke Bagian Akademik (BAAK) Kampus UNMARIS
                                untuk verifikasi akhir & pengambilan Jas Almamater.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="bg-purple-100 p-1 rounded text-purple-600">🔐</div>
                            <p class="text-sm font-bold text-gray-600">Tunggu notifikasi User & Password SIAKAD di
                                halaman ini (maksimal 1x24 jam).</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 🔑 CREDENTIAL BOX (HANYA MUNCUL JIKA SUDAH SYNC) -->
            @if ($showCredentialBox)
                <div
                    class="mb-10 bg-yellow-100 border-4 border-black rounded-3xl p-6 md:p-8 animate-fade-in-up relative overflow-hidden">
                    <div
                        class="absolute top-0 left-0 bg-black text-yellow-400 font-black px-4 py-1 text-xs uppercase rounded-br-xl">
                        KARTU AKSES MAHASISWA
                    </div>

                    <h3 class="font-black text-xl md:text-2xl text-black uppercase mb-4 mt-2 text-center md:text-left">
                        🔐 Informasi Login SIAKAD
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- URL -->
                        <div class="bg-white border-2 border-black p-4 rounded-xl shadow-sm text-center">
                            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Alamat Website</p>
                            <a href="https://siakad.unmarissumba.ac.id" target="_blank"
                                class="text-blue-600 font-black text-lg hover:underline truncate block">
                                siakad.unmarissumba.ac.id
                            </a>
                        </div>

                        <!-- Username -->
                        <div class="bg-white border-2 border-black p-4 rounded-xl shadow-sm text-center relative group">
                            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Username / Email</p>
                            <p class="text-black font-black text-xl">{{ Auth::user()->email }}</p>
                            <span
                                class="absolute top-2 right-2 text-xs bg-gray-200 px-1 rounded hidden group-hover:block">Sesuai
                                PMB</span>
                        </div>

                        <!-- Password -->
                        <div class="bg-white border-2 border-black p-4 rounded-xl shadow-sm text-center relative">
                            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Password Awal</p>
                            <div class="flex items-center justify-center gap-2">
                                <p class="text-black font-black text-xl tracking-widest bg-gray-100 px-2 rounded">
                                    {{ $pendaftar->nik }}</p>
                            </div>
                            <!-- Penjelasan kenapa pakai NIK -->
                            <div class="mt-2 bg-blue-50 border border-blue-200 rounded p-2 text-center">
                                <p class="text-[10px] text-blue-600 font-bold leading-tight">
                                    ⚠️ Untuk keamanan, password direset ke NIK Anda saat pertama kali masuk SIAKAD.
                                    Bukan password PMB.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-xs font-bold text-gray-600">
                            💡 Harap segera ganti password Anda setelah berhasil login pertama kali di SIAKAD.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 3. JADWAL SAYA (FITUR BARU) -->
            <!-- LOGIKA DIPERBARUI: Hanya muncul jika ada jadwal DAN BELUM LULUS/GAGAL -->
            @if (
                $pendaftar &&
                    ($pendaftar->jadwal_ujian || $pendaftar->jadwal_wawancara) &&
                    !in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']))
                <div class="mb-10 animate-fade-in-up">
                    <h3
                        class="font-black text-xl text-unmaris-blue mb-4 uppercase flex items-center border-l-8 border-unmaris-yellow pl-3">
                        📅 Agenda & Jadwal
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($pendaftar->jadwal_ujian)
                            <div
                                class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 relative overflow-hidden group hover:bg-blue-50 transition">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="bg-blue-100 p-3 rounded-lg border-2 border-unmaris-blue text-2xl flex-shrink-0">
                                        📝</div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase">Ujian Tulis</p>
                                        <h4 class="text-xl font-black text-unmaris-blue">
                                            {{ $pendaftar->jadwal_ujian->format('l, d F Y') }}
                                        </h4>
                                        <p class="text-lg font-bold text-blue-600">
                                            {{ $pendaftar->jadwal_ujian->format('H:i') }} WITA
                                        </p>
                                        <div
                                            class="mt-2 text-xs font-bold bg-gray-100 px-2 py-1 rounded inline-block border border-gray-300">
                                            📍 {{ $pendaftar->lokasi_ujian }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($pendaftar->jadwal_wawancara)
                            <div
                                class="bg-white border-4 border-orange-500 shadow-neo rounded-xl p-6 relative overflow-hidden group hover:bg-orange-50 transition">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="bg-orange-100 p-3 rounded-lg border-2 border-orange-500 text-2xl flex-shrink-0">
                                        🎤</div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase">Wawancara</p>
                                        <h4 class="text-xl font-black text-orange-900">
                                            {{ $pendaftar->jadwal_wawancara->format('l, d F Y') }}
                                        </h4>
                                        <p class="text-lg font-bold text-orange-600">
                                            {{ $pendaftar->jadwal_wawancara->format('H:i') }} WITA
                                        </p>
                                        <div
                                            class="mt-2 text-xs font-bold bg-gray-100 px-2 py-1 rounded inline-block border border-gray-300">
                                            👨‍🏫 {{ $pendaftar->pewawancara ?? 'Dosen Penguji' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 4. NAVIGATION GRID (Menu Pintas) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <!-- Menu 1 -->
                <a href="{{ route('camaba.formulir') }}"
                    class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full">
                    <div
                        class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">
                            📝</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Formulir</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Biodata & Berkas</p>
                        </div>
                    </div>
                    @if ($pendaftar)
                        <div class="absolute top-2 right-2 text-green-500"><svg class="w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg></div>
                    @endif
                </a>

                <!-- Menu 2 -->
                <a href="{{ route('camaba.pembayaran') }}"
                    class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full">
                    <div
                        class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div
                            class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">
                            💸</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Pembayaran</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Upload Bukti</p>
                        </div>
                    </div>
                    @if ($pendaftar && $pendaftar->status_pembayaran == 'lunas')
                        <div class="absolute top-2 right-2 text-green-500"><svg class="w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg></div>
                    @endif
                </a>

                <!-- Menu 3 -->
                <a href="{{ $pendaftar && $pendaftar->jadwal_ujian ? route('camaba.cetak-kartu') : '#' }}"
                    class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full {{ !$pendaftar || !$pendaftar->jadwal_ujian ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <div
                        class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">
                            🎫</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Kartu Ujian</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Cetak PDF</p>
                        </div>
                    </div>
                    @if (!$pendaftar || !$pendaftar->jadwal_ujian)
                        <div
                            class="absolute top-2 right-2 text-gray-400 text-[10px] font-black uppercase border border-gray-400 px-1 rounded">
                            LOCKED</div>
                    @endif
                </a>

                <!-- Menu 4 -->
                <a href="{{ $pendaftar && in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']) ? route('camaba.pengumuman') : '#' }}"
                    class="group bg-white p-4 rounded-xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:-translate-y-1 transition-all h-full {{ !$pendaftar || !in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']) ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <div
                        class="flex flex-col md:flex-row items-center gap-3 text-center md:text-left h-full justify-center md:justify-start">
                        <div
                            class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-xl border-2 border-black flex-shrink-0 group-hover:scale-110 transition-transform">
                            📢</div>
                        <div>
                            <h3 class="font-black text-sm text-unmaris-blue uppercase leading-tight">Pengumuman</h3>
                            <p class="text-[10px] text-gray-500 font-bold hidden md:block">Hasil Seleksi</p>
                        </div>
                    </div>
                    @if (!$pendaftar || !in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']))
                        <div
                            class="absolute top-2 right-2 text-gray-400 text-[10px] font-black uppercase border border-gray-400 px-1 rounded">
                            LOCKED</div>
                    @endif
                </a>
            </div>

            <!-- 5. PROFILE SUMMARY -->
            <div
                class="bg-white border-4 border-black rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-unmaris-yellow border-2 border-black overflow-hidden flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=facc15&color=1e3a8a"
                            alt="User">
                    </div>
                    <div>
                        <h4 class="font-black text-unmaris-blue text-lg">{{ Auth::user()->name }}</h4>
                        <p class="text-xs font-bold text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-red-500 font-black text-xs uppercase hover:underline border-2 border-red-500 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors w-full md:w-auto">
                        Keluar Akun
                    </button>
                </form>
            </div>

        </div>

        <!-- KONFETI SCRIPT (Hanya Load Jika Lulus) -->
        @if ($isLulus)
            <!-- Canvas Eksplisit untuk Confetti agar tidak error di iframe -->
            <canvas id="confetti-canvas"
                style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999;"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var canvas = document.getElementById('confetti-canvas');

                    // Gunakan canvas khusus
                    var myConfetti = confetti.create(canvas, {
                        resize: true,
                        useWorker: true
                    });

                    var duration = 3000;
                    var end = Date.now() + duration;

                    (function frame() {
                        // Tembakan kiri
                        myConfetti({
                            particleCount: 5,
                            angle: 60,
                            spread: 55,
                            origin: {
                                x: 0
                            },
                            colors: ['#FACC15', '#1E3A8A', '#16A34A']
                        });
                        // Tembakan kanan
                        myConfetti({
                            particleCount: 5,
                            angle: 120,
                            spread: 55,
                            origin: {
                                x: 1
                            },
                            colors: ['#FACC15', '#1E3A8A', '#16A34A']
                        });

                        if (Date.now() < end) {
                            requestAnimationFrame(frame);
                        }
                    }());
                });
            </script>
        @endif
    </div>
