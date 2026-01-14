<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- SEO & META TAGS -->
        <title>PMB UNMARIS - Universitas Stella Maris Sumba</title>
        <meta name="description" content="Pendaftaran Mahasiswa Baru Universitas Stella Maris Sumba. Kampus berbasis teknologi pertama di Sumba.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-yellow-50 text-black selection:bg-unmaris-blue selection:text-white relative pb-20 md:pb-0"> 
        
        @php
            // Cek apakah ada gelombang yang sedang aktif
            // Asumsi $gelombangs dikirim dari Controller sebagai Collection
            $isRegistrationOpen = $gelombangs->contains('is_active', true);
        @endphp

        <!-- NAVBAR -->
        <nav class="fixed top-0 w-full z-50 bg-white border-b-4 border-black px-4 md:px-8 py-4 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15'" class="h-10 w-10  rounded-full bg-white">
                <span class="font-black text-xl tracking-tighter uppercase text-unmaris-blue hidden md:block">PMB UNMARIS</span>
            </div>
            
            <div class="flex gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-black text-sm uppercase px-4 py-2 border-2 border-black bg-unmaris-blue text-white hover:bg-blue-800 hover:shadow-neo transition-all rounded">
                            Dashboard 🚀
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-sm uppercase px-4 py-2 hover:underline decoration-4 underline-offset-4 decoration-unmaris-yellow text-unmaris-blue">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            @if($isRegistrationOpen)
                                <a href="{{ route('register') }}" class="hidden md:inline-block font-black text-sm uppercase px-4 py-2 border-2 border-black bg-unmaris-yellow text-unmaris-blue shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all rounded">
                                    Daftar Sekarang
                                </a>
                            @else
                                <button disabled class="hidden md:inline-block font-black text-sm uppercase px-4 py-2 border-2 border-gray-400 bg-gray-200 text-gray-500 cursor-not-allowed rounded">
                                    Belum Dibuka
                                </button>
                            @endif
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- HERO SECTION (UPDATED SESUAI BROSUR) -->
        <header class="pt-32 pb-20 px-6 max-w-7xl mx-auto text-center relative overflow-hidden">
            <div class="absolute top-24 left-4 md:left-20 text-4xl md:text-6xl animate-bounce-slight opacity-60">✨</div>
            <div class="absolute bottom-10 right-4 md:right-20 text-4xl md:text-6xl animate-bounce-slight opacity-60 animation-delay-200">🎓</div>

            <!-- Tag T.A. 2026/2027 -->
            <div class="inline-block bg-unmaris-blue text-white px-4 py-1 font-bold text-xs md:text-sm uppercase tracking-widest border-2 border-black mb-6 transform -rotate-2 shadow-[2px_2px_0px_0px_#000]">
                Penerimaan Mahasiswa Baru T.A. 2026/2027
            </div>
            
            <!-- Headline Utama: 500rb -->
            <h1 class="text-5xl md:text-7xl font-black text-unmaris-blue mb-6 uppercase tracking-tight leading-none drop-shadow-sm" style="text-shadow: 4px 4px 0px #FACC15;">
                Cukup <span class="text-unmaris-yellow bg-black px-2 transform rotate-1 inline-block border-2 border-white">Rp 500rb</span><br>
                Sudah Bisa Kuliah!
            </h1>
            
            <!-- Subheadline: Gratis Dana Pembangunan -->
            <div class="flex flex-col md:flex-row justify-center items-center gap-2 mb-8">
                <span class="bg-green-500 text-white font-black px-3 py-1 text-sm uppercase border-2 border-black transform -rotate-1 shadow-sm">
                    GRATIS DANA PEMBANGUNAN
                </span>
                <p class="text-lg md:text-xl font-bold text-gray-600">
                    di <strong>Universitas Stella Maris Sumba</strong>.
                </p>
            </div>

            <p class="text-base font-medium text-gray-500 max-w-xl mx-auto mb-10">
                Kampus berbasis teknologi pertama di Sumba dengan visi global dan kearifan lokal. Wujudkan masa depan cerahmu sekarang juga.
            </p>

            <div class="flex flex-col md:flex-row gap-4 justify-center">
                @if($isRegistrationOpen)
                    <a href="{{ route('register') }}" class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue text-lg md:text-xl font-black py-3 md:py-4 px-8 md:px-10 border-4 border-black shadow-neo-lg hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-xl uppercase flex items-center justify-center gap-2">
                        🚀 Daftar Sekarang
                    </a>
                @else
                    <button disabled class="bg-gray-200 text-gray-500 text-lg md:text-xl font-black py-3 md:py-4 px-8 md:px-10 border-4 border-gray-400 rounded-xl uppercase flex items-center justify-center gap-2 cursor-not-allowed">
                        ⏳ Segera Dibuka
                    </button>
                @endif

                <a href="#prodi" class="bg-white hover:bg-gray-50 text-black text-lg md:text-xl font-black py-3 md:py-4 px-8 md:px-10 border-4 border-black shadow-neo-lg hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-xl uppercase flex items-center justify-center gap-2">
                    🔍 Lihat Jurusan
                </a>
            </div>
        </header>

        <!-- STATS SECTION -->
        <section class="bg-unmaris-blue border-y-4 border-black py-12 text-white">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 border-2 border-white/20 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <div class="text-5xl font-black text-unmaris-yellow mb-2">7</div>
                    <div class="font-bold uppercase tracking-widest text-sm">Program Studi Unggulan</div>
                </div>
                <div class="p-6 border-2 border-white/20 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <div class="text-5xl font-black text-unmaris-yellow mb-2">B</div>
                    <div class="font-bold uppercase tracking-widest text-sm">Akreditasi Institusi</div>
                </div>
                <div class="p-6 border-2 border-white/20 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <div class="text-5xl font-black text-unmaris-yellow mb-2">Digital</div>
                    <div class="font-bold uppercase tracking-widest text-sm">Kampus Berbasis Tech</div>
                </div>
            </div>
        </section>

        <!-- FASILITAS KAMPUS (BARU DENGAN FOTO) -->
        <section class="py-20 px-6 max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-unmaris-blue mb-2 uppercase">Fasilitas Unggulan</h2>
                <p class="font-bold text-gray-500">Kami sediakan fasilitas terbaik untuk menunjang prestasimu.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Gedung -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="h-48 bg-gray-200 mb-4 rounded-lg border-2 border-black overflow-hidden relative">
                        <!-- Placeholder Foto: Ganti src dengan foto gedung asli -->
                        <img src="https://placehold.co/600x400/EEE/31343C?text=Gedung+St.+Alexander" alt="Gedung St. Alexander" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-black text-xl uppercase mb-2">Gedung St. Alexander</h3>
                    <p class="text-sm font-bold text-gray-500">Gedung kuliah baru 4 lantai yang modern dan nyaman.</p>
                </div>
                
                <!-- Lab -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="h-48 bg-gray-200 mb-4 rounded-lg border-2 border-black overflow-hidden relative">
                         <!-- Placeholder Foto: Ganti src dengan foto lab asli -->
                        <img src="https://placehold.co/600x400/EEE/31343C?text=Laboratorium+Komputer" alt="Laboratorium" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-black text-xl uppercase mb-2">Laboratorium Lengkap</h3>
                    <p class="text-sm font-bold text-gray-500">Lab Multimedia, Hardware, Jaringan, K3, & Adm. Rumah Sakit.</p>
                </div>

                <!-- Olahraga -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="h-48 bg-gray-200 mb-4 rounded-lg border-2 border-black overflow-hidden relative">
                         <!-- Placeholder Foto: Ganti src dengan foto lapangan asli -->
                        <img src="https://placehold.co/600x400/EEE/31343C?text=Lapangan+Olahraga" alt="Olahraga" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="font-black text-xl uppercase mb-2">Sarana Olahraga</h3>
                    <p class="text-sm font-bold text-gray-500">Lapangan Futsal, Voli, dan Bulutangkis tersedia di kampus.</p>
                </div>
            </div>
        </section>

        <!-- JADWAL & BIAYA -->
        <section class="py-20 px-6 max-w-7xl mx-auto bg-gray-50 border-y-4 border-black">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                <!-- BIAYA PENDAFTARAN -->
                <div class="bg-white border-4 border-black shadow-neo-lg rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-4 py-2 font-black text-sm border-b-4 border-l-4 border-black rounded-bl-xl">
                        TERMURAH!
                    </div>
                    <h2 class="text-3xl font-black text-unmaris-blue mb-4 uppercase">💰 Biaya Pendaftaran</h2>
                    <p class="text-gray-600 font-bold mb-6">Investasi awal untuk masa depan gemilang. Biaya pendaftaran sangat terjangkau.</p>
                    
                    <div class="bg-green-100 border-4 border-black rounded-xl p-6 text-center mb-6">
                        <span class="block text-sm font-bold text-green-800 uppercase tracking-widest mb-1">Hanya</span>
                        <span class="block text-5xl font-black text-green-600"> Rp {{ number_format($settings->biaya_pendaftaran ?? 200000, 0, ',', '.') }}</span>
                        <span class="block text-xs font-bold text-green-800 mt-2">*Sudah termasuk biaya ujian seleksi</span>
                    </div>

                    <ul class="space-y-3 font-bold text-gray-700">
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Formulir Digital
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Kartu Ujian Tulis & Wawancara
                        </li>
                    </ul>
                </div>

                <!-- JADWAL GELOMBANG (DINAMIS DARI DB) -->
                <div>
                    <h2 class="text-3xl font-black text-unmaris-blue mb-6 uppercase">📅 Jadwal Gelombang</h2>
                    
                    <div class="space-y-4">
                        @forelse($gelombangs as $g)
                            @php
                                $isActive = $g->is_active; 
                                $isPast = \Carbon\Carbon::now()->gt($g->tgl_selesai);
                                // Style Logic
                                $borderColor = $isActive ? 'border-unmaris-blue' : 'border-gray-400';
                                $bgColor = $isActive ? 'bg-white' : 'bg-gray-100 opacity-75';
                                $scale = $isActive ? 'transform scale-105 border-l-8 border-l-unmaris-yellow' : '';
                            @endphp

                            <div class="{{ $bgColor }} border-4 {{ $borderColor }} shadow-neo rounded-xl p-6 flex justify-between items-center {{ $scale }}">
                                <div>
                                    <h4 class="font-black text-xl {{ $isActive ? 'text-unmaris-blue' : 'text-gray-600' }}">{{ $g->nama_gelombang }}</h4>
                                    <p class="text-sm font-bold text-gray-500">
                                        {{ \Carbon\Carbon::parse($g->tgl_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($g->tgl_selesai)->format('d M Y') }}
                                    </p>
                                </div>
                                
                                @if($isActive)
                                    <span class="bg-green-500 text-white px-3 py-1 rounded border-2 border-black font-black text-xs uppercase shadow-sm animate-pulse">
                                        BUKA SEKARANG
                                    </span>
                                @elseif($isPast)
                                    <span class="bg-red-500 text-white px-3 py-1 rounded border-2 border-black font-black text-xs uppercase">
                                        TUTUP
                                    </span>
                                @else
                                    <span class="bg-gray-300 text-gray-600 px-3 py-1 rounded border-2 border-gray-500 font-black text-xs uppercase">
                                        SEGERA
                                    </span>
                                @endif
                            </div>
                        @empty
                            <div class="p-6 bg-yellow-100 border-2 border-yellow-400 rounded-lg text-yellow-800 text-center font-bold">
                                Belum ada jadwal gelombang yang dirilis. Pantau terus ya!
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6 p-4 bg-yellow-100 border-2 border-yellow-400 rounded-lg text-yellow-800 text-sm font-bold">
                        💡 Tips: Daftar di Gelombang awal untuk peluang diterima lebih besar!
                    </div>
                </div>

            </div>
        </section>

        <!-- PRODI LIST (UPDATED DATA) -->
        <section id="prodi" class="bg-black py-20 text-white border-y-4 border-unmaris-yellow">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-black text-unmaris-yellow mb-2 uppercase">Program Studi Unggulan</h2>
                    <p class="font-bold text-gray-400">Pilih jurusan yang sesuai dengan minat dan bakatmu.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- TEKNIK -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-blue-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">💻</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Teknik Informatika (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Software Engineering, AI, IoT.</p>
                    </div>
                    
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-blue-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">📊</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Manajemen Informatika (D3)</h3>
                        <p class="text-sm font-bold text-gray-500">Sistem Informasi Praktis & Database.</p>
                    </div>

                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-green-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">🌱</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Teknik Lingkungan (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Rekayasa Lingkungan Berkelanjutan.</p>
                    </div>

                    <!-- EKONOMI -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-yellow-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">📈</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Bisnis Digital (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">E-Commerce & Digital Marketing.</p>
                    </div>

                    <!-- KESEHATAN -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-red-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">🏥</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Administrasi RS (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Manajemen Rumah Sakit Profesional.</p>
                    </div>

                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-red-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">⛑️</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">K3 (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Keselamatan & Kesehatan Kerja.</p>
                    </div>

                    <!-- PENDIDIKAN -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-purple-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">🎓</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Pendidikan TI (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Guru TIK Kompeten & Berkarakter.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAHASISWA LIFE (BEASISWA & EKSKUL) - BARU -->
        <section class="py-20 px-6 max-w-7xl mx-auto">
             <h2 class="text-3xl md:text-4xl font-black text-center text-unmaris-blue mb-16 uppercase">
                <span class="bg-unmaris-yellow px-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">Kehidupan</span> Kampus
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Beasiswa -->
                <div class="bg-blue-50 border-4 border-unmaris-blue rounded-xl p-8 shadow-neo hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                    <h3 class="text-2xl font-black text-unmaris-blue mb-4 flex items-center gap-3">
                        <span>🎓</span> Beasiswa Tersedia
                    </h3>
                    <ul class="space-y-4 font-bold text-gray-700">
                        <li class="flex items-start gap-3">
                            <div class="bg-blue-500 text-white rounded-full p-1 mt-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                            <div>
                                <span class="block text-lg">KIP Kuliah</span>
                                <span class="text-sm font-medium text-gray-500">Bantuan biaya pendidikan dari pemerintah.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="bg-blue-500 text-white rounded-full p-1 mt-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></div>
                            <div>
                                <span class="block text-lg">Beasiswa PEMDA</span>
                                <span class="text-sm font-medium text-gray-500">Khusus Sumba Tengah, Sumba Barat, & Sumba Barat Daya.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Ekskul -->
                <div class="bg-yellow-50 border-4 border-unmaris-yellow rounded-xl p-8 shadow-neo hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                    <h3 class="text-2xl font-black text-yellow-800 mb-4 flex items-center gap-3">
                        <span>🎸</span> Ekstrakurikuler
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">⚽ Futsal</span>
                        <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🏸 Bulutangkis</span>
                        <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🏐 Voli</span>
                        <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🎤 Paduan Suara</span>
                        <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🎨 Kesenian</span>
                    </div>
                    <p class="mt-6 font-bold text-gray-600 text-sm">
                        Kembangkan bakat dan minatmu di luar akademik bersama teman-teman sehobi!
                    </p>
                </div>
            </div>
        </section>

        <!-- SYARAT & BERKAS (SESUAI BROSUR) -->
        <section id="syarat" class="py-20 px-6 max-w-7xl mx-auto border-t-4 border-black bg-white">
            <h2 class="text-3xl md:text-4xl font-black text-center text-unmaris-blue mb-16 uppercase">
                <span class="bg-unmaris-yellow px-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">Syarat</span> Pendaftaran
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-blue-50 border-4 border-unmaris-blue rounded-xl p-6 text-center group hover:-translate-y-2 transition-transform">
                    <div class="text-4xl mb-4">📄</div>
                    <h3 class="font-black text-lg text-unmaris-blue uppercase mb-2">Ijazah / SKL</h3>
                    <p class="text-sm font-bold text-gray-500">Scan Ijazah Terakhir atau Surat Tanda Lulus.</p>
                </div>
                
                <!-- Card 2 (UPDATED: KK / KTP) -->
                <div class="bg-yellow-50 border-4 border-unmaris-yellow rounded-xl p-6 text-center group hover:-translate-y-2 transition-transform">
                    <div class="text-4xl mb-4">🪪</div>
                    <h3 class="font-black text-lg text-yellow-800 uppercase mb-2">KK / KTP</h3>
                    <p class="text-sm font-bold text-gray-500">Scan Kartu Keluarga atau KTP (Pilih satu).</p>
                </div>
                
                <!-- Card 3 (UPDATED: Penjelasan Digital vs Fisik) -->
                <div class="bg-red-50 border-4 border-red-500 rounded-xl p-6 text-center group hover:-translate-y-2 transition-transform">
                    <div class="text-4xl mb-4">📸</div>
                    <h3 class="font-black text-lg text-red-800 uppercase mb-2">Pas Foto</h3>
                    <p class="text-sm font-bold text-gray-500">
                        Upload 1 file digital. Fisik 4 lembar (4x6) diserahkan saat daftar ulang.
                    </p>
                </div>
                
                <!-- Card 4 -->
                <div class="bg-green-50 border-4 border-green-500 rounded-xl p-6 text-center group hover:-translate-y-2 transition-transform">
                    <div class="text-4xl mb-4">💸</div>
                    <h3 class="font-black text-lg text-green-800 uppercase mb-2">Biaya Daftar</h3>
                    <p class="text-sm font-bold text-gray-500">Transfer biaya Rp {{ number_format($settings->biaya_pendaftaran ?? 200000, 0, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <!-- ALUR PENDAFTARAN -->
        <section id="alur" class="py-20 px-6 max-w-7xl mx-auto bg-gray-50 border-t-4 border-black">
            <h2 class="text-3xl md:text-4xl font-black text-center text-unmaris-blue mb-16 uppercase">
                <span class="bg-white px-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">Alur</span> Pendaftaran
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 gap-y-12">
                <!-- Step 1 -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-unmaris-blue text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">1</div>
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Buat Akun</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Klik "Daftar Sekarang", isi Nama, Email & Password.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-unmaris-yellow text-black flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">2</div>
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Isi Formulir</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Isi biodata & upload berkas.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-green-500 text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">3</div>
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Pembayaran</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Transfer biaya & tunggu verifikasi.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-red-500 text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">4</div>
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Seleksi</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Ikuti ujian & cek kelulusan.</p>
                </div>
            </div>
        </section>

        <!-- FAQ & FOOTER (SAMA SEPERTI SEBELUMNYA) -->
        <section class="py-20 px-6 max-w-4xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-black text-center text-unmaris-blue mb-12 uppercase">
                🤔 Pertanyaan Umum (FAQ)
            </h2>
            <div class="space-y-4">
                <div x-data="{ open: false }" class="bg-white border-4 border-black rounded-xl overflow-hidden shadow-neo">
                    <button @click="open = !open" class="w-full text-left p-4 font-black text-lg text-unmaris-blue flex justify-between items-center hover:bg-yellow-50">
                        <span>Apakah bisa mendaftar tanpa Ijazah (Pakai SKL)?</span>
                        <span x-show="!open">➕</span><span x-show="open">➖</span>
                    </button>
                    <div x-show="open" class="p-4 border-t-2 border-black bg-gray-50 font-medium text-gray-700">
                        Bisa! Gunakan Surat Keterangan Lulus (SKL) sementara dari sekolah.
                    </div>
                </div>
                <div x-data="{ open: false }" class="bg-white border-4 border-black rounded-xl overflow-hidden shadow-neo">
                    <button @click="open = !open" class="w-full text-left p-4 font-black text-lg text-unmaris-blue flex justify-between items-center hover:bg-yellow-50">
                        <span>Bagaimana cara pembayaran?</span>
                        <span x-show="!open">➕</span><span x-show="open">➖</span>
                    </button>
                    <div x-show="open" class="p-4 border-t-2 border-black bg-gray-50 font-medium text-gray-700">
                        Transfer ke Rekening BNI/BRI Yayasan, atau bayar tunai di Bagian Keuangan Kampus.
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-white border-t-4 border-black py-10 px-6 pb-24 md:pb-10">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15'" class="h-12 w-12 border-2 border-black rounded-full bg-white">
                    <div>
                        <h4 class="font-black text-unmaris-blue uppercase text-lg">Universitas Stella Maris Sumba</h4>
                        <p class="text-xs font-bold text-gray-500">Jl. Soekarno Hatta No.05, Tambolaka, NTT</p>
                    </div>
                </div>
                <div class="text-sm font-bold text-gray-500 text-center md:text-right">
                    &copy; {{ date('Y') }} PMB UNMARIS. All rights reserved.
                </div>
            </div>
        </footer>

        <!-- MOBILE STICKY BOTTOM -->
        <div class="fixed bottom-0 left-0 w-full bg-white border-t-4 border-black p-4 md:hidden z-40 flex justify-between items-center shadow-[0px_-4px_10px_rgba(0,0,0,0.1)]">
            <div class="text-xs font-bold text-gray-500">
                {{ $isRegistrationOpen ? 'Pendaftaran Dibuka!' : 'Pendaftaran Belum Dibuka' }}
            </div>
            @if($isRegistrationOpen)
                <a href="{{ route('register') }}" class="bg-unmaris-yellow text-unmaris-blue font-black py-2 px-6 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none transition-all uppercase text-sm">
                    🔥 Daftar
                </a>
            @else
                <button disabled class="bg-gray-300 text-gray-500 font-black py-2 px-6 rounded-lg border-2 border-gray-500 uppercase text-sm cursor-not-allowed">
                    ⏳ Tutup
                </button>
            @endif
        </div>

        <!-- FLOATING CS WIDGET (DYNAMIC DARI DATABASE) -->
        <div x-data="{ open: false }" class="fixed bottom-24 md:bottom-6 right-6 z-50 flex flex-col items-end gap-2">
            
            <!-- MENU LIST KONTAK -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="bg-white border-4 border-black shadow-neo-lg rounded-xl p-4 mb-2 min-w-[250px]" 
                 style="display: none;">
                
                <h5 class="font-black text-unmaris-blue uppercase text-sm mb-3 border-b-2 border-black pb-2">
                    Butuh Bantuan? Chat Kami:
                </h5>
                
                <div class="space-y-2">
                    @if(!empty($settings->admin_contacts))
                        @foreach($settings->admin_contacts as $contact)
                            <a href="https://wa.me/{{ $contact['phone'] }}?text=Halo%20{{ urlencode($contact['name']) }},%20saya%20mau%20tanya%20info%20PMB%20Unmaris" 
                               target="_blank" 
                               class="flex items-center gap-3 hover:bg-yellow-50 p-2 rounded border-2 border-transparent hover:border-black transition-all group">
                                
                                <!-- Icon WA -->
                                <div class="bg-green-500 text-white p-1 rounded-full border-2 border-black shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                </div>
                                
                                <!-- Nama & Info -->
                                <div>
                                    <div class="font-bold text-sm leading-tight text-unmaris-blue">
                                        {{ $contact['name'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 font-bold">Panitia PMB</div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <!-- Fallback Jika Data Kosong -->
                        <div class="text-center text-xs font-bold text-gray-500 py-2">
                            Belum ada kontak tersedia.
                        </div>
                    @endif
                </div>
            </div>

            <!-- TOMBOL UTAMA (TRIGGER) -->
            <button @click="open = !open" class="bg-green-500 text-white p-4 rounded-full border-4 border-black shadow-neo-lg hover:scale-110 transition-transform flex items-center justify-center group relative">
                
                <!-- Notifikasi Merah (Hiasan) -->
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-black"></span>
                </span>

                <!-- Icon Silang (Saat Open) -->
                <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                
                <!-- Icon Chat (Saat Close) -->
                <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                
                <span class="ml-2 font-black hidden group-hover:block transition-all" x-text="open ? 'Tutup' : 'Chat Panitia'"></span>
            </button>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>