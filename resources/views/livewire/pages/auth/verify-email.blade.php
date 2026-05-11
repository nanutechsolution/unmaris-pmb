<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\SiteSetting;

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

    /**
     * Mengambil dan memformat array nomor WhatsApp Admin dari database.
     * Mendukung multi-admin jika dipisahkan dengan koma (,) atau (/)
     */
    public function getAdminWaListProperty(): array
    {
        $setting = SiteSetting::first();
        $rawWa = $setting ? $setting->no_wa_admin : '6281216156883'; // Fallback default
        
        // Memisahkan string berdasarkan koma, titik koma, atau garis miring
        $waArray = preg_split('/[,;\/]+/', $rawWa);
        $formattedNumbers = [];

        foreach ($waArray as $noWa) {
            // Bersihkan karakter non-angka
            $cleanWa = preg_replace('/[^0-9]/', '', $noWa);
            
            if (empty($cleanWa)) continue;
            
            // Ubah awalan 0 menjadi 62 agar sesuai standar API WhatsApp
            if (str_starts_with($cleanWa, '0')) {
                $cleanWa = '62' . substr($cleanWa, 1);
            }
            
            $formattedNumbers[] = $cleanWa;
        }
        
        // Jika kosong setelah dibersihkan, kembalikan nomor default
        return count($formattedNumbers) > 0 ? $formattedNumbers : ['6281234567890'];
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

    <div class="mb-6 text-sm font-medium text-gray-700 leading-relaxed text-center border-2 border-dashed border-gray-300 p-6 rounded-xl bg-gray-50">
        <p class="mb-2">Link verifikasi telah dikirim ke:</p>
        
        <!-- MENAMPILKAN EMAIL USER (UX IMPROVEMENT) -->
        <div class="font-black text-lg text-unmaris-blue break-all bg-white border-2 border-unmaris-blue py-2 px-4 rounded-lg shadow-sm inline-block mb-4">
            {{ Auth::user()->email }}
        </div>

        <p class="text-xs text-gray-500 italic">
            {{ __('Salah ketik email? Klik tombol "Keluar" di bawah dan daftar ulang dengan email yang benar.') }}
        </p>
    </div>

    <!-- Status Session -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-bold text-sm text-green-600 bg-green-100 p-3 rounded-lg border-2 border-green-500 text-center animate-fade-in-down">
            {{ __('Link verifikasi BARU telah dikirim ulang.') }}
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

    <!-- Bantuan Verifikasi (WhatsApp Admin) -->
    <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-300">
        <p class="text-sm font-bold text-gray-600 text-center mb-3">Punya kendala? Hubungi Admin PMB</p>
        
        <div class="flex flex-col gap-3">
            @foreach($this->adminWaList as $index => $waNumber)
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Admin PMB UNMARIS, saya butuh bantuan terkait verifikasi akun. Email yang saya daftarkan adalah: ' . Auth::user()->email) }}" 
                   target="_blank"
                   class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-3 rounded-xl border-2 border-black shadow-neo hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all uppercase text-sm flex justify-center items-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Bantuan via WhatsApp {{ count($this->adminWaList) > 1 ? 'Admin ' . ($index + 1) : '' }}
                </a>
            @endforeach
        </div>
    </div>
</div>