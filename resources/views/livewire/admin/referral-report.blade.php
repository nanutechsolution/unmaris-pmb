<div class="space-y-6">
    
    <!-- HEADER & STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Prospek -->
        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 text-white p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-xs uppercase tracking-widest text-purple-200">Total Prospek Masuk</h3>
                <div class="font-black text-4xl mt-2">{{ $totalReferral }} <span class="text-lg font-normal">Orang</span></div>
                <p class="text-xs mt-2 font-medium">Melalui rekomendasi kerabat/civitas.</p>
            </div>
            <div class="absolute right-[-20px] bottom-[-20px] text-8xl opacity-20">🤝</div>
        </div>

        <!-- Card 2: Top Referrer -->
        <div class="bg-white text-gray-800 p-6 rounded-xl border-4 border-black shadow-neo relative overflow-hidden">
             <div class="relative z-10">
                <h3 class="font-bold text-xs uppercase tracking-widest text-gray-500">🏆 Top Referrer</h3>
                <div class="font-black text-2xl mt-2 truncate" title="{{ $topReferralName }}">{{ $topReferralName }}</div>
                <p class="text-xs mt-2 font-medium text-green-600">Paling rajin ajak teman!</p>
            </div>
             <div class="absolute right-2 top-2 text-4xl">🥇</div>
        </div>

         <!-- Card 3: Calculator Setting -->
         <div class="bg-green-50 p-6 rounded-xl border-2 border-green-500 shadow-sm flex flex-col justify-center">
            <label class="font-bold text-xs uppercase text-green-800 mb-2">Simulasi Komisi / Siswa</label>
            <div class="flex items-center">
                <span class="bg-green-200 text-green-800 font-bold px-3 py-2 rounded-l border-y-2 border-l-2 border-green-500">Rp</span>
                <input type="number" wire:model.live="rewardPerSiswa" class="w-full border-2 border-green-500 rounded-r px-3 py-2 font-bold text-green-900 focus:outline-none focus:ring-0">
            </div>
            <p class="text-[10px] text-green-600 mt-2 italic">*Hanya simulasi hitungan reward.</p>
        </div>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-4 rounded-xl shadow-sm border-2 border-gray-200 gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
             <h2 class="text-unmaris-blue font-black text-lg uppercase flex items-center gap-2">
                <span>📋</span> Laporan Referral
            </h2>
            <select wire:model.live="filterSumber" class="border-2 border-gray-300 rounded-lg px-3 py-1 font-bold text-sm">
                <option value="">Semua Sumber</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen / Staf</option>
                <option value="alumni">Alumni</option>
                <option value="kerabat">Kerabat / Lainnya</option>
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Nama Pemberi Referensi..." class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 font-bold text-sm focus:border-unmaris-blue outline-none">
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden">
        <table class="min-w-full text-left">
            <thead class="bg-gray-100 border-b-4 border-unmaris-blue text-gray-600">
                <tr>
                    <th class="p-4 font-black uppercase text-xs w-10">#</th>
                    <th class="p-4 font-black uppercase text-xs">Nama Pemberi Referensi</th>
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
                            <!-- Tombol ini nanti bisa dikembangkan untuk melihat list nama siswa yang direkrut -->
                            <button class="text-xs font-bold text-blue-600 hover:underline">Lihat List ➜</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400 font-bold bg-gray-50 italic">
                            Belum ada data referral.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 bg-gray-50 border-t-2 border-gray-200">
            {{ $referrals->links() }}
        </div>
    </div>

</div>