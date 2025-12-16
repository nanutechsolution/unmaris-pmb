<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PMB UNMARIS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-yellow-50 selection:bg-unmaris-blue selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 p-6">
            
            <!-- Logo Branding -->
            <div class="mb-8 text-center">
                <a href="/" wire:navigate class="inline-block group">
                    <div class="relative transform transition group-hover:scale-110 duration-300">
                        <div class="absolute inset-0 bg-unmaris-yellow rounded-full blur-xl opacity-60"></div>
                        <img src="{{ asset('images/logo.png') }}" 
                             onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15&size=128'"
                             class="h-24 w-24 border-4 border-black rounded-full bg-white relative z-10 shadow-sm">
                    </div>
                    <h1 class="mt-4 font-black text-3xl text-unmaris-blue uppercase tracking-tighter" style="text-shadow: 2px 2px 0px #FACC15;">
                        PMB UNMARIS
                    </h1>
                </a>
            </div>

            <!-- Card Container (Neo-Brutalist) -->
            <div class="w-full sm:max-w-md px-8 py-10 bg-white border-4 border-black shadow-neo-lg rounded-3xl overflow-hidden relative">
                
                <!-- Dekorasi Sudut -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-unmaris-yellow rounded-bl-full border-b-4 border-l-4 border-black -mr-1 -mt-1 z-0"></div>
                <div class="absolute bottom-0 left-0 w-12 h-12 bg-unmaris-blue rounded-tr-full border-t-4 border-r-4 border-black -ml-1 -mb-1 z-0"></div>

                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer Link -->
            <div class="mt-8">
                <a href="/" class="text-sm font-bold text-gray-500 hover:text-unmaris-blue transition-colors flex items-center gap-1 group">
                    <span class="group-hover:-translate-x-1 transition-transform">👈</span> Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>
</html>