<div class="space-y-8 font-sans">
    
    <!-- HEADER -->
    <div class="bg-unmaris-blue p-6 rounded-xl border-4 border-black shadow-neo flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-white font-black text-2xl uppercase tracking-wider flex items-center gap-2">
                🌍 Peta Sebaran Pendaftar
            </h2>
            <p class="text-blue-200 font-bold mt-1">Analisis demografi asal daerah & sekolah calon mahasiswa.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border-2 border-black font-black text-unmaris-blue shadow-sm">
            Total Data: {{ $totalPendaftar }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- TOP KOTA ASAL -->
        <div class="bg-white border-4 border-black rounded-xl p-6 shadow-neo-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-yellow-100 px-3 py-1 border-b-2 border-l-2 border-black font-black text-xs uppercase">
                Based on Birthplace
            </div>
            
            <h3 class="font-black text-xl text-black mb-6 flex items-center gap-2">
                🏙️ Top 10 Kota Asal
            </h3>

            <div class="space-y-4">
                @foreach($topCities as $city)
                    @php
                        $percent = $totalPendaftar > 0 ? ($city->total / $totalPendaftar) * 100 : 0;
                        // Warna-warni bar
                        $colors = ['bg-red-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400', 'bg-purple-400'];
                        $barColor = $colors[$loop->index % 5];
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-end mb-1">
                            <span class="font-bold text-gray-700 uppercase text-sm">{{ $city->tempat_lahir }}</span>
                            <span class="font-black text-black">{{ $city->total }} <span class="text-gray-400 text-xs">Org</span></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 border-2 border-black overflow-hidden relative">
                            <div class="{{ $barColor }} h-full border-r-2 border-black transition-all duration-1000 group-hover:opacity-80" 
                                 style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach

                @if($topCities->isEmpty())
                    <p class="text-center text-gray-400 italic font-bold">Belum ada data pendaftar.</p>
                @endif
            </div>
        </div>

        <!-- TOP ASAL SEKOLAH -->
        <div class="bg-white border-4 border-black rounded-xl p-6 shadow-neo-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-blue-100 px-3 py-1 border-b-2 border-l-2 border-black font-black text-xs uppercase">
                Feeder Schools
            </div>

            <h3 class="font-black text-xl text-black mb-6 flex items-center gap-2">
                🏫 Top 10 Asal Sekolah
            </h3>

            <div class="space-y-4">
                @foreach($topSchools as $school)
                    @php
                        $percent = $totalPendaftar > 0 ? ($school->total / $totalPendaftar) * 100 : 0;
                        $barColor = 'bg-unmaris-blue';
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-end mb-1">
                            <span class="font-bold text-gray-700 uppercase text-xs truncate max-w-[70%]">{{ $school->asal_sekolah }}</span>
                            <span class="font-black text-black">{{ $school->total }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-r-lg h-3 border-y-2 border-r-2 border-black overflow-hidden">
                            <div class="{{ $barColor }} h-full transition-all duration-1000 group-hover:bg-blue-800" 
                                 style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach

                @if($topSchools->isEmpty())
                    <p class="text-center text-gray-400 italic font-bold">Belum ada data sekolah.</p>
                @endif
            </div>
        </div>

    </div>

    <!-- STRATEGI REKOMENDASI (Auto Insight) -->
    @if($topCities->isNotEmpty())
    <div class="bg-yellow-50 border-4 border-yellow-500 rounded-xl p-6 shadow-sm flex items-start gap-4">
        <div class="text-4xl">💡</div>
        <div>
            <h4 class="font-black text-yellow-800 text-lg uppercase">Insight Marketing</h4>
            <p class="text-sm font-bold text-yellow-900 mt-1">
                Berdasarkan data, mayoritas pendaftar berasal dari <span class="underline decoration-2">{{ $topCities->first()->tempat_lahir }}</span>. 
                Disarankan untuk meningkatkan kegiatan sosialisasi atau *roadshow* ke sekolah-sekolah di wilayah tersebut untuk memaksimalkan potensi pendaftar di gelombang berikutnya.
            </p>
        </div>
    </div>
    @endif

</div>