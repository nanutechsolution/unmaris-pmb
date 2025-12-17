<div class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-6 font-sans">
    
    <!-- KOLOM KIRI: DAFTAR TIKET -->
    <!-- Di mobile disembunyikan jika chat sedang terbuka -->
    <div class="w-full md:w-1/3 bg-white border-4 border-black rounded-xl shadow-neo flex flex-col {{ $isChatOpen ? 'hidden md:flex' : 'flex' }}">
        
        <!-- Header Filter -->
        <div class="p-4 border-b-2 border-gray-200 bg-gray-50 rounded-t-lg">
            <h2 class="font-black text-xl text-unmaris-blue uppercase mb-3">📨 Kotak Masuk</h2>
            <div class="flex gap-2">
                <select wire:model.live="filterStatus" class="w-1/2 border-2 border-black rounded-lg px-2 py-1 text-sm font-bold focus:shadow-neo">
                    <option value="open">Perlu Balasan</option>
                    <option value="answered">Sudah Dibalas</option>
                    <option value="closed">Selesai (Closed)</option>
                    <option value="all">Semua Tiket</option>
                </select>
                <input wire:model.live.debounce="search" type="text" placeholder="Cari..." class="w-1/2 border-2 border-black rounded-lg px-2 py-1 text-sm font-bold focus:shadow-neo">
            </div>
        </div>

        <!-- List Item -->
        <div class="flex-1 overflow-y-auto p-2 space-y-2">
            @forelse($tickets as $t)
                <div wire:click="openTicket({{ $t->id }})" 
                     class="p-3 rounded-lg border-2 cursor-pointer transition-all hover:translate-x-1
                     {{ $activeTicket && $activeTicket->id == $t->id ? 'bg-blue-50 border-unmaris-blue shadow-sm' : 'bg-white border-gray-200 hover:border-black' }}">
                    
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-black text-unmaris-blue bg-blue-100 px-1.5 rounded uppercase">
                            {{ $t->category }}
                        </span>
                        <span class="text-[10px] text-gray-400 font-bold">{{ $t->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <h4 class="font-bold text-sm text-black mt-1 truncate">{{ $t->subject }}</h4>
                    <p class="text-xs text-gray-500 mt-1">Oleh: <span class="font-bold">{{ $t->user->name }}</span></p>
                    
                    <div class="mt-2 text-right">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $t->status_color }}">
                            {{ $t->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 font-bold text-sm italic">
                    Tidak ada tiket.
                </div>
            @endforelse
        </div>
        
        <!-- Pagination Kecil -->
        <div class="p-2 border-t-2 border-gray-200 bg-gray-50 text-xs">
            {{ $tickets->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- KOLOM KANAN: CHAT ROOM -->
    <div class="w-full md:w-2/3 bg-white border-4 border-unmaris-blue rounded-xl shadow-neo-lg flex flex-col relative overflow-hidden {{ !$isChatOpen ? 'hidden md:flex' : 'flex' }}">
        
        @if($activeTicket)
            <!-- Chat Header -->
            <div class="p-4 bg-unmaris-blue text-white flex justify-between items-center shadow-md z-10">
                <div class="flex items-center gap-3">
                    <button wire:click="backToList" class="md:hidden text-white hover:text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <div>
                        <h3 class="font-black text-lg leading-none">{{ $activeTicket->subject }}</h3>
                        <p class="text-xs text-blue-200 mt-1">
                            {{ $activeTicket->user->name }} • {{ $activeTicket->created_at->format('d M H:i') }}
                        </p>
                    </div>
                </div>
                
                @if($activeTicket->status != 'closed')
                    <button wire:click="closeTicket" onclick="return confirm('Tandai masalah ini selesai?')" class="bg-green-500 hover:bg-green-600 text-white text-xs font-black px-3 py-2 rounded border-2 border-white shadow-sm uppercase tracking-wide transition-transform hover:scale-105">
                        ✓ Tandai Selesai
                    </button>
                @else
                    <span class="bg-gray-500 text-white text-xs font-black px-3 py-1 rounded border-2 border-gray-400 uppercase">
                        🔒 Selesai
                    </span>
                @endif
            </div>

            <!-- Chat Bubbles -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50 relative">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>

                @foreach($activeTicket->replies as $reply)
                    <div class="flex {{ $reply->user_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] relative group">
                            <div class="p-4 rounded-2xl border-2 border-black text-sm font-medium shadow-sm
                                {{ $reply->user_id == Auth::id() 
                                    ? 'bg-unmaris-yellow text-black rounded-tr-none' 
                                    : 'bg-white text-gray-800 rounded-tl-none' }}">
                                {{ $reply->message }}
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 font-bold {{ $reply->user_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                {{ $reply->user->role == 'admin' ? 'Admin' : $reply->user->name }} • {{ $reply->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Chat Input -->
            <div class="p-4 bg-white border-t-2 border-gray-200">
                @if($activeTicket->status == 'closed')
                    <div class="text-center p-3 bg-gray-100 rounded-lg border-2 border-gray-200 text-gray-500 font-bold text-sm">
                        🚫 Tiket ini telah ditutup.
                    </div>
                @else
                    <form wire:submit.prevent="sendReply" class="flex gap-2">
                        <input type="text" wire:model="replyMessage" placeholder="Ketik balasan..." 
                               class="flex-1 border-2 border-black rounded-lg px-4 py-3 focus:outline-none focus:border-unmaris-blue focus:shadow-neo transition-all">
                        <button type="submit" class="bg-unmaris-blue text-white p-3 rounded-lg border-2 border-black hover:bg-blue-800 hover:shadow-neo transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                @endif
            </div>

        @else
            <!-- Empty State (Belum Pilih Chat) -->
            <div class="flex-1 flex flex-col items-center justify-center text-gray-300">
                <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <p class="text-lg font-black uppercase">Pilih tiket untuk membaca</p>
            </div>
        @endif
    </div>
</div>