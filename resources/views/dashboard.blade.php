<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900 text-center py-10 max-w-3xl mx-auto">

        @if (auth()->user()->pendaftar && auth()->user()->pendaftar->status_pembayaran == 'belum_bayar')
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 flex justify-between items-center">
                <div>
                    <p class="font-bold">⚠️ Menunggu Pembayaran</p>
                    <p class="text-sm">Anda belum melakukan pembayaran pendaftaran.</p>
                </div>
                <a href="{{ route('camaba.pembayaran') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded font-bold hover:bg-red-700 shadow-sm text-sm">
                    Bayar Sekarang
                </a>
            </div>
        @endif
        @if (auth()->user()->pendaftar)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Status Pendaftaran: {{ ucfirst(auth()->user()->pendaftar->status_pendaftaran) }}
                </p>
                <p>Data Anda sedang diproses oleh admin.</p>
            </div>
            <a href="{{ route('camaba.cetak-kartu') }}" target="_blank"
                class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                <span>Cetak Kartu Ujian</span>
            </a>
        @else
            <div class="text-center py-10">
                <h3 class="text-2xl font-bold mb-2">Selamat Datang di PMB UNMARIS</h3>
                <p class="mb-6 text-gray-600">Silakan lengkapi formulir pendaftaran untuk memulai proses seleksi.</p>
                <a href="{{ route('camaba.formulir') }}"
                    class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    Isi Formulir Pendaftaran Sekarang
                </a>
            </div>
        @endif


    </div>
</x-app-layout>
