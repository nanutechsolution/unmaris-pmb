<x-camaba-layout>
    <!-- Slot Header: Cukup teks saja karena H2 sudah ada di layout -->
    <x-slot name="header">
        Formulir Pendaftaran
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Panggil Component Livewire -->
            @livewire('pendaftaran-wizard')
        </div>
    </div>
</x-camaba-layout>