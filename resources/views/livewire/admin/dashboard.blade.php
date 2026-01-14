    <div class="py-10 font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- HEADER SECTION -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 2px 2px 0px #FACC15;">
                        ⚡ Command Center
                    </h1>
                    <p class="text-gray-600 font-bold">Pantau data PMB UNMARIS secara Real-time.</p>
                </div>
                
                <!-- Action Button -->
                <a href="{{ route('admin.export') }}" class="bg-unmaris-green hover:bg-green-600 text-white font-black py-3 px-6 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-neo-hover hover:translate-x-[2px] hover:translate-y-[2px] transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    EXPORT DATA (.XLSX)
                </a>
            </div>

            <!-- STATUS SINKRONISASI PRODI (NEW) -->
            @php
                $totalProdi = \App\Models\StudyProgram::count();
            @endphp

            <div class="mb-8">
                @if($totalProdi == 0)
                    <!-- ALERT: BELUM SYNC -->
                    <div class="bg-red-100 border-l-8 border-red-600 p-4 rounded-r-xl shadow-md flex flex-col md:flex-row justify-between items-center gap-4 animate-pulse">
                        <div class="flex items-center gap-4">
                            <div class="bg-red-600 text-white rounded-full p-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-red-800 text-lg uppercase">Data Prodi Kosong!</h3>
                                <p class="text-sm font-bold text-red-600">Formulir pendaftaran tidak dapat digunakan. Harap sinkronisasi data dari SIAKAD sekarang.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-black py-2 px-6 rounded-lg border-2 border-black shadow-sm uppercase text-sm whitespace-nowrap">
                            ⚙️ Sync Sekarang
                        </a>
                    </div>
                @else
                    <!-- INFO: SUDAH SYNC -->
                    <div class="bg-green-100 border-l-8 border-green-500 p-4 rounded-r-xl shadow-sm flex justify-between items-center">
                         <div class="flex items-center gap-4">
                            <div class="bg-green-500 text-white rounded-full p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-green-800 text-lg uppercase">Sistem Terhubung ke SIAKAD</h3>
                                <p class="text-sm font-bold text-green-600">{{ $totalProdi }} Program Studi aktif & tersinkronisasi.</p>
                            </div>
                        </div>
                        <div class="hidden md:block text-xs font-black text-green-800 bg-green-200 px-3 py-1 rounded-full border border-green-400">
                            STATUS: ONLINE
                        </div>
                    </div>
                @endif
            </div>

            <!-- KPI CARDS (Statistik Utama) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

                <!-- Card 1: Total Pendaftar -->
                <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo relative overflow-hidden group hover:bg-yellow-50 transition">
                    <div class="absolute -right-4 -top-4 bg-unmaris-yellow w-20 h-20 rounded-full opacity-20 group-hover:scale-150 transition-transform"></div>
                    <h3 class="text-unmaris-blue font-bold uppercase text-sm tracking-wider">Total Pendaftar</h3>
                    <div class="text-5xl font-black text-unmaris-blue mt-2">{{ $totalPendaftar }}</div>
                    <p class="text-xs font-bold text-gray-500 mt-2">Calon Mahasiswa</p>
                </div>

                <!-- Card 2: Menunggu Verifikasi (Critical) -->
                <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo relative overflow-hidden group hover:bg-blue-50 transition">
                    <div class="absolute -right-4 -top-4 bg-blue-500 w-20 h-20 rounded-full opacity-20 group-hover:scale-150 transition-transform"></div>
                    <h3 class="text-unmaris-blue font-bold uppercase text-sm tracking-wider">Butuh Verifikasi</h3>
                    <div class="text-5xl font-black text-blue-600 mt-2">{{ $menungguVerifikasi }}</div>
                    <p class="text-xs font-bold text-gray-500 mt-2">
                        <a href="{{ route('admin.pendaftar.index') }}" class="underline hover:text-blue-800">Segera Proses 👉</a>
                    </p>
                </div>

                <!-- Card 3: Sudah Lulus -->
                <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo relative overflow-hidden group hover:bg-green-50 transition">
                    <div class="absolute -right-4 -top-4 bg-unmaris-green w-20 h-20 rounded-full opacity-20 group-hover:scale-150 transition-transform"></div>
                    <h3 class="text-unmaris-blue font-bold uppercase text-sm tracking-wider">Lulus Seleksi</h3>
                    <div class="text-5xl font-black text-unmaris-green mt-2">{{ $sudahLulus }}</div>
                    <p class="text-xs font-bold text-gray-500 mt-2">Mahasiswa Baru</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- CHART: STATISTIK PRODI (Simple CSS Bar Chart) -->
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

                <!-- LIST: PENDAFTAR TERBARU -->
                <div class="bg-white border-2 border-unmaris-blue rounded-xl p-6 shadow-neo">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-black text-lg text-unmaris-blue">
                            📝 BARU MASUK
                        </h3>
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
                                        <span class="px-2 py-1 text-xs font-black rounded border border-gray-300
                                            {{ $p->status_pendaftaran == 'submit' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : '' }}
                                            {{ $p->status_pendaftaran == 'lulus' ? 'bg-green-100 text-green-800 border-green-300' : '' }}
                                            {{ $p->status_pendaftaran == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                        ">
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