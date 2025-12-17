<div class="max-w-4xl mx-auto py-6 font-sans px-4">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight">💬 Pusat Bantuan</h1>
            <p class="text-gray-500 font-bold text-sm">Punya kendala? Hubungi admin langsung.</p>
        </div>
        @if($viewState == 'list')
            <button wire:click="openCreate" class="bg-unmaris-yellow text-unmaris-blue font-black px-4 py-2 rounded-lg border-2 border-unmaris-blue shadow-neo hover:shadow-none transition-all uppercase text-sm">
                + Tiket Baru
            </button>
        @else
            <button wire:click="cancel" class="bg-white text-gray-600 font-bold px-4 py-2 rounded-lg border-2 border-gray-300 hover:bg-gray-100 uppercase text-sm">
                Kembali
            </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 font-bold shadow-sm">{{ session('message') }}</div>
    @endif

    <!-- VIEW 1: LIST TIKET -->
    @if($viewState == 'list')
        <div class="bg-white border-4 border-unmaris-blue shadow-neo-lg rounded-xl overflow-hidden">
            @forelse($tickets as $t)
                <div wire:click="show({{ $t->id }})" class="p-4 border-b border-gray-100 hover:bg-blue-50 cursor-pointer transition flex justify-between items-center group">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-xl bg-gray-100 border-2 border-gray-200 group-hover:border-unmaris-blue group-hover:bg-white transition">
                            {{ $t->category == 'pembayaran' ? '💸' : ($t->category == 'berkas' ? '📂' : '💬') }}
                        </div>
                        <div>
                            <h4 class="font-black text-unmaris-blue text-lg">{{ $t->subject }}</h4>
                            <p class="text-xs text-gray-500 font-bold uppercase">{{ $t->created_at->diffForHumans() }} • {{ ucfirst($t->category) }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $t->status_color }}">
                            {{ $t->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <div class="text-4xl mb-2">🤷‍♂️</div>
                    <h3 class="font-bold text-gray-500">Belum ada riwayat bantuan.</h3>
                </div>
            @endforelse
        </div>
    @endif

    <!-- VIEW 2: BUAT TIKET BARU -->
    @if($viewState == 'create')
        <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl p-6">
            <form wire:submit.prevent="store" class="space-y-4">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Judul Kendala</label>
                    <input type="text" wire:model="subject" placeholder="Misal: Gagal Upload Bukti Bayar" class="w-full border-2 border-black rounded-lg p-2 font-bold focus:shadow-neo transition-all outline-none">
                    @error('subject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kategori</label>
                    <select wire:model="category" class="w-full border-2 border-black rounded-lg p-2 font-bold focus:shadow-neo transition-all outline-none">
                        <option value="umum">Umum</option>
                        <option value="pembayaran">Pembayaran</option>
                        <option value="berkas">Berkas/Dokumen</option>
                        <option value="akun">Masalah Akun</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Detail Masalah</label>
                    <textarea wire:model="message" rows="4" placeholder="Jelaskan masalah Anda secara rinci..." class="w-full border-2 border-black rounded-lg p-2 font-medium focus:shadow-neo transition-all outline-none"></textarea>
                    @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full bg-unmaris-blue text-white font-black py-3 rounded-lg border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                    KIRIM TIKET
                </button>
            </form>
        </div>
    @endif

    <!-- VIEW 3: DETAIL CHAT -->
    @if($viewState == 'detail' && $activeTicket)
        <div class="bg-white border-4 border-unmaris-blue shadow-neo rounded-xl overflow-hidden flex flex-col h-[600px]">
            <!-- Header Chat -->
            <div class="bg-gray-100 p-4 border-b-2 border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-unmaris-blue text-lg">{{ $activeTicket->subject }}</h3>
                    <p class="text-xs text-gray-500">ID: #{{ $activeTicket->id }} • {{ ucfirst($activeTicket->category) }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $activeTicket->status_color }}">
                    {{ $activeTicket->status }}
                </span>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                @foreach($activeTicket->replies as $reply)
                    <div class="flex {{ $reply->user_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%]">
                            <div class="p-3 rounded-xl border-2 border-black text-sm font-medium
                                {{ $reply->user_id == Auth::id() ? 'bg-unmaris-yellow text-black rounded-tr-none' : 'bg-white text-gray-800 rounded-tl-none' }}">
                                {{ $reply->message }}
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 {{ $reply->user_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                {{ $reply->user->name }} • {{ $reply->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Reply -->
            <div class="p-4 bg-white border-t-2 border-gray-200">
                @if($activeTicket->status == 'closed')
                    <p class="text-center text-gray-500 font-bold text-sm bg-gray-100 p-2 rounded">Tiket ini telah ditutup.</p>
                    <button wire:click="sendReply" class="w-full mt-2 text-blue-600 text-xs font-bold hover:underline">Balas untuk membuka kembali</button>
                @endif
                
                <form wire:submit.prevent="sendReply" class="flex gap-2">
                    <input type="text" wire:model="replyMessage" placeholder="Tulis balasan..." class="flex-1 border-2 border-black rounded-lg px-4 py-2 focus:outline-none focus:border-unmaris-blue">
                    <button type="submit" class="bg-unmaris-blue text-white p-2 rounded-lg border-2 border-black hover:bg-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    @endif

</div>