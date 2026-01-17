<div class="space-y-6">
    
    <!-- HEADER & SUMMARY CARDS -->
    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Total Pemasukan -->
        <div class="flex-1 bg-green-600 text-white p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 bg-white/20 w-32 h-32 rounded-full group-hover:scale-110 transition-transform"></div>
            <h3 class="font-bold text-sm uppercase tracking-widest text-green-200">Total Pemasukan (Lunas)</h3>
            <div class="font-black text-3xl md:text-4xl mt-2">Rp {{ number_format($summary['total_uang_masuk'], 0, ',', '.') }}</div>
            <p class="text-xs mt-2 font-medium">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>

        <!-- Potensi / Menunggu -->
        <div class="flex-1 bg-yellow-400 text-yellow-900 p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 bg-white/20 w-32 h-32 rounded-full group-hover:scale-110 transition-transform"></div>
            <h3 class="font-bold text-sm uppercase tracking-widest opacity-80">Potensi (Menunggu Verif)</h3>
            <div class="font-black text-3xl md:text-4xl mt-2">Rp {{ number_format($summary['potensi_uang'], 0, ',', '.') }}</div>
            <p class="text-xs mt-2 font-medium">Segera verifikasi agar menjadi pemasukan sah.</p>
        </div>

    </div>

    <!-- FILTER BAR -->
    <div class="bg-white p-4 rounded-xl border-2 border-black shadow-sm flex flex-col md:flex-row gap-4 items-end md:items-center justify-between">
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div class="flex flex-col">
                <label class="text-[10px] font-black uppercase text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="border-2 border-gray-300 rounded-lg px-3 py-2 font-bold text-sm focus:border-unmaris-blue focus:shadow-neo outline-none">
            </div>
            <div class="flex flex-col">
                <label class="text-[10px] font-black uppercase text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" class="border-2 border-gray-300 rounded-lg px-3 py-2 font-bold text-sm focus:border-unmaris-blue focus:shadow-neo outline-none">
            </div>
            <div class="flex flex-col w-full md:w-48">
                <label class="text-[10px] font-black uppercase text-gray-500 mb-1">Status Bayar</label>
                <select wire:model.live="filterStatus" class="border-2 border-gray-300 rounded-lg px-3 py-2 font-bold text-sm focus:border-unmaris-blue focus:shadow-neo outline-none">
                    <option value="">Semua Status</option>
                    <option value="lunas">✅ Lunas</option>
                    <option value="menunggu_verifikasi">⏳ Menunggu Verifikasi</option>
                    <option value="ditolak">❌ Ditolak</option>
                </select>
            </div>
        </div>

        <button onclick="window.print()" class="bg-white border-2 border-black text-black px-4 py-2 rounded-lg font-black hover:bg-gray-100 transition shadow-neo-sm text-sm flex items-center gap-2">
            <span>🖨️</span> Cetak Laporan
        </button>
    </div>

    <!-- TABEL TRANSAKSI -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-unmaris-blue text-white">
                    <tr>
                        <th class="p-4 font-black uppercase text-xs">Tanggal</th>
                        <th class="p-4 font-black uppercase text-xs">Nama Pendaftar</th>
                        <th class="p-4 font-black uppercase text-xs">Nominal</th>
                        <th class="p-4 font-black uppercase text-xs text-center">Status</th>
                        <th class="p-4 font-black uppercase text-xs text-center">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($transaksi as $trx)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-4 text-sm font-bold text-gray-600">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4">
                                <div class="font-black text-unmaris-blue text-sm">{{ $trx->user->name }}</div>
                                <div class="text-[10px] font-bold text-gray-500">ID: #{{ $trx->id }}</div>
                            </td>
                            <td class="p-4 text-sm font-bold text-gray-800">
                                Rp {{ number_format($nominal, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                @if($trx->status_pembayaran == 'lunas')
                                    <span class="bg-green-100 text-green-800 border border-green-300 px-2 py-1 rounded text-[10px] font-black uppercase">LUNAS</span>
                                @elseif($trx->status_pembayaran == 'menunggu_verifikasi')
                                    <span class="bg-yellow-100 text-yellow-800 border border-yellow-300 px-2 py-1 rounded text-[10px] font-black uppercase">VERIFIKASI</span>
                                @else
                                    <span class="bg-red-100 text-red-800 border border-red-300 px-2 py-1 rounded text-[10px] font-black uppercase">{{ $trx->status_pembayaran }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($trx->bukti_pembayaran)
                                    <a href="{{ asset('storage/'.$trx->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Lihat</a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 font-bold bg-gray-50 italic">
                                Tidak ada data transaksi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-2 border-gray-200">
            {{ $transaksi->links() }}
        </div>
    </div>

</div>