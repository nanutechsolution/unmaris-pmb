<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar;
use App\Models\Gelombang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Exception;

class PendaftaranWizard extends Component
{
    use WithFileUploads;

    #[Layout('layouts.camaba')] 
    
    public $currentStep = 1;
    public $totalSteps = 3;

    // STEP 1: Biodata
    public $jalur_pendaftaran = 'reguler';
    public $scholarship_id;
    // Tambahkan $nomor_hp di sini
    public $nisn, $nik, $tempat_lahir, $tgl_lahir, $jenis_kelamin, $alamat, $agama, $nomor_hp;

    // STEP 2: Akademik
    public $asal_sekolah, $tahun_lulus;
    public $pilihan_prodi_1, $pilihan_prodi_2;

    // STEP 3: Berkas & Ortu
    public $nama_ayah, $pekerjaan_ayah, $nama_ibu, $pekerjaan_ibu;
    public $foto, $ijazah, $file_beasiswa;
    public $existingFotoPath, $existingIjazahPath, $existingFileBeasiswaPath;

    public function getWarnaLatarProperty()
    {
        $colors = [
            'Teknik Informatika' => 'BIRU',
            'Teknik Lingkungan' => 'BIRU',
            'Manajemen Informatika' => 'BIRU',
            'Bisnis Digital' => 'KUNING',
            'Manajemen' => 'KUNING',
            'Akuntansi' => 'KUNING',
            'Administrasi Rumah Sakit' => 'MERAH',
            'Keselamatan dan Kesehatan Kerja' => 'MERAH',
            'Pendidikan Teknologi Informasi' => 'HIJAU',
        ];
        return $colors[$this->pilihan_prodi_1] ?? 'MERAH/BIRU (Bebas)';
    }

    public function mount()
    {
        $pendaftar = Auth::user()->pendaftar;
        if ($pendaftar) {
            if ($pendaftar->status_pendaftaran !== 'draft') {
                session()->flash('message', 'Formulir terkunci.');
                return redirect()->route('camaba.dashboard');
            }
            
            // Load semua data otomatis
            $this->fill($pendaftar->toArray());
            
            // Pastikan nomor_hp terisi jika di pendaftar kosong tapi di user ada
            if (empty($this->nomor_hp)) {
                $this->nomor_hp = Auth::user()->nomor_hp;
            }

            $this->existingFotoPath = $pendaftar->foto_path;
            $this->existingIjazahPath = $pendaftar->ijazah_path;
            $this->existingFileBeasiswaPath = $pendaftar->file_beasiswa;
        } else {
            // Jika pendaftar baru, ambil no hp dari akun user sebagai default
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

    public function validateStep1()
    {
        $rules = [
            'jalur_pendaftaran' => 'required',
            'nisn' => ['nullable', 'numeric', Rule::unique('pendaftars', 'nisn')->ignore(Auth::id(), 'user_id')],
            'nik' => 'required|numeric',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'nomor_hp' => 'required|numeric|digits_between:10,15', // Validasi HP
        ];

        if ($this->jalur_pendaftaran == 'beasiswa') {
            $rules['scholarship_id'] = 'required|exists:scholarships,id';
        }

        $this->validate($rules);
        $this->currentStep = 2;
    }

    public function validateStep2()
    {
        $this->validate([
            'asal_sekolah' => 'required',
            'tahun_lulus' => 'required|digits:4',
            'pilihan_prodi_1' => 'required',
        ]);
        $this->currentStep = 3;
    }

    public function submit()
    {
        $rules = [
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
        ];

        if (!$this->existingFotoPath) $rules['foto'] = 'required|image|max:2048';
        if (!$this->existingIjazahPath) $rules['ijazah'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        if ($this->jalur_pendaftaran == 'beasiswa' && !$this->existingFileBeasiswaPath) {
            $rules['file_beasiswa'] = 'required|mimes:pdf,jpg,png|max:5120';
        }

        $this->validate($rules);
        
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            
            $fotoPath = $this->existingFotoPath;
            if ($this->foto) $fotoPath = $this->foto->store('uploads/foto', 'public');

            $ijazahPath = $this->existingIjazahPath;
            if ($this->ijazah) $ijazahPath = $this->ijazah->store('uploads/ijazah', 'public');

            $beasiswaPath = $this->existingFileBeasiswaPath;
            if ($this->file_beasiswa && $this->jalur_pendaftaran == 'beasiswa') {
                $beasiswaPath = $this->file_beasiswa->store('uploads/beasiswa', 'public');
            }

            Pendaftar::updateOrCreate(
                ['user_id' => $userId],
                [
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'scholarship_id' => ($this->jalur_pendaftaran == 'beasiswa') ? $this->scholarship_id : null,
                    'file_beasiswa' => ($this->jalur_pendaftaran == 'beasiswa') ? $beasiswaPath : null,

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
                    'pekerjaan_ayah' => $this->pekerjaan_ayah,
                    'nama_ibu' => $this->nama_ibu,
                    'pekerjaan_ibu' => $this->pekerjaan_ibu,

                    'foto_path' => $fotoPath,
                    'ijazah_path' => $ijazahPath,
                    
                    'status_pendaftaran' => 'submit',
                ]
            );

            // Opsional: Update juga nomor HP di tabel Users agar sinkron
            $user = Auth::user();
            if ($user->nomor_hp !== $this->nomor_hp) {
                $user->update(['nomor_hp' => $this->nomor_hp]);
            }

            DB::commit();
            session()->flash('message', 'Pendaftaran berhasil! Silakan cek dashboard.');
            return redirect()->route('camaba.dashboard');

        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function render()
    {
        return view('livewire.pendaftaran-wizard');
    }
}