<div class="p-6 space-y-6">

    <!-- Judul -->
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Referral Scheme</h1>

    <!-- Search & Tambah -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <input type="text" wire:model="search" placeholder="Cari scheme..." class="border rounded px-3 py-2 w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button wire:click="$set('editId', null)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            Tambah Scheme Baru
        </button>
    </div>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mt-2">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah / Edit -->
    <div class="border rounded shadow p-4 bg-white mt-4">
        <h2 class="text-xl font-semibold mb-4">{{ $editId ? 'Edit Scheme' : 'Tambah Scheme Baru' }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block mb-1 font-medium">Nama Scheme</label>
                <input type="text" wire:model="name" class="border rounded w-full px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Jalur (opsional)</label>
                <input type="text" wire:model="jalur" class="border rounded w-full px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('jalur') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Reward Amount</label>
                <input type="number" wire:model="reward_amount" class="border rounded w-full px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('reward_amount') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Target Minimal</label>
                <input type="number" wire:model="target_min" class="border rounded w-full px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('target_min') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Start Date</label>
                <input type="date" wire:model="start_date" class="border rounded w-full px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">End Date (opsional)</label>
                <input type="date" wire:model="end_date" class="border rounded w-full px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="flex items-center space-x-2 mt-2 md:mt-0">
                <input type="checkbox" wire:model="is_active" id="is_active" class="form-checkbox h-5 w-5 text-blue-600">
                <label for="is_active" class="font-medium">Aktifkan Scheme</label>
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button wire:click="save" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                {{ $editId ? 'Update' : 'Simpan' }}
            </button>
            <button wire:click="resetForm" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                Reset
            </button>
        </div>
    </div>

    <!-- Tabel Scheme -->
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Jalur</th>
                    <th class="px-4 py-2 border">Reward</th>
                    <th class="px-4 py-2 border">Target Min</th>
                    <th class="px-4 py-2 border">Start Date</th>
                    <th class="px-4 py-2 border">End Date</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schemes as $scheme)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $scheme->name }}</td>
                        <td class="px-4 py-2 border">{{ $scheme->jalur ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $scheme->reward_amount }}</td>
                        <td class="px-4 py-2 border">{{ $scheme->target_min }}</td>
                        <td class="px-4 py-2 border">{{ $scheme->start_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border">{{ $scheme->end_date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-4 py-2 border">
                            @if($scheme->is_active)
                                <span class="text-green-600 font-bold">Aktif</span>
                            @else
                                <span class="text-gray-600">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border flex flex-wrap gap-1">
                            <button wire:click="edit({{ $scheme->id }})" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-sm">Edit</button>
                            <button wire:click="delete({{ $scheme->id }})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">Hapus</button>
                            @if(!$scheme->is_active)
                                <button wire:click="toggleActive({{ $scheme->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm">Aktifkan</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 border text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $schemes->links() }}
    </div>

</div>
