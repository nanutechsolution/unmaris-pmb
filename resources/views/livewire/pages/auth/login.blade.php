<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header Sambutan -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 1px 1px 0px #FACC15;">
            👋 Selamat Datang Kembali
        </h2>
        <p class="text-sm font-bold text-gray-500 mt-2 bg-yellow-100 inline-block px-3 py-1 rounded border-2 border-yellow-400">
            Silakan masuk untuk melanjutkan ke portal.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        
        <!-- Email Address -->
        <div>
            <label for="email" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Alamat Email</label>
            <input wire:model="form.email" id="email" type="email" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="email.kamu@gmail.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Password</label>
            <input wire:model="form.password" id="password" type="password" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="Masukkan password akun" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Remember Me & Forgot PW -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-2 border-black text-unmaris-blue shadow-sm focus:ring-0 cursor-pointer w-5 h-5" name="remember">
                <span class="ms-2 text-sm font-bold text-gray-600 group-hover:text-black transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-black text-unmaris-blue hover:text-blue-600 underline decoration-2 underline-offset-4" href="{{ route('password.request') }}" wire:navigate>
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Tombol Aksi dengan Loading State -->
        <div class="pt-4">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full bg-unmaris-blue hover:bg-blue-900 text-white font-black py-4 rounded-xl border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider text-lg flex justify-center items-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-x-[2px] disabled:translate-y-[2px]">
                
                <!-- Tampil Saat Normal -->
                <span wire:loading.remove>MASUK / LOGIN</span>
                <span wire:loading.remove class="group-hover:translate-x-1 transition-transform">🚀</span>

                <!-- Tampil Saat Loading -->
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    MEMPROSES...
                </span>
            </button>
        </div>

        <div class="text-center mt-6 pt-4 border-t-2 border-dashed border-gray-300">
            <span class="text-xs font-bold text-gray-500">Belum punya akun pendaftaran?</span>
            <a href="{{ route('register') }}" wire:navigate class="block mt-1 text-sm font-black text-unmaris-yellow hover:text-yellow-600 underline decoration-2 underline-offset-4" style="text-shadow: 1px 1px 0px black; -webkit-text-stroke: 0.5px black;">
                Daftar Akun Baru Disini
            </a>
        </div>
    </form>
</div>