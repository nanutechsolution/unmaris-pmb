<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- Header Sambutan -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 1px 1px 0px #FACC15;">
            🔑 Lupa Password?
        </h2>
        <p class="text-sm font-bold text-gray-500 mt-2 bg-yellow-100 inline-block px-3 py-1 rounded border-2 border-yellow-400">
            Tenang, kami akan bantu reset akunmu.
        </p>
    </div>

    <!-- Instructions -->
    <div class="mb-6 p-4 bg-blue-50 border-2 border-unmaris-blue rounded-lg text-sm font-medium text-unmaris-blue">
        Masukkan alamat email yang Anda gunakan saat mendaftar. Kami akan mengirimkan link untuk membuat password baru.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        
        <!-- Email Address -->
        <div>
            <label for="email" class="block font-black text-sm text-unmaris-blue mb-1 uppercase">Alamat Email Terdaftar</label>
            <input wire:model="email" id="email" type="email" 
                   class="w-full bg-gray-50 border-2 border-black rounded-lg px-4 py-3 font-bold text-gray-800 focus:bg-white focus:outline-none focus:shadow-neo transition-all placeholder-gray-400" 
                   placeholder="email.kamu@gmail.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-1 font-bold text-red-500 text-xs" />
        </div>

        <!-- Tombol Aksi -->
        <div>
            <button type="submit" class="w-full bg-unmaris-blue hover:bg-blue-900 text-white font-black py-4 rounded-xl border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider text-lg flex justify-center items-center gap-2 group">
                <span>KIRIM LINK RESET</span>
                <span class="group-hover:translate-x-1 transition-transform">📨</span>
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center pt-2 border-t-2 border-dashed border-gray-300">
            <a href="{{ route('login') }}" wire:navigate class="text-sm font-bold text-gray-500 hover:text-unmaris-blue transition-colors flex items-center justify-center gap-1 group">
                <span class="group-hover:-translate-x-1 transition-transform">👈</span> Kembali ke Halaman Login
            </a>
        </div>
    </form>
</div>