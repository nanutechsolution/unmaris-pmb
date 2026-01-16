<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal Camaba UNMARIS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- icon --}}
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    </head>
    <body class="font-sans antialiased bg-yellow-50">
        <!-- Root Alpine State -->
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex relative">
            
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/50 z-40 md:hidden"
                 style="display: none;">
            </div>

            <!-- SIDEBAR CAMABA -->
            <x-camaba-sidebar />

            <!-- Main Content -->
            <div class="flex-1 w-full ml-0 md:ml-64 transition-all duration-300">
                
                <!-- Topbar -->
                <header class="bg-white border-b-4 border-black shadow-sm h-16 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30">
                    
                    <!-- Hamburger (Mobile) -->
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-unmaris-blue focus:outline-none hover:text-yellow-500 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <h2 class="font-black text-lg md:text-xl text-unmaris-blue uppercase tracking-tight ml-2 md:ml-0">
                        {{ $header ?? 'Portal Mahasiswa' }}
                    </h2>
                    
                    <!-- User Profile -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <div class="text-right hidden md:block">
                            <div class="font-black text-sm text-unmaris-blue truncate max-w-[150px]">{{ Auth::user()->name }}</div>
                            <div class="text-xs font-bold text-gray-500 uppercase">Calon Mahasiswa</div>
                        </div>
                        <div class="h-8 w-8 md:h-10 md:w-10 rounded-full bg-blue-100 border-2 border-black shadow-neo-sm overflow-hidden">
                            @if(Auth::user()->pendaftar && Auth::user()->pendaftar->foto_path)
                                <img src="{{ asset('storage/'.Auth::user()->pendaftar->foto_path) }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1e3a8a&color=facc15" alt="Camaba">
                            @endif
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-4 md:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>