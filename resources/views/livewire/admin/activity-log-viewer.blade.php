<div class="space-y-6">
    
    <!-- HEADER -->
    <div class="bg-gray-800 p-6 rounded-xl border-4 border-black shadow-neo">
        <h2 class="text-white font-black text-2xl uppercase tracking-wider flex items-center gap-3">
            🕵️‍♂️ Log Aktivitas Sistem
        </h2>
        <p class="text-gray-400 font-bold mt-1">Rekaman jejak digital seluruh aktivitas admin dan sistem.</p>
    </div>

    <!-- SEARCH -->
    <div class="flex justify-end">
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce="search" type="text" placeholder="Cari Log (User, Aksi, Info)..." 
                   class="w-full bg-white border-2 border-black rounded-lg px-4 py-2 font-bold focus:shadow-neo transition-all">
        </div>
    </div>

    <!-- LOG TABLE -->
    <div class="bg-white border-4 border-black shadow-neo rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left font-mono text-sm">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="p-4 font-bold uppercase">Waktu</th>
                        <th class="p-4 font-bold uppercase">Pelaku (User)</th>
                        <th class="p-4 font-bold uppercase">Aksi</th>
                        <th class="p-4 font-bold uppercase">Detail / Deskripsi</th>
                        <th class="p-4 font-bold uppercase text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 whitespace-nowrap text-gray-500">
                                {{ $log->created_at->format('d M Y H:i:s') }}
                            </td>
                            <td class="p-4 font-bold text-unmaris-blue">
                                {{ $log->user->name }}
                                <span class="block text-[10px] text-gray-400 uppercase">{{ $log->user->role }}</span>
                            </td>
                            <td class="p-4">
                                @php
                                    $badgeColor = match($log->action) {
                                        'LOGIN' => 'bg-green-100 text-green-700 border-green-300',
                                        'UPDATE' => 'bg-blue-100 text-blue-700 border-blue-300',
                                        'DELETE' => 'bg-red-100 text-red-700 border-red-300',
                                        'CREATE' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                        'SYNC' => 'bg-purple-100 text-purple-700 border-purple-300',
                                        default => 'bg-gray-100 text-gray-700 border-gray-300'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded border {{ $badgeColor }} font-bold text-xs">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-black">{{ $log->subject }}</span>
                                <p class="text-gray-600 mt-1">{{ $log->description }}</p>
                            </td>
                            <td class="p-4 text-right text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 font-bold italic">
                                Belum ada aktivitas terekam.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50 border-t-4 border-black">
            {{ $logs->links() }}
        </div>
    </div>
</div>