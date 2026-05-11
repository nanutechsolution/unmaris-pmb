<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar;
use App\Models\Gelombang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Tambahkan Log untuk merekam error
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
    public $totalSteps = 4;

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

            // FITUR SMART RESUME: Cek user sudah sampai tahap mana saat refresh
            if (!empty($pendaftar->nama_ayah) && $pendaftar->nama_ayah !== '-') {
                $this->currentStep = 4;
            } elseif (!empty($pendaftar->asal_sekolah) && $pendaftar->asal_sekolah !== '-') {
                $this->currentStep = 3;
            } elseif (!empty($pendaftar->tempat_lahir)) {
                $this->currentStep = 2;
            } else {
                $this->currentStep = 1;
            }
        } else {
            $this->nomor_hp = Auth::user()->nomor_hp;
            $this->currentStep = 1;
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

    public function updatedStatusAyah($value)
    {
        if ($value === 'Meninggal') {
            $this->reset(['nik_ayah', 'pendidikan_ayah', 'pekerjaan_ayah']);
            $this->resetErrorBag(['nik_ayah', 'pendidikan_ayah', 'pekerjaan_ayah']);
        }
    }

    public function updatedStatusIbu($value)
    {
        if ($value === 'Meninggal') {
            $this->reset(['nik_ibu', 'pendidikan_ibu', 'pekerjaan_ibu']);
            $this->resetErrorBag(['nik_ibu', 'pendidikan_ibu', 'pekerjaan_ibu']);
        }
    }

    public function validateStep1()
    {
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

        $messages = [
            'jalur_pendaftaran.required' => 'Mohon pilih jalur pendaftaran Anda.',
            'nisn.numeric' => 'NISN hanya boleh berisi angka.',
            'nisn.unique' => 'NISN ini sudah dipakai mendaftar oleh orang lain.',
            'nik.required' => 'Nomor KTP (NIK) wajib diisi.',
            'nik.numeric' => 'Nomor KTP (NIK) hanya boleh berisi angka.',
            'nik.digits' => 'Nomor KTP (NIK) harus berjumlah tepat 16 angka.',
            'nik.unique' => 'Nomor KTP (NIK) ini sudah dipakai mendaftar.',
            'nomor_hp.required' => 'Nomor HP / WA wajib diisi agar kami bisa menghubungi Anda.',
            'nomor_hp.numeric' => 'Nomor HP / WA hanya boleh berisi angka.',
            'nomor_hp.digits_between' => 'Nomor HP / WA tidak valid (harus 10-15 angka).',
            'tempat_lahir.required' => 'Tempat lahir sesuai ijazah wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.before' => 'Maaf, usia Anda belum mencukupi (minimal 15 tahun).',
            'tgl_lahir.after' => 'Maaf, batas usia maksimal pendaftar adalah 60 tahun.',
            'jenis_kelamin.required' => 'Mohon pilih jenis kelamin Anda.',
            'agama.required' => 'Mohon pilih agama Anda.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'alamat.min' => 'Alamat terlalu singkat, mohon tulis lebih detail.',
            'sumber_informasi.required' => 'Mohon beritahu kami dari mana Anda mengetahui kampus ini.',
            'nama_referensi.required' => 'Nama pemberi rekomendasi wajib diisi.',
            'nama_referensi.min' => 'Nama pemberi rekomendasi terlalu singkat.',
            'nomor_hp_referensi.required' => 'Nomor WA pemberi rekomendasi wajib diisi.',
            'nomor_hp_referensi.numeric' => 'Nomor WA pemberi rekomendasi hanya boleh berisi angka.',
            'scholarship_id.required' => 'Mohon pilih jenis beasiswa yang ingin Anda lamar.',
        ];
        $this->validate($rules, $messages);
        $this->saveDraft();
        $this->currentStep = 2;
    }

    public function validateStep2()
    {
        $rules = [
            'asal_sekolah' => 'required|string|min:5',
            'tahun_lulus' => 'required|numeric|digits:4|min:2000|max:' . (date('Y') + 1),
            'pilihan_prodi_1' => 'required',
            'pilihan_prodi_2' => 'required|different:pilihan_prodi_1',
        ];

        $messages = [
            'asal_sekolah.required' => 'Nama sekolah asal wajib diisi.',
            'asal_sekolah.min' => 'Nama sekolah terlalu pendek. Ketik nama lengkap sekolah.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'tahun_lulus.numeric' => 'Tahun lulus hanya boleh berupa angka.',
            'tahun_lulus.digits' => 'Tahun lulus harus berupa 4 angka (contoh: 2024).',
            'tahun_lulus.min' => 'Tahun lulus yang Anda masukkan terlalu lama.',
            'tahun_lulus.max' => 'Tahun lulus tidak boleh melebihi tahun depan.',
            'pilihan_prodi_1.required' => 'Silakan pilih Program Studi utama Anda.',
            'pilihan_prodi_2.required' => 'Silakan pilih Program Studi alternatif (pilihan 2).',
            'pilihan_prodi_2.different' => 'Pilihan ke-2 tidak boleh sama dengan Pilihan Utama.',
        ];

        $this->validate($rules, $messages);

        $this->saveDraft();
        $this->currentStep = 3;
    }

    public function validateStep3()
    {
        $this->nik_ayah = preg_replace('/[^0-9]/', '', (string) $this->nik_ayah);
        $this->nik_ibu = preg_replace('/[^0-9]/', '', (string) $this->nik_ibu);

        $rules = [
            'nama_ayah' => 'required|string',
            'status_ayah' => 'required|in:Hidup,Meninggal',
            'nama_ibu' => 'required|string',
            'status_ibu' => 'required|in:Hidup,Meninggal',
        ];

        if ($this->status_ayah === 'Hidup') {
            $rules['nik_ayah'] = 'required|digits:16';
            $rules['pendidikan_ayah'] = 'required|string';
            $rules['pekerjaan_ayah'] = 'required|string';
        }

        if ($this->status_ibu === 'Hidup') {
            $rules['nik_ibu'] = 'required|digits:16';
            $rules['pendidikan_ibu'] = 'required|string';
            $rules['pekerjaan_ibu'] = 'required|string';
        }

        $messages = [
            'nama_ayah.required' => 'Nama ayah wajib diisi.',
            'status_ayah.required' => 'Silakan pilih status ayah saat ini.',
            'nik_ayah.required' => 'NIK KTP ayah wajib diisi.',
            'nik_ayah.digits' => 'NIK KTP ayah harus berjumlah tepat 16 angka.',
            'pendidikan_ayah.required' => 'Pendidikan terakhir ayah wajib dipilih.',
            'pekerjaan_ayah.required' => 'Pekerjaan utama ayah wajib diisi.',

            'nama_ibu.required' => 'Nama ibu kandung wajib diisi.',
            'status_ibu.required' => 'Silakan pilih status ibu saat ini.',
            'nik_ibu.required' => 'NIK KTP ibu wajib diisi.',
            'nik_ibu.digits' => 'NIK KTP ibu harus berjumlah tepat 16 angka.',
            'pendidikan_ibu.required' => 'Pendidikan terakhir ibu wajib dipilih.',
            'pekerjaan_ibu.required' => 'Pekerjaan utama ibu wajib diisi.',
        ];

        $this->validate($rules, $messages);

        $this->saveDraft();
        $this->currentStep = 4;
    }

    public function submit()
    {
        $rules = [
            'jenis_dokumen' => 'required|in:ijazah,skl',
        ];

        if (!$this->existingFotoPath) $rules['foto'] = 'required|mimes:pdf,jpg,jpeg,png|max:5048';
        if (!$this->existingKtpPath) $rules['file_ktp'] = 'required|mimes:pdf,jpg,jpeg,png|max:5048';
        if ($this->file_akta) $rules['file_akta'] = 'mimes:pdf,jpg,jpeg,png|max:5048';
        if (!$this->existingIjazahPath) $rules['ijazah'] = 'required|mimes:pdf,jpg,jpeg,png|max:5048';
        if ($this->jenis_dokumen == 'ijazah' && !$this->existingTranskripPath) {
            $rules['transkrip'] = 'required|mimes:pdf,jpg,jpeg,png|max:5048';
        }
        if ($this->jalur_pendaftaran == 'beasiswa' && !$this->existingFileBeasiswaPath) {
            $rules['file_beasiswa'] = 'required|mimes:pdf,jpg,png|max:5120';
        }

        $messages = [
            'jenis_dokumen.required' => 'Tentukan jenis dokumen kelulusan yang Anda gunakan.',
            'foto.required' => 'Pas foto resmi wajib diunggah.',
            'foto.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal adalah 5MB.',

            'file_ktp.required' => 'Scan KTP/KK wajib diunggah.',
            'file_ktp.mimes' => 'File KTP/KK harus berformat PDF, JPG, atau PNG.',
            'file_ktp.max' => 'Ukuran file KTP/KK maksimal 5MB.',

            'file_akta.mimes' => 'File Akta Kelahiran harus berformat PDF, JPG, atau PNG.',
            'file_akta.max' => 'Ukuran Akta Kelahiran maksimal 5MB.',

            'ijazah.required' => 'Scan dokumen Ijazah / SKL wajib diunggah.',
            'ijazah.mimes' => 'File Ijazah / SKL harus berformat PDF, JPG, atau PNG.',
            'ijazah.max' => 'Ukuran file Ijazah / SKL maksimal 5MB.',

            'transkrip.required' => 'Scan nilai (Transkrip/Belakang Ijazah) wajib diunggah.',
            'transkrip.mimes' => 'File nilai harus berformat PDF, JPG, atau PNG.',
            'transkrip.max' => 'Ukuran file nilai maksimal 5MB.',

            'file_beasiswa.required' => 'Dokumen pendukung beasiswa wajib diunggah.',
            'file_beasiswa.mimes' => 'Dokumen beasiswa harus berformat PDF, JPG, atau PNG.',
            'file_beasiswa.max' => 'Ukuran dokumen beasiswa maksimal 5MB.',
        ];

        $this->validate($rules, $messages);

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
            } catch (Exception $mailError) {
                Log::error('Gagal mengirim email PMB: ' . $mailError->getMessage());
            }

            session()->flash('message', 'Pendaftaran berhasil dikirim!');
            return redirect()->route('camaba.dashboard');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal Submit Form PMB: ' . $e->getMessage());
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
                    'sumber_informasi' => $this->sumber_informasi ?: null,
                    'nama_referensi' => $this->nama_referensi ?: null,
                    'nomor_hp_referensi' => $this->nomor_hp_referensi ?: null,
                    'jalur_pendaftaran' => $this->jalur_pendaftaran,
                    'scholarship_id' => ($this->jalur_pendaftaran == 'beasiswa' && $this->scholarship_id) ? $this->scholarship_id : null,

                    'nisn' => $this->nisn ?: null,
                    'nik' => $this->nik ?: null,
                    'tempat_lahir' => $this->tempat_lahir,
                    'tgl_lahir' => $this->tgl_lahir ?: null,
                    'jenis_kelamin' => $this->jenis_kelamin ?: null,
                    'alamat' => $this->alamat,
                    'agama' => $this->agama ?: null,
                    'nomor_hp' => $this->nomor_hp,
                    'asal_sekolah' => $this->asal_sekolah ?? '-',
                    'tahun_lulus' => $this->tahun_lulus ?? date('Y'),
                    'pilihan_prodi_1' => $this->pilihan_prodi_1 ?? '-',
                    'pilihan_prodi_2' => $this->pilihan_prodi_2 ?: null,

                    'nama_ayah' => $this->nama_ayah ?? '-',
                    'nik_ayah' => $this->status_ayah === 'Hidup' ? ($this->nik_ayah ?: null) : null,
                    'status_ayah' => $this->status_ayah,
                    'pendidikan_ayah' => $this->status_ayah === 'Hidup' ? ($this->pendidikan_ayah ?: null) : null,
                    'pekerjaan_ayah' => $this->status_ayah === 'Hidup' ? ($this->pekerjaan_ayah ?: null) : null,

                    'nama_ibu' => $this->nama_ibu ?? '-',
                    'nik_ibu' => $this->status_ibu === 'Hidup' ? ($this->nik_ibu ?: null) : null,
                    'status_ibu' => $this->status_ibu,
                    'pendidikan_ibu' => $this->status_ibu === 'Hidup' ? ($this->pendidikan_ibu ?: null) : null,
                    'pekerjaan_ibu' => $this->status_ibu === 'Hidup' ? ($this->pekerjaan_ibu ?: null) : null,

                    'jenis_dokumen' => $this->jenis_dokumen,
                    'status_pendaftaran' => $statusToSave,
                ]
            );
        } catch (Exception $e) {
            // Log error agar tidak silent (diam-diam gagal)
            Log::error('Draft PMB Gagal Disimpan: ' . $e->getMessage() . ' di baris ' . $e->getLine());
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
