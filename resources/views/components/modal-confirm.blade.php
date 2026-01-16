@props([
    'show',
    'title',
    'color',
    'prodi',
    'action',
    'pilihan' => null, // <-- tambahkan ini
]);

<div>
    <div x-show="{{ $show }}" x-transition.opacity class="fixed inset-0 bg-black/60 z-40"
        @click="{{ $show }}=false" x-cloak></div>

    <div x-show="{{ $show }}" x-transition class="fixed inset-0 z-50 flex items-center justify-center px-4"
        x-cloak>

        <div class="bg-white max-w-lg w-full rounded-2xl border-4 border-{{ $color }}-600 shadow-2xl">

            <div class="bg-{{ $color }}-600 text-white p-4 rounded-t-xl font-black uppercase">
                {{ $title }}
            </div>

            <div class="p-6 space-y-4">
                <p class="font-bold">Program Studi:</p>
                <p class="text-2xl font-black text-{{ $color }}-700">
                    {{ $prodi }}
                </p>

                {{ $slot }}

                <div class="text-xs bg-red-50 text-red-600 p-3 rounded border font-bold">
                    ⚠️ Keputusan ini FINAL dan tidak dapat diubah
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-3 p-4 border-t bg-gray-50 rounded-b-xl justify-end">

                <!-- BATAL -->
                <button @click="{{ $show }}=false"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-md w-full md:w-auto px-6 transition">
                    BATAL
                </button>
                <!-- YA, LULUSKAN -->
                <form action="{{ $action }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="pilihan" value="{{ $pilihan }}">
                    <button type="submit"
                        class="w-full bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white font-black py-2 rounded-md shadow-md transition flex items-center justify-center gap-2 px-6">
                        ✅ YA, LULUSKAN
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>
