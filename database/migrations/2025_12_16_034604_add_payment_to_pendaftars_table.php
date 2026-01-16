<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
    <!-- Header with dynamic background based on status -->
    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm uppercase tracking-wider">
            <span>💳</span> Verifikasi Pembayaran
        </h3>
        <!-- Status Badge (Modern Pill Style) -->
         @if($pendaftar->status_pembayaran == 'lunas')
            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-green-100 text-green-700 flex items-center gap-1 border border-green-200">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                SUDAH LUNAS
            </span>
        @elseif($pendaftar->status_pembayaran == 'menunggu_verifikasi')
            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-yellow-100 text-yellow-700 flex items-center gap-1 animate-pulse border border-yellow-200">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                BUTUH VERIFIKASI
            </span>
        @elseif($pendaftar->status_pembayaran == 'ditolak')
            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-red-100 text-red-700 border border-red-200">DITOLAK</span>
        @else
            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-500 border border-gray-200">BELUM BAYAR</span>
        @endif
    </div>

    <div class="p-6">
        <!-- Flash Messages (Soft Style) -->
        @if (session()->has('success'))
            <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2 border border-green-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2 border border-red-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left: Evidence Image -->
            <div class="w-full md:w-1/2">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bukti Transfer</h4>
                @if($pendaftar->bukti_pembayaran)
                    <div class="group relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50 aspect-video cursor-zoom-in shadow-sm" onclick="window.open('{{ asset('storage/'.$pendaftar->bukti_pembayaran) }}', '_blank')">
                        <img src="{{ asset('storage/'.$pendaftar->bukti_pembayaran) }}" class="w-full h-full object-cover transition transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                            <span class="text-white text-xs font-bold border border-white/50 px-3 py-1 rounded-full backdrop-blur-sm">🔍 Perbesar Gambar</span>
                        </div>
                    </div>
                @else
                     <div class="h-32 rounded-lg border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                        <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-xs font-medium">Belum ada bukti upload</span>
                    </div>
                @endif
            </div>

            <!-- Right: Actions & Info -->
            <div class="w-full md:w-1/2 flex flex-col justify-between">
                <div>
                     <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi</h4>
                     <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        @if($pendaftar->status_pembayaran == 'lunas')
                            <p class="font-medium text-blue-800">✅ Pembayaran Valid</p>
                            <p class="text-xs mt-1">Mahasiswa sudah lunas dan dapat melanjutkan ke tahap berikutnya.</p>
                        @else
                            <p class="font-medium text-blue-800">ℹ️ Cek Nominal</p>
                            <p class="text-xs mt-1">Pastikan nominal sesuai dengan tagihan <strong>Rp {{ number_format($settings->biaya_pendaftaran ?? 200000, 0, ',', '.') }}</strong>.</p>
                        @endif
                     </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    @if($pendaftar->status_pembayaran != 'lunas')
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="approve" wire:loading.attr="disabled" class="bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg text-sm font-bold shadow-md shadow-emerald-200 transition-all transform active:scale-95 flex justify-center items-center gap-2">
                                <span wire:loading.remove wire:target="approve">Terima Pembayaran</span>
                                <span wire:loading wire:target="approve">Memproses...</span>
                            </button>
                            <button wire:click="reject" wire:loading.attr="disabled" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 py-2.5 rounded-lg text-sm font-bold transition-all flex justify-center items-center gap-2">
                                <span wire:loading.remove wire:target="reject">Tolak</span>
                                <span wire:loading wire:target="reject">...</span>
                            </button>
                        </div>
                    @else
                         <button wire:click="reject" onclick="return confirm('Batalkan status lunas?')" class="w-full py-2 text-xs text-gray-400 hover:text-red-600 font-bold transition text-center underline decoration-dotted">
                            Batalkan Status Lunas
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>