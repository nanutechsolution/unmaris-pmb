<x-admin-layout>
    <x-slot name="header">
        Laporan Rekapitulasi
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-8">
            <h2 class="text-2xl font-black text-unmaris-blue mb-6 uppercase border-b-4 border-unmaris-yellow inline-block">
                🖨️ Cetak Laporan PDF
            </h2>

            <form action="{{ route('admin.laporan.cetak') }}" method="GET" target="_blank">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Filter Prodi -->
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Program Studi (Pilihan 1)</label>
                        <select name="prodi" class="w-full border-2 border-black rounded-lg px-4 py-3 font-bold focus:shadow-neo cursor-pointer">
                            <option value="">-- Semua Prodi --</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Agroteknologi">Agroteknologi</option>
                            <option value="Manajemen">Manajemen</option>
                            <option value="Akuntansi">Akuntansi</option>
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Status Kelulusan</label>
                        <select name="status" class="w-full border-2 border-black rounded-lg px-4 py-3 font-bold focus:shadow-neo cursor-pointer">
                            <option value="">-- Semua Status --</option>
                            <option value="lulus">✅ Lulus Seleksi</option>
                            <option value="gagal">❌ Tidak Lulus</option>
                            <option value="submit">⏳ Belum Diverifikasi</option>
                        </select>
                    </div>

                    <!-- Filter Jalur -->
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Jalur Masuk</label>
                        <select name="jalur" class="w-full border-2 border-black rounded-lg px-4 py-3 font-bold focus:shadow-neo cursor-pointer">
                            <option value="">-- Semua Jalur --</option>
                            <option value="reguler">Reguler</option>
                            <option value="pindahan">Pindahan</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t-2 border-dashed border-gray-300">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-black py-3 px-8 rounded-lg border-2 border-black shadow-neo hover:shadow-none transition-all uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        CETAK PDF
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-admin-layout>