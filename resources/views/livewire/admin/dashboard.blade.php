<div class="py-4 font-sans" wire:poll.30s> <!-- Auto refresh setiap 30 detik -->
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 2px 2px 0px #FACC15;">
                    ⚡ Command Center
                </h1>
                <p class="text-gray-600 font-bold">Pantau data PMB UNMARIS secara Real-time.</p>
            </div>
            
            <a href="{{ route('admin.export') }}" class="bg-unmaris-green hover:bg-green-600 text-white font-black py-3 px-6 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                EXPORT DATA (.XLSX)
            </a>
        </div>

        <!-- KPI CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Card Total -->
            <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo relative overflow-hidden group hover:bg-yellow-50 transition">
                <div class="absolute -right-4 -top-4 bg-unmaris-yellow w-20 h-20 rounded-full opacity-20 group-hover:scale-150 transition-transform"></div>
                <h3 class="text-unmaris-blue font-bold uppercase text-sm tracking-wider">Total Pendaftar</h3>
                <div class="text-5xl font-black text-unmaris-blue mt-2">{{ $totalPendaftar }}</div>
                <p class="text-xs font-bold text-gray-500 mt-2">Calon Mahasiswa</p>
            </div>

            <!-- Card Verifikasi -->
            <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo relative overflow-hidden group hover:bg-blue-50 transition">
                <div class="absolute -right-4 -top-4 bg-blue-500 w-20 h-20 rounded-full opacity-20 group-hover:scale-150 transition-transform"></div>
                <h3 class="text-unmaris-blue font-bold uppercase text-sm tracking-wider">Butuh Verifikasi</h3>
                <div class="text-5xl font-black text-blue-600 mt-2">{{ $menungguVerifikasi }}</div>
                <p class="text-xs font-bold text-gray-500 mt-2">
                    <a href="{{ route('admin.pendaftar.index') }}" class="underline hover:text-blue-800">Segera Proses 👉</a>
                </p>
            </div>

            <!-- Card Lulus -->
            <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo relative overflow-hidden group hover:bg-green-50 transition">
                <div class="absolute -right-4 -top-4 bg-unmaris-green w-20 h-20 rounded-full opacity-20 group-hover:scale-150 transition-transform"></div>
                <h3 class="text-unmaris-blue font-bold uppercase text-sm tracking-wider">Lulus Seleksi</h3>
                <div class="text-5xl font-black text-unmaris-green mt-2">{{ $sudahLulus }}</div>
                <p class="text-xs font-bold text-gray-500 mt-2">Mahasiswa Baru</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- CHART -->
            <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo">
                <h3 class="font-black text-lg text-unmaris-blue mb-6 flex items-center">
                    <span class="bg-unmaris-yellow px-2 py-1 mr-2 border border-unmaris-blue rounded text-xs">TOP</span>
                    PEMINAT PRODI
                </h3>
                <div class="space-y-4">
                    @foreach($statsProdi as $stat)
                        <div>
                            <div class="flex justify-between text-sm font-bold mb-1 text-unmaris-blue">
                                <span>{{ $stat->pilihan_prodi_1 }}</span>
                                <span>{{ $stat->total }} Org</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 border border-unmaris-blue overflow-hidden">
                                <div class="bg-unmaris-blue h-4 rounded-full" style="width: {{ ($stat->total / ($totalPendaftar > 0 ? $totalPendaftar : 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if($statsProdi->isEmpty())
                        <p class="text-center text-gray-400 font-bold italic py-4">Belum ada data masuk.</p>
                    @endif
                </div>
            </div>

            <!-- LIST TERBARU -->
            <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-black text-lg text-unmaris-blue">📝 BARU MASUK</h3>
                    <a href="{{ route('admin.pendaftar.index') }}" class="text-xs font-bold text-blue-600 hover:underline border border-blue-600 px-2 py-1 rounded hover:bg-blue-50">Lihat Semua</a>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                            @forelse($terbaru as $p)
                            <tr class="hover:bg-yellow-50 transition">
                                <td class="py-3">
                                    <div class="font-bold text-unmaris-blue">{{ $p->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->asal_sekolah }}</div>
                                </td>
                                <td class="py-3 text-right">
                                    <span class="px-2 py-1 text-xs font-black rounded border border-gray-300 {{ $p->status_pendaftaran == 'submit' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ strtoupper($p->status_pendaftaran) }}
                                    </span>
                                    <br>
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $p->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-4 text-gray-400 font-bold">Belum ada pendaftar baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>