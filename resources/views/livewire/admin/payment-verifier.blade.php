<div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6 mt-6">
    <h3 class="font-black text-unmaris-blue text-lg mb-4 uppercase border-b-2 border-unmaris-blue pb-2 flex items-center">
        💰 Status Pembayaran
    </h3>

    <!-- Notifikasi Sukses -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-xs font-bold animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="text-center mb-4">
        @if($pendaftar->status_pembayaran == 'lunas')
            <span class="bg-green-100 text-green-800 border-2 border-green-600 px-3 py-1 rounded font-black uppercase text-sm">LUNAS</span>
        @elseif($pendaftar->status_pembayaran == 'menunggu_verifikasi')
            <span class="bg-yellow-100 text-yellow-800 border-2 border-yellow-600 px-3 py-1 rounded font-black uppercase text-sm">BUTUH CEK</span>
        @elseif($pendaftar->status_pembayaran == 'ditolak')
            <span class="bg-red-100 text-red-800 border-2 border-red-600 px-3 py-1 rounded font-black uppercase text-sm">DITOLAK</span>
        @else
            <span class="bg-gray-200 text-gray-600 border-2 border-gray-400 px-3 py-1 rounded font-black uppercase text-sm">BELUM BAYAR</span>
        @endif
    </div>

    <!-- INFO PEMBAYARAN TUNAI -->
    <div class="bg-blue-50 border-l-4 border-unmaris-blue p-3 mb-4 text-xs text-blue-900 rounded-r">
        <strong>Info Admin:</strong> Jika bayar tunai, terima uangnya lalu klik tombol <b>TERIMA (LUNAS)</b>.
    </div>

    @if($pendaftar->bukti_pembayaran)
        <a href="{{ asset('storage/'.$pendaftar->bukti_pembayaran) }}" target="_blank" class="block w-full text-center text-xs font-bold text-blue-600 underline mb-4 hover:text-blue-800">
            📄 Lihat Bukti Transfer
        </a>
    @else
        <div class="text-center text-xs text-gray-400 mb-4 italic border-b border-gray-100 pb-2">
            Tidak ada bukti transfer diunggah.
        </div>
    @endif
    
    <!-- ACTION BUTTONS (LIVEWIRE) -->
    <div class="grid grid-cols-2 gap-2">
        <!-- Tombol Terima -->
        <button wire:click="verify('lunas')" 
                wire:loading.attr="disabled"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded text-xs border-2 border-black shadow-neo-sm hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all flex justify-center items-center">
            <span wire:loading.remove wire:target="verify('lunas')">TERIMA (LUNAS)</span>
            <span wire:loading wire:target="verify('lunas')">PROSES...</span>
        </button>

        <!-- Tombol Tolak -->
        <button wire:click="verify('ditolak')" 
                wire:loading.attr="disabled"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 rounded text-xs border-2 border-black shadow-neo-sm hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all flex justify-center items-center">
            <span wire:loading.remove wire:target="verify('ditolak')">TOLAK / BATAL</span>
            <span wire:loading wire:target="verify('ditolak')">PROSES...</span>
        </button>
    </div>
</div>