<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO & META TAGS -->
    <title>PMB UNMARIS - Universitas Stella Maris Sumba</title>
    <meta name="description"
        content="Pendaftaran Mahasiswa Baru Universitas Stella Maris Sumba. Kampus berbasis teknologi pertama di Sumba.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-yellow-50 text-black selection:bg-unmaris-blue selection:text-white relative pb-20 md:pb-0">

    @php
        // Cek apakah ada gelombang yang sedang aktif
        // Asumsi $gelombangs dikirim dari Controller sebagai Collection
        $isRegistrationOpen = $gelombangs->contains('is_active', true);
    @endphp

    <!-- NAVBAR -->
    <nav
        class="fixed top-0 w-full z-50 bg-white border-b-4 border-black px-4 md:px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}"
                onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15'"
                class="h-10 w-10  rounded-full bg-white">
            <span class="font-black text-xl tracking-tighter uppercase text-unmaris-blue hidden md:block">PMB
                UNMARIS</span>
        </div>

        <div class="flex gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="font-black text-sm uppercase px-4 py-2 border-2 border-black bg-unmaris-blue text-white hover:bg-blue-800 hover:shadow-neo transition-all rounded">
                        Dashboard 🚀
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="font-bold text-sm uppercase px-4 py-2 hover:underline decoration-4 underline-offset-4 decoration-unmaris-yellow text-unmaris-blue">
                        Masuk
                    </a>

                    @if (Route::has('register'))
                        @if ($isRegistrationOpen)
                            <a href="{{ route('register') }}"
                                class="hidden md:inline-block font-black text-sm uppercase px-4 py-2 border-2 border-black bg-unmaris-yellow text-unmaris-blue shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all rounded">
                                Daftar Sekarang
                            </a>
                        @else
                            <button disabled
                                class="hidden md:inline-block font-black text-sm uppercase px-4 py-2 border-2 border-gray-400 bg-gray-200 text-gray-500 cursor-not-allowed rounded">
                                Belum Dibuka
                            </button>
                        @endif
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- HERO SECTION (MODERN & VIBRANT) -->
    <header class="relative pt-32 pb-24 px-6 overflow-hidden bg-yellow-50">

        <!-- Background Pattern (Grid Tech Style) -->
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 30px 30px;">
        </div>

        <!-- Floating Decorations (Agar lebih hidup) -->
        <div class="absolute top-20 left-10 md:left-40 text-6xl animate-bounce-slight opacity-80"
            style="animation-duration: 3s;">✨</div>
        <div class="absolute bottom-20 right-5 md:right-40 text-6xl animate-bounce-slight opacity-80"
            style="animation-duration: 4s; animation-delay: 1s;">🎓</div>
        <div class="absolute top-40 right-10 md:right-20 text-4xl animate-bounce-slight opacity-60 hidden md:block"
            style="animation-duration: 5s;">🚀</div>

        <div class="max-w-7xl mx-auto text-center relative z-10">

            <!-- Badge Tahun Ajaran -->
            <div class="inline-block relative group cursor-default mb-6">
                <div class="absolute inset-0 bg-black translate-x-1 translate-y-1 rounded-lg"></div>
                <div
                    class="relative bg-unmaris-blue text-white px-6 py-2 font-black text-sm md:text-base uppercase tracking-widest border-2 border-black rounded-lg transform -rotate-2 group-hover:rotate-0 transition-transform duration-300">
                    Penerimaan Mahasiswa Baru T.A. 2026/2027
                </div>
            </div>

            <!-- Headline Utama -->
            <h1
                class="text-5xl md:text-8xl font-black text-unmaris-blue mb-8 leading-tight tracking-tight drop-shadow-sm">
                Hanya
                <br class="md:hidden">
                <!-- Highlight 500rb (Vibrant & Alive) -->
                <span
                    class="relative inline-block mx-2 transform -rotate-2 hover:rotate-0 transition-transform duration-300 cursor-pointer group my-2 md:my-0">
                    <!-- Efek Glow Belakang -->
                    <span
                        class="absolute inset-0 bg-yellow-400 blur-2xl opacity-60 group-hover:opacity-100 animate-pulse transition-opacity duration-500"></span>

                    <!-- Teks Utama dengan Gradient & Border -->
                    <span
                        class="relative block bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500 text-transparent bg-clip-text px-6 py-2 border-4 border-black bg-white rounded-xl shadow-[6px_6px_0px_0px_#000]">
                        Rp 500rb
                    </span>
                </span>
                <br>
                <span style="text-shadow: 3px 3px 0px #FACC15;">Sudah Bisa Kuliah!</span>
            </h1>

            <!-- Subheadline: Gratis Dana Pembangunan -->
            <div class="flex flex-col md:flex-row justify-center items-center gap-3 mb-10">
                <div
                    class="bg-green-500 text-white font-black px-4 py-2 text-lg uppercase border-2 border-black transform -rotate-1 shadow-[4px_4px_0px_0px_#000] hover:scale-105 transition-transform">
                    ✅ GRATIS DANA PEMBANGUNAN
                </div>
                <p class="text-lg md:text-xl font-bold text-gray-700 mt-2 md:mt-0">
                    di <strong>UNIVERSITAS Stella Maris Sumba</strong>.
                </p>
            </div>

            <p
                class="text-base md:text-lg font-medium text-gray-600 max-w-2xl mx-auto mb-10 leading-relaxed border-l-4 border-unmaris-yellow pl-4 md:border-none md:pl-0 text-left md:text-center">
                Jadilah bagian dari <span class="bg-yellow-200 px-1 font-bold text-black">Kampus Berbasis Teknologi
                    Pertama</span> di Sumba. Kami menggabungkan visi global dengan kearifan lokal untuk masa depanmu
                yang cerah.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
                @if ($isRegistrationOpen)
                    <a href="{{ route('register') }}" class="group relative inline-block focus:outline-none focus:ring">
                        <span
                            class="absolute inset-0 translate-x-1.5 translate-y-1.5 bg-black transition-transform group-hover:translate-y-0 group-hover:translate-x-0 rounded-xl"></span>
                        <span
                            class="relative inline-flex items-center gap-2 px-8 py-4 text-lg md:text-xl font-black uppercase tracking-widest text-unmaris-blue bg-unmaris-yellow border-2 border-black rounded-xl group-active:text-opacity-75">
                            🚀 Daftar Sekarang
                        </span>
                    </a>
                @else
                    <button disabled
                        class="bg-gray-200 text-gray-500 text-lg md:text-xl font-black py-4 px-8 border-2 border-gray-400 rounded-xl uppercase flex items-center justify-center gap-2 cursor-not-allowed">
                        ⏳ Pendaftaran Segera Dibuka
                    </button>
                @endif

                <a href="#prodi" class="group relative inline-block focus:outline-none focus:ring">
                    <span
                        class="absolute inset-0 translate-x-1.5 translate-y-1.5 bg-black transition-transform group-hover:translate-y-0 group-hover:translate-x-0 rounded-xl"></span>
                    <span
                        class="relative inline-flex items-center gap-2 px-8 py-4 text-lg md:text-xl font-black uppercase tracking-widest text-black bg-white border-2 border-black rounded-xl group-active:text-opacity-75">
                        🔍 Lihat Jurusan
                    </span>
                </a>
            </div>
        </div>
    </header>

    <!-- ... (Kode Hero Section di atas tetap sama) ... -->
    </div>
    </div>
    </header>

    <!-- SECTION TRUST & AKREDITASI (INFINITE SLIDER) -->
    <section class="bg-white border-y-4 border-black py-8 relative overflow-hidden">
        <!-- Background Noise -->
        <div class="absolute inset-0 opacity-50"
            style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <p class="text-xs md:text-sm font-bold text-gray-500 uppercase tracking-widest mb-8">
                Terakreditasi & Diakui Oleh Pemerintah Republik Indonesia
            </p>

            <!-- LOGO SLIDER CONTAINER -->
            <div class="relative w-full overflow-hidden mask-image-linear-gradient">
                <!-- CSS Mask untuk efek fade di kiri & kanan -->
                <style>
                    .mask-image-linear-gradient {
                        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
                        -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
                    }

                    @keyframes scroll {
                        0% {
                            transform: translateX(0);
                        }

                        100% {
                            transform: translateX(-50%);
                        }
                    }

                    .animate-scroll {
                        animation: scroll 20s linear infinite;
                    }

                    /* Pause saat di-hover */
                    .animate-scroll:hover {
                        animation-play-state: paused;
                    }
                </style>

                <div class="flex items-center gap-16 animate-scroll w-[200%]">
                    <!-- LOGO SET 1 -->
                    <div
                        class="flex items-center justify-around w-1/2 gap-16 grayscale hover:grayscale-0 transition-all duration-500">
                        <!-- Tut Wuri Handayani -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg/400px-Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.png"
                            class="h-16 md:h-20 object-contain hover:scale-110 transition-transform" alt="Kemdikbud">

                        <!-- Kampus Merdeka -->
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Logo_Kampus_Merdeka_Kemendikbud.png/800px-Logo_Kampus_Merdeka_Kemendikbud.png"
                            class="h-12 md:h-16 object-contain hover:scale-110 transition-transform"
                            alt="Kampus Merdeka">

                        <!-- BAN-PT -->
                        <img src="https://1.bp.blogspot.com/-pictMWBnFp8/X6bd5gW6JQI/AAAAAAAACek/y1XIwzIwmh0HbCKsTDilLWL8V-Bs8lcCgCLcBGAsYHQ/s16000/Logo%2BBAN-PT.png"
                            onerror="this.src='https://placehold.co/150x80/transparent/000?text=BAN-PT'"
                            class="h-14 md:h-18 object-contain hover:scale-110 transition-transform" alt="BAN-PT">

                        <!-- PDDikti (Logo Tambahan agar lebih panjang) -->
                        <img src="https://pddikti.kemdikbud.go.id/asset/gambar/logopddikti.png"
                            onerror="this.src='https://placehold.co/150x80/transparent/000?text=PDDIKTI'"
                            class="h-10 md:h-14 object-contain hover:scale-110 transition-transform" alt="PDDikti">
                    </div>

                    <!-- LOGO SET 2 (DUPLIKAT UNTUK LOOPING) -->
                    <div
                        class="flex items-center justify-around w-1/2 gap-16 grayscale hover:grayscale-0 transition-all duration-500">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg/400px-Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.png"
                            class="h-16 md:h-20 object-contain hover:scale-110 transition-transform" alt="Kemdikbud">

                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Logo_Kampus_Merdeka_Kemendikbud.png/800px-Logo_Kampus_Merdeka_Kemendikbud.png"
                            class="h-12 md:h-16 object-contain hover:scale-110 transition-transform"
                            alt="Kampus Merdeka">

                        <img src="https://1.bp.blogspot.com/-pictMWBnFp8/X6bd5gW6JQI/AAAAAAAACek/y1XIwzIwmh0HbCKsTDilLWL8V-Bs8lcCgCLcBGAsYHQ/s16000/Logo%2BBAN-PT.png"
                            onerror="this.src='https://placehold.co/150x80/transparent/000?text=BAN-PT'"
                            class="h-14 md:h-18 object-contain hover:scale-110 transition-transform" alt="BAN-PT">

                        <img src="https://pddikti.kemdikbud.go.id/asset/gambar/logopddikti.png"
                            onerror="this.src='https://placehold.co/150x80/transparent/000?text=PDDIKTI'"
                            class="h-10 md:h-14 object-contain hover:scale-110 transition-transform" alt="PDDikti">
                    </div>
                </div>
            </div>

            <div
                class="mt-8 inline-block bg-blue-50 border-2 border-unmaris-blue px-4 py-2 rounded-full shadow-[2px_2px_0px_0px_#1e3a8a] transform hover:scale-105 transition">
                <p class="text-xs md:text-sm font-black text-unmaris-blue flex items-center justify-center gap-2">
                    <span class="text-lg">📜</span>
                    SK Menteri Pendidikan No: 985/E/O/2023
                </p>
            </div>
        </div>
    </section>





    <!-- SECTION 2: FASILITAS & KEUNGGULAN (DYNAMIC DATABASE) -->
    <section class="bg-unmaris-blue border-b-4 border-black py-16 md:py-24 text-white relative overflow-hidden">

        <!-- Background Decoration -->
        <div
            class="absolute top-0 right-0 p-10 opacity-10 text-9xl font-black text-white select-none pointer-events-none">
            UNMARIS</div>
        <div
            class="absolute bottom-0 left-0 p-10 opacity-10 text-9xl font-black text-white select-none pointer-events-none">
            2026</div>

        <div class="max-w-7xl mx-auto px-6 relative z-10" x-data="{
            active: 0,
        
            // INI BAGIAN DINAMISNYA (MENGAMBIL DARI DATABASE)
            slides: {{ $facilitySlides->map(function ($slide) {
                    return [
                        'title' => $slide->title,
                        'desc' => $slide->description,
                        'icon' => $slide->icon,
                        // Logika Gambar: Cek apakah URL http (seeder) atau path storage (upload admin)
                        'images' => collect($slide->images)->map(function ($img) {
                            return \Illuminate\Support\Str::startsWith($img, 'http') ? $img : asset('storage/' . $img);
                        }),
                    ];
                })->toJson() }},
        
            next() {
                this.active = (this.active + 1) % this.slides.length;
            },
            prev() {
                this.active = (this.active - 1 + this.slides.length) % this.slides.length;
            },
            init() {
                // Auto slide jika ada datanya
                if (this.slides.length > 0) {
                    setInterval(() => this.next(), 8000);
                }
            }
        }">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h2 class="text-3xl md:text-5xl font-black text-white uppercase leading-none mb-2">
                        Fasilitas <span class="text-unmaris-yellow">Unggulan</span>
                    </h2>
                    <p class="text-blue-200 font-bold max-w-xl text-lg">
                        Bukti nyata fasilitas modern untuk menunjang masa depanmu.
                    </p>
                </div>

                <!-- Slider Controls -->
                <div class="flex gap-3" x-show="slides.length > 1">
                    <button @click="prev()"
                        class="w-12 h-12 flex items-center justify-center border-2 border-white bg-transparent hover:bg-white hover:text-unmaris-blue text-white rounded-full transition-all text-xl font-bold">
                        ←
                    </button>
                    <button @click="next()"
                        class="w-12 h-12 flex items-center justify-center border-2 border-white bg-unmaris-yellow text-unmaris-blue border-black hover:scale-110 rounded-full transition-all text-xl font-bold shadow-[4px_4px_0px_0px_#000]">
                        →
                    </button>
                </div>
            </div>

            <!-- SLIDER CONTENT -->
            <div class="relative h-[550px] md:h-[500px]">

                <!-- Jika Data Kosong -->
                <template x-if="slides.length === 0">
                    <div
                        class="absolute inset-0 flex items-center justify-center border-4 border-dashed border-white/20 rounded-2xl">
                        <p class="text-white/50 font-bold text-xl">Belum ada data fasilitas.</p>
                    </div>
                </template>

                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="active === index" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-x-20"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-20" class="absolute inset-0 w-full h-full">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 h-full">

                            <!-- Image Side (Dengan Mini Slider Internal) -->
                            <div class="relative h-64 md:h-full border-4 border-black rounded-2xl overflow-hidden shadow-[8px_8px_0px_0px_#FACC15] group bg-gray-900"
                                x-data="{ currentImg: 0 }" x-init="// Ganti foto internal setiap 2.5 detik jika foto lebih dari 1
                                if (slide.images.length > 1) {
                                    setInterval(() => { currentImg = (currentImg + 1) % slide.images.length }, 2500)
                                }">

                                <!-- Looping Gambar Internal -->
                                <template x-for="(imgUrl, imgIndex) in slide.images" :key="imgIndex">
                                    <img :src="imgUrl" x-show="currentImg === imgIndex"
                                        x-transition:enter="transition ease-in duration-500"
                                        x-transition:enter-start="opacity-0 scale-105"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-out duration-500"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95" :alt="slide.title"
                                        class="absolute inset-0 w-full h-full object-cover">
                                </template>

                                <!-- Badge Icon -->
                                <div class="absolute top-4 left-4 bg-white text-4xl w-16 h-16 flex items-center justify-center border-4 border-black rounded-full shadow-neo z-10"
                                    x-text="slide.icon"></div>

                                <!-- Indikator Foto Kecil -->
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10"
                                    x-show="slide.images.length > 1">
                                    <template x-for="(img, i) in slide.images">
                                        <div class="h-1.5 rounded-full transition-all duration-300 shadow-sm"
                                            :class="currentImg === i ? 'w-6 bg-unmaris-yellow' : 'w-1.5 bg-white/50'">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Text Side -->
                            <div class="flex flex-col justify-center text-left p-2">
                                <h3 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight"
                                    x-text="slide.title"></h3>
                                <div class="w-24 h-2 bg-unmaris-yellow mb-6"></div>
                                <p class="text-lg md:text-xl text-blue-100 leading-relaxed font-medium"
                                    x-text="slide.desc"></p>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Dots Indicator Utama -->
            <div class="flex justify-center md:justify-start gap-3 mt-8" x-show="slides.length > 1">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="active = index" class="h-3 rounded-full transition-all duration-300"
                        :class="active === index ? 'w-12 bg-unmaris-yellow' : 'w-3 bg-white/30 hover:bg-white'">
                    </button>
                </template>
            </div>

        </div>
    </section>

    <!-- JADWAL & BIAYA -->
    <section class="py-20 px-6 max-w-7xl mx-auto bg-gray-50 border-y-4 border-black">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            <!-- BIAYA PENDAFTARAN -->
            <div class="bg-white border-4 border-black shadow-neo-lg rounded-3xl p-8 relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 bg-green-500 text-white px-4 py-2 font-black text-sm border-b-4 border-l-4 border-black rounded-bl-xl">
                    TERMURAH!
                </div>
                <h2 class="text-3xl font-black text-unmaris-blue mb-4 uppercase">💰 Biaya Pendaftaran</h2>
                <p class="text-gray-600 font-bold mb-6">Investasi awal untuk masa depan gemilang. Biaya pendaftaran
                    sangat terjangkau.</p>

                <div class="bg-green-100 border-4 border-black rounded-xl p-6 text-center mb-6">
                    <span class="block text-sm font-bold text-green-800 uppercase tracking-widest mb-1">Hanya</span>
                    <span class="block text-5xl font-black text-green-600"> Rp
                        {{ number_format($settings->biaya_pendaftaran ?? 200000, 0, ',', '.') }}</span>
                    <span class="block text-xs font-bold text-green-800 mt-2">*Sudah termasuk biaya ujian
                        seleksi</span>
                </div>

                <ul class="space-y-3 font-bold text-gray-700">
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Formulir Digital
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
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

                        <div
                            class="{{ $bgColor }} border-4 {{ $borderColor }} shadow-neo rounded-xl p-6 flex justify-between items-center {{ $scale }}">
                            <div>
                                <h4
                                    class="font-black text-xl {{ $isActive ? 'text-unmaris-blue' : 'text-gray-600' }}">
                                    {{ $g->nama_gelombang }}</h4>
                                <p class="text-sm font-bold text-gray-500">
                                    {{ \Carbon\Carbon::parse($g->tgl_mulai)->format('d M') }} -
                                    {{ \Carbon\Carbon::parse($g->tgl_selesai)->format('d M Y') }}
                                </p>
                            </div>

                            @if ($isActive)
                                <span
                                    class="bg-green-500 text-white px-3 py-1 rounded border-2 border-black font-black text-xs uppercase shadow-sm animate-pulse">
                                    BUKA SEKARANG
                                </span>
                            @elseif($isPast)
                                <span
                                    class="bg-red-500 text-white px-3 py-1 rounded border-2 border-black font-black text-xs uppercase">
                                    TUTUP
                                </span>
                            @else
                                <span
                                    class="bg-gray-300 text-gray-600 px-3 py-1 rounded border-2 border-gray-500 font-black text-xs uppercase">
                                    SEGERA
                                </span>
                            @endif
                        </div>
                    @empty
                        <div
                            class="p-6 bg-yellow-100 border-2 border-yellow-400 rounded-lg text-yellow-800 text-center font-bold">
                            Belum ada jadwal gelombang yang dirilis. Pantau terus ya!
                        </div>
                    @endforelse
                </div>

                <div
                    class="mt-6 p-4 bg-yellow-100 border-2 border-yellow-400 rounded-lg text-yellow-800 text-sm font-bold">
                    💡 Tips: Daftar di Gelombang awal untuk peluang diterima lebih besar!
                </div>
            </div>

        </div>
    </section>


    <!-- PRODI LIST (UPDATED DATA) -->
    <section id="prodi" class="bg-black py-20 text-white border-y-4 border-unmaris-yellow">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-unmaris-yellow mb-2 uppercase">Program Studi Unggulan
                </h2>
                <p class="font-bold text-gray-400">Pilih jurusan yang sesuai dengan minat dan bakatmu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- TEKNIK -->
                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-blue-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        💻</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Teknik Informatika (S1)</h3>
                    <p class="text-sm font-bold text-gray-500">Software Engineering, AI, IoT.</p>
                </div>

                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-blue-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        📊</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Manajemen Informatika (D3)</h3>
                    <p class="text-sm font-bold text-gray-500">Sistem Informasi Praktis & Database.</p>
                </div>

                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-green-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        🌱</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Teknik Lingkungan (S1)</h3>
                    <p class="text-sm font-bold text-gray-500">Rekayasa Lingkungan Berkelanjutan.</p>
                </div>

                <!-- EKONOMI -->
                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-yellow-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        📈</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Bisnis Digital (S1)</h3>
                    <p class="text-sm font-bold text-gray-500">E-Commerce & Digital Marketing.</p>
                </div>

                <!-- KESEHATAN -->
                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-red-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        🏥</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Administrasi RS (S1)</h3>
                    <p class="text-sm font-bold text-gray-500">Manajemen Rumah Sakit Profesional.</p>
                </div>

                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-red-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        ⛑️</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">K3 (S1)</h3>
                    <p class="text-sm font-bold text-gray-500">Keselamatan & Kesehatan Kerja.</p>
                </div>

                <!-- PENDIDIKAN -->
                <div
                    class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                    <div
                        class="text-4xl mb-4 bg-purple-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">
                        🎓</div>
                    <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Pendidikan TI (S1)</h3>
                    <p class="text-sm font-bold text-gray-500">Guru TIK Kompeten & Berkarakter.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAHASISWA LIFE (BEASISWA & EKSKUL) - BARU -->
    <section class="py-20 px-6 max-w-7xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-black text-center text-unmaris-blue mb-16 uppercase">
            <span class="bg-unmaris-yellow px-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">Kehidupan</span>
            Kampus
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Beasiswa -->
            <div
                class="bg-blue-50 border-4 border-unmaris-blue rounded-xl p-8 shadow-neo hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                <h3 class="text-2xl font-black text-unmaris-blue mb-4 flex items-center gap-3">
                    <span>🎓</span> Beasiswa Tersedia
                </h3>
                <ul class="space-y-4 font-bold text-gray-700">
                    <li class="flex items-start gap-3">
                        <div class="bg-blue-500 text-white rounded-full p-1 mt-1"><svg class="w-3 h-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg></div>
                        <div>
                            <span class="block text-lg">KIP Kuliah</span>
                            <span class="text-sm font-medium text-gray-500">Bantuan biaya pendidikan dari
                                pemerintah.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="bg-blue-500 text-white rounded-full p-1 mt-1"><svg class="w-3 h-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg></div>
                        <div>
                            <span class="block text-lg">Beasiswa PEMDA</span>
                            <span class="text-sm font-medium text-gray-500">Khusus Sumba Tengah & Sumba Barat</span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Ekskul -->
            <div
                class="bg-yellow-50 border-4 border-unmaris-yellow rounded-xl p-8 shadow-neo hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-none transition-all">
                <h3 class="text-2xl font-black text-yellow-800 mb-4 flex items-center gap-3">
                    <span>🎸</span> Ekstrakurikuler
                </h3>
                <div class="flex flex-wrap gap-3">
                    <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">⚽
                        Futsal</span>

                    <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🏐
                        Voli</span>
                    <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🎤 Paduan
                        Suara</span>
                    <span class="bg-white border-2 border-black px-4 py-2 rounded-full font-bold shadow-sm">🎨
                        Kesenian</span>
                </div>
                <p class="mt-6 font-bold text-gray-600 text-sm">
                    Kembangkan bakat dan minatmu di luar akademik bersama teman-teman sehobi!
                </p>
            </div>
        </div>
    </section>

    <!-- SYARAT & BERKAS (UPDATED: DENGAN PENEGASAN FORMAT FILE DI TIAP KARTU) -->
    <section id="syarat" class="py-20 px-6 max-w-7xl mx-auto border-t-4 border-black bg-white">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-black text-unmaris-blue mb-4 uppercase">
                <span class="bg-unmaris-yellow px-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">Siapkan</span>
                Berkas Ini
            </h2>
            <p class="font-bold text-gray-500 max-w-2xl mx-auto">
                Sebelum mendaftar, pastikan kamu sudah memfoto/scan dokumen berikut di HP atau Laptopmu:
            </p>
        </div>

        <!-- INFO TEKNIS FILE (PENTING UNTUK ORANG AWAM) -->
        <div
            class="max-w-3xl mx-auto bg-blue-50 border-l-8 border-unmaris-blue p-4 mb-12 rounded-r-lg shadow-sm flex items-start gap-4">
            <div class="text-3xl">⚠️</div>
            <div>
                <h4 class="font-black text-unmaris-blue text-lg uppercase">Ketentuan File Upload</h4>
                <ul class="list-disc list-inside text-sm font-bold text-gray-700 mt-1 space-y-1">
                    <li>Ukuran file <strong>Maksimal 2MB</strong> per dokumen.</li>
                    <li>Format file bisa <strong>PDF</strong> (dokumen) atau <strong>JPG/PNG</strong> (foto/gambar).
                    </li>
                    <li>Pastikan tulisan & gambar terlihat <strong>JELAS</strong> (tidak buram/gelap).</li>
                </ul>
            </div>
        </div>

        <!-- GRID 5 KOLOM (AGAR JELAS SATU PER SATU) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">

            <!-- Card 1: Ijazah -->
            <div
                class="bg-white border-4 border-gray-200 rounded-xl p-6 text-center hover:border-unmaris-blue hover:-translate-y-2 transition-transform group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">📄</div>
                <h3 class="font-black text-lg text-gray-800 uppercase mb-2">1. Ijazah / SKL</h3>
                <p class="text-xs font-bold text-gray-500 leading-relaxed">
                    Foto/Scan Ijazah asli atau Surat Keterangan Lulus (SKL).
                    <br><span class="text-unmaris-blue bg-blue-100 px-1 rounded mt-1 inline-block">Format: PDF /
                        JPG</span>
                </p>
            </div>

            <!-- Card 2: Transkrip -->
            <div
                class="bg-white border-4 border-gray-200 rounded-xl p-6 text-center hover:border-unmaris-blue hover:-translate-y-2 transition-transform group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">📊</div>
                <h3 class="font-black text-lg text-gray-800 uppercase mb-2">2. Transkrip Nilai</h3>
                <p class="text-xs font-bold text-gray-500 leading-relaxed">
                    Foto/Scan Transkrip Nilai yang ada di belakang Ijazah.
                    <br><span class="text-unmaris-blue bg-blue-100 px-1 rounded mt-1 inline-block">Format: PDF /
                        JPG</span>
                </p>
            </div>

            <!-- Card 3: Identitas -->
            <div
                class="bg-white border-4 border-gray-200 rounded-xl p-6 text-center hover:border-unmaris-yellow hover:-translate-y-2 transition-transform group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">🪪</div>
                <h3 class="font-black text-lg text-gray-800 uppercase mb-2">3. KTP & KK</h3>
                <p class="text-xs font-bold text-gray-500 leading-relaxed">
                    Foto Kartu Keluarga & KTP (boleh juga Akta Lahir).
                    <br><span class="text-yellow-700 bg-yellow-100 px-1 rounded mt-1 inline-block">Format: JPG /
                        PDF</span>
                </p>
            </div>

            <!-- Card 4: Foto -->
            <div
                class="bg-white border-4 border-gray-200 rounded-xl p-6 text-center hover:border-red-500 hover:-translate-y-2 transition-transform group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">📸</div>
                <h3 class="font-black text-lg text-gray-800 uppercase mb-2">4. Pas Foto</h3>
                <p class="text-xs font-bold text-gray-500 leading-relaxed">
                    Foto diri resmi wajah jelas dengan <strong>LATAR BIRU</strong>.
                    <br><span class="text-red-700 bg-red-100 px-1 rounded mt-1 inline-block">Format: JPG / PNG</span>
                </p>
            </div>

            <!-- Card 5: Biaya -->
            <div
                class="bg-white border-4 border-gray-200 rounded-xl p-6 text-center hover:border-green-500 hover:-translate-y-2 transition-transform group">
                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">💸</div>
                <h3 class="font-black text-lg text-gray-800 uppercase mb-2">5. Biaya Daftar</h3>
                <p class="text-xs font-bold text-gray-500 leading-relaxed">
                    Siapkan bukti transfer / uang tunai <strong>Rp
                        {{ number_format($settings->biaya_pendaftaran ?? 200000, 0, ',', '.') }}</strong>.
                    <br><span class="text-green-700 bg-green-100 px-1 rounded mt-1 inline-block">Bukti Struk:
                        JPG</span>
                </p>
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
            <div
                class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                <div
                    class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-unmaris-blue text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">
                    1</div>
                <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Buat Akun</h3>
                <p class="text-center text-sm font-bold text-gray-500">Klik "Daftar Sekarang", isi Nama, Email &
                    Password.</p>
            </div>
            <!-- Step 2 -->
            <div
                class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                <div
                    class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-unmaris-yellow text-black flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">
                    2</div>
                <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Isi Formulir</h3>
                <p class="text-center text-sm font-bold text-gray-500">Isi biodata & upload berkas.</p>
            </div>
            <!-- Step 3 -->
            <div
                class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                <div
                    class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-green-500 text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">
                    3</div>
                <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Pembayaran</h3>
                <p class="text-center text-sm font-bold text-gray-500">Transfer biaya & tunggu verifikasi.</p>
            </div>
            <!-- Step 4 -->
            <div
                class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                <div
                    class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-red-500 text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">
                    4</div>
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
                <button @click="open = !open"
                    class="w-full text-left p-4 font-black text-lg text-unmaris-blue flex justify-between items-center hover:bg-yellow-50">
                    <span>Apakah bisa mendaftar tanpa Ijazah (Pakai SKL)?</span>
                    <span x-show="!open">➕</span><span x-show="open">➖</span>
                </button>
                <div x-show="open" class="p-4 border-t-2 border-black bg-gray-50 font-medium text-gray-700">
                    Bisa! Gunakan Surat Keterangan Lulus (SKL) sementara dari sekolah.
                </div>
            </div>
            <div x-data="{ open: false }" class="bg-white border-4 border-black rounded-xl overflow-hidden shadow-neo">
                <button @click="open = !open"
                    class="w-full text-left p-4 font-black text-lg text-unmaris-blue flex justify-between items-center hover:bg-yellow-50">
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
                <img src="{{ asset('images/logo.png') }}"
                    onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15'"
                    class="h-12 w-12 border-2 border-black rounded-full bg-white">
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
    <div
        class="fixed bottom-0 left-0 w-full bg-white border-t-4 border-black p-4 md:hidden z-40 flex justify-between items-center shadow-[0px_-4px_10px_rgba(0,0,0,0.1)]">
        <div class="text-xs font-bold text-gray-500">
            {{ $isRegistrationOpen ? 'Pendaftaran Dibuka!' : 'Pendaftaran Belum Dibuka' }}
        </div>
        @if ($isRegistrationOpen)
            <a href="{{ route('register') }}"
                class="bg-unmaris-yellow text-unmaris-blue font-black py-2 px-6 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none transition-all uppercase text-sm">
                🔥 Daftar
            </a>
        @else
            <button disabled
                class="bg-gray-300 text-gray-500 font-black py-2 px-6 rounded-lg border-2 border-gray-500 uppercase text-sm cursor-not-allowed">
                ⏳ Tutup
            </button>
        @endif
    </div>

    <!-- FLOATING CS WIDGET (DYNAMIC DARI DATABASE) -->
    <div x-data="{ open: false }" class="fixed bottom-24 md:bottom-6 right-6 z-50 flex flex-col items-end gap-2">

        <!-- MENU LIST KONTAK -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="bg-white border-4 border-black shadow-neo-lg rounded-xl p-4 mb-2 min-w-[250px]"
            style="display: none;">

            <h5 class="font-black text-unmaris-blue uppercase text-sm mb-3 border-b-2 border-black pb-2">
                Butuh Bantuan? Chat Kami:
            </h5>

            <div class="space-y-2">
                @if (!empty($settings->admin_contacts))
                    @foreach ($settings->admin_contacts as $contact)
                        <a href="https://wa.me/{{ $contact['phone'] }}?text=Halo%20{{ urlencode($contact['name']) }},%20saya%20mau%20tanya%20info%20PMB%20Unmaris"
                            target="_blank"
                            class="flex items-center gap-3 hover:bg-yellow-50 p-2 rounded border-2 border-transparent hover:border-black transition-all group">

                            <!-- Icon WA -->
                            <div class="bg-green-500 text-white p-1 rounded-full border-2 border-black shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
                                </svg>
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
        <button @click="open = !open"
            class="bg-green-500 text-white p-4 rounded-full border-4 border-black shadow-neo-lg hover:scale-110 transition-transform flex items-center justify-center group relative">

            <!-- Notifikasi Merah (Hiasan) -->
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-black"></span>
            </span>

            <!-- Icon Silang (Saat Open) -->
            <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>

            <!-- Icon Chat (Saat Close) -->
            <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                </path>
            </svg>

            <span class="ml-2 font-black hidden group-hover:block transition-all"
                x-text="open ? 'Tutup' : 'Chat Panitia'"></span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
