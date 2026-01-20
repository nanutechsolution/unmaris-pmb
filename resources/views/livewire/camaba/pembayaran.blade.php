<div class="max-w-4xl mx-auto py-10 font-sans">

    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight"
            style="text-shadow: 2px 2px 0px #FACC15;">
            💸 Administrasi Pendaftaran
        </h1>
        <p class="text-unmaris-blue font-bold mt-2">Lakukan pembayaran untuk validasi formulir.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- INFO REKENING (KIRI) -->
        <div class="space-y-6">
            <div
                class="bg-unmaris-blue text-white p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 4v10h16V8H4zm2 2h12v2H6v-2z" />
                    </svg>
                </div>

                <h3 class="font-bold text-unmaris-yellow uppercase tracking-widest text-sm mb-1">Total Tagihan</h3>
                <div class="text-4xl font-black mb-6">Rp {{ number_format($biaya_pendaftaran, 0, ',', '.') }}</div>
                <p class="text-xs uppercase font-bold text-gray-300 mb-2">Silakan transfer ke salah satu rekening:</p>

                <!-- LIST REKENING -->
                <div class="space-y-3">
                    @foreach ($bank_accounts as $b)
                        <div
                            class="bg-white/10 p-3 rounded-lg border border-white/20 backdrop-blur-sm flex justify-between items-center group hover:bg-white/20 transition">
                            <div>
                                <div class="text-xs font-bold text-yellow-300 uppercase">{{ $b['bank'] }}</div>
                                <div class="text-xl font-black tracking-wider font-mono">{{ $b['rekening'] }}</div>
                                <div class="text--[10px] font-bold text-gray-300">a.n {{ $b['atas_nama'] }}</div>
                            </div>
                            <button
                                onclick="navigator.clipboard.writeText('{{ $b['rekening'] }}'); alert('No. Rekening Disalin!')"
                                class="bg-black/40 hover:bg-black/60 text-white p-2 rounded-lg transition"
                                title="Salin No. Rekening">
                                📋
                            </button>
                        </div>
                    @endforeach

                    @if (empty($bank_accounts))
                        <div
                            class="p-3 text-center text-sm font-bold text-red-300 bg-red-900/20 rounded border border-red-500/50">
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
        <div class="bg-white p-6 rounded-xl border-4 border-unmaris-blue shadow-neo">

            <h3 class="font-black text-unmaris-blue text-lg mb-4 uppercase border-b-2 border-unmaris-blue pb-2">
                📤 Konfirmasi Pembayaran
            </h3>

            @if (session('message'))
                <div class="bg-green-100 border-2 border-green-500 text-green-700 p-3 rounded font-bold mb-4 text-sm">
                    {{ session('message') }}
                </div>
            @endif

            <!-- CASE 1: LUNAS -->
            @if ($pendaftar->status_pembayaran == 'lunas')
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="text-2xl font-black text-green-600 uppercase">PEMBAYARAN LUNAS</h2>
                    <p class="font-bold text-gray-500 text-sm mt-2">Terima kasih! Data Anda sedang diproses ke tahap
                        seleksi.</p>
                </div>

                <!-- CASE 2: MENUNGGU VERIFIKASI -->
            @elseif($pendaftar->status_pembayaran == 'menunggu_verifikasi')
                <div x-data="{ gantiFile: false }">
                    <div x-show="!gantiFile" class="text-center py-8">
                        <div class="text-6xl mb-4 animate-pulse">⏳</div>
                        <h2 class="text-xl font-black text-yellow-600 uppercase">MENUNGGU VERIFIKASI</h2>
                        <p class="font-bold text-gray-500 text-sm mt-2">Admin sedang mengecek bukti transfer Anda.</p>

                        <div class="mt-4 mb-6">
                            @if (str_ends_with($pendaftar->bukti_pembayaran, '.pdf'))
                                <a href="{{ asset('storage/' . $pendaftar->bukti_pembayaran) }}" target="_blank"
                                    class="flex flex-col items-center justify-center p-4 bg-gray-50 border-2 border-black rounded shadow-sm hover:bg-gray-100">
                                    <span class="text-2xl">📄</span>
                                    <span class="text-xs font-bold mt-1 text-blue-600 underline">Lihat PDF Bukti</span>
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $pendaftar->bukti_pembayaran) }}"
                                    class="h-32 mx-auto border-2 border-black rounded shadow-sm object-cover">
                            @endif
                            <span class="text-xs text-gray-400 font-bold block mt-2">Bukti terkirim</span>
                        </div>

                        <button @click="gantiFile = true"
                            class="text-xs font-bold text-red-500 hover:text-red-700 underline decoration-2 cursor-pointer">
                            ⚠️ Salah kirim bukti? Upload ulang disini
                        </button>
                    </div>

                    <!-- Form Ganti File -->
                    <div x-show="gantiFile" x-transition class="mt-4">
                        <form wire:submit.prevent="save">
                            <div class="mb-4">
                                <div
                                    class="border-2 border-dashed border-unmaris-blue bg-blue-50 rounded-lg p-6 text-center hover:bg-white transition cursor-pointer relative group overflow-hidden">

                                    <!-- LOADING OVERLAY -->
                                    <div wire:loading wire:target="bukti_transfer"
                                        class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm z-20">
                                        <svg class="animate-spin h-8 w-8 text-unmaris-blue mb-2"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-bold text-unmaris-blue">Mengunggah...</span>
                                    </div>

                                    @if ($bukti_transfer)
                                        <!-- Preview Logic -->
                                        @if ($bukti_transfer->isPreviewable())
                                            <img src="{{ $bukti_transfer->temporaryUrl() }}"
                                                class="h-40 mx-auto rounded shadow-sm border-2 border-black object-cover">
                                        @elseif ($bukti_transfer->getMimeType() === 'application/pdf')
                                            <div class="flex flex-col items-center justify-center py-4">
                                                <span class="text-4xl">📄</span>
                                                <p class="text-sm font-black text-unmaris-blue mt-2">File PDF Terpilih
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $bukti_transfer->getClientOriginalName() }}</p>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center py-4">
                                                <span class="text-4xl">📁</span>
                                                <p class="text-sm font-black text-unmaris-blue mt-2">File Siap Upload
                                                </p>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-4xl block">📸</span>
                                        <p class="text-xs font-bold text-unmaris-blue mt-2">Pilih File Baru
                                            (PDF/JPG/PNG)</p>
                                    @endif
                                    <input type="file" wire:model="bukti_transfer" accept=".jpg,.jpeg,.png,.pdf"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 mt-1 block">Max Size: 1MB</span>
                                @error('bukti_transfer')
                                    <span class="text-red-600 font-bold text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex gap-2">
                                <button type="button" @click="gantiFile = false"
                                    class="flex-1 py-2 rounded-lg border-2 border-gray-400 font-bold text-gray-600 hover:bg-gray-100">
                                    Batal
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="flex-1 bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-2 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover transition-all">
                                    <span wire:loading.remove>KIRIM ULANG</span>
                                    <span wire:loading>...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CASE 3: BELUM BAYAR / DITOLAK -->
            @else
                @if ($pendaftar->status_pembayaran == 'ditolak')
                    <div
                        class="bg-red-100 border-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-center shadow-sm">
                        <div class="text-3xl mb-2">❌</div>
                        <h4 class="font-black text-lg uppercase">Pembayaran Ditolak</h4>
                        <p class="text-sm font-bold mt-1">Bukti tidak valid. Silakan upload ulang.</p>
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-unmaris-blue mb-2">Upload Bukti Transfer</label>
                        <span class="text-xs font-bold text-gray-400 mb-2 block">Format: JPG, PNG, PDF (Max 1MB)</span>

                        <div
                            class="border-2 border-dashed border-unmaris-blue bg-blue-50 rounded-lg p-6 text-center hover:bg-white transition cursor-pointer relative group overflow-hidden">

                            <!-- LOADING OVERLAY -->
                            <div wire:loading wire:target="bukti_transfer"
                                class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm z-20">
                                <svg class="animate-spin h-8 w-8 text-unmaris-blue mb-2"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-xs font-bold text-unmaris-blue">Mengunggah File...</span>
                            </div>

                            @if ($bukti_transfer)
                                <!-- Preview Logic (Updated) -->
                                @if ($bukti_transfer->isPreviewable())
                                    <img src="{{ $bukti_transfer->temporaryUrl() }}"
                                        class="h-40 mx-auto rounded shadow-sm border-2 border-black object-cover">
                                @elseif ($bukti_transfer->getMimeType() === 'application/pdf')
                                    <div class="flex flex-col items-center justify-center py-4">
                                        <span class="text-4xl">📄</span>
                                        <p class="text-sm font-black text-unmaris-blue mt-2">File PDF Terpilih</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $bukti_transfer->getClientOriginalName() }}</p>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-4">
                                        <span class="text-4xl">📁</span>
                                        <p class="text-sm font-black text-gray-600 mt-2">File Siap Upload</p>
                                    </div>
                                @endif
                            @else
                                <span class="text-4xl group-hover:scale-110 transition-transform block">📸</span>
                                <p class="text-xs font-bold text-unmaris-blue mt-2">Klik untuk pilih gambar/PDF</p>
                            @endif

                            <input type="file" wire:model="bukti_transfer" accept=".jpg,.jpeg,.png,.pdf"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('bukti_transfer')
                            <span
                                class="text-red-600 font-bold text-xs mt-1 block bg-red-100 p-1 border border-red-500 rounded">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-6 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider flex justify-center items-center gap-2"
                        wire:target="save">
                        <span wire:loading.remove wire:target="save">KIRIM BUKTI BAYAR 🚀</span>
                        <span wire:loading wire:target="save">MEMPROSES... ⏳</span>
                    </button>
                </form>
            @endif

        </div>
    </div>
</div>
