<!-- Menggunakan Alpine logic untuk class binding -->

<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed left-0 top-0 h-screen w-64 bg-unmaris-blue text-white border-r-4 border-black overflow-y-auto z-50 flex flex-col shadow-[4px_0px_0px_0px_rgba(0,0,0,1)] transition-transform duration-300 ease-in-out transform md:translate-x-0">

    <!-- Tombol Close (Hanya di Mobile) -->
    <button @click="sidebarOpen = false" class="md:hidden absolute top-2 right-2 text-white hover:text-yellow-400 p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Logo Area -->
    <div class="p-6 text-center border-b-4 border-black bg-unmaris-yellow relative">
        <div class="inline-block relative transform hover:scale-110 transition duration-300">
            <img src="{{ asset('images/logo.png') }}"
                onerror="this.src='https://ui-avatars.com/api/?name=UNMARIS&background=1e3a8a&color=facc15&size=128'"
                class="h-16 w-16 mx-auto drop-shadow-[2px_2px_0px_rgba(0,0,0,1)] rounded-full border-2 bg-unmaris-blue ">
        </div>
        <h2 class="mt-3 font-black text-unmaris-blue text-2xl tracking-tighter uppercase leading-none drop-shadow-sm">
            ADMIN<br>UNMARIS
        </h2>
        <span
            class="text-xs font-bold bg-white px-2 py-0.5 rounded border border-black uppercase mt-1 inline-block text-unmaris-blue">
            Role: {{ Auth::user()->role }}
        </span>
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 px-4 py-6 space-y-4">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo 
           {{ request()->routeIs('admin.dashboard')
               ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
            <span class="text-xl mr-3">⚡</span>
            Command Center
        </a>

        <!-- Data Pendaftar -->
        <a href="{{ route('admin.pendaftar.index') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
           {{ request()->routeIs('admin.pendaftar*')
               ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
            <span class="text-xl mr-3">📂</span>
            Data Pendaftar
        </a>

        <a href="{{ route('admin.beasiswa.index') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo opacity-80 hover:opacity-100
           {{ request()->routeIs('admin.beasiswa*')
               ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
            <span class="text-xl mr-3">🎓</span>
            Beasiswa
        </a>

        <!-- SELEKSI (Hanya Akademik & Admin) -->
        @if (in_array(Auth::user()->role, ['admin', 'akademik']))
            <!-- Manajemen Gelombang -->
            <a href="{{ route('admin.gelombang.index') ?? '#' }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
            {{ request()->routeIs('admin.gelombang*')
                ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
                : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">🌊</span>
                Gelombang PMB
            </a>

            <a href="{{ route('admin.seleksi.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.seleksi*')
           ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
           : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">🎯</span>
                Seleksi & Nilai
            </a>
            <a href="{{ route('admin.wawancara.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.wawancara*')
           ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
           : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">🎤</span>
                Wawancara
            </a>
        @endif

        <!-- TEKNIS (Hanya Super Admin) -->
        @if (Auth::user()->role === 'admin')
            <div class="border-t-2 border-white/20 my-2"></div>
            <p class="px-4 text-[10px] text-white/50 font-bold uppercase">System Area</p>

            <!-- Manajemen User -->
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.users*')
           ? 'bg-white text-unmaris-blue shadow-neo translate-x-1'
           : 'bg-gray-800 text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">👥</span>
                Akun User
            </a>

            <a href="{{ route('admin.laporan.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.laporan*') ? 'bg-white text-unmaris-blue shadow-neo translate-x-1' : 'bg-gray-800 text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">🖨️</span> Laporan
            </a>
            <a href="{{ route('admin.geographic.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
   {{ request()->routeIs('admin.geographic*') ? 'bg-white text-unmaris-blue shadow-neo translate-x-1' : 'bg-gray-800 text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">🌍</span> Peta Sebaran
            </a>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.settings*') ? 'bg-white text-unmaris-blue shadow-neo translate-x-1' : 'bg-gray-800 text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">⚙️</span> Settings
            </a>

            <a href="{{ route('admin.announcements.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.announcements*') ? 'bg-white text-unmaris-blue shadow-neo translate-x-1' : 'bg-unmaris-blue text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">📢</span> Pengumuman
            </a>

            <a href="{{ route('admin.logs.index') }}"
                class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
       {{ request()->routeIs('admin.logs*') ? 'bg-white text-unmaris-blue shadow-neo translate-x-1' : 'bg-gray-800 text-white hover:bg-yellow-400 hover:text-unmaris-blue' }}">
                <span class="text-xl mr-3">🕵️‍♂️</span> Log Aktivitas
            </a>
        @endif

        <!-- Separator -->
        <div class="border-t-2 border-white/20 my-2"></div>

        <!-- Logout Form (Fixed) -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-4 py-3 font-black border-2 border-black rounded-lg bg-red-500 text-white hover:bg-red-600 hover:shadow-neo transition-all transform hover:-translate-y-1">
                <span class="text-xl mr-3">🚪</span>
                Keluar
            </button>
        </form>

    </nav>

    <!-- Footer -->
    <div
        class="p-4 text-center text-[10px] font-bold text-white/50 uppercase tracking-widest border-t-4 border-black bg-black">
        UNMARIS Admin System v2.0
    </div>
</div>
