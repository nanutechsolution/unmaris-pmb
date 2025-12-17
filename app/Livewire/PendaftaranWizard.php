<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar;
use App\Models\Gelombang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Penting untuk hapus file
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Exception;

class PendaftaranWizard extends Component
{
    use WithFileUploads;

    // Gunakan layout camaba
    #[Layout('layouts.camaba')] 
    
    public $currentStep = 1;
    public $totalSteps = 3;

    // --- STEP 1: BIODATA ---
    public $jalur_pendaftaran = 'reguler';
    public $nisn, $nik, $tempat_lahir, $tgl_lahir, $jenis_kelamin, $alamat, $agama;

    // --- STEP 2: SEKOLAH & ORTU & BERKAS ---
    public $asal_sekolah, $tahun_lulus, $nama_ayah, $pekerjaan_ayah, $nama_ibu, $pekerjaan_ibu;
    
    // File Uploads (Temporary)
    public $foto;
    public $ijazah;
    
    // Path File Lama (Untuk referensi hapus)
    public $existingFotoPath;
    public $existingIjazahPath;

    // --- STEP 3: PRODI ---
    public $pilihan_prodi_1, $pilihan_prodi_2;

    public function mount()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if ($pendaftar) {
            // 🛡️ PROTEKSI: Jika sudah submit, kunci halaman
            if ($pendaftar->status_pendaftaran !== 'draft') {
                session()->flash('message', 'Formulir Anda sudah dikirim dan sedang dikunci untuk verifikasi. Pantau status di Dashboard.');
                return redirect()->route('camaba.dashboard');
            }

            // LOAD DATA EKSISTING
            $this->jalur_pendaftaran = $pendaftar->jalur_pendaftaran;
            $this->nisn = $pendaftar->nisn;
            $this->nik = $pendaftar->nik;
            $this->tempat_lahir = $pendaftar->tempat_lahir;
            $this->tgl_lahir = $pendaftar->tgl_lahir;
            $this->jenis_kelamin = $pendaftar->jenis_kelamin;
            $this->alamat = $pendaftar->alamat;
            $this->agama = $pendaftar->agama;

            $this->asal_sekolah = $pendaftar->asal_sekolah;
            $this->tahun_lulus = $pendaftar->tahun_lulus;
            $this->nama_ayah = $pendaftar->nama_ayah;
            $this->pekerjaan_ayah = $pendaftar->pekerjaan_ayah;
            $this->nama_ibu = $pendaftar->nama_ibu;
            $this->pekerjaan_ibu = $pendaftar->pekerjaan_ibu;

            // Simpan path file lama ke variable public agar bisa dicek view/logic
            $this->existingFotoPath = $pendaftar->foto_path;
            $this->existingIjazahPath = $pendaftar->ijazah_path;

            $this->pilihan_prodi_1 = $pendaftar->pilihan_prodi_1;
            $this->pilihan_prodi_2 = $pendaftar->pilihan_prodi_2;
        }
    }

    public function updatedJalurPendaftaran($value)
    {
        if ($value !== 'reguler') {
            $this->resetErrorBag('nisn');
            $this->nisn = null; 
        }
    }

    public function validateStep1()
    {
        $this->validate([
            'jalur_pendaftaran' => 'required|in:reguler,pindahan,asing',
            'nisn' => [
                'nullable', 
                'numeric', 
                'digits_between:9,11',
                Rule::unique('pendaftars', 'nisn')->ignore(Auth::id(), 'user_id')
            ],
            'nik' => 'required|numeric|digits:16',
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'alamat' => 'required|string|max:500',
        ]);

        $this->currentStep = 2;
    }

    public function validateStep2()
    {
        $rules = [
            'asal_sekolah' => 'required|string|max:100',
            'tahun_lulus' => 'required|digits:4|integer|min:2000|max:'.(date('Y')+1),
            'nama_ayah' => 'required|string|max:100',
            'nama_ibu' => 'required|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:50',
            'pekerjaan_ibu' => 'nullable|string|max:50',
        ];

        // Validasi File Cerdas:
        // Jika belum ada file lama -> WAJIB upload
        // Jika sudah ada file lama -> BOLEH kosong (berarti tidak ganti file)
        if (!$this->existingFotoPath) {
            $rules['foto'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        } else {
            $rules['foto'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        }

        if (!$this->existingIjazahPath) {
            $rules['ijazah'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        } else {
            $rules['ijazah'] = 'nullable|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        $this->validate($rules);
        $this->currentStep = 3;
    }

    public function submit()
    {
        $this->validate([
            'pilihan_prodi_1' => 'required|string',
            'pilihan_prodi_2' => 'nullable|string|different:pilihan_prodi_1',
        ]);

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            
            // Siapkan path default (gunakan yang lama jika tidak ada upload baru)
            $fotoPathToSave = $this->existingFotoPath;
            $ijazahPathToSave = $this->existingIjazahPath;

            // --- LOGIC GANTI FOTO (SALAH UPLOAD) ---
            if ($this->foto) {
                // 1. Hapus file lama fisik di storage agar tidak menuhin server
                if ($this->existingFotoPath && Storage::disk('public')->exists($this->existingFotoPath)) {
                    Storage::disk('public')->delete($this->existingFotoPath);
                }
                // 2. Upload file baru
                $fotoPathToSave = $this->foto->store('uploads/foto', 'public');
            }

            // --- LOGIC GANTI IJAZAH (SALAH UPLOAD) ---
            if ($this->ijazah) {
                if ($this->existingIjazahPath && Storage::disk('public')->exists($this->existingIjazahPath)) {
                    Storage::disk('public')->delete($this->existingIjazahPath);
                }
                $ijazahPathToSave = $this->ijazah->store('uploads/ijazah', 'public');
            }

            // Simpan ke Database
            Pendaftar::updateOrCreate(
                ['user_id' => $userId],
                [
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'nisn' => ($this->jalur_pendaftaran === 'reguler') ? $this->nisn : null, 
                    'nik' => $this->nik,
                    'tempat_lahir' => strip_tags($this->tempat_lahir),
                    'tgl_lahir' => $this->tgl_lahir,
                    'jenis_kelamin' => $this->jenis_kelamin,
                    'alamat' => strip_tags($this->alamat),
                    'agama' => $this->agama,
                    
                    'asal_sekolah' => strip_tags($this->asal_sekolah),
                    'tahun_lulus' => $this->tahun_lulus,
                    'nama_ayah' => strip_tags($this->nama_ayah),
                    'pekerjaan_ayah' => strip_tags($this->pekerjaan_ayah),
                    'nama_ibu' => strip_tags($this->nama_ibu),
                    'pekerjaan_ibu' => strip_tags($this->pekerjaan_ibu),

                    // Update path file (Entah itu baru atau tetap yang lama)
                    'foto_path' => $fotoPathToSave,
                    'ijazah_path' => $ijazahPathToSave,

                    'pilihan_prodi_1' => $this->pilihan_prodi_1,
                    'pilihan_prodi_2' => $this->pilihan_prodi_2,
                    
                    'status_pendaftaran' => 'submit', // Kunci data setelah ini
                ]
            );

            DB::commit();

            session()->flash('message', 'Pendaftaran berhasil dikirim! Silakan lakukan pembayaran.');
            return redirect()->route('camaba.dashboard');

        } catch (Exception $e) {
            DB::rollBack();
            // Jika error DB, hapus file yang BARUSAN terlanjur ke-upload (Cleanup Orphan)
            if ($this->foto && isset($fotoPathToSave)) Storage::disk('public')->delete($fotoPathToSave);
            if ($this->ijazah && isset($ijazahPathToSave)) Storage::disk('public')->delete($ijazahPathToSave);
            
            session()->flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            return;
        }
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function render()
    {
        // Cek Gelombang Aktif (Proteksi Tambahan)
        $gelombangAktif = Gelombang::where('is_active', true)
            ->whereDate('tgl_mulai', '<=', now())
            ->whereDate('tgl_selesai', '>=', now())
            ->first();

        if (!$gelombangAktif && !Auth::user()->pendaftar) {
            return view('livewire.pendaftaran-tutup');
        }

        return view('livewire.pendaftaran-wizard');
    }
}