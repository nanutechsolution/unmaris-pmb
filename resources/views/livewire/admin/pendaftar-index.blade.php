<div class="space-y-6">
    
    <!-- TOOLBAR: SEARCH & FILTER -->
    <div class="flex flex-col md:flex-row justify-between gap-4">
        
        <!-- Search Box -->
        <div class="w-full md:w-1/3 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-unmaris-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NISN..." 
                   class="w-full pl-10 border-2 border-unmaris-blue rounded-lg py-3 font-bold text-unmaris-blue placeholder-gray-400 focus:outline-none focus:shadow-neo transition-all">
        </div>

        <!-- Filter Status (Fixed) -->
        <div class="w-full md:w-1/4">
            <select wire:model.live="filterStatus" class="w-full border-2 border-unmaris-blue rounded-lg py-3 px-4 font-bold text-unmaris-blue focus:outline-none focus:shadow-neo transition-all cursor-pointer bg-white">
                <option value="">📂 Semua Status</option>
                <option value="draft">📝 Draft</option>
                <option value="submit">📩 Baru Submit</option>
                <option value="verifikasi">🔍 Sedang Verifikasi</option>
                <option value="lulus">✅ Lulus</option>
                <option value="gagal">❌ Tidak Lulus</option>
            </select>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-unmaris-blue text-white">
                    <tr>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Tanggal</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Identitas Pendaftar</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm">Pilihan Prodi</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm text-center">Status</th>
                        <th class="p-4 font-black uppercase tracking-wider text-sm text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($pendaftars as $p)
                        <tr class="hover:bg-yellow-50 transition group">
                            <!-- Tanggal -->
                            <td class="p-4 text-sm font-bold text-gray-600 whitespace-nowrap">
                                {{ $p->created_at->format('d M Y') }}
                                <br>
                                <span class="text-xs font-normal text-gray-400">{{ $p->created_at->format('H:i') }} WIB</span>
                            </td>
                            
                            <!-- Identitas -->
                            <td class="p-4">
                                <div class="font-black text-unmaris-blue text-lg">{{ $p->user->name }}</div>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wide mt-1">
                                    <span class="bg-gray-200 px-1 rounded">{{ $p->jalur_pendaftaran }}</span> • {{ $p->nisn ?? 'No NISN' }}
                                </div>
                            </td>

                            <!-- Prodi -->
                            <td class="p-4 text-sm font-bold text-gray-700">
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

                            <!-- Status Badge -->
                            <td class="p-4 text-center">
                                @php
                                    $statusConfig = [
                                        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-400', 'label' => 'DRAFT'],
                                        'submit' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-500', 'label' => 'BARU'],
                                        'verifikasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-500', 'label' => 'DIPROSES'],
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
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.pendaftar.show', $p->id) }}" class="inline-flex items-center bg-white text-unmaris-blue border-2 border-unmaris-blue px-4 py-2 rounded-lg font-bold shadow-[2px_2px_0px_0px_rgba(30,58,138,1)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] hover:bg-unmaris-yellow transition-all text-sm group-hover:scale-105">
                                    Proses ⚡
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