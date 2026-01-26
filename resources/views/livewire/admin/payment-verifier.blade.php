<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden relative font-sans" 
     x-data="{ showProof: false, showCashModal: false }">
    
    <!-- HEADER STATUS -->
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide flex items-center gap-2">
            <span>💰</span> Verifikasi Keuangan
        </h3>
        
        <!-- Status Badge -->
        @if($pendaftar->status_pembayaran == 'lunas')
            <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                LUNAS
            </span>
        @elseif($pendaftar->status_pembayaran == 'menunggu_verifikasi')
            <span class="inline-flex items-center rounded-md bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20 animate-pulse">
                BUTUH VERIFIKASI
            </span>
        @elseif($pendaftar->status_pembayaran == 'ditolak')
            <span class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                DITOLAK
            </span>
        @else
            <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                BELUM BAYAR
            </span>
        @endif
    </div>

    <div class="p-5">
        <!-- NOTIFIKASI -->
        @if (session()->has('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3 text-sm font-medium text-green-800 border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 rounded-md bg-red-50 p-3 text-sm font-medium text-red-800 border border-red-200">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- CASE A: SUDAH LUNAS -->
        @if($pendaftar->status_pembayaran == 'lunas')
            <div class="flex flex-col items-center justify-center py-4 text-center">
                <div class="h-12 w-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h4 class="text-gray-900 font-bold">Pembayaran Selesai</h4>
                <p class="text-xs text-gray-500 mt-1 mb-4">Administrasi keuangan telah terpenuhi.</p>
                
                @if($pendaftar->bukti_pembayaran)
                    <button @click="showProof = true" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                        Lihat Arsip Bukti
                    </button>
                @endif

                <div class="mt-6 pt-4 border-t border-gray-100 w-full text-center">
                    <button wire:click="reject" 
                            wire:confirm="Yakin ingin membatalkan status LUNAS? User harus upload bukti lagi."
                            class="text-[10px] text-red-400 hover:text-red-600 font-medium uppercase tracking-wide">
                        Batalkan Status Lunas
                    </button>
                </div>
            </div>

        <!-- CASE B: BELUM LUNAS -->
        @else
            
            <!-- SUB-CASE B1: DITOLAK (Prioritas Tinggi agar file lama tidak muncul sebagai task) -->
            @if($pendaftar->status_pembayaran == 'ditolak')
                <div class="text-center py-6 bg-red-50 rounded-lg border border-red-100 mb-4">
                    <div class="mx-auto h-12 w-12 text-red-400 mb-2 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h4 class="text-red-800 font-bold">Pembayaran Ditolak</h4>
                    <p class="text-xs text-red-600 mt-1">Menunggu peserta mengunggah bukti pembayaran baru.</p>
                    
                    @if($pendaftar->bukti_pembayaran)
                        <button @click="showProof = true" class="text-xs text-red-400 underline mt-3 hover:text-red-600">
                            Lihat Arsip Ditolak
                        </button>
                    @endif
                </div>

                <!-- Tetap tampilkan opsi bayar tunai jika camaba datang langsung -->
                <button @click="showCashModal = true" class="w-full flex justify-center items-center gap-2 rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                    <span>💵</span> Input Bayar Tunai
                </button>

            <!-- SUB-CASE B2: ADA BUKTI & MENUNGGU VERIFIKASI -->
            @elseif($pendaftar->bukti_pembayaran)
                <div class="flex items-start gap-4 mb-5 bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                    <div class="bg-blue-100 p-2 rounded text-blue-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-blue-900 uppercase mb-1">Bukti Transfer Masuk</p>
                        <button @click="showProof = true" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 underline">
                            Lihat File Bukti
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="approve" wire:loading.attr="disabled" class="flex justify-center items-center gap-2 w-full rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Terima
                    </button>
                    
                    <button wire:click="reject" 
                            wire:confirm="Apakah Anda yakin bukti ini TIDAK VALID? Status akan berubah menjadi DITOLAK."
                            wire:loading.attr="disabled"
                            class="flex justify-center items-center gap-2 w-full rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tolak
                    </button>
                </div>

            <!-- SUB-CASE B3: BELUM ADA BUKTI -->
            @else
                <div class="text-center py-6">
                    <div class="mx-auto h-10 w-10 text-gray-300 mb-2">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">Belum ada bukti transfer.</p>
                </div>

                <button @click="showCashModal = true" class="w-full flex justify-center items-center gap-2 rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                    <span>💵</span> Input Bayar Tunai
                </button>
            @endif
        @endif
    </div>

    <!-- MODAL 1: LIHAT BUKTI -->
    <div x-show="showProof" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showProof = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-2xl">
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-900">Bukti Pembayaran</h3>
                    <button @click="showProof = false" class="text-gray-400 hover:text-gray-500">✕</button>
                </div>
                <div class="bg-black/5 p-4 flex justify-center min-h-[300px]">
                    @if($pendaftar->bukti_pembayaran)
                        <img src="{{ asset('storage/'.$pendaftar->bukti_pembayaran) }}" class="max-h-[70vh] max-w-full object-contain rounded shadow-lg">
                    @endif
                </div>
                
                <!-- Action di dalam modal proof (HANYA JIKA MENUNGGU) -->
                @if($pendaftar->status_pembayaran == 'menunggu_verifikasi')
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button type="button" wire:click="approve" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">Terima</button>
                    <button type="button" wire:click="reject" wire:confirm="Yakin tolak?" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tolak</button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL 2: CASH -->
    <div x-show="showCashModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showCashModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-sm">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="text-xl">💵</span>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Terima Tunai?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Pastikan Anda telah menerima uang tunai sejumlah nominal pendaftaran. Aksi ini akan melunaskan tagihan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" wire:click="payCash" @click="showCashModal = false" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">Terima Uang</button>
                    <button type="button" @click="showCashModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>

</div>