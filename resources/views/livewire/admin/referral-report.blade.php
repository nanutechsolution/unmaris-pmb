<div class="space-y-4 md:space-y-6">
    
    <!-- HEADER & STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <!-- Card 1: Total Prospek -->
        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 text-white p-5 md:p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-[10px] md:text-xs uppercase tracking-widest text-purple-200">Total Prospek Masuk</h3>
                <div class="font-black text-3xl md:text-4xl mt-2">{{ $totalReferral }} <span class="text-base md:text-lg font-normal">Orang</span></div>
                <p class="text-[10px] md:text-xs mt-2 font-medium">Melalui rekomendasi kerabat/civitas.</p>
            </div>
            <div class="absolute right-[-20px] bottom-[-20px] text-7xl md:text-8xl opacity-20">🤝</div>
        </div>

        <!-- Card 2: Top Referrer -->
        <div class="bg-white text-gray-800 p-5 md:p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden">
             <div class="relative z-10">
                <h3 class="font-bold text-[10px] md:text-xs uppercase tracking-widest text-gray-500">🏆 Top Referrer</h3>
                <div class="font-black text-xl md:text-2xl mt-2 truncate pr-2" title="{{ $topReferralName }}">{{ $topReferralName }}</div>
                <p class="text-[10px] md:text-xs mt-2 font-medium text-green-600">Paling rajin ajak teman!</p>
            </div>
             <div class="absolute right-2 top-2 text-3xl md:text-4xl">🥇</div>
        </div>

         <!-- Card 3: Calculator Setting -->
         <div class="bg-green-50 p-5 md:p-6 rounded-xl border-2 border-green-500 shadow-sm flex flex-col justify-center">
            <label class="font-bold text-[10px] md:text-xs uppercase text-green-800 mb-2">Simulasi Komisi / Siswa</label>
            
            <div class="flex items-center" x-data="{
                displayVal: '{{ number_format($rewardPerSiswa, 0, ',', '.') }}',
                format(val) { return val.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.') },
                update(e) {
                    let raw = e.target.value.replace(/\./g, '');
                    this.displayVal = this.format(raw);
                    $wire.set('rewardPerSiswa', raw);
                }
            }">
                <span class="bg-green-200 text-green-800 font-bold px-3 py-2 text-sm md:text-base rounded-l border-y-2 border-l-2 border-green-500">Rp</span>
                <input type="text" 
                       x-model="displayVal"
                       @input="update"
                       class="w-full border-2 border-green-500 rounded-r px-3 py-2 font-bold text-green-900 text-sm md:text-base focus:outline-none focus:ring-0"
                       placeholder="0">
            </div>

            <p class="text-[10px] text-green-600 mt-2 italic">*Hanya simulasi hitungan reward.</p>
        </div>
    </div>

    <!-- FILTER & SEARCH & EXPORT -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center bg-white p-4 rounded-xl shadow-sm border-2 border-gray-200 gap-4">
        
        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full xl:w-auto">
             <h2 class="text-unmaris-blue font-black text-lg uppercase flex items-center gap-2 whitespace-nowrap">
                <span>📋</span> <span class="hidden md:inline">Data</span> Referral
            </h2>
            <select wire:model.live="filterSumber" class="w-full md:w-auto border-2 border-gray-300 rounded-lg px-3 py-2 font-bold text-xs md:text-sm focus:border-unmaris-blue outline-none">
                <option value="">Semua Sumber</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen / Staf</option>
                <option value="alumni">Alumni</option>
                <option value="kerabat">Kerabat / Lainnya</option>
            </select>
        </div>

        <div class="flex flex-col md:flex-row gap-2 w-full xl:w-auto">
            <div class="relative w-full md:w-64">
                <input wire:model.live.debounce="search" type="text" placeholder="Cari Nama / HP..." class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 font-bold text-sm focus:border-unmaris-blue outline-none">
            </div>
            
            <button wire:click="export" wire:loading.attr="disabled" wire:target="export" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-black px-4 py-2 rounded-lg border-2 border-black shadow-neo-sm hover:shadow-none transition-all flex items-center justify-center gap-2 whitespace-nowrap text-xs md:text-sm">
                <span wire:loading.remove wire:target="export">📊 Export Excel</span>
                <span wire:loading wire:target="export">Processing...</span>
            </button>
        </div>
    </div>

    <!-- CONTAINER DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden relative min-h-[300px]">
        
        <!-- MOBILE CARD VIEW (< md) -->
        <div class="block md:hidden bg-gray-50 p-4 space-y-4">
            @forelse($referrals as $ref)
                <div class="bg-white p-4 rounded-xl border-2 border-gray-200 shadow-sm relative overflow-hidden">
                    <!-- Status Badge Absolute -->
                    <div class="absolute top-0 right-0">
                         @php
                            $badgeColor = match($ref->sumber_informasi) {
                                'mahasiswa' => 'bg-blue-100 text-blue-800 border-b border-l border-blue-200',
                                'dosen' => 'bg-purple-100 text-purple-800 border-b border-l border-purple-200',
                                'alumni' => 'bg-orange-100 text-orange-800 border-b border-l border-orange-200',
                                default => 'bg-gray-100 text-gray-800 border-b border-l border-gray-200'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-bl-xl {{ $badgeColor }} text-[10px] font-black uppercase tracking-wider block">
                            {{ $ref->sumber_informasi }}
                        </span>
                    </div>

                    <!-- Identitas -->
                    <div class="pr-20 mb-3">
                        <div class="font-black text-unmaris-blue text-lg leading-tight">{{ strtoupper($ref->nama_referensi) }}</div>
                        <div class="text-xs text-gray-500 mt-1 font-bold">{{ $ref->nomor_hp_referensi ?? '-' }}</div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="bg-yellow-50 p-2 rounded border border-yellow-200 text-center">
                            <div class="text-[10px] text-gray-500 font-bold uppercase">Rekrut</div>
                            <div class="font-black text-xl text-unmaris-blue">{{ $ref->total_rekrut }}</div>
                        </div>
                        <div class="bg-green-50 p-2 rounded border border-green-200 text-center">
                            <div class="text-[10px] text-gray-500 font-bold uppercase">Reward</div>
                            <div class="font-black text-sm text-green-700 mt-1">
                                Rp {{ number_format($ref->total_rekrut * $rewardPerSiswa, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        @if($ref->nomor_hp_referensi)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $ref->nomor_hp_referensi)) }}?text=Halo%20{{ urlencode($ref->nama_referensi) }},%20terima%20kasih." target="_blank" class="flex-1 bg-green-500 text-white py-2 rounded-lg text-center font-bold text-xs border-b-4 border-green-700 active:border-b-0 active:translate-y-1 transition-all">
                            Chat WA 💬
                        </a>
                        @endif
                        <button wire:click="showDetails('{{ $ref->nama_referensi }}', '{{ $ref->nomor_hp_referensi ?? '' }}')" class="flex-1 bg-unmaris-blue text-white py-2 rounded-lg text-center font-bold text-xs border-b-4 border-indigo-900 active:border-b-0 active:translate-y-1 transition-all">
                            Lihat List ➜
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 font-bold italic bg-white rounded-xl border-2 border-dashed border-gray-300">
                    🍃 Belum ada data referral.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE VIEW (>= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-100 border-b-4 border-unmaris-blue text-gray-600">
                    <tr>
                        <th class="p-4 font-black uppercase text-xs w-10">#</th>
                        <th class="p-4 font-black uppercase text-xs">Nama Pemberi Referensi</th>
                        <th class="p-4 font-black uppercase text-xs">Kontak (WA)</th>
                        <th class="p-4 font-black uppercase text-xs">Status / Sumber</th>
                        <th class="p-4 font-black uppercase text-xs text-center">Jumlah Rekrut</th>
                        <th class="p-4 font-black uppercase text-xs text-right">Estimasi Reward</th>
                        <th class="p-4 font-black uppercase text-xs text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($referrals as $index => $ref)
                        <tr class="hover:bg-yellow-50 transition group">
                            <td class="p-4 font-bold text-gray-400">{{ $loop->iteration + ($referrals->firstItem() - 1) }}</td>
                            
                            <td class="p-4">
                                <div class="font-black text-unmaris-blue text-base">{{ strtoupper($ref->nama_referensi) }}</div>
                            </td>

                            <td class="p-4">
                                @if($ref->nomor_hp_referensi)
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-600 text-sm">{{ $ref->nomor_hp_referensi }}</span>
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $ref->nomor_hp_referensi)) }}?text=Halo%20{{ urlencode($ref->nama_referensi) }},%20terima%20kasih%20telah%20merekomendasikan%20UNMARIS." 
                                           target="_blank" 
                                           class="bg-green-500 text-white p-1 rounded-full hover:bg-green-600 transition shadow-sm border border-green-700"
                                           title="Hubungi Perekomendasi">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">-</span>
                                @endif
                            </td>

                            <td class="p-4">
                                @php
                                    $badgeColor = match($ref->sumber_informasi) {
                                        'mahasiswa' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'dosen' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'alumni' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded border {{ $badgeColor }} text-[10px] font-black uppercase">
                                    {{ $ref->sumber_informasi }}
                                </span>
                            </td>

                            <td class="p-4 text-center">
                                <div class="inline-block bg-unmaris-yellow text-unmaris-blue font-black text-lg w-10 h-10 flex items-center justify-center rounded-full border-2 border-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ $ref->total_rekrut }}
                                </div>
                            </td>

                            <td class="p-4 text-right">
                                <div class="font-bold text-green-600">
                                    Rp {{ number_format($ref->total_rekrut * $rewardPerSiswa, 0, ',', '.') }}
                                </div>
                            </td>

                            <td class="p-4 text-right">
                                <button wire:click="showDetails('{{ $ref->nama_referensi }}', '{{ $ref->nomor_hp_referensi ?? '' }}')" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center justify-end gap-1 ml-auto">
                                    Lihat List <span class="text-lg">➜</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 font-bold bg-gray-50 italic">
                                Belum ada data referral.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-gray-50 border-t-2 border-gray-200">
            {{ $referrals->links() }}
        </div>
    </div>

    <!-- MODAL DETAIL REKRUTAN (RESPONSIVE) -->
    @if($showDetailModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 animate-fade-in-up" x-data @keydown.escape.window="$wire.closeDetailModal()">
        <div class="bg-white w-full max-w-3xl rounded-2xl border-4 border-unmaris-blue shadow-neo-lg overflow-hidden flex flex-col max-h-[90vh]">
            
            <!-- Header Modal -->
            <div class="bg-unmaris-blue p-4 flex justify-between items-center text-white shrink-0">
                <div class="flex items-center gap-3 md:gap-4 overflow-hidden">
                    <div class="bg-white/20 p-2 rounded-lg shrink-0">
                        <span class="text-xl md:text-2xl">🤝</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-black text-sm md:text-lg uppercase tracking-wide truncate">Daftar Rekrutan</h3>
                        <p class="text-[10px] md:text-xs text-blue-200 font-bold truncate">Referrer: <span class="text-yellow-400">{{ strtoupper($detailReferrerName) }}</span></p>
                    </div>
                </div>
                <button wire:click="closeDetailModal" class="text-white hover:text-yellow-400 font-black text-2xl transition shrink-0 ml-2">&times;</button>
            </div>
            
            <!-- List Content -->
            <div class="p-0 overflow-y-auto custom-scrollbar bg-gray-50">
                
                <!-- MOBILE LIST (< md) -->
                <div class="block md:hidden p-2 space-y-2">
                    @forelse($detailList as $item)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="font-bold text-unmaris-blue text-sm">{{ $item->user->name }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $item->user->email }}</div>
                                </div>
                                <div class="text-[10px] font-bold text-gray-400">{{ $item->created_at->format('d/m/y') }}</div>
                            </div>
                            <div class="flex justify-between items-center border-t pt-2 border-dashed border-gray-100">
                                <div class="text-xs font-bold text-gray-600 truncate max-w-[50%]">{{ $item->pilihan_prodi_1 }}</div>
                                @php
                                    $statusClass = match($item->status_pendaftaran) {
                                        'lulus' => 'bg-green-100 text-green-800 border-green-300',
                                        'submit' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'verifikasi' => 'bg-blue-100 text-blue-800 border-blue-300',
                                        default => 'bg-gray-100 text-gray-600 border-gray-300'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded border {{ $statusClass }} text-[10px] font-black uppercase">
                                    {{ $item->status_pendaftaran }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-400 italic text-sm">Tidak ada data detail.</div>
                    @endforelse
                </div>

                <!-- DESKTOP TABLE (>= md) -->
                <table class="hidden md:table min-w-full text-left">
                    <thead class="bg-gray-200 text-gray-600 border-b border-gray-300 sticky top-0 shadow-sm z-10">
                        <tr>
                            <th class="p-3 font-black text-[10px] uppercase tracking-wider w-10">#</th>
                            <th class="p-3 font-black text-[10px] uppercase tracking-wider">Nama Camaba</th>
                            <th class="p-3 font-black text-[10px] uppercase tracking-wider">Prodi Pilihan</th>
                            <th class="p-3 font-black text-[10px] uppercase tracking-wider text-center">Status</th>
                            <th class="p-3 font-black text-[10px] uppercase tracking-wider text-right">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($detailList as $item)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="p-3 font-bold text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-unmaris-blue text-sm">{{ $item->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold">{{ $item->user->email }}</div>
                                </td>
                                <td class="p-3 text-xs font-bold text-gray-600">{{ $item->pilihan_prodi_1 }}</td>
                                <td class="p-3 text-center">
                                    @php
                                        $statusClass = match($item->status_pendaftaran) {
                                            'lulus' => 'bg-green-100 text-green-800 border-green-500',
                                            'submit' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
                                            'verifikasi' => 'bg-blue-100 text-blue-800 border-blue-500',
                                            default => 'bg-gray-100 text-gray-600 border-gray-300'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded border {{ $statusClass }} text-[10px] font-black uppercase inline-block">
                                        {{ $item->status_pendaftaran }}
                                    </span>
                                </td>
                                <td class="p-3 text-right text-xs font-bold text-gray-500">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400 italic text-sm">Tidak ada data detail.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer -->
            <div class="p-4 bg-white border-t-2 border-gray-200 flex flex-col sm:flex-row justify-end shrink-0 gap-2">
                <button wire:click="exportDetail('{{ $detailReferrerName }}', '{{ $detailReferrerHp ?? '' }}')" 
                        wire:loading.attr="disabled" 
                        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-xs uppercase transition flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="exportDetail">📥 Download Excel</span>
                    <span wire:loading wire:target="exportDetail">⏳ Processing...</span>
                </button>

                <button wire:click="closeDetailModal" class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg text-xs uppercase transition text-center">Tutup</button>
            </div>
        </div>
    </div>
    @endif

</div>