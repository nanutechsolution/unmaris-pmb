<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(): void
    {
        // Logout manual agar tidak tergantung class Action external
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="text-6xl mb-4 animate-bounce">📧</div>
        <h2 class="text-2xl font-black text-unmaris-blue uppercase tracking-tight" style="text-shadow: 1px 1px 0px #FACC15;">
            Cek Email Kamu!
        </h2>
        <p class="text-sm font-bold text-gray-600 mt-2 bg-yellow-100 inline-block px-3 py-1 rounded border-2 border-black transform -rotate-1">
            Satu langkah lagi untuk bergabung.
        </p>
    </div>

    <div class="mb-6 text-sm font-medium text-gray-700 leading-relaxed text-center border-2 border-dashed border-gray-300 p-4 rounded-xl">
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, tolong verifikasi akunmu dengan mengklik link yang baru saja kami kirim ke emailmu.') }}
        <br><br>
        <span class="text-xs text-gray-500 italic">{{ __('Tidak menerima email? Cek folder Spam atau klik tombol di bawah.') }}</span>
    </div>

    <!-- Status Session -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-bold text-sm text-green-600 bg-green-100 p-3 rounded-lg border-2 border-green-500 text-center animate-fade-in-down">
            {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 mt-8">
        <!-- Tombol Kirim Ulang -->
        <button wire:click="sendVerification" 
                wire:loading.attr="disabled" 
                class="w-full bg-unmaris-blue hover:bg-blue-900 text-white font-black py-3 rounded-xl border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase tracking-wider flex justify-center items-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-x-[2px] disabled:translate-y-[2px]">
            
            <span wire:loading.remove wire:target="sendVerification">Kirim Ulang Link</span>
            <svg wire:loading.remove wire:target="sendVerification" class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>

            <span wire:loading wire:target="sendVerification" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                MENGIRIM...
            </span>
        </button>

        <!-- Tombol Logout -->
        <button wire:click="logout" 
                wire:loading.attr="disabled"
                class="w-full bg-white hover:bg-gray-100 text-gray-600 font-bold py-3 rounded-xl border-2 border-gray-400 hover:border-gray-600 transition-all uppercase text-xs flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
            
            <span wire:loading.remove wire:target="logout" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar / Ganti Akun
            </span>

            <span wire:loading wire:target="logout" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                KELUAR...
            </span>
        </button>
    </div>
</div>