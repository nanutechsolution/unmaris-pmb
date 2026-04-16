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

    // Properti Pendaftar (B. DATA CALON MAHASISWA)
    public $nik, $nomor_hp, $jenis_kelamin = 'L';
    public $tempat_lahir, $tgl_lahir, $agama, $alamat;
    
    // Properti Akademik & Sekolah
    public $nisn, $asal_sekolah, $tahun_lulus;
    public $gelombang_id, $jalur_pendaftaran = 'reguler';
    public $pilihan_prodi_1, $pilihan_prodi_2;
    public $status_pembayaran = 'belum_bayar'; 

    // Properti Data Orang Tua (11. DATA ORANG TUA)
    public $nama_ayah, $nik_ayah, $pekerjaan_ayah, $pendidikan_ayah;
    public $nama_ibu, $nik_ibu, $pekerjaan_ibu, $pendidikan_ibu;

    // Properti Data Referral (Gunting Disini / Prospek)
    public $nama_referensi, $nomor_hp_referensi, $sumber_informasi = 'offline';

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
        // 1. SANITASI INPUT STRICT (Hanya Angka)
        $this->nik = preg_replace('/[^0-9]/', '', (string) $this->nik);
        $this->nomor_hp = preg_replace('/[^0-9]/', '', (string) $this->nomor_hp);
        $this->nisn = preg_replace('/[^0-9]/', '', (string) $this->nisn);
        $this->nik_ayah = preg_replace('/[^0-9]/', '', (string) $this->nik_ayah);
        $this->nik_ibu = preg_replace('/[^0-9]/', '', (string) $this->nik_ibu);
        $this->nomor_hp_referensi = preg_replace('/[^0-9]/', '', (string) $this->nomor_hp_referensi);

        // 2. VALIDASI KETAT
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:filter|unique:users,email',
            'password' => $this->auto_generate_password ? 'nullable' : 'required|min:8',
            
            // Calon Mahasiswa
            'nik' => 'required|digits:16|unique:pendaftars,nik',
            'nomor_hp' => 'required|string|min:10|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date|before_or_equal:today',
            'agama' => 'required|string|max:255',
            'alamat' => 'required|string',
            
            // Akademik & Sekolah
            'nisn' => 'nullable|numeric',
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|digits:4',
            'gelombang_id' => 'required|exists:gelombangs,id',
            'jalur_pendaftaran' => 'required|string',
            'pilihan_prodi_1' => 'required|string',
            'pilihan_prodi_2' => 'nullable|string|different:pilihan_prodi_1', // Prodi 2 tidak boleh sama dengan Prodi 1
            'status_pembayaran' => 'required|in:belum_bayar,lunas',

            // Orang Tua
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'nullable|digits:16',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|string|max:255',
            
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'nullable|digits:16',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|string|max:255',

            // Prospek
            'nama_referensi' => 'nullable|string|max:255',
            'nomor_hp_referensi' => 'nullable|string|max:20',
        ], [
            'email.unique' => 'Email pendaftar sudah terdaftar.',
            'nik.unique' => 'NIK pendaftar sudah terdaftar.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik_ayah.digits' => 'NIK Ayah harus tepat 16 digit angka.',
            'nik_ibu.digits' => 'NIK Ibu harus tepat 16 digit angka.',
            'pilihan_prodi_2.different' => 'Prodi Pilihan 2 tidak boleh sama dengan Pilihan 1.',
        ]);

        try {
            $result = DB::transaction(function () use (&$finalPassword) {
                // Generate password jika dicentang
                $finalPassword = $this->auto_generate_password ? Str::random(8) : $this->password;

                // 1. Buat User (Otomatis Verified)
                $user = User::create([
                    'name' => strtoupper(trim($this->name)),
                    'email' => strtolower(trim($this->email)),
                    'password' => Hash::make($finalPassword),
                    'role' => 'camaba',
                    'email_verified_at' => now(), 
                ]);

                // Logika status pendaftaran (Lunas -> Verifikasi, Belum -> Submit/Draft)
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
                    
                    'nisn' => $this->nisn,
                    'asal_sekolah' => $this->asal_sekolah,
                    'tahun_lulus' => $this->tahun_lulus,
                    
                    'nama_ayah' => $this->nama_ayah,
                    'nik_ayah' => $this->nik_ayah,
                    'pekerjaan_ayah' => $this->pekerjaan_ayah,
                    'pendidikan_ayah' => $this->pendidikan_ayah,
                    
                    'nama_ibu' => $this->nama_ibu,
                    'nik_ibu' => $this->nik_ibu,
                    'pekerjaan_ibu' => $this->pekerjaan_ibu,
                    'pendidikan_ibu' => $this->pendidikan_ibu,
                    
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'pilihan_prodi_1' => $this->pilihan_prodi_1,
                    'pilihan_prodi_2' => $this->pilihan_prodi_2,
                    'status_pembayaran' => $this->status_pembayaran,
                    'status_pendaftaran' => $statusPendaftaran,
                    'status_pilihan_1' => 'pending',

                    // Kolom Referral
                    'sumber_informasi' => $this->sumber_informasi,
                    'nama_referensi' => $this->nama_referensi,
                    'nomor_hp_referensi' => $this->nomor_hp_referensi,
                ]);

                return ['user' => $user, 'pendaftar' => $pendaftar];
            });

            // 3. Notifikasi Pendaftaran Manual
            if ($this->send_notification) {
                try {
                    $result['user']->notify(new ManualRegistrationNotification(
                        $result['user']->name, 
                        $result['user']->email, 
                        $finalPassword
                    ));
                } catch (\Exception $e) {
                    Logger::record('ERROR', 'Notif Failed', "Gagal kirim WA/Email registrasi manual ke {$result['user']->email}");
                }
            }

            Logger::record('CREATE', 'Tambah Manual', "Admin mendaftarkan {$result['user']->name} secara manual dari form offline.");
            
            session()->flash('generated_password', $finalPassword);
            session()->flash('success', "Data pendaftar berhasil disimpan dan tersinkronisasi. Email otomatis diverifikasi.");
            
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