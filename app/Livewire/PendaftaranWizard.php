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
use App\Models\ReferralReward;
use App\Models\ReferralScheme;
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
    public $nama_ayah, $nik_ayah, $status_ayah = 'Hidup', $pendidikan_ayah, $pekerjaan_ayah;
    public $nama_ibu, $nik_ibu, $status_ibu = 'Hidup', $pendidikan_ibu, $pekerjaan_ibu;

    // Logika Dokumen Baru
    public $jenis_dokumen = 'ijazah';

    // File Uploads
    public $foto, $ijazah, $transkrip, $file_ktp, $file_akta, $file_beasiswa;

    // Path Existing
    public $existingFotoPath;
    public $existingIjazahPath;
    public $existingTranskripPath;
    public $existingKtpPath;
    public $existingAktaPath;
    public $existingFileBeasiswaPath;

    // Flag Mode Revisi
    public $isRevision = false;

    public function getWarnaLatarProperty()
    {
        return 'BIRU';
    }

    public function mount()
    {
        $pendaftar = Auth::user()->pendaftar;

        if ($pendaftar) {
            if (!in_array($pendaftar->status_pendaftaran, ['draft', 'perbaikan'])) {
                session()->flash('message', 'Formulir sudah dikirim dan sedang diproses.');
                return redirect()->route('camaba.dashboard');
            }

            if ($pendaftar->status_pendaftaran === 'perbaikan') {
                $this->isRevision = true;
                session()->flash('warning', 'Anda dalam mode perbaikan. Silakan perbaiki data/dokumen yang ditolak.');
            }

            $this->fill($pendaftar->toArray());

            if (empty($this->nomor_hp)) {
                $this->nomor_hp = Auth::user()->nomor_hp;
            }

            // Fallback status default
            $this->status_ayah = $pendaftar->status_ayah ?? 'Hidup';
            $this->status_ibu = $pendaftar->status_ibu ?? 'Hidup';

            $this->existingFotoPath = $pendaftar->foto_path;
            $this->existingIjazahPath = $pendaftar->ijazah_path;
            $this->existingTranskripPath = $pendaftar->transkrip_path;
            $this->existingKtpPath = $pendaftar->ktp_path;
            $this->existingAktaPath = $pendaftar->akta_path;
            $this->existingFileBeasiswaPath = $pendaftar->file_beasiswa;

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

    public function updatedJenisDokumen($value)
    {
        if ($value == 'skl') {
            $this->transkrip = null;
            $this->resetErrorBag('transkrip');
        }
    }

    // Logika Pintar: Jika status ortu meninggal, reset & abaikan error NIK dll
    public function updatedStatusAyah($value)
    {
        if($value === 'Meninggal') {
            $this->reset(['nik_ayah', 'pendidikan_ayah', 'pekerjaan_ayah']);
            $this->resetErrorBag(['nik_ayah', 'pendidikan_ayah', 'pekerjaan_ayah']);
        }
    }

    public function updatedStatusIbu($value)
    {
        if($value === 'Meninggal') {
            $this->reset(['nik_ibu', 'pendidikan_ibu', 'pekerjaan_ibu']);
            $this->resetErrorBag(['nik_ibu', 'pendidikan_ibu', 'pekerjaan_ibu']);
        }
    }

    public function validateStep1()
    {
        // Sanitasi manual NIK & HP
        $this->nik = preg_replace('/[^0-9]/', '', (string) $this->nik);
        $this->nomor_hp = preg_replace('/[^0-9]/', '', (string) $this->nomor_hp);
        
        $rules = [
            'jalur_pendaftaran' => 'required',
            'nisn' => ['nullable', 'numeric', Rule::unique('pendaftars', 'nisn')->ignore(Auth::id(), 'user_id')],
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique('pendaftars', 'nik')->ignore(Auth::id(), 'user_id')],
            'nomor_hp' => 'required|numeric|digits_between:10,15',
            'tempat_lahir' => 'required|string',
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

    public function validateStep2()
    {
        $this->validate([
            'asal_sekolah' => 'required|string|min:5',
            'tahun_lulus' => 'required|numeric|digits:4|min:2000|max:' . (date('Y') + 1),
            'pilihan_prodi_1' => 'required',
            'pilihan_prodi_2' => 'required|different:pilihan_prodi_1',
        ]);

        $this->saveDraft();
        $this->currentStep = 3;
    }

    public function submit()
    {
        // Sanitasi manual NIK Ortu
        $this->nik_ayah = preg_replace('/[^0-9]/', '', (string) $this->nik_ayah);
        $this->nik_ibu = preg_replace('/[^0-9]/', '', (string) $this->nik_ibu);

        // 1. Validasi Data Ortu
        $rules = [
            'jenis_dokumen' => 'required|in:ijazah,skl',
            'nama_ayah' => 'required|string',
            'status_ayah' => 'required|in:Hidup,Meninggal',
            'nama_ibu' => 'required|string',
            'status_ibu' => 'required|in:Hidup,Meninggal',
        ];

        if($this->status_ayah === 'Hidup') {
            $rules['nik_ayah'] = 'required|digits:16';
            $rules['pendidikan_ayah'] = 'required|string';
            $rules['pekerjaan_ayah'] = 'required|string';
        }

        if($this->status_ibu === 'Hidup') {
            $rules['nik_ibu'] = 'required|digits:16';
            $rules['pendidikan_ibu'] = 'required|string';
            $rules['pekerjaan_ibu'] = 'required|string';
        }

        // 2. Validasi File
        if (!$this->existingFotoPath) $rules['foto'] = 'required|image|max:2048';
        if (!$this->existingKtpPath) $rules['file_ktp'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        if ($this->file_akta) $rules['file_akta'] = 'mimes:pdf,jpg,jpeg,png|max:2048';
        if (!$this->existingIjazahPath) $rules['ijazah'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        if ($this->jenis_dokumen == 'ijazah' && !$this->existingTranskripPath) {
            $rules['transkrip'] = 'required|mimes:pdf,jpg,jpeg,png|max:2048';
        }
        if ($this->jalur_pendaftaran == 'beasiswa' && !$this->existingFileBeasiswaPath) {
            $rules['file_beasiswa'] = 'required|mimes:pdf,jpg,png|max:5120';
        }

        $this->validate($rules, [
            'nik_ayah.digits' => 'NIK Ayah wajib 16 digit.',
            'nik_ibu.digits' => 'NIK Ibu wajib 16 digit.',
        ]);

        // --- PROSES SIMPAN KE DB ---
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $user = Auth::user();

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

            $pendaftar = Pendaftar::updateOrCreate(
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
                    
                    // Kolom Ortu Baru
                    'nama_ayah' => $this->nama_ayah,
                    'nik_ayah' => $this->status_ayah === 'Hidup' ? $this->nik_ayah : null,
                    'status_ayah' => $this->status_ayah,
                    'pendidikan_ayah' => $this->status_ayah === 'Hidup' ? $this->pendidikan_ayah : null,
                    'pekerjaan_ayah' => $this->status_ayah === 'Hidup' ? $this->pekerjaan_ayah : null,
                    
                    'nama_ibu' => $this->nama_ibu,
                    'nik_ibu' => $this->status_ibu === 'Hidup' ? $this->nik_ibu : null,
                    'status_ibu' => $this->status_ibu,
                    'pendidikan_ibu' => $this->status_ibu === 'Hidup' ? $this->pendidikan_ibu : null,
                    'pekerjaan_ibu' => $this->status_ibu === 'Hidup' ? $this->pekerjaan_ibu : null,
                    
                    'jenis_dokumen' => $this->jenis_dokumen,
                    'status_pendaftaran' => 'submit',
                ], $paths)
            );

            if ($user->nomor_hp !== $this->nomor_hp) {
                $user->update(['nomor_hp' => $this->nomor_hp]);
            }

            if ($this->nama_referensi && $this->nomor_hp_referensi) {
                $scheme = ReferralScheme::where('is_active', 1)
                    ->where('jalur', $this->jalur_pendaftaran)
                    ->whereDate('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                    })
                    ->first();

                if ($scheme) {
                    ReferralReward::updateOrCreate(
                        ['pendaftar_id' => $pendaftar->id, 'referral_scheme_id' => $scheme->id],
                        ['reward_amount' => $scheme->reward_amount, 'status' => 'eligible']
                    );
                }
            }
            DB::commit();

            try {
                Mail::to($user->email)->send(new PmbNotification(
                    $user,
                    $this->isRevision ? 'Perbaikan Data Dikirim' : 'Pendaftaran Berhasil Dikirim',
                    $this->isRevision ? 'REVISI DITERIMA' : 'Terima Kasih Telah Mendaftar!',
                    'Data formulir Anda telah kami terima. Silakan tunggu verifikasi admin.',
                    'CEK STATUS',
                    route('camaba.dashboard'),
                    'info'
                ));
            } catch (Exception $mailError) {}

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
            $pendaftar = Auth::user()->pendaftar;
            $statusToSave = ($pendaftar && $pendaftar->status_pendaftaran == 'perbaikan') ? 'perbaikan' : 'draft';

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
                    'nik_ayah' => $this->status_ayah === 'Hidup' ? $this->nik_ayah : null,
                    'status_ayah' => $this->status_ayah,
                    'pendidikan_ayah' => $this->status_ayah === 'Hidup' ? $this->pendidikan_ayah : null,
                    'pekerjaan_ayah' => $this->status_ayah === 'Hidup' ? $this->pekerjaan_ayah : null,
                    
                    'nama_ibu' => $this->nama_ibu,
                    'nik_ibu' => $this->status_ibu === 'Hidup' ? $this->nik_ibu : null,
                    'status_ibu' => $this->status_ibu,
                    'pendidikan_ibu' => $this->status_ibu === 'Hidup' ? $this->pendidikan_ibu : null,
                    'pekerjaan_ibu' => $this->status_ibu === 'Hidup' ? $this->pekerjaan_ibu : null,

                    'jenis_dokumen' => $this->jenis_dokumen,
                    'status_pendaftaran' => $statusToSave,
                ]
            );
        } catch (Exception $e) {}
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