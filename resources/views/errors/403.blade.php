<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditolak - PMB UNMARIS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .shadow-neo { box-shadow: 6px 6px 0px 0px #000; }
        .text-unmaris-blue { color: #1e3a8a; }
        .bg-unmaris-yellow { background-color: #facc15; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-black min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10" 
         style="background-image: repeating-linear-gradient(45deg, #000 0, #000 1px, transparent 0, transparent 50%); background-size: 10px 10px;">
    </div>

    <!-- Floating Security Icons -->
    <div class="absolute top-20 right-20 text-8xl opacity-10 rotate-12">🔒</div>
    <div class="absolute bottom-20 left-20 text-8xl opacity-10 -rotate-12">🚫</div>

    <div class="max-w-md w-full px-6 relative z-10">
        
        <div class="bg-white border-4 border-black shadow-neo rounded-3xl p-8 text-center relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
            
            <!-- Police Line -->
            <div class="absolute top-4 -left-10 -right-10 bg-yellow-400 text-black font-black text-xs uppercase py-1 transform -rotate-3 border-y-2 border-black tracking-widest overflow-hidden">
                DILARANG MASUK &bull; RESTRICTED AREA &bull; DILARANG MASUK &bull; RESTRICTED AREA &bull; DILARANG MASUK
            </div>

            <!-- Icon Utama -->
            <div class="mb-6 mt-8 relative inline-block">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center border-4 border-black relative z-10">
                    <span class="text-5xl">✋</span>
                </div>
                <!-- Shadow dot -->
                <div class="absolute -bottom-2 -right-2 w-full h-full bg-black rounded-full -z-10"></div>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl font-black text-black uppercase leading-none mb-2">
                403<br><span class="text-red-600 text-2xl">Akses Ditolak!</span>
            </h1>
            
            <p class="text-gray-600 font-bold text-sm mb-8 leading-relaxed mt-4 bg-gray-50 p-3 rounded-xl border-2 border-gray-200">
                Ups, maaf ya! Kamu tidak punya izin untuk masuk ke area ini. Ini khusus untuk Admin atau peran tertentu saja.
            </p>

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ url('/') }}" class="block w-full bg-unmaris-blue text-white font-black py-3 rounded-xl border-2 border-black hover:bg-blue-900 transition shadow-[4px_4px_0px_0px_#000] active:translate-y-1 active:shadow-none uppercase tracking-wider text-sm">
                    🏠 Kembali ke Halaman Depan
                </a>

                <button onclick="history.back()" class="block w-full bg-white text-black font-black py-3 rounded-xl border-2 border-black hover:bg-gray-50 transition shadow-[4px_4px_0px_0px_#ccc] active:translate-y-1 active:shadow-none uppercase tracking-wider text-sm">
                    🔙 Kembali Saja
                </button>
            </div>

            <div class="mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Error Code: 403 Forbidden
            </div>

        </div>

    </div>

</body>
</html>