<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Sedang Pemeliharaan - PMB UNMARIS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts (Pastikan Vite berjalan atau ganti dengan CDN Tailwind jika di Production tanpa build step) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Fallback CSS jika Vite belum build */
        .shadow-neo { box-shadow: 4px 4px 0px 0px #000; }
        .text-unmaris-blue { color: #1e3a8a; }
        .bg-unmaris-yellow { background-color: #facc15; }
    </style>
</head>
<body class="font-sans antialiased bg-yellow-50 text-black min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.05]" 
         style="background-image: radial-gradient(#1e3a8a 1px, transparent 1px); background-size: 20px 20px;">
    </div>

    <!-- Floating Icons Decoration -->
    <div class="absolute top-10 left-10 text-6xl opacity-20 animate-bounce">🚧</div>
    <div class="absolute bottom-10 right-10 text-6xl opacity-20 animate-bounce" style="animation-delay: 1s;">⚙️</div>

    <div class="max-w-lg w-full px-6 relative z-10">
        
        <div class="bg-white border-4 border-black shadow-neo rounded-3xl p-8 text-center relative overflow-hidden">
            
            <!-- Top Bar Decor -->
            <div class="absolute top-0 left-0 w-full h-4 bg-stripes"></div>
            <style>
                .bg-stripes {
                    background: repeating-linear-gradient(
                        45deg,
                        #facc15,
                        #facc15 10px,
                        #000 10px,
                        #000 20px
                    );
                }
            </style>

            <!-- Logo -->
            <div class="mb-6 mt-4">
                <img src="{{ asset('images/logo.png') }}" 
                     onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15&size=128'"
                     class="h-20 w-20 mx-auto rounded-full border-4 border-black shadow-sm bg-white">
            </div>

            <!-- Headline -->
            <h1 class="text-3xl md:text-4xl font-black text-unmaris-blue uppercase leading-tight mb-2">
                Lagi <span class="bg-yellow-300 px-2 transform -rotate-2 inline-block border-2 border-black">Upgrade</span> Nih!
            </h1>
            
            <p class="text-gray-600 font-bold text-sm md:text-base mb-8 leading-relaxed">
                Sistem PMB UNMARIS sedang istirahat sebentar untuk peningkatan performa & fitur baru. Jangan khawatir, kami akan segera kembali.
            </p>

            <!-- Status Box -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-8">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                    <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Status: Maintenance</span>
                </div>
                <p class="text-xs font-bold text-gray-500">Estimasi selesai: Segera</p>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <button onclick="window.location.reload()" class="w-full bg-black text-white font-black py-3 rounded-xl hover:bg-gray-800 transition shadow-neo-sm transform hover:-translate-y-1 active:translate-y-0">
                    🔄 Coba Refresh Halaman
                </button>

                <div class="pt-4 border-t-2 border-dashed border-gray-300">
                    <p class="text-xs font-bold text-gray-400 mb-2 uppercase">Butuh Bantuan Mendesak?</p>
                    <a href="https://wa.me/6281216156883" class="inline-flex items-center justify-center gap-2 text-green-600 font-black hover:underline">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi Panitia (Pak Yolen)
                    </a>
                </div>
            </div>

        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">&copy; {{ date('Y') }} Tim IT UNMARIS</p>
        </div>

    </div>

</body>
</html>