<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

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
                class="h-16 w-16 mx-auto drop-shadow-[2px_2px_0px_rgba(0,0,0,1)] rounded-full border-2 border-black bg-white">
        </div>
        <h2 class="mt-3 font-black text-unmaris-blue text-2xl tracking-tighter uppercase leading-none drop-shadow-sm">
            ADMIN<br>UNMARIS
        </h2>
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


        <!-- Separator -->
        <div class="border-t-2 border-white/20 my-2"></div>

        <!-- Logout -->
        <button type="submit" wire:click="logout"
            class="w-full flex items-center px-4 py-3 font-black border-2 border-black rounded-lg bg-red-500 text-white hover:bg-red-600 hover:shadow-neo transition-all transform hover:-translate-y-1">
            <span class="text-xl mr-3">🚪</span>
            Keluar
        </button>

    </nav>

    <!-- Footer -->
    <div
        class="p-4 text-center text-[10px] font-bold text-white/50 uppercase tracking-widest border-t-4 border-black bg-black">
        UNMARIS Admin System v2.0
    </div>
</div>
