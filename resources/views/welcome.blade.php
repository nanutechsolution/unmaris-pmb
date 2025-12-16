<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PMB UNMARIS - Universitas Stella Maris Sumba</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-yellow-50 text-black selection:bg-unmaris-blue selection:text-white relative">
        
        <!-- NAVBAR -->
        <nav class="fixed top-0 w-full z-50 bg-white border-b-4 border-black px-4 md:px-8 py-4 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15'" class="h-10 w-10 border-2 border-black rounded-full bg-white">
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
                            <a href="{{ route('register') }}" class="font-black text-sm uppercase px-4 py-2 border-2 border-black bg-unmaris-yellow text-unmaris-blue shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all rounded">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- HERO SECTION -->
        <header class="pt-32 pb-20 px-6 max-w-7xl mx-auto text-center relative overflow-hidden">
            <!-- Decoration Icons -->
            <div class="absolute top-24 left-4 md:left-20 text-4xl md:text-6xl animate-bounce-slight opacity-60">✨</div>
            <div class="absolute bottom-10 right-4 md:right-20 text-4xl md:text-6xl animate-bounce-slight opacity-60 animation-delay-200">🎓</div>

            <div class="inline-block bg-unmaris-blue text-white px-4 py-1 font-bold text-xs md:text-sm uppercase tracking-widest border-2 border-black mb-6 transform -rotate-2 shadow-[2px_2px_0px_0px_#000]">
                Penerimaan Mahasiswa Baru 2025/2026
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-unmaris-blue mb-6 uppercase tracking-tight leading-none drop-shadow-sm" style="text-shadow: 4px 4px 0px #FACC15;">
                Masa Depan Cerah<br>Dimulai Di Sini
            </h1>
            
            <p class="text-lg md:text-xl font-bold text-gray-600 max-w-2xl mx-auto mb-10 border-l-4 border-unmaris-yellow pl-4 text-left md:text-center md:border-none md:pl-0">
                Bergabunglah dengan <strong>Universitas Stella Maris Sumba</strong>. Kampus berbasis teknologi pertama di Sumba dengan visi global. Jadilah profesional unggul yang berkarakter!
            </p>

            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue text-lg md:text-xl font-black py-3 md:py-4 px-8 md:px-10 border-4 border-black shadow-neo-lg hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px] transition-all rounded-xl uppercase flex items-center justify-center gap-2">
                    🚀 Daftar Sekarang
                </a>
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

        <!-- JADWAL & BIAYA -->
        <section class="py-20 px-6 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                <!-- BIAYA PENDAFTARAN -->
                <div class="bg-white border-4 border-black shadow-neo-lg rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-4 py-2 font-black text-sm border-b-4 border-l-4 border-black rounded-bl-xl">
                        TERMURAH!
                    </div>
                    <h2 class="text-3xl font-black text-unmaris-blue mb-4 uppercase">💰 Biaya Pendaftaran</h2>
                    <p class="text-gray-600 font-bold mb-6">Investasi awal untuk masa depan gemilang. Biaya pendaftaran sangat terjangkau untuk semua kalangan.</p>
                    
                    <div class="bg-green-100 border-4 border-black rounded-xl p-6 text-center mb-6">
                        <span class="block text-sm font-bold text-green-800 uppercase tracking-widest mb-1">Hanya</span>
                        <span class="block text-5xl font-black text-green-600">Rp 250.000</span>
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
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            Hasil Seleksi Online
                        </li>
                    </ul>
                </div>

                <!-- JADWAL GELOMBANG -->
                <div>
                    <h2 class="text-3xl font-black text-unmaris-blue mb-6 uppercase">📅 Jadwal Gelombang</h2>
                    <div class="space-y-4">
                        <!-- Gelombang 1 -->
                        <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 flex justify-between items-center transform scale-105 border-l-8 border-l-unmaris-yellow">
                            <div>
                                <h4 class="font-black text-xl text-unmaris-blue">GELOMBANG 1</h4>
                                <p class="text-sm font-bold text-gray-500">Januari - Maret 2025</p>
                            </div>
                            <span class="bg-green-500 text-white px-3 py-1 rounded border-2 border-black font-black text-xs uppercase shadow-sm">
                                BUKA SEKARANG
                            </span>
                        </div>

                        <!-- Gelombang 2 -->
                        <div class="bg-gray-100 border-4 border-gray-400 rounded-xl p-6 flex justify-between items-center opacity-75">
                            <div>
                                <h4 class="font-black text-xl text-gray-600">GELOMBANG 2</h4>
                                <p class="text-sm font-bold text-gray-500">April - Juni 2025</p>
                            </div>
                            <span class="bg-gray-300 text-gray-600 px-3 py-1 rounded border-2 border-gray-500 font-black text-xs uppercase">
                                SEGERA
                            </span>
                        </div>

                        <!-- Gelombang 3 -->
                        <div class="bg-gray-100 border-4 border-gray-400 rounded-xl p-6 flex justify-between items-center opacity-75">
                            <div>
                                <h4 class="font-black text-xl text-gray-600">GELOMBANG 3</h4>
                                <p class="text-sm font-bold text-gray-500">Juli - Agustus 2025</p>
                            </div>
                            <span class="bg-gray-300 text-gray-600 px-3 py-1 rounded border-2 border-gray-500 font-black text-xs uppercase">
                                SEGERA
                            </span>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-yellow-100 border-2 border-yellow-400 rounded-lg text-yellow-800 text-sm font-bold">
                        💡 Tips: Daftar lebih awal di Gelombang 1 untuk peluang diterima lebih besar!
                    </div>
                </div>

            </div>
        </section>

        <!-- ALUR PENDAFTARAN -->
        <section id="alur" class="py-20 px-6 max-w-7xl mx-auto border-t-4 border-black bg-white">
            <h2 class="text-3xl md:text-4xl font-black text-center text-unmaris-blue mb-16 uppercase">
                <span class="bg-unmaris-yellow px-2 border-2 border-black shadow-[4px_4px_0px_0px_#000]">Alur</span> Pendaftaran
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
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Lengkapi Data</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Login, lalu isi formulir biodata & upload Ijazah/Foto.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-green-500 text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">3</div>
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Pembayaran</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Transfer Rp 250rb ke Rekening Kampus & Upload Bukti.</p>
                </div>

                <!-- Step 4 -->
                <div class="bg-white p-6 border-4 border-black shadow-neo rounded-xl relative group hover:-translate-y-2 transition-transform">
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-red-500 text-white flex items-center justify-center font-black text-xl border-2 border-black rounded-full shadow-sm">4</div>
                    <h3 class="mt-6 text-xl font-black text-center mb-2 uppercase text-unmaris-blue">Ujian & Lulus</h3>
                    <p class="text-center text-sm font-bold text-gray-500">Cetak Kartu Ujian, Ikuti Tes, dan Lihat Pengumuman.</p>
                </div>
            </div>
        </section>

        <!-- PRODI LIST (UPDATED) -->
        <section id="prodi" class="bg-black py-20 text-white border-t-4 border-unmaris-yellow">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-black text-unmaris-yellow mb-2 uppercase">Program Studi Unggulan</h2>
                    <p class="font-bold text-gray-400">Pilih jurusan yang sesuai dengan minat dan bakatmu di UNMARIS.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- FAKULTAS TEKNIK -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-blue-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">💻</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Teknik Informatika (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Mencetak ahli software engineering, AI, dan profesional IT yang handal.</p>
                    </div>
                    
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-blue-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">📊</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Manajemen Informatika (D3)</h3>
                        <p class="text-sm font-bold text-gray-500">Fokus pada aplikasi komputer, manajemen data, dan sistem informasi praktis.</p>
                    </div>

                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-green-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">🌱</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Teknik Lingkungan (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Solusi rekayasa untuk masalah lingkungan dan pembangunan berkelanjutan.</p>
                    </div>

                    <!-- FAKULTAS EKONOMI & BISNIS -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-yellow-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">📈</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Bisnis Digital (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Menggabungkan ilmu manajemen bisnis dengan teknologi digital terkini.</p>
                    </div>

                    <!-- FAKULTAS KESEHATAN -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-red-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">🏥</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Administrasi RS (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Manajemen operasional rumah sakit dan layanan kesehatan profesional.</p>
                    </div>

                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-red-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">⛑️</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">K3 (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Keselamatan dan Kesehatan Kerja untuk industri yang aman dan produktif.</p>
                    </div>

                    <!-- FAKULTAS KEGURUAN -->
                    <div class="bg-white text-black p-6 border-4 border-unmaris-blue shadow-[8px_8px_0px_0px_#FACC15] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[4px_4px_0px_0px_#FACC15] transition-all rounded-xl">
                        <div class="text-4xl mb-4 bg-purple-100 w-16 h-16 flex items-center justify-center rounded-full border-2 border-black">🎓</div>
                        <h3 class="font-black text-xl uppercase mb-2 text-unmaris-blue">Pendidikan TI (S1)</h3>
                        <p class="text-sm font-bold text-gray-500">Mencetak tenaga pendidik TIK yang kompeten dan berkarakter.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ SECTION -->
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
                        Bisa! Calon mahasiswa lulusan tahun ini yang belum menerima Ijazah asli diperbolehkan menggunakan Surat Keterangan Lulus (SKL) dari sekolah untuk mendaftar.
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white border-4 border-black rounded-xl overflow-hidden shadow-neo">
                    <button @click="open = !open" class="w-full text-left p-4 font-black text-lg text-unmaris-blue flex justify-between items-center hover:bg-yellow-50">
                        <span>Bagaimana jika saya lupa password akun?</span>
                        <span x-show="!open">➕</span><span x-show="open">➖</span>
                    </button>
                    <div x-show="open" class="p-4 border-t-2 border-black bg-gray-50 font-medium text-gray-700">
                        Silakan hubungi admin via WhatsApp atau datang langsung ke bagian PMB di kampus untuk reset password. Pastikan mengingat email yang didaftarkan.
                    </div>
                </div>

                <div x-data="{ open: false }" class="bg-white border-4 border-black rounded-xl overflow-hidden shadow-neo">
                    <button @click="open = !open" class="w-full text-left p-4 font-black text-lg text-unmaris-blue flex justify-between items-center hover:bg-yellow-50">
                        <span>Apakah ada beasiswa untuk mahasiswa baru?</span>
                        <span x-show="!open">➕</span><span x-show="open">➖</span>
                    </button>
                    <div x-show="open" class="p-4 border-t-2 border-black bg-gray-50 font-medium text-gray-700">
                        Tentu ada! UNMARIS menyediakan berbagai beasiswa seperti KIP-Kuliah, Beasiswa Yayasan, dan Beasiswa Prestasi. Informasi lebih lanjut bisa ditanyakan saat daftar ulang.
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-white border-t-4 border-black py-10 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15'" class="h-12 w-12 border-2 border-black rounded-full bg-white">
                    <div>
                        <h4 class="font-black text-unmaris-blue uppercase text-lg">Universitas Stella Maris Sumba</h4>
                        <p class="text-xs font-bold text-gray-500">Jl. Soekarno Hatta No.05, Tambolaka, NTT</p>
                    </div>
                </div>
                
                <div class="text-sm font-bold text-gray-500 text-center md:text-right">
                    &copy; {{ date('Y') }} PMB UNMARIS. All rights reserved.<br>
                    <span class="text-xs">Built with ❤️ & Laravel</span>
                </div>
            </div>
        </footer>

        <!-- FLOATING WA BUTTON -->
        <a href="https://wa.me/6281234567890" target="_blank" class="fixed bottom-6 right-6 z-50 bg-green-500 text-white p-4 rounded-full border-4 border-black shadow-neo-lg hover:scale-110 transition-transform flex items-center justify-center group">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span class="ml-2 font-black hidden group-hover:block transition-all">Chat Admin</span>
        </a>

        <!-- Alpine.js untuk FAQ Toggle -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>