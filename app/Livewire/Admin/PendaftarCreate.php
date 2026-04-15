<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Pendaftar;
use App\Models\Gelombang;
use App\Models\StudyProgram;
use App\Services\Logger;
use App\Notifications\Admin\ManualRegistrationNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class PendaftarCreate extends Component
{
    // Properti Akun
    public $name, $email, $password;
    public $auto_generate_password = true;
    public $send_notification = true;

    // Properti Pendaftar
    public $nik, $nomor_hp, $jenis_kelamin;
    public $gelombang_id, $jalur_pendaftaran = 'reguler';
    public $pilihan_prodi_1;
    public $status_pembayaran = 'menunggu_pembayaran';

    /**
     * Inisialisasi data awal dengan pengamanan state.
     */
    public function mount()
    {
        $activeGelombang = Gelombang::where('is_active', true)->first();
        if ($activeGelombang) {
            $this->gelombang_id = $activeGelombang->id;
        }
    }

    /**
     * Reset password saat toggle auto-generate berubah untuk mencegah pengiriman password lama yang tersembunyi.
     */
    public function updatedAutoGeneratePassword($value)
    {
        if ($value) {
            $this->reset('password');
            $this->resetErrorBag('password');
        }
    }

    /**
     * Proses penyimpanan dengan pertahanan tingkat tinggi.
     */
    public function save()
    {
        // 1. SANITASI TOTAL (Mencegah input non-numerik menembus validasi)
        $this->nik = preg_replace('/[^0-9]/', '', (string) $this->nik);
        $this->nomor_hp = preg_replace('/[^0-9]/', '', (string) $this->nomor_hp);

        // 2. VALIDASI KETAT (Back-end as single source of truth)
        $this->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email:filter|unique:users,email',
            'password' => $this->auto_generate_password ? 'nullable' : 'required|min:8',
            'nik' => 'required|digits:16|unique:pendaftars,nik',
            'nomor_hp' => 'required|string|min:10|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'gelombang_id' => 'required|exists:gelombangs,id',
            'jalur_pendaftaran' => 'required|string|max:100',
            'pilihan_prodi_1' => 'required|string|max:255',
            'status_pembayaran' => 'required|in:menunggu_pembayaran,lunas',
        ], [
            'email.unique' => 'Alamat email sudah terdaftar dalam sistem.',
            'nik.unique' => 'NIK ini sudah digunakan oleh pendaftar lain.',
            'nik.digits' => 'NIK wajib terdiri dari tepat 16 digit angka.',
            'password.min' => 'Password manual terlalu lemah, minimal 8 karakter.',
            'name.min' => 'Nama terlalu pendek, pastikan memasukkan nama lengkap.',
        ]);

        // 3. DATABASE TRANSACTION (Atomicity: All or Nothing)
        try {
            $createdUser = DB::transaction(function () use (&$finalPassword) {
                // Generate password jika otomatis
                $finalPassword = $this->auto_generate_password ? Str::random(8) : $this->password;

                // Buat Akun Utama
                $user = User::create([
                    'name' => trim($this->name),
                    'email' => strtolower(trim($this->email)),
                    'password' => Hash::make($finalPassword),
                    'role' => 'camaba',
                ]);

                // Logika status pendaftaran otomatis
                // Jika lunas (Bypass), langsung ke tahap Verifikasi Berkas
                $statusPendaftaran = $this->status_pembayaran === 'lunas' ? 'verifikasi' : 'submit';

                // Buat Data Pendaftaran
                $pendaftar = Pendaftar::create([
                    'user_id' => $user->id,
                    'gelombang_id' => $this->gelombang_id,
                    'nik' => $this->nik,
                    'nomor_hp' => $this->nomor_hp,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'pilihan_prodi_1' => $this->pilihan_prodi_1,
                    'status_pembayaran' => $this->status_pembayaran,
                    'status_pendaftaran' => $statusPendaftaran,
                    'is_locked' => false,
                ]);

                return $user;
            });

            // 4. NOTIFIKASI (Try-Catch agar kegagalan API WA/Email tidak merusak transaksi DB)
            if ($this->send_notification && $createdUser) {
                try {
                    $createdUser->notify(new ManualRegistrationNotification(
                        $createdUser->name, 
                        $createdUser->email, 
                        $finalPassword
                    ));
                } catch (\Exception $e) {
                    Logger::record('ERROR', 'Notification Failed', "Gagal kirim notifikasi pendaftaran manual ke {$createdUser->email}");
                    // Transaksi DB sudah sukses, tidak perlu di-rollback hanya karena email gagal
                }
            }

            Logger::record('CREATE', 'Registrasi Manual', "Admin mendaftarkan pendaftar baru: {$createdUser->name}");

            // Pesan sukses informatif
            $msg = "Pendaftar berhasil didaftarkan secara manual!";
            if($this->auto_generate_password) $msg .= " Password: <b>{$finalPassword}</b> (Berikan ke pendaftar)";
            
            session()->flash('success', $msg);
            return redirect()->route('admin.pendaftar.show', $createdUser->pendaftar->id);

        } catch (\Exception $e) {
            // Log error teknis untuk developer
            report($e);
            session()->flash('error', 'Kegagalan sistem saat menyimpan data. Pastikan semua field unik dan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.admin.pendaftar-create', [
            'gelombangs' => Gelombang::orderBy('tgl_mulai', 'desc')->get(),
            'prodiList' => StudyProgram::where('is_active', true)->get(),
        ]);
    }
}