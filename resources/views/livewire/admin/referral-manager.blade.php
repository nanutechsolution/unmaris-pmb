<div class="p-6">
    
    {{-- FLASH MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border-2 border-green-500 text-green-700 px-4 py-3 rounded-xl relative shadow-[4px_4px_0px_0px_rgba(34,197,94,1)]" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-black uppercase tracking-wide text-unmaris-blue">
            Manajemen Komisi Referral
        </h1>

        <button wire:click="create"
            class="px-4 py-2 bg-yellow-400 border-2 border-black rounded-xl font-bold uppercase text-sm
                   shadow-[3px_3px_0px_0px_#000] hover:-translate-y-1 hover:shadow-[5px_5px_0px_0px_#000] transition active:translate-y-0 active:shadow-none">
            + Tambah Reward
        </button>
    </div>

    {{-- FILTER --}}
    <div class="bg-white border-2 border-black rounded-xl p-4 mb-6 shadow-[4px_4px_0px_0px_#000]">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            {{-- Search --}}
            <div>
                <label class="text-xs font-bold uppercase mb-1 block">Cari</label>
                <input type="text" 
                    wire:model.live.debounce.500ms="search" 
                    placeholder="Nama Camaba / Referensi..."
                    class="w-full border-2 border-black rounded-lg px-3 py-2 font-semibold focus:ring-0 focus:border-yellow-400 focus:shadow-[2px_2px_0px_0px_#FACC15] transition">
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="text-xs font-bold uppercase mb-1 block">Status</label>
                <select wire:model.live="filterStatus"
                    class="w-full border-2 border-black rounded-lg px-3 py-2 font-semibold focus:ring-0 focus:border-yellow-400 focus:shadow-[2px_2px_0px_0px_#FACC15] transition">
                    <option value="">Semua Status</option>
                    <option value="eligible">Eligible</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            {{-- Per Page --}}
            <div>
                <label class="text-xs font-bold uppercase mb-1 block">Baris per Halaman</label>
                <select wire:model.live="perPage"
                    class="w-full border-2 border-black rounded-lg px-3 py-2 font-semibold focus:ring-0 focus:border-yellow-400 focus:shadow-[2px_2px_0px_0px_#FACC15] transition">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border-2 border-black rounded-xl shadow-[4px_4px_0px_0px_#000] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blue-900 text-white uppercase text-xs font-black tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama Camaba</th>
                        <th class="px-4 py-3 text-left">Skema</th>
                        <th class="px-4 py-3 text-left">Nominal</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-black">
                    @forelse ($rewards as $reward)
                        <tr class="hover:bg-yellow-50 transition font-medium text-gray-800">
                            <td class="px-4 py-3">
                                <div class="font-bold">{{ $reward->pendaftar->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">Ref: {{ $reward->pendaftar->nama_referensi ?? '-' }}</div>
                            </td>
                            
                            <td class="px-4 py-3">
                                <span class="bg-gray-200 border border-black px-2 py-1 rounded text-xs font-bold">
                                    {{ $reward->scheme->name ?? '-' }}
                                </span>
                            </td>
                            
                            <td class="px-4 py-3 font-black text-green-700">
                                Rp {{ number_format($reward->reward_amount,0,',','.') }}
                            </td>
                            
                            <td class="px-4 py-3">
                                @if($reward->status === 'paid')
                                    <span class="px-2 py-1 bg-green-400 border border-black text-black rounded text-xs font-black shadow-[2px_2px_0px_0px_#000]">
                                        PAID
                                    </span>
                                @elseif($reward->status === 'eligible')
                                    <span class="px-2 py-1 bg-yellow-400 border border-black text-black rounded text-xs font-black shadow-[2px_2px_0px_0px_#000]">
                                        ELIGIBLE
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-red-500 border border-black text-white rounded text-xs font-black shadow-[2px_2px_0px_0px_#000]">
                                        CANCELLED
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-3 text-center space-x-1 whitespace-nowrap">
                                <button wire:click="edit({{ $reward->id }})" 
                                    class="px-2 py-1 border border-black rounded bg-white hover:bg-gray-100 text-xs font-bold transition">
                                    Edit
                                </button>
                                
                                @if($reward->status !== 'paid')
                                    <button wire:click="markAsPaid({{ $reward->id }})" 
                                        wire:confirm="Yakin tandai sebagai PAID?"
                                        class="px-2 py-1 bg-green-500 text-white border border-black rounded text-xs font-bold hover:bg-green-600 transition">
                                        Pay
                                    </button>
                                @endif

                                <button wire:click="delete({{ $reward->id }})" 
                                    wire:confirm="Yakin ingin menghapus data ini?"
                                    class="px-2 py-1 bg-red-500 text-white border border-black rounded text-xs font-bold hover:bg-red-600 transition">
                                    Del
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <div class="text-gray-400 font-bold text-lg">Tidak ada data ditemukan.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t-2 border-black bg-gray-50">
            {{ $rewards->links() }}
        </div>
    </div>

    {{-- MODAL (Create/Edit) --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4">
        <div class="bg-white border-4 border-black rounded-2xl w-full max-w-lg shadow-[8px_8px_0px_0px_#000] relative animate-fade-in-up">
            
            {{-- Modal Header --}}
            <div class="bg-yellow-400 border-b-4 border-black p-4 rounded-t-xl flex justify-between items-center">
                <h2 class="text-xl font-black uppercase">{{ $isEdit ? 'Edit Reward' : 'Tambah Reward' }}</h2>
                <button wire:click="closeModal" class="text-black font-bold hover:text-red-600 text-xl">&times;</button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <form wire:submit.prevent="save">
                    
                    {{-- Input: Camaba --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold uppercase mb-2">Pilih Pendaftar (Camaba)</label>
                        <select wire:model="pendaftar_id" class="w-full border-2 border-black rounded-lg px-3 py-2 font-medium focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <option value="">-- Pilih Camaba --</option>
                            @foreach($pendaftars as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('pendaftar_id') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Input: Skema --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold uppercase mb-2">Skema Referral</label>
                        <select wire:model="referral_scheme_id" class="w-full border-2 border-black rounded-lg px-3 py-2 font-medium focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <option value="">-- Pilih Skema --</option>
                            @foreach($schemes as $scheme)
                                <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                            @endforeach
                        </select>
                        @error('referral_scheme_id') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Input: Nominal --}}
                    <div class="mb-4">
                        <label class="block text-sm font-bold uppercase mb-2">Nominal Reward (Rp)</label>
                        <input type="number" wire:model="reward_amount" class="w-full border-2 border-black rounded-lg px-3 py-2 font-medium focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        @error('reward_amount') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Input: Status --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold uppercase mb-2">Status</label>
                        <select wire:model="status" class="w-full border-2 border-black rounded-lg px-3 py-2 font-medium focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <option value="eligible">Eligible (Siap Bayar)</option>
                            <option value="paid">Paid (Sudah Dibayar)</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" 
                            class="px-4 py-2 border-2 border-black text-black font-bold rounded-lg hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-black text-white border-2 border-black font-bold rounded-lg hover:bg-gray-800 transition shadow-[2px_2px_0px_0px_#888]">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @endif

</div>