<x-camaba-layout>
    <div class="py-12 font-sans">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- CASE 1: LULUS (THE HAPPY PATH) -->
            @if($pendaftar->status_pendaftaran == 'lulus')
                <div class="bg-white border-4 border-unmaris-green shadow-neo-lg rounded-3xl p-8 md:p-12 text-center relative overflow-hidden animate-fade-in-up">
                    
                    <!-- Confetti Decoration -->
                    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-20" 
                         style="background-image: radial-gradient(#16A34A 2px, transparent 2px); background-size: 30px 30px;">
                    </div>

                    <div class="relative z-10">
                        <div class="inline-block bg-unmaris-green text-white font-black text-xl px-4 py-1 transform -rotate-2 border-2 border-unmaris-blue shadow-neo mb-6">
                            🎉 PENGUMUMAN RESMI
                        </div>

                        <h1 class="text-4xl md:text-6xl font-black text-unmaris-blue mb-4 uppercase tracking-tighter">
                            SELAMAT! <br> ANDA DITERIMA.
                        </h1>
                        
                        <p class="text-lg md:text-xl font-bold text-gray-600 mb-8 max-w-2xl mx-auto">
                            Selamat datang di keluarga besar <span class="text-unmaris-blue">Universitas Stella Maris Sumba</span>. Perjalanan masa depanmu dimulai hari ini!
                        </p>

                        <div class="bg-blue-50 border-2 border-unmaris-blue rounded-xl p-6 mb-8 max-w-md mx-auto transform rotate-1">
                            <p class="text-sm font-bold text-gray-500 uppercase">Diterima pada Program Studi:</p>
                            <p class="text-2xl font-black text-unmaris-blue mt-1">{{ $pendaftar->pilihan_prodi_1 }}</p>
                        </div>

                        <div class="flex flex-col md:flex-row justify-center gap-4">
                            <!-- Tombol Download Surat -->
                            <a href="{{ route('camaba.pengumuman.cetak') }}" class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-4 px-8 rounded-xl border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[4px] hover:translate-y-[4px] transition-all flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                DOWNLOAD SURAT KELULUSAN (PDF)
                            </a>
                        </div>
                    </div>
                </div>

            <!-- CASE 2: TIDAK LULUS -->
            @elseif($pendaftar->status_pendaftaran == 'gagal')
                <div class="bg-white border-4 border-gray-400 shadow-neo rounded-3xl p-12 text-center grayscale">
                    <h1 class="text-4xl font-black text-gray-600 mb-4 uppercase">Mohon Maaf</h1>
                    <p class="text-lg font-bold text-gray-500 mb-6">
                        Berdasarkan hasil seleksi, Anda dinyatakan <span class="text-red-600 underline">TIDAK LULUS</span> seleksi masuk UNMARIS tahun ini.
                    </p>
                    <p class="text-gray-400">Jangan patah semangat. Masih banyak jalan menuju kesuksesan!</p>
                </div>

            <!-- CASE 3: MENUNGGU / VERIFIKASI -->
            @else
                <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-3xl p-12 text-center">
                    <div class="animate-bounce-slight mb-6 text-6xl">⏳</div>
                    <h1 class="text-3xl font-black text-unmaris-blue mb-4 uppercase">Sedang Diproses</h1>
                    <p class="text-lg font-bold text-gray-600 mb-2">
                        Data pendaftaran Anda sedang dalam tahap verifikasi oleh Panitia PMB.
                    </p>
                    <p class="text-sm bg-yellow-100 inline-block px-3 py-1 rounded text-yellow-800 font-bold border border-yellow-300">
                        Status Saat Ini: {{ strtoupper($pendaftar->status_pendaftaran) }}
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-camaba-layout>