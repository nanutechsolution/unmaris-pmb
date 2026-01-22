<!-- Menggunakan Alpine logic untuk class binding -->

<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed left-0 top-0 h-screen w-64 bg-unmaris-blue text-white border-r-4 border-black overflow-y-auto z-50 flex flex-col shadow-[4px_0px_0px_0px_rgba(0,0,0,0.5)] transition-transform duration-300 ease-out transform md:translate-x-0">

    <!-- Tombol Close (Hanya di Mobile) -->
    <button @click="sidebarOpen = false" class="md:hidden absolute top-3 right-3 text-white hover:text-yellow-400 p-2 transition-colors z-50">
        <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div class="p-6 border-b border-white/10 bg-unmaris-blue relative">
        <div class="flex items-center gap-4">
            <!-- Logo -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt"></div>
                <img src="{{ asset('images/logo.png') }}"
                    onerror="this.src='https://ui-avatars.com/api/?name=UN&background=1e3a8a&color=facc15&size=128'"
                    class="relative h-12 w-12 rounded-full border-2 border-black bg-white object-contain shadow-sm">
                <!-- Status Dot -->
                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-500 ring-2 ring-unmaris-blue"></span>
            </div>
            
            <!-- Teks -->
            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-black text-white leading-none tracking-tight">
                    ADMIN<span class="text-yellow-400">PANEL</span>
                </h2>
                <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest mt-1 truncate">
                    {{ Auth::user()->role }}
                </p>
            </div>
        </div>
    </div>

    <!-- Menu Items Container -->
    <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto custom-scrollbar">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="group relative flex items-center px-3 py-2.5 font-black text-xs uppercase tracking-wide border-2 border-black rounded-xl transition-all duration-200 ease-out
           {{ request()->routeIs('admin.dashboard')
               ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
               : 'bg-unmaris-blue text-white shadow-none hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
            <span class="text-xl mr-3 group-hover:rotate-12 transition-transform duration-300">⚡</span>
            <span>Command Center</span>
            
            @if(request()->routeIs('admin.dashboard'))
                <span class="absolute right-2 w-1.5 h-1.5 bg-red-500 rounded-full animate-ping"></span>
            @endif
        </a>

        <!-- GROUP: PENDAFTARAN -->
        <div class="pt-4 pb-1 px-2 flex items-center gap-2">
            <div class="h-px bg-white/20 flex-1"></div>
            <p class="text-[9px] text-yellow-400 font-black uppercase tracking-widest">Pendaftaran</p>
            <div class="h-px bg-white/20 flex-1"></div>
        </div>

        <a href="{{ route('admin.pendaftar.index') }}"
            class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
           {{ request()->routeIs('admin.pendaftar*')
               ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
               : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
            <span class="text-lg mr-3 group-hover:scale-110 transition-transform">📂</span>
            Data Pendaftar
        </a>

        <!-- ROLE: KEUANGAN & ADMIN (Laporan Keuangan & Referral) -->
        @if(in_array(Auth::user()->role, ['admin', 'keuangan']))
            <a href="{{ route('admin.payment-report') }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
               {{ request()->routeIs('admin.payment-report')
                   ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
                   : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">💰</span>
                Laporan Keuangan
            </a>

            <a href="{{ route('admin.referral') }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
               {{ request()->routeIs('admin.referral')
                   ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
                   : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🤝</span>
                Data Referral
            </a>
        @endif

        <a href="{{ route('admin.beasiswa.index') }}"
            class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
           {{ request()->routeIs('admin.beasiswa*')
               ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
               : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
            <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🎓</span>
            Beasiswa
        </a>

        <!-- ROLE: AKADEMIK & ADMIN (Seleksi & Master Data) -->
        @if (in_array(Auth::user()->role, ['admin', 'akademik']))
            <div class="pt-4 pb-1 px-2 flex items-center gap-2">
                <div class="h-px bg-white/20 flex-1"></div>
                <p class="text-[9px] text-yellow-400 font-black uppercase tracking-widest">Akademik</p>
                <div class="h-px bg-white/20 flex-1"></div>
            </div>

            <a href="{{ route('admin.gelombang.index') ?? '#' }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
            {{ request()->routeIs('admin.gelombang*')
                ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
                : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
                <span class="text-lg mr-3 group-hover:animate-wave">🌊</span>
                Gelombang PMB
            </a>
            
             <a href="{{ route('admin.prodi.index') ?? '#' }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
            {{ request()->routeIs('admin.prodi*')
                ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
                : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🏛️</span>
                Program Studi
            </a>

            <a href="{{ route('admin.seleksi.index') }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
       {{ request()->routeIs('admin.seleksi*')
           ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
           : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🎯</span>
                Seleksi & Nilai
            </a>
            
            <a href="{{ route('admin.wawancara.index') }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200
       {{ request()->routeIs('admin.wawancara*')
           ? 'bg-white text-unmaris-blue shadow-[3px_3px_0px_0px_#FACC15] translate-x-0.5'
           : 'bg-unmaris-blue text-white/90 hover:bg-yellow-400 hover:text-unmaris-blue hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🎤</span>
                Wawancara
            </a>
        @endif

        <!-- ROLE: SUPER ADMIN ONLY (System & Users) -->
        @if (Auth::user()->role === 'admin')
            <div class="pt-4 pb-1 px-2 flex items-center gap-2">
                <div class="h-px bg-white/20 flex-1"></div>
                <p class="text-[9px] text-yellow-400 font-black uppercase tracking-widest">System Area</p>
                <div class="h-px bg-white/20 flex-1"></div>
            </div>

            <!-- Fasilitas & Slider (Featured) -->
            <a href="{{ route('admin.facilities') }}"
               class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200 bg-gray-800 text-white hover:bg-white hover:text-gray-900 hover:shadow-[3px_3px_0px_0px_#FACC15] hover:-translate-y-0.5
               {{ request()->routeIs('admin.facilities*') ? 'ring-2 ring-yellow-400 ring-offset-2 ring-offset-unmaris-blue' : '' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🏢</span>
                Slider & Fasilitas
            </a>

            <!-- Settings (Featured) -->
            <a href="{{ route('admin.settings') }}"
                class="group flex items-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl transition-all duration-200 bg-gray-800 text-white hover:bg-white hover:text-gray-900 hover:shadow-[3px_3px_0px_0px_#FACC15] hover:-translate-y-0.5
       {{ request()->routeIs('admin.settings*') ? 'ring-2 ring-yellow-400 ring-offset-2 ring-offset-unmaris-blue' : '' }}">
                <span class="text-lg mr-3 group-hover:rotate-90 transition-transform duration-500">⚙️</span>
                Web Settings
            </a>

            <!-- Dropdown Menu Lainnya -->
            <div x-data="{ open: false }" class="mt-2 border-2 border-black rounded-xl bg-unmaris-blue/50 overflow-hidden">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-[10px] font-black uppercase tracking-wider text-white/80 hover:text-white hover:bg-white/10 transition">
                    <span class="flex items-center gap-2"><span class="text-sm">🛠️</span> Menu Lainnya</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-1 p-2 bg-black/20">
                    
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/10 rounded flex items-center gap-2 transition">
                        <span>👥</span> User Management
                    </a>
                    <a href="{{ route('admin.helpdesk.index') }}" class="block px-3 py-2 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/10 rounded flex items-center gap-2 transition">
                        <span>💬</span> Helpdesk
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="block px-3 py-2 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/10 rounded flex items-center gap-2 transition">
                        <span>🖨️</span> Laporan
                    </a>
                    <a href="{{ route('admin.geographic.index') }}" class="block px-3 py-2 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/10 rounded flex items-center gap-2 transition">
                        <span>🌍</span> Peta Sebaran
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="block px-3 py-2 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/10 rounded flex items-center gap-2 transition">
                        <span>📢</span> Pengumuman
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="block px-3 py-2 text-[10px] font-bold text-gray-300 hover:text-white hover:bg-white/10 rounded flex items-center gap-2 transition">
                        <span>🕵️‍♂️</span> Log Aktivitas
                    </a>
                </div>
            </div>
            
        @endif

        <!-- Logout Area -->
        <div class="pt-4 pb-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="group w-full flex items-center justify-center px-3 py-2.5 font-black text-xs uppercase border-2 border-black rounded-xl bg-red-600 text-white hover:bg-red-500 hover:shadow-[3px_3px_0px_0px_#000] hover:-translate-y-0.5 transition-all duration-200">
                    <span class="text-lg mr-2 group-hover:rotate-180 transition-transform duration-500">🚪</span>
                    Keluar Sistem
                </button>
            </form>
        </div>

    </nav>

    <!-- Footer Info -->
    <div class="p-3 text-center border-t-4 border-black bg-black">
        <p class="text-[9px] font-black text-white/40 uppercase tracking-widest">
            UNMARIS SYSTEM v2.0
        </p>
        <p class="text-[8px] text-white/20 mt-1">&copy; {{ date('Y') }} IT Dev Team</p>
    </div>
</div>