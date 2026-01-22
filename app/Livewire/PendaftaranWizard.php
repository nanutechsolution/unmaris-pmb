<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar;
use App\Models\Gelombang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PmbNotification;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Exception;
use Livewire\Attributes\Url;

class PendaftaranWizard extends Component
{
    use WithFileUploads;

    #[Layout('layouts.camaba')]
    #[Url(keep: true)]

    public $currentStep = 1;
    public $totalSteps = 3;

    // STEP 1: Biodata
    public $jalur_pendaftaran = 'reguler';
    public $scholarship_id;
    public $sumber_informasi;
    public $nama_referensi;
    public $nomor_hp_referensi; 

    public $nisn, $nik, $tempat_lahir, $tgl_lahir, $jenis_kelamin, $alamat, $agama, $nomor_hp;

    // STEP 2: Akademik
    public $asal_sekolah, $tahun_lulus;
    public $pilihan_prodi_1, $pilihan_prodi_2;

    // STEP 3: Berkas & Ortu
    public $nama_ayah, $pekerjaan_ayah, $nama_ibu, $pekerjaan_ibu;

    // Logika Dokumen Baru
    public $jenis_dokumen = 'ijazah'; // Default 'ijazah', opsi lain 'skl'

    // File Uploads
    public $foto, $ijazah, $transkrip, $file_ktp, $file_akta, $file_beasiswa;

    // Path Existing (Untuk Cek apakah file sudah ada di DB)
    public $existingFotoPath;
    public $existingIjazahPath;
    public $existingTranskripPath;
    public $existingKtpPath;
    public $existingAktaPath;
    public $existingFileBeasiswaPath;

    // Helper Warna Latar Pas Foto (UPDATED: SEMUA BIRU)
    public function getWarnaLatarProperty()
    {
        return 'BIRU';
    }

    public function mount()
    {
        $pendaftar = Auth::user()->pendaftar;
        if ($pendaftar) {
            if ($pendaftar->status_pendaftaran !== 'draft') {
                session()->flash('message', 'Formulir sudah dikirim dan sedang diproses.');
                return redirect()->route('camaba.dashboard');
            }

            // Load semua data otomatis dari DB ke variabel Livewire
            $this->fill($pendaftar->toArray());

            // Pastikan nomor_hp terisi dari user login jika di tabel pendaftar kosong
            if (empty($this->nomor_hp)) {
                $this->nomor_hp = Auth::user()->nomor_hp;
            }

            // Load Path File Lama
            $this->existingFotoPath = $pendaftar->foto_path;
            $this->existingIjazahPath = $pendaftar->ijazah_path;
            $this->existingTranskripPath = $pendaftar->transkrip_path; // Kolom Baru
            $this->existingKtpPath = $pendaftar->ktp_path; // Kolom Baru
            $this->existingAktaPath = $pendaftar->akta_path; // Kolom Baru
            $this->existingFileBeasiswaPath = $pendaftar->file_beasiswa;

            // Load Jenis Dokumen
            $this->jenis_dokumen = $pendaftar->jenis_dokumen ?? 'ijazah';
        } else {
            $this->nomor_hp = Auth::user()->nomor_hp;
        }
    }

    public function updatedJalurPendaftaran($value)
    {
        if ($value !== 'reguler') {
            $this->resetErrorBag('nisn');
        }
        if ($value !== 'beasiswa') {
            $this->scholarship_id = null;
        }
    }

    // RESET TRANSKRIP JIKA GANTI KE SKL
    public function updatedJenisDokumen($value)
    {
        if ($value == 'skl') {
            $this->transkrip = null;
            $this->resetErrorBag('transkrip');
        }
    }

    // --- VALIDASI STEP 1 (LANGSUNG SAAT KLIK NEXT) ---
    public function validateStep1()
    {
        $rules = [
            'jalur_pendaftaran' => 'required',
            'nisn' => ['nullable', 'numeric', Rule::unique('pendaftars', 'nisn')->ignore(Auth::id(), 'user_id')],
            // Cek NIK unik & format valid (abaikan milik user sendiri saat update)
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique('pendaftars', 'nik')->ignore(Auth::id(), 'user_id')],
            // Validasi Keras NIK & HP
            'nik' => 'required|numeric|digits:16',
            'nomor_hp' => 'required|numeric|digits_between:10,15',

            'tempat_lahir' => 'required|string',
            // Validasi Umur: Minimal 15 tahun, Maksimal 60 tahun (Mencegah tahun 1111)
            'tgl_lahir' => 'required|date|before:-15 years|after:-60 years',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required',
            'alamat' => 'required|string|min:5',
            'sumber_informasi' => 'required',
            'nama_referensi' => 'nullable|string|max:100',
             'nomor_hp_referensi' => 'nullable|numeric|digits_between:10,15',
        ];

        if (in_array($this->sumber_informasi, ['mahasiswa', 'alumni', 'dosen', 'kerabat'])) {
            $rules['nama_referensi'] = 'required|string|min:3';
              $rules['nomor_hp_referensi'] = 'required|numeric';
        }
        if ($this->jalur_pendaftaran == 'beasiswa') {
            $rules['scholarship_id'] = 'required|exists:scholarships,id';
        }

        $this->validate($rules);
        $this->saveDraft();
        $this->currentStep = 2;
    }

    // --- VALIDASI STEP 2 ---
    public function validateStep2()
    {
        $this->validate([
            'asal_sekolah' => 'required|string|min:5', // Wajib sesuai nomenklatur
            'tahun_lulus' => 'required|numeric|digits:4|min:2000|max:' . (date('Y') + 1),
            'pilihan_prodi_1' => 'required',
            'pilihan_prodi_2' => 'required|different:pilihan_prodi_1',
        ]);

        $this->saveDraft();
        $this->currentStep = 3;
    }

    // --- SUBMIT FINAL (VALIDASI STEP 3 & FILE) ---
    public function submit()
    {
        // 1. Validasi Data Ortu
        $rules = [
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'jenis_dokumen' => 'required|in:ijazah,skl',
        ];

        // 2. Validasi File (Hanya wajib jika belum ada di DB)

        // Foto
        if (!$this->existingFotoPath) {
            $rules['foto'] = 'required|image|max:2048';
        }

        // KTP / KK (Wajib)
        if (!$this->existingKtpPath) {
            $rules['file_ktp'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        // Akta (Opsional)
        if ($this->file_akta) {
            $rules['file_akta'] = 'mimes:pdf,jpg,jpeg,png|max:2048';
        }

        // Ijazah / SKL (Wajib)
        if (!$this->existingIjazahPath) {
            $rules['ijazah'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        // Transkrip (Wajib HANYA JIKA pilih Ijazah)
        if ($this->jenis_dokumen == 'ijazah' && !$this->existingTranskripPath) {
            $rules['transkrip'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        // Beasiswa
        if ($this->jalur_pendaftaran == 'beasiswa' && !$this->existingFileBeasiswaPath) {
            $rules['file_beasiswa'] = 'required|mimes:pdf,jpg,png|max:5120';
        }

        $this->validate($rules);

        // --- PROSES SIMPAN KE DB ---
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $user = Auth::user();

            // Upload Logic
            $paths = [
                'foto_path' => $this->existingFotoPath,
                'ktp_path' => $this->existingKtpPath,
                'akta_path' => $this->existingAktaPath,
                'ijazah_path' => $this->existingIjazahPath,
                'transkrip_path' => $this->existingTranskripPath,
                'file_beasiswa' => $this->existingFileBeasiswaPath,
            ];

            if ($this->foto) $paths['foto_path'] = $this->foto->store('uploads/foto', 'public');
            if ($this->file_ktp) $paths['ktp_path'] = $this->file_ktp->store('uploads/ktp', 'public');
            if ($this->file_akta) $paths['akta_path'] = $this->file_akta->store('uploads/akta', 'public');
            if ($this->ijazah) $paths['ijazah_path'] = $this->ijazah->store('uploads/ijazah', 'public');
            if ($this->transkrip) $paths['transkrip_path'] = $this->transkrip->store('uploads/transkrip', 'public');

            if ($this->file_beasiswa && $this->jalur_pendaftaran == 'beasiswa') {
                $paths['file_beasiswa'] = $this->file_beasiswa->store('uploads/beasiswa', 'public');
            }

            // Simpan Data Pendaftar
            Pendaftar::updateOrCreate(
                ['user_id' => $userId],
                array_merge([
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'scholarship_id' => ($this->jalur_pendaftaran == 'beasiswa') ? $this->scholarship_id : null,
                    'sumber_informasi' => $this->sumber_informasi,
                    'nama_referensi' => $this->nama_referensi,
                    'nomor_hp_referensi' => $this->nomor_hp_referensi,
                    'nisn' => $this->nisn,
                    'nik' => $this->nik,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tgl_lahir' => $this->tgl_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'alamat' => $this->alamat,
                    'agama' => $this->agama,
                    'nomor_hp' => $this->nomor_hp,
                    'asal_sekolah' => $this->asal_sekolah,
                    'tahun_lulus' => $this->tahun_lulus,
                    'pilihan_prodi_1' => $this->pilihan_prodi_1,
                    'pilihan_prodi_2' => $this->pilihan_prodi_2,
                    'nama_ayah' => $this->nama_ayah,
                    'nama_ibu' => $this->nama_ibu,
                    'jenis_dokumen' => $this->jenis_dokumen,
                    'status_pendaftaran' => 'submit', // Final Submit
                ], $paths)
            );

            // Update nomor HP user jika berubah
            if ($user->nomor_hp !== $this->nomor_hp) {
                $user->update(['nomor_hp' => $this->nomor_hp]);
            }

            DB::commit();

            // Kirim Email Notifikasi
            try {
                Mail::to($user->email)->send(new PmbNotification(
                    $user,
                    'Pendaftaran Berhasil Dikirim',
                    'Terima Kasih Telah Mendaftar!',
                    'Formulir pendaftaran Anda telah kami terima. Silakan tunggu verifikasi admin atau lakukan pembayaran jika diperlukan.',
                    'CEK STATUS',
                    route('camaba.dashboard'),
                    'info'
                ));
            } catch (Exception $mailError) {
                // Ignore mail error
            }

            session()->flash('message', 'Pendaftaran berhasil dikirim!');
            return redirect()->route('camaba.dashboard');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        try {
            Pendaftar::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'sumber_informasi' => $this->sumber_informasi,
                    'nama_referensi' => $this->nama_referensi,
                       'nomor_hp_referensi' => $this->nomor_hp_referensi,
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'scholarship_id' => ($this->jalur_pendaftaran == 'beasiswa') ? $this->scholarship_id : null,
                    'nisn' => $this->nisn,
                    'nik' => $this->nik,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tgl_lahir' => $this->tgl_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'alamat' => $this->alamat,
                    'agama' => $this->agama,
                    'nomor_hp' => $this->nomor_hp,
                    'asal_sekolah' => $this->asal_sekolah,
                    'tahun_lulus' => $this->tahun_lulus,
                    'pilihan_prodi_1' => $this->pilihan_prodi_1,
                    'pilihan_prodi_2' => $this->pilihan_prodi_2,
                    'nama_ayah' => $this->nama_ayah,
                    'nama_ibu' => $this->nama_ibu,
                    'jenis_dokumen' => $this->jenis_dokumen, // Save draft jenis dokumen
                    'status_pendaftaran' => 'draft',
                ]
            );
        } catch (Exception $e) {
        }
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function render()
    {
        $gelombangAktif = Gelombang::where('is_active', true)
            ->whereDate('tgl_mulai', '<=', now())
            ->whereDate('tgl_selesai', '>=', now())
            ->first();

        $pendaftar = Auth::user()->pendaftar;
        $sudahPunyaData = $pendaftar && $pendaftar->created_at;

        if (!$gelombangAktif && !$sudahPunyaData) {
            return view('livewire.pendaftaran-tutup');
        }

        return view('livewire.pendaftaran-wizard', [
            'gelombangAktif' => $gelombangAktif,
        ]);
    }
}
