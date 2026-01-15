<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $nomor_hp = ''; // Property Baru
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['required', 'numeric', 'digits_between:10,15'], // Validasi No HP
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Pastikan kolom 'nomor_hp' ada di fillable model User & Database
        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header Sambutan -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 1px 1px 0px #FACC15;">
            🚀 Buat Akun Baru
        </h2>
        <p class="text-sm font-bold text-gray-500 mt-2 bg-yellow-100 inline-block px-3 py-1 rounded border-2 border-yellow-400">
            Langkah awal menjadi mahasiswa UNMARIS!
        </p>
    </div>

    <form wire:submit="register" class="space-y-5">
        
        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input wire:model="name" id="name" type="text" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400 uppercase" 
                   placeholder="SESUAI IJAZAH TERAKHIR" required autofocus autocomplete="name" />
            <p class="text-[10px] font-bold text-gray-500 mt-1">⚠️ Wajib sama persis dengan ijazah SMA/SMK.</p>
            <x-input-error :messages="$errors->get('name')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Nomor HP / WhatsApp (BARU) -->
        <div>
            <label for="nomor_hp" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">
                No.WhatsApp (Aktif) <span class="text-red-500">*</span>
            </label>
            <input wire:model="nomor_hp" id="nomor_hp" type="tel" inputmode="numeric" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="Contoh: 081234567890" required />
            <p class="text-[10px] font-bold text-gray-500 mt-1">Digunakan untuk info jadwal ujian & kelulusan.</p>
            <x-input-error :messages="$errors->get('nomor_hp')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">
                Alamat Email <span class="text-red-500">*</span>
            </label>
            <input wire:model="email" id="email" type="email" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="email.kamu@gmail.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">
                Buat Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input wire:model="password" id="password" :type="show ? 'text' : 'password'"
                       class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400 pr-10" 
                       placeholder="Minimal 8 karakter" required autocomplete="new-password" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-unmaris-blue focus:outline-none">
                    <!-- Icon Mata Terbuka (Show) -->
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Icon Mata Tertutup (Hide) -->
                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">
                Ulangi Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input wire:model="password_confirmation" id="password_confirmation" :type="show ? 'text' : 'password'"
                       class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400 pr-10" 
                       placeholder="Ketik ulang password di atas" required autocomplete="new-password" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-unmaris-blue focus:outline-none">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-4 rounded-xl border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider text-lg flex justify-center items-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed disabled:shadow-none">
                
                <span wire:loading.remove>DAFTAR SEKARANG</span>
                <span wire:loading.remove class="group-hover:translate-x-1 transition-transform">👉</span>

                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-unmaris-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    MENDAFTAR...
                </span>
            </button>
        </div>

        <div class="text-center mt-6 pt-4 border-t-2 border-dashed border-gray-300">
            <span class="text-xs font-bold text-gray-500">Sudah punya akun sebelumnya?</span>
            <a href="{{ route('login') }}" wire:navigate class="block mt-1 text-sm font-black text-unmaris-blue hover:text-blue-600 underline decoration-2 underline-offset-4">
                Masuk / Login Disini
            </a>
        </div>
    </form>
</div>