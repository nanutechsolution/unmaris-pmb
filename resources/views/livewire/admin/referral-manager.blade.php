<div class="p-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-black uppercase tracking-wide text-unmaris-blue">
            Manajemen Komisi Referral
        </h1>

        <button wire:click="create"
            class="px-4 py-2 bg-yellow-400 border-2 border-black rounded-xl font-bold uppercase text-sm
                   shadow-[3px_3px_0px_0px_#000] hover:-translate-y-1 transition">
            + Tambah Reward
        </button>
    </div>

    {{-- FILTER --}}
    <div class="bg-white border-2 border-black rounded-xl p-4 mb-6 shadow-[4px_4px_0px_0px_#000]">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <input type="text"
                wire:model.debounce.500ms="search"
                placeholder="Cari nama / referensi..."
                class="border-2 border-black rounded-lg px-3 py-2 font-semibold focus:ring-0 focus:border-yellow-400">

            <select wire:model="filterStatus"
                class="border-2 border-black rounded-lg px-3 py-2 font-semibold focus:ring-0 focus:border-yellow-400">
                <option value="">Semua Status</option>
                <option value="eligible">Eligible</option>
                <option value="paid">Paid</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <select wire:model="perPage"
                class="border-2 border-black rounded-lg px-3 py-2 font-semibold focus:ring-0 focus:border-yellow-400">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white border-2 border-black rounded-xl shadow-[4px_4px_0px_0px_#000] overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-unmaris-blue text-white uppercase text-xs font-black">
                <tr>
                    <th class="px-4 py-3 text-left">Nama Camaba</th>
                    <th class="px-4 py-3 text-left">Skema</th>
                    <th class="px-4 py-3 text-left">Nominal</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rewards as $reward)
                    <tr class="border-t-2 border-black hover:bg-yellow-50 transition">
                        <td class="px-4 py-3 font-semibold">
                            {{ $reward->pendaftar->user->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $reward->scheme->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 font-bold">
                            Rp {{ number_format($reward->reward_amount,0,',','.') }}
                        </td>

                        <td class="px-4 py-3">
                            @if($reward->status === 'paid')
                                <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold">
                                    PAID
                                </span>
                            @elseif($reward->status === 'eligible')
                                <span class="px-3 py-1 bg-yellow-400 text-black rounded-full text-xs font-bold">
                                    ELIGIBLE
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold">
                                    CANCELLED
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="edit({{ $reward->id }})"
                                class="px-3 py-1 border-2 border-black rounded-lg text-xs font-bold hover:bg-blue-100">
                                Edit
                            </button>

                            @if($reward->status !== 'paid')
                                <button wire:click="markAsPaid({{ $reward->id }})"
                                    class="px-3 py-1 bg-green-500 text-white border-2 border-black rounded-lg text-xs font-bold hover:-translate-y-1 transition">
                                    Mark Paid
                                </button>
                            @endif

                            <button wire:click="delete({{ $reward->id }})"
                                class="px-3 py-1 bg-red-500 text-white border-2 border-black rounded-lg text-xs font-bold hover:-translate-y-1 transition">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 font-bold text-gray-500">
                            Tidak ada data reward.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t-2 border-black">
            {{ $rewards->links() }}
        </div>
    </div>
</div>
