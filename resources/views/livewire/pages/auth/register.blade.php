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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Set default role jika database mendukung, atau biarkan default migration handle
        // $validated['role'] = 'camaba'; 

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
            <label for="name" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Nama Lengkap</label>
            <input wire:model="name" id="name" type="text" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="Sesuai Ijazah / KTP" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Alamat Email</label>
            <input wire:model="email" id="email" type="email" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="email.kamu@gmail.com" required autocomplete="username" />
            <p class="text-[10px] font-bold text-gray-500 mt-1 flex items-center">
                <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                Pastikan email aktif untuk info kelulusan.
            </p>
            <x-input-error :messages="$errors->get('email')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Buat Password</label>
            <input wire:model="password" id="password" type="password" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="Minimal 8 karakter" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Ulangi Password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="Ketik ulang password di atas" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4">
            <button type="submit" class="w-full bg-unmaris-yellow hover:bg-yellow-400 text-unmaris-blue font-black py-4 rounded-xl border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider text-lg flex justify-center items-center gap-2 group">
                <span>DAFTAR SEKARANG</span>
                <span class="group-hover:translate-x-1 transition-transform">👉</span>
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