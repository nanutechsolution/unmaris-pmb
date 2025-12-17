<div class="space-y-6">
    <!-- Header -->
    <div class="bg-unmaris-blue p-6 rounded-xl border-4 border-black shadow-neo">
        <h2 class="text-white font-black text-2xl uppercase tracking-wider">
            🛠️ Pengaturan Website (CMS)
        </h2>
        <p class="text-blue-200 font-bold mt-1">Ubah info kampus, rekening, dan kontak secara instan.</p>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 font-bold shadow-sm animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="update" class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-8">
        
        <!-- SECTION 1: IDENTITAS -->
        <h3 class="text-xl font-black text-unmaris-blue mb-4 border-b-2 border-gray-200 pb-2">🏫 Identitas Kampus</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block font-bold text-gray-700 mb-1">Nama Kampus</label>
                <input type="text" wire:model="nama_kampus" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Singkatan</label>
                <input type="text" wire:model="singkatan_kampus" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
            </div>
            <div class="md:col-span-2">
                <label class="block font-bold text-gray-700 mb-1">Alamat Lengkap</label>
                <input type="text" wire:model="alamat_kampus" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
            </div>
        </div>

        <!-- SECTION 2: PEMBAYARAN & BANK (DYNAMIC) -->
        <h3 class="text-xl font-black text-unmaris-blue mb-4 border-b-2 border-gray-200 pb-2 flex justify-between items-center">
            <span>💸 Info Pembayaran</span>
            <button type="button" wire:click="addBank" class="bg-green-500 text-white text-xs px-3 py-1 rounded border-2 border-black hover:bg-green-600 transition shadow-sm">
                + Tambah Bank
            </button>
        </h3>
        
        <div class="mb-6">
            <label class="block font-bold text-gray-700 mb-1">Biaya Pendaftaran (Rp)</label>
            <input type="number" wire:model="biaya_pendaftaran" class="w-full md:w-1/2 border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
        </div>

        <!-- Daftar Bank Repeater -->
        <div class="space-y-4 mb-8">
            @foreach($bank_accounts as $index => $bank)
                <div class="flex flex-col md:flex-row gap-4 items-end bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-300">
                    <div class="w-full md:w-1/4">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama Bank (BRI, BNI, dll)</label>
                        <input type="text" wire:model="bank_accounts.{{ $index }}.bank" placeholder="Contoh: BRI" class="w-full border-2 border-gray-400 rounded px-2 py-1 font-bold">
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nomor Rekening</label>
                        <input type="text" wire:model="bank_accounts.{{ $index }}.rekening" placeholder="1234-5678-xxxx" class="w-full border-2 border-gray-400 rounded px-2 py-1 font-bold">
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Atas Nama</label>
                        <input type="text" wire:model="bank_accounts.{{ $index }}.atas_nama" placeholder="Yayasan UNMARIS" class="w-full border-2 border-gray-400 rounded px-2 py-1 font-bold">
                    </div>
                    <div>
                        <button type="button" wire:click="removeBank({{ $index }})" class="bg-red-500 text-white p-2 rounded border-2 border-black hover:bg-red-600" title="Hapus Bank">
                            🗑️
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- SECTION 3: KONTAK -->
        <h3 class="text-xl font-black text-unmaris-blue mb-4 border-b-2 border-gray-200 pb-2">📞 Kontak Bantuan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block font-bold text-gray-700 mb-1">WhatsApp Admin</label>
                <input type="text" wire:model="no_wa_admin" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
            </div>
            <div>
                <label class="block font-bold text-gray-700 mb-1">Email Official</label>
                <input type="email" wire:model="email_admin" class="w-full border-2 border-black rounded px-3 py-2 font-bold focus:shadow-neo transition-all">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-3 px-8 rounded-lg border-2 border-black shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider">
                💾 SIMPAN PERUBAHAN
            </button>
        </div>
    </form>
</div>