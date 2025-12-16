<x-camaba-layout>
    <x-slot name="header">
        Dashboard Utama
    </x-slot>
    <div class="py-4 font-sans">
        <div class="max-w-7xl mx-auto">

            <!-- 1. HERO SECTION (WELCOME) -->
            <div
                class="bg-unmaris-blue text-white rounded-3xl p-8 mb-8 border-4 border-black shadow-neo-lg relative overflow-hidden">
                <!-- Background Pattern -->
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
                            Status Saat Ini: <span
                                class="text-white font-bold bg-white/20 px-2 py-0.5 rounded">{{ $currentStage }}</span>
                        </p>
                    </div>

                    <!-- Progress Circle -->
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

            <!-- 2. MAIN MENU GRID (APPS) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                <!-- MENU 1: FORMULIR -->
                <a href="{{ route('camaba.formulir') }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 bg-yellow-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4 group-hover:rotate-12 transition-transform">
                            📝
                        </div>
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

                <!-- MENU 2: PEMBAYARAN -->
                <a href="{{ route('camaba.pembayaran') }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 bg-green-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4 group-hover:-rotate-12 transition-transform">
                            💸
                        </div>
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

                <!-- MENU 3: KARTU UJIAN -->
                @php
                    $isJadwalReady = $pendaftar && $pendaftar->jadwal_ujian && $pendaftar->status_pembayaran == 'lunas';
                @endphp
                <a href="{{ $isJadwalReady ? route('camaba.cetak-kartu') : '#' }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden {{ !$isJadwalReady ? 'opacity-60 cursor-not-allowed grayscale' : '' }}">
                    <div
                        class="absolute top-0 right-0 bg-blue-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4">
                            🎫
                        </div>
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

                <!-- MENU 4: HASIL SELEKSI -->
                @php
                    $isResultReady = $pendaftar && in_array($pendaftar->status_pendaftaran, ['lulus', 'gagal']);
                @endphp
                <a href="{{ $isResultReady ? route('camaba.pengumuman') : '#' }}"
                    class="group bg-white p-6 rounded-2xl border-4 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all relative overflow-hidden {{ !$isResultReady ? 'opacity-60 cursor-not-allowed grayscale' : '' }}">
                    <div
                        class="absolute top-0 right-0 bg-purple-400 w-16 h-16 rounded-bl-full -mr-8 -mt-8 z-0 group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-2xl border-2 border-black mb-4">
                            📢
                        </div>
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

            <!-- 3. INFO STATUS (Timeline Style) -->
            <div class="bg-white border-4 border-black rounded-3xl p-8 shadow-neo relative">
                <div
                    class="absolute -top-5 left-8 bg-unmaris-yellow px-4 py-2 border-2 border-black font-black uppercase text-sm transform -rotate-2 shadow-sm">
                    🚀 Timeline Pendaftaran
                </div>

                <div class="mt-4 space-y-6">

                    <!-- Step 1 -->
                    <div class="flex gap-4 items-start {{ $pendaftar ? 'opacity-100' : 'opacity-50' }}">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center font-bold {{ $pendaftar ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                1</div>
                            <div class="h-10 w-1 bg-black/10 my-1"></div>
                        </div>
                        <div>
                            <h4 class="font-black text-unmaris-blue text-lg">Pendaftaran Akun & Formulir</h4>
                            <p class="text-sm text-gray-600">Melengkapi biodata diri, data sekolah, dan memilih program
                                studi.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div
                        class="flex gap-4 items-start {{ $pendaftar && $pendaftar->status_pembayaran == 'lunas' ? 'opacity-100' : 'opacity-50' }}">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center font-bold {{ $pendaftar && $pendaftar->status_pembayaran == 'lunas' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                2</div>
                            <div class="h-10 w-1 bg-black/10 my-1"></div>
                        </div>
                        <div>
                            <h4 class="font-black text-unmaris-blue text-lg">Pembayaran Biaya Pendaftaran</h4>
                            <p class="text-sm text-gray-600">Melakukan transfer dan upload bukti pembayaran untuk
                                diverifikasi admin.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div
                        class="flex gap-4 items-start {{ $pendaftar && $pendaftar->nilai_ujian > 0 ? 'opacity-100' : 'opacity-50' }}">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center font-bold {{ $pendaftar && $pendaftar->nilai_ujian > 0 ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                3</div>
                            <div class="h-10 w-1 bg-black/10 my-1"></div>
                        </div>
                        <div>
                            <h4 class="font-black text-unmaris-blue text-lg">Ujian Seleksi</h4>
                            <p class="text-sm text-gray-600">Mengikuti ujian sesuai jadwal yang tertera pada Kartu
                                Ujian.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div
                        class="flex gap-4 items-start {{ $pendaftar && $pendaftar->status_pendaftaran == 'lulus' ? 'opacity-100' : 'opacity-50' }}">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center font-bold {{ $pendaftar && $pendaftar->status_pendaftaran == 'lulus' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                                4</div>
                        </div>
                        <div>
                            <h4 class="font-black text-unmaris-blue text-lg">Pengumuman Kelulusan</h4>
                            <p class="text-sm text-gray-600">Menerima Surat Keterangan Lulus (LoA) dan melakukan daftar
                                ulang.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-camaba-layout>
