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
use Carbon\Carbon;

#[Layout('layouts.admin')]
class PendaftarCreate extends Component
{
    // Properti Akun
    public $name, $email, $password;
    public $auto_generate_password = true;
    public $send_notification = true;

    // Properti Pendaftar (Sesuai Skema Database pendaftars)
    public $nik, $nomor_hp, $jenis_kelamin = 'L';
    public $tempat_lahir, $tgl_lahir, $agama, $alamat;
    public $asal_sekolah, $tahun_lulus;
    public $nama_ayah, $nama_ibu;

    // Properti Akademik
    public $gelombang_id, $jalur_pendaftaran = 'reguler';
    public $pilihan_prodi_1;
    public $status_pembayaran = 'belum_bayar'; // Sesuai ENUM SQL: belum_bayar

    public function mount()
    {
        $activeGelombang = Gelombang::where('is_active', true)->first();
        if ($activeGelombang) {
            $this->gelombang_id = $activeGelombang->id;
        }
        $this->tahun_lulus = date('Y');
    }

    public function updatedAutoGeneratePassword($value)
    {
        if ($value) {
            $this->reset('password');
            $this->resetErrorBag('password');
        }
    }

    public function save()
    {
        // 1. SANITASI INPUT
        $this->nik = preg_replace('/[^0-9]/', '', (string) $this->nik);
        $this->nomor_hp = preg_replace('/[^0-9]/', '', (string) $this->nomor_hp);

        // 2. VALIDASI KETAT (Sesuai Batasan Database)
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|unique:users,email',
            'password' => $this->auto_generate_password ? 'nullable' : 'required|min:8',
            'nik' => 'required|digits:16|unique:pendaftars,nik',
            'nomor_hp' => 'required|string|min:10|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date|before_or_equal:today',
            'agama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|digits:4',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'gelombang_id' => 'required|exists:gelombangs,id',
            'pilihan_prodi_1' => 'required|string',
            'status_pembayaran' => 'required|in:belum_bayar,lunas',
        ], [
            'email.unique' => 'Email pendaftar sudah terdaftar.',
            'nik.unique' => 'NIK pendaftar sudah terdaftar.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak valid.',
        ]);

        try {
            $result = DB::transaction(function () use (&$finalPassword) {
                // Generate password
                $finalPassword = $this->auto_generate_password ? Str::random(8) : $this->password;

                // 1. Buat User & Langsung Set Verified
                $user = User::create([
                    'name' => strtoupper(trim($this->name)),
                    'email' => strtolower(trim($this->email)),
                    'password' => Hash::make($finalPassword),
                    'nomor_hp' => $this->nomor_hp,
                    'role' => 'camaba',
                    'email_verified_at' => now(), // LANGSUNG VERIFIKASI
                ]);

                // Logika status pendaftaran
                $statusPendaftaran = $this->status_pembayaran === 'lunas' ? 'verifikasi' : 'submit';

                // 2. Buat Record Pendaftar Lengkap
                $pendaftar = Pendaftar::create([
                    'user_id' => $user->id,
                    'gelombang_id' => $this->gelombang_id,
                    'nik' => $this->nik,
                    'nomor_hp' => $this->nomor_hp,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tgl_lahir' => $this->tgl_lahir,
                    'agama' => $this->agama,
                    'alamat' => $this->alamat,
                    'asal_sekolah' => $this->asal_sekolah,
                    'tahun_lulus' => $this->tahun_lulus,
                    'nama_ayah' => $this->nama_ayah,
                    'nama_ibu' => $this->nama_ibu,
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'pilihan_prodi_1' => $this->pilihan_prodi_1,
                    'status_pembayaran' => $this->status_pembayaran,
                    'status_pendaftaran' => $statusPendaftaran,
                    'status_pilihan_1' => 'pending',
                ]);

                return ['user' => $user, 'pendaftar' => $pendaftar];
            });

            // 3. Notifikasi
            if ($this->send_notification) {
                try {
                    $result['user']->notify(new ManualRegistrationNotification(
                        $result['user']->name,
                        $result['user']->email,
                        $finalPassword
                    ));
                } catch (\Exception $e) {
                    Logger::record('ERROR', 'Notif Failed', "Gagal kirim WA/Email ke {$result['user']->email}");
                }
            }

            Logger::record('CREATE', 'Tambah Manual', "Admin mendaftarkan {$result['user']->name} secara manual (Verified).");

            // Masukkan password ke session agar bisa ditampilkan di UI halaman detail
            session()->flash('generated_password', $finalPassword);
            session()->flash('success', "Akun pendaftar berhasil dibuat dan status email LANGSUNG TERVERIFIKASI.");

            return redirect()->route('admin.pendaftar.show', $result['pendaftar']->id);
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
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
