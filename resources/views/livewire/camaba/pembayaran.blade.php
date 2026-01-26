<div class="max-w-4xl mx-auto py-10 font-sans">
    
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 2px 2px 0px #FACC15;">
            💸 Administrasi Pendaftaran
        </h1>
        <p class="text-unmaris-blue font-bold mt-2">Lakukan pembayaran untuk validasi formulir.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- INFO REKENING (KIRI) -->
        <div class="space-y-6">
            <div class="bg-unmaris-blue text-white p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 4v10h16V8H4zm2 2h12v2H6v-2z"/></svg>
                </div>
                
                <h3 class="font-bold text-unmaris-yellow uppercase tracking-widest text-sm mb-1">Total Tagihan</h3>
                <div class="text-4xl font-black mb-6">Rp {{ number_format($biaya_pendaftaran, 0, ',', '.') }}</div>
                <p class="text-xs uppercase font-bold text-gray-300 mb-2">Silakan transfer ke salah satu rekening:</p>

                <!-- LIST REKENING -->
                <div class="space-y-3">
                    @foreach($bank_accounts as $b)
                    <div class="bg-white/10 p-3 rounded-lg border border-white/20 backdrop-blur-sm flex justify-between items-center group hover:bg-white/20 transition">
                        <div>
                            <div class="text-xs font-bold text-yellow-300 uppercase">{{ $b['bank'] }}</div>
                            <div class="text-xl font-black tracking-wider font-mono">{{ $b['rekening'] }}</div>
                            <div class="text-[10px] font-bold text-gray-300">a.n {{ $b['atas_nama'] }}</div>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $b['rekening'] }}'); alert('No. Rekening Disalin!')" 
                                class="bg-black/40 hover:bg-black/60 text-white p-2 rounded-lg transition" title="Salin No. Rekening">
                            📋
                        </button>
                    </div>
                    @endforeach

                    @if(empty($bank_accounts))
                        <div class="p-3 text-center text-sm font-bold text-red-300 bg-red-900/20 rounded border border-red-500/50">
                            Belum ada data rekening aktif. Hubungi Admin.
                        </div>
                    @endif
                </div>

                <div class="flex items-center text-xs font-bold text-yellow-300 bg-black/20 p-2 rounded mt-4">
                    <span class="mr-2">💡</span>
                    <span>Pastikan nominal transfer sesuai hingga 3 digit terakhir.</span>
                </div>
            </div>
        </div>

        <!-- FORM UPLOAD (KANAN) -->
        <div class="bg-white p-6 rounded-xl border-4 border-unmaris-blue shadow-neo h-fit">
            
            <h3 class="font-black text-unmaris-blue text-lg mb-4 uppercase border-b-2 border-unmaris-blue pb-2">
                📤 Konfirmasi Pembayaran
            </h3>

            @if(session('message'))
                <div class="bg-green-100 border-2 border-green-500 text-green-700 p-3 rounded font-bold mb-4 text-sm animate-pulse">
                    ✅ {{ session('message') }}
                </div>
            @endif

            <!-- CASE 1: LUNAS -->
            @if($pendaftar->status_pembayaran == 'lunas')
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="text-2xl font-black text-green-600 uppercase">PEMBAYARAN LUNAS</h2>
                    <p class="font-bold text-gray-500 text-sm mt-2">Terima kasih! Data Anda sedang diproses ke tahap seleksi.</p>
                </div>
            
            <!-- CASE 2: MENUNGGU VERIFIKASI -->
            @elseif($pendaftar->status_pembayaran == 'menunggu_verifikasi')
                <div x-data="{ gantiFile: false }">
                    <div x-show="!gantiFile" class="text-center py-8">
                        <div class="text-6xl mb-4 animate-pulse">⏳</div>
                        <h2 class="text-xl font-black text-yellow-600 uppercase">MENUNGGU VERIFIKASI</h2>
                        <p class="font-bold text-gray-500 text-sm mt-2">Admin sedang mengecek bukti transfer Anda.</p>
                        
                        <div class="mt-4 mb-6">
                            <img src="{{ asset('storage/'.$pendaftar->bukti_pembayaran) }}" class="h-32 mx-auto border-2 border-black rounded shadow-sm object-cover bg-gray-100">
                            <span class="text-xs text-gray-400 font-bold block mt-2">Bukti terkirim</span>
                        </div>

                        <button @click="gantiFile = true" class="text-xs font-bold text-red-500 hover:text-red-700 underline decoration-2 cursor-pointer">
                            ⚠️ Salah kirim bukti? Upload ulang disini
                        </button>
                    </div>

                    <!-- Form Ganti File (Inline Re-upload) -->
                    <div x-show="gantiFile" x-transition class="mt-4">
                        @include('livewire.camaba.partials.inline-upload-logic')
                        <button type="button" @click="gantiFile = false" class="mt-2 w-full py-2 rounded-lg border-2 border-gray-400 font-bold text-gray-600 hover:bg-gray-100 text-xs uppercase">
                            Batal Ganti
                        </button>
                    </div>
                </div>

            <!-- CASE 3: BELUM BAYAR / DITOLAK -->
            @else
                @if($pendaftar->status_pembayaran == 'ditolak')
                    <div class="bg-red-100 border-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-center shadow-sm">
                        <div class="text-3xl mb-2">❌</div>
                        <h4 class="font-black text-lg uppercase">Pembayaran Ditolak</h4>
                        <p class="text-sm font-bold mt-1">Bukti tidak valid. Silakan upload ulang.</p>
                    </div>
                @endif

                <!-- Upload Form Utama -->
                @include('livewire.camaba.partials.inline-upload-logic')
            @endif

        </div>
    </div>
</div>