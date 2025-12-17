<div class="space-y-6">
    
    <!-- TOOLBAR: SEARCH & FILTER -->
    <div class="flex flex-col md:flex-row justify-between gap-4 bg-white p-4 rounded-xl border-2 border-unmaris-blue shadow-sm">
        
        <!-- Search Box -->
        <div class="w-full md:w-1/3 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NISN..." 
                   class="w-full pl-10 border-2 border-gray-300 rounded-lg py-2 font-bold text-gray-700 focus:outline-none focus:border-unmaris-blue focus:ring-1 focus:ring-unmaris-blue transition-all">
        </div>

        <div class="flex gap-2 w-full md:w-2/3 justify-end">
            <!-- Filter Status Pendaftaran -->
            <div class="w-1/2 md:w-1/3">
                <select wire:model.live="filterStatus" class="w-full border-2 border-gray-300 rounded-lg py-2 px-3 font-bold text-gray-600 focus:outline-none focus:border-unmaris-blue cursor-pointer">
                    <option value="">📂 Semua Pendaftaran</option>
                    <option value="draft">📝 Draft</option>
                    <option value="submit">📩 Submit</option>
                    <option value="verifikasi">🔍 Verifikasi</option>
                    <option value="lulus">✅ Lulus</option>
                    <option value="gagal">❌ Gagal</option>
                </select>
            </div>

            <!-- Filter Status Pembayaran (BARU) -->
            <div class="w-1/2 md:w-1/3">
                <select wire:model.live="filterPembayaran" class="w-full border-2 border-unmaris-blue bg-blue-50 rounded-lg py-2 px-3 font-bold text-unmaris-blue focus:outline-none focus:shadow-neo cursor-pointer">
                    <option value="">💰 Semua Pembayaran</option>
                    <option value="belum_bayar">❌ Belum Bayar</option>
                    <option value="menunggu_verifikasi">⏳ Butuh Verifikasi</option>
                    <option value="lunas">✅ Lunas</option>
                    <option value="ditolak">🚫 Ditolak</option>
                </select>
            </div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-unmaris-blue text-white">
                    <tr>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Tanggal</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Identitas & Pembayaran</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Pilihan Prodi</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm text-center">Status Akademik</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($pendaftars as $p)
                        <tr class="hover:bg-yellow-50 transition group">
                            <!-- Tanggal -->
                            <td class="p-4 text-sm font-bold text-gray-600 whitespace-nowrap align-top">
                                {{ $p->created_at->format('d M Y') }}
                                <br>
                                <span class="text-xs font-normal text-gray-400">{{ $p->created_at->format('H:i') }} WIB</span>
                            </td>
                            
                            <!-- Identitas & Pembayaran -->
                            <td class="p-4 align-top">
                                <div class="font-black text-unmaris-blue text-lg">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wide mt-1 mb-2">
                                    {{ $p->jalur_pendaftaran }} • {{ $p->nisn ?? 'No NISN' }}
                                </div>

                                <!-- BADGE STATUS PEMBAYARAN -->
                                @if($p->status_pembayaran == 'lunas')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-green-100 text-green-700 border border-green-300">
                                        ✅ LUNAS
                                    </span>
                                @elseif($p->status_pembayaran == 'menunggu_verifikasi')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-yellow-100 text-yellow-700 border border-yellow-300 animate-pulse">
                                        ⏳ CEK BUKTI BAYAR
                                    </span>
                                @elseif($p->status_pembayaran == 'ditolak')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-red-100 text-red-700 border border-red-300">
                                        🚫 DITOLAK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-black bg-gray-100 text-gray-500 border border-gray-300">
                                        BELUM BAYAR
                                    </span>
                                @endif
                            </td>

                            <!-- Prodi -->
                            <td class="p-4 text-sm font-bold text-gray-700 align-top">
                                <div class="mb-1 flex items-center">
                                    <span class="text-xs bg-unmaris-blue text-white px-1.5 rounded mr-2">1</span> 
                                    {{ $p->pilihan_prodi_1 }}
                                </div>
                                @if($p->pilihan_prodi_2)
                                <div class="text-xs text-gray-500 flex items-center">
                                    <span class="text-[10px] bg-gray-400 text-white px-1.5 rounded mr-2">2</span>
                                    {{ $p->pilihan_prodi_2 }}
                                </div>
                                @endif
                            </td>

                            <!-- Status Akademik -->
                            <td class="p-4 text-center align-top">
                                @php
                                    $statusConfig = [
                                        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-400', 'label' => 'DRAFT'],
                                        'submit' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'label' => 'SUBMIT'],
                                        'verifikasi' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200', 'label' => 'VERIFIKASI'],
                                        'lulus' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-500', 'label' => 'LULUS'],
                                        'gagal' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-500', 'label' => 'GAGAL'],
                                    ];
                                    $s = $statusConfig[$p->status_pendaftaran] ?? $statusConfig['draft'];
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-black border-2 {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }} shadow-sm uppercase tracking-wide">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="p-4 text-right align-top">
                                <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="inline-flex items-center bg-white text-unmaris-blue border-2 border-unmaris-blue px-4 py-2 rounded-lg font-bold shadow-[2px_2px_0px_0px_rgba(30,58,138,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] hover:bg-unmaris-yellow transition-all text-sm group-hover:scale-105">
                                    Detail 🔎
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 font-bold italic bg-gray-50">
                                🍃 Belum ada data pendaftar yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t-4 border-unmaris-blue bg-gray-50">
            {{ $pendaftars->links() }}
        </div>
    </div>
</div>