    <x-slot name="header">
        Dashboard Utama
    </x-slot>

    <div class="py-4 font-sans">
        <div class="max-w-7xl mx-auto">

            <!-- 1. HERO SECTION -->
            <div
                class="bg-unmaris-blue text-white rounded-3xl p-8 mb-8 border-4 border-black shadow-neo-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 opacity-10 transform translate-x-10 -translate-y-10">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <div
                            class="inline-block bg-unmaris-yellow text-unmaris-blue font-black px-3 py-1 rounded border-2 border-black shadow-sm transform -rotate-2 mb-2 text-xs uppercase tracking-widest">
                            Student Portal v2.0
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tight">
                            Halo, {{ explode(' ', $user->name)[0] }}! 👋
                        </h1>
                        <p class="text-blue-200 font-medium mt-1">
                            Status: <span
                                class="text-white font-bold bg-white/20 px-2 py-0.5 rounded">{{ $currentStage }}</span>
                        </p>
                    </div>

                    <div
                        class="flex items-center gap-4 bg-black/20 p-4 rounded-xl border border-white/10 backdrop-blur-sm">
                        <div class="relative w-16 h-16">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path class="text-blue-900"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke="currentColor" stroke-width="4" />
                                <path class="text-unmaris-yellow" stroke-dasharray="{{ $progress }}, 100"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center font-black text-sm">
                                {{ $progress }}%</div>
                        </div>
                        <div class="text-xs font-bold text-blue-100">
                            Kelengkapan<br>Data Kamu
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. JADWAL SAYA (FITUR BARU) -->
            <!-- Hanya muncul jika jadwal sudah diatur admin -->
            @if ($pendaftar && ($pendaftar->jadwal_ujian || $pendaftar->jadwal_wawancara))
                <div class="mb-10">
                    <h3 class="font-black text-xl text-unmaris-blue mb-4 uppercase flex items-center">
                        📅 Agenda & Jadwal Saya
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Jadwal Ujian Tulis -->
                        @if ($pendaftar->jadwal_ujian)
                            <div
                                class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 relative overflow-hidden group hover:bg-blue-50 transition">
                                <div
                                    class="absolute top-0 right-0 bg-blue-100 px-3 py-1 rounded-bl-lg font-bold text-xs text-unmaris-blue border-b-2 border-l-2 border-unmaris-blue">
                                    UJIAN TULIS
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="bg-blue-100 p-3 rounded-lg border-2 border-unmaris-blue text-2xl">📝
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase">Waktu Pelaksanaan</p>
                                        <h4 class="text-xl font-black text-unmaris-blue">
                                            {{ $pendaftar->jadwal_ujian->format('l, d F Y') }}
                                        </h4>
                                        <p class="text-lg font-bold text-blue-600">
                                            Pukul {{ $pendaftar->jadwal_ujian->format('H:i') }} WITA
                                        </p>

                                        <div class="mt-3 pt-3 border-t-2 border-dashed border-gray-300">
                                            <p class="text-xs font-bold text-gray-500 uppercase">Lokasi</p>
                                            <p class="font-bold text-gray-800 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $pendaftar->lokasi_ujian }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Jadwal Wawancara -->
                        @if ($pendaftar->jadwal_wawancara)
                            <div
                                class="bg-white border-4 border-orange-500 shadow-neo rounded-xl p-6 relative overflow-hidden group hover:bg-orange-50 transition">
                                <div
                                    class="absolute top-0 right-0 bg-orange-100 px-3 py-1 rounded-bl-lg font-bold text-xs text-orange-800 border-b-2 border-l-2 border-orange-500">
                                    WAWANCARA
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="bg-orange-100 p-3 rounded-lg border-2 border-orange-500 text-2xl">🎤
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase">Waktu Pelaksanaan</p>
                                        <h4 class="text-xl font-black text-orange-900">
                                            {{ $pendaftar->jadwal_wawancara->format('l, d F Y') }}
                                        </h4>
                                        <p class="text-lg font-bold text-orange-600">
                                            Pukul {{ $pendaftar->jadwal_wawancara->format('H:i') }} WITA
                                        </p>

                                        <div class="mt-3 pt-3 border-t-2 border-dashed border-gray-300">
                                            <p class="text-xs font-bold text-gray-500 uppercase">Pewawancara</p>
                                            <p class="font-bold text-gray-800 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $pendaftar->pewawancara ?? 'Dosen Penguji' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            <!-- 3. MAIN MENU GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- FORMULIR -->
                <a href="{{ route('camaba.formulir') }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 bg-yellow-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4 group-hover:rotate-12 transition-transform">
                            📝</div>
                        <h3 class="font-black text-lg text-unmaris-blue uppercase leading-tight">Isi Formulir</h3>
                        <p class="text-xs text-gray-500 font-bold mt-1">Biodata & Berkas</p>
                        @if (!$pendaftar)
                            <span
                                class="mt-3 inline-block bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded border border-black">BELUM
                                DIISI</span>
                        @else
                            <span
                                class="mt-3 inline-block bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded border border-black">TERISI</span>
                        @endif
                    </div>
                </a>

                <!-- PEMBAYARAN -->
                <a href="{{ route('camaba.pembayaran') }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 bg-green-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4 group-hover:-rotate-12 transition-transform">
                            💸</div>
                        <h3 class="font-black text-lg text-unmaris-blue uppercase leading-tight">Pembayaran</h3>
                        <p class="text-xs text-gray-500 font-bold mt-1">Upload Bukti</p>
                        @if ($pendaftar && $pendaftar->status_pembayaran == 'lunas')
                            <span
                                class="mt-3 inline-block bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded border border-black">LUNAS</span>
                        @elseif($pendaftar && $pendaftar->status_pembayaran == 'menunggu_verifikasi')
                            <span
                                class="mt-3 inline-block bg-yellow-400 text-black text-[10px] font-bold px-2 py-1 rounded border border-black">DIPROSES</span>
                        @else
                            <span
                                class="mt-3 inline-block bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-1 rounded border border-black">BELUM
                                BAYAR</span>
                        @endif
                    </div>
                </a>

                <!-- KARTU UJIAN -->
                @php $isJadwalReady = $pendaftar && $pendaftar->jadwal_ujian && $pendaftar->status_pembayaran == 'lunas'; @endphp
                <a href="{{ $isJadwalReady ? route('camaba.cetak-kartu') : '#' }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden {{ !$isJadwalReady ? 'opacity-60 cursor-not-allowed grayscale' : '' }}">
                    <div
                        class="absolute top-0 right-0 bg-blue-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4">
                            🎫</div>
                        <h3 class="font-black text-lg text-unmaris-blue uppercase leading-tight">Kartu Ujian</h3>
                        <p class="text-xs text-gray-500 font-bold mt-1">Cetak Kartu</p>
                        @if ($isJadwalReady)
                            <span
                                class="mt-3 inline-block bg-blue-500 text-white text-[10px] font-bold px-2 py-1 rounded border border-black">SIAP
                                CETAK</span>
                        @else
                            <span
                                class="mt-3 inline-block bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-1 rounded border border-black">TERKUNCI</span>
                        @endif
                    </div>
                </a>

                <!-- PENGUMUMAN -->
                @php $isResultReady = $pendaftar && in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']); @endphp
                <a href="{{ $isResultReady ? route('camaba.pengumuman') : '#' }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden {{ !$isResultReady ? 'opacity-60 cursor-not-allowed grayscale' : '' }}">
                    <div
                        class="absolute top-0 right-0 bg-purple-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4">
                            📢</div>
                        <h3 class="font-black text-lg text-unmaris-blue uppercase leading-tight">Pengumuman</h3>
                        <p class="text-xs text-gray-500 font-bold mt-1">Hasil Seleksi</p>
                        @if ($isResultReady)
                            <span
                                class="mt-3 inline-block bg-purple-500 text-white text-[10px] font-bold px-2 py-1 rounded border border-black">LIHAT
                                HASIL</span>
                        @else
                            <span
                                class="mt-3 inline-block bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-1 rounded border border-black">BELUM
                                ADA</span>
                        @endif
                    </div>
                </a>
            </div>

        </div>
    </div>
