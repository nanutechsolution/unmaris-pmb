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

<!-- Sidebar Camaba: Menggunakan tema Putih-Kuning agar beda dengan Admin (Biru) -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed left-0 top-0 h-screen w-64 bg-white text-unmaris-blue border-r-4 border-black overflow-y-auto z-50 flex flex-col shadow-[4px_0px_0px_0px_rgba(0,0,0,1)] transition-transform duration-300 ease-in-out transform md:translate-x-0">

    <!-- Tombol Close (Mobile Only) -->
    <button @click="sidebarOpen = false"
        class="md:hidden absolute top-2 right-2 text-unmaris-blue hover:text-red-500 p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Logo Area -->
    <div class="p-6 text-center border-b-4 border-black bg-unmaris-blue relative">
        <div class="inline-block relative transform hover:scale-110 transition duration-300">
            <img src="{{ asset('images/logo.png') }}"
                onerror="this.src='https://ui-avatars.com/api/?name=UNMARIS&background=1e3a8a&color=facc15&size=128'"
                class="h-16 w-16 mx-auto drop-shadow-[2px_2px_0px_rgba(0,0,0,1)] rounded-full border-2 border-black bg-white">
        </div>
        <h2 class="mt-3 font-black text-unmaris-yellow text-xl tracking-tighter uppercase leading-none drop-shadow-sm">
            PORTAL<br>CAMABA
        </h2>
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 px-4 py-6 space-y-4">

        <!-- Dashboard -->
        <a href="{{ route('camaba.dashboard') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo 
           {{ request()->routeIs('camaba.dashboard')
               ? 'bg-unmaris-yellow text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-white text-unmaris-blue hover:bg-blue-50' }}">
            <span class="text-xl mr-3">🏠</span>
            Beranda
        </a>

        <!-- Formulir -->
        <a href="{{ route('camaba.formulir') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
           {{ request()->routeIs('camaba.formulir')
               ? 'bg-unmaris-yellow text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-white text-unmaris-blue hover:bg-blue-50' }}">
            <span class="text-xl mr-3">📝</span>
            Isi Formulir
        </a>

        <!-- Pembayaran -->
        <a href="{{ route('camaba.pembayaran') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
           {{ request()->routeIs('camaba.pembayaran')
               ? 'bg-unmaris-yellow text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-white text-unmaris-blue hover:bg-blue-50' }}">
            <span class="text-xl mr-3">💸</span>
            Pembayaran
        </a>

        <!-- Cetak Kartu -->
        @php
            // Cek sederhana apakah user bisa cetak kartu (sudah bayar & jadwal ada)
            $p = auth()->user()->pendaftar;
            $bisaCetak = $p && $p->status_pembayaran == 'lunas' && $p->jadwal_ujian;
        @endphp
        <a href="{{ $bisaCetak ? route('camaba.cetak-kartu') : '#' }}" target="{{ $bisaCetak ? '_blank' : '_self' }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
           {{ $bisaCetak ? 'bg-white text-unmaris-blue hover:bg-blue-50' : 'bg-gray-200 text-gray-400 cursor-not-allowed border-gray-400' }}">
            <span class="text-xl mr-3">🎫</span>
            Kartu Ujian
        </a>

        <!-- Pengumuman -->
        <a href="{{ route('camaba.pengumuman') }}"
            class="flex items-center px-4 py-3 font-black border-2 border-black rounded-lg transition-all transform hover:-translate-y-1 hover:shadow-neo
           {{ request()->routeIs('camaba.pengumuman')
               ? 'bg-unmaris-yellow text-unmaris-blue shadow-neo translate-x-1'
               : 'bg-white text-unmaris-blue hover:bg-blue-50' }}">
            <span class="text-xl mr-3">📢</span>
            Pengumuman
        </a>

        <!-- Separator -->
        <div class="border-t-2 border-black/10 my-2"></div>

        <!-- Logout -->
        <button wire:click="logout"
            class="w-full flex items-center px-4 py-3 font-black border-2 border-black rounded-lg bg-red-500 text-white hover:bg-red-600 hover:shadow-neo transition-all transform hover:-translate-y-1">
            <span class="text-xl mr-3">🚪</span>
            Keluar
        </button>

    </nav>

    <!-- Footer -->
    <div
        class="p-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-t-4 border-black bg-gray-50">
        © 2025 Portal Mahasiswa
    </div>
</div>
