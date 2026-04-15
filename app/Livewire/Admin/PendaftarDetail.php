<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar;
use App\Models\StudyProgram;
use App\Services\Logger;
use Illuminate\Support\Facades\Mail;
use App\Mail\PmbNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Tambahan untuk Database Transaction
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.admin')]
class PendaftarDetail extends Component
{
    use WithFileUploads;

    public $pendaftar_id;
    public $catatan_seleksi;
    public $rekomendasi_prodi;

    // --- PROPERTI EDIT BIODATA & ORANG TUA ---
    public $isEditingBiodata = false;
    public $edit_name, $edit_nik, $edit_tempat_lahir, $edit_tgl_lahir, $edit_jenis_kelamin, $edit_agama, $edit_alamat, $edit_nomor_hp, $edit_asal_sekolah;
    
    public $edit_nama_ayah, $edit_nik_ayah, $edit_status_ayah, $edit_pendidikan_ayah, $edit_pekerjaan_ayah;
    public $edit_nama_ibu, $edit_nik_ibu, $edit_status_ibu, $edit_pendidikan_ibu, $edit_pekerjaan_ibu;

    // --- PROPERTI GANTI BERKAS ---
    public $showUploadModal = false;
    public $upload_tipe;
    public $upload_label;
    public $upload_file;

    // --- PROPERTI AKADEMIK ---
    public $nilai_ujian;
    public $jadwal_ujian;
    public $lokasi_ujian;
    public $nilai_wawancara;
    public $jadwal_wawancara;

    public function mount($id)
    {
        $this->pendaftar_id = $id;
        $pendaftar = Pendaftar::findOrFail($id);
        
        $this->catatan_seleksi = $pendaftar->catatan_seleksi;
        $this->rekomendasi_prodi = $pendaftar->rekomendasi_prodi;
        
        // Load data akademik (Format waktu disesuaikan untuk input datetime-local HTML5)
        $this->nilai_ujian = $pendaftar->nilai_ujian;
        $this->jadwal_ujian = $pendaftar->jadwal_ujian ? Carbon::parse($pendaftar->jadwal_ujian)->format('Y-m-d\TH:i') : null;
        $this->lokasi_ujian = $pendaftar->lokasi_ujian;
        $this->nilai_wawancara = $pendaftar->nilai_wawancara;
        $this->jadwal_wawancara = $pendaftar->jadwal_wawancara ? Carbon::parse($pendaftar->jadwal_wawancara)->format('Y-m-d\TH:i') : null;
    }

    public function getPendaftarProperty()
    {
        return Pendaftar::with('user')->findOrFail($this->pendaftar_id);
    }

    // ==========================================
    // 1. FITUR BIODATA & DATA ORANG TUA
    // ==========================================
    public function editBiodata()
    {
        $pendaftar = $this->pendaftar;
        
        // Data Diri
        $this->edit_name = $pendaftar->user->name;
        $this->edit_nik = $pendaftar->nik;
        $this->edit_tempat_lahir = $pendaftar->tempat_lahir;
        $this->edit_tgl_lahir = $pendaftar->tgl_lahir ? Carbon::parse($pendaftar->tgl_lahir)->format('Y-m-d') : null;
        $this->edit_jenis_kelamin = $pendaftar->jenis_kelamin;
        $this->edit_agama = $pendaftar->agama;
        $this->edit_alamat = $pendaftar->alamat;
        $this->edit_nomor_hp = $pendaftar->nomor_hp;
        $this->edit_asal_sekolah = $pendaftar->asal_sekolah;

        // Data Orang Tua
        $this->edit_nama_ayah = $pendaftar->nama_ayah;
        $this->edit_nik_ayah = $pendaftar->nik_ayah;
        $this->edit_status_ayah = $pendaftar->status_ayah ?? 'Hidup';
        $this->edit_pendidikan_ayah = $pendaftar->pendidikan_ayah;
        $this->edit_pekerjaan_ayah = $pendaftar->pekerjaan_ayah;
        
        $this->edit_nama_ibu = $pendaftar->nama_ibu;
        $this->edit_nik_ibu = $pendaftar->nik_ibu;
        $this->edit_status_ibu = $pendaftar->status_ibu ?? 'Hidup';
        $this->edit_pendidikan_ibu = $pendaftar->pendidikan_ibu;
        $this->edit_pekerjaan_ibu = $pendaftar->pekerjaan_ibu;
        
        $this->isEditingBiodata = true;
    }

    public function simpanBiodata()
    {
        // 1. SANITASI KETAT: Memaksa membuang semua karakter selain angka (Anti-bypass JS)
        $this->edit_nik = preg_replace('/[^0-9]/', '', (string) $this->edit_nik);
        $this->edit_nomor_hp = preg_replace('/[^0-9]/', '', (string) $this->edit_nomor_hp);
        
        if(!empty($this->edit_nik_ayah)) {
            $this->edit_nik_ayah = preg_replace('/[^0-9]/', '', (string) $this->edit_nik_ayah);
        }
        if(!empty($this->edit_nik_ibu)) {
            $this->edit_nik_ibu = preg_replace('/[^0-9]/', '', (string) $this->edit_nik_ibu);
        }

        // 2. VALIDASI BACKEND: Pastikan data tidak melanggar aturan database
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_nomor_hp' => 'required|string|min:10|max:15',
            'edit_nik' => 'required|digits:16',
            'edit_jenis_kelamin' => 'required|in:L,P',
            'edit_tempat_lahir' => 'required|string|max:255',
            'edit_tgl_lahir' => 'required|date|before_or_equal:today',
            'edit_agama' => 'nullable|string|max:50',
            'edit_alamat' => 'required|string',
            'edit_asal_sekolah' => 'required|string|max:255',
            
            // Ayah
            'edit_nama_ayah' => 'required|string|max:255',
            'edit_status_ayah' => 'required|in:Hidup,Meninggal',
            'edit_nik_ayah' => 'nullable|required_if:edit_status_ayah,Hidup|digits:16',
            'edit_pendidikan_ayah' => 'nullable|string|max:50',
            'edit_pekerjaan_ayah' => 'nullable|string|max:255',
            
            // Ibu
            'edit_nama_ibu' => 'required|string|max:255',
            'edit_status_ibu' => 'required|in:Hidup,Meninggal',
            'edit_nik_ibu' => 'nullable|required_if:edit_status_ibu,Hidup|digits:16',
            'edit_pendidikan_ibu' => 'nullable|string|max:50',
            'edit_pekerjaan_ibu' => 'nullable|string|max:255',
        ], [
            // Custom Error Messages dalam bahasa Indonesia agar mudah dipahami Admin
            'edit_name.required' => 'Nama lengkap wajib diisi.',
            'edit_nik.required' => 'NIK pendaftar wajib diisi.',
            'edit_nik.digits' => 'Format NIK Pendaftar tidak valid! NIK mutlak harus 16 digit.',
            'edit_nomor_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'edit_nomor_hp.min' => 'Nomor HP tidak valid. Minimal 10 digit angka.',
            'edit_tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'edit_tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'edit_tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'edit_tgl_lahir.before_or_equal' => 'Tanggal lahir mustahil dari masa depan. Maksimal hari ini.',
            'edit_jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'edit_alamat.required' => 'Alamat domisili lengkap wajib diisi.',
            'edit_asal_sekolah.required' => 'Asal sekolah tidak boleh dikosongkan.',
            
            'edit_nama_ayah.required' => 'Nama Ayah wajib diisi.',
            'edit_nik_ayah.required_if' => 'NIK Ayah WAJIB diisi karena statusnya masih "Hidup".',
            'edit_nik_ayah.digits' => 'Bila diisi, NIK Ayah mutlak harus 16 digit.',
            
            'edit_nama_ibu.required' => 'Nama Ibu wajib diisi.',
            'edit_nik_ibu.required_if' => 'NIK Ibu WAJIB diisi karena statusnya masih "Hidup".',
            'edit_nik_ibu.digits' => 'Bila diisi, NIK Ibu mutlak harus 16 digit.',
        ]);

        $pendaftar = $this->pendaftar;
        
        try {
            // Menggunakan DB Transaction untuk mencegah data parsial tersimpan jika server crash
            DB::transaction(function () use ($pendaftar) {
                // Update Table Users
                $pendaftar->user->update(['name' => $this->edit_name]);

                // Update Table Pendaftars
                $pendaftar->update([
                    'nik' => $this->edit_nik,
                    'tempat_lahir' => $this->edit_tempat_lahir,
                    'tgl_lahir' => $this->edit_tgl_lahir,
                    'jenis_kelamin' => $this->edit_jenis_kelamin,
                    'agama' => $this->edit_agama,
                    'alamat' => $this->edit_alamat,
                    'nomor_hp' => $this->edit_nomor_hp,
                    'asal_sekolah' => $this->edit_asal_sekolah,
                    
                    'nama_ayah' => $this->edit_nama_ayah,
                    'nik_ayah' => empty(trim((string)$this->edit_nik_ayah)) ? null : $this->edit_nik_ayah,
                    'status_ayah' => $this->edit_status_ayah,
                    'pendidikan_ayah' => $this->edit_pendidikan_ayah,
                    'pekerjaan_ayah' => $this->edit_pekerjaan_ayah,
                    
                    'nama_ibu' => $this->edit_nama_ibu,
                    'nik_ibu' => empty(trim((string)$this->edit_nik_ibu)) ? null : $this->edit_nik_ibu,
                    'status_ibu' => $this->edit_status_ibu,
                    'pendidikan_ibu' => $this->edit_pendidikan_ibu,
                    'pekerjaan_ibu' => $this->edit_pekerjaan_ibu,
                ]);
            });

            $this->isEditingBiodata = false;
            Logger::record('UPDATE', 'Edit Biodata', "Admin mengubah biodata dan data ortu pendaftar #{$pendaftar->id}");
            session()->flash('success', 'Data Biodata dan Orang Tua berhasil diperbarui dan divalidasi dengan aman!');

        } catch (\Exception $e) {
            Logger::record('ERROR', 'Gagal Edit Biodata', "DB Error pada #{$pendaftar->id}: " . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan sistem saat menyimpan data. Silakan coba lagi.');
        }
    }

    // ==========================================
    // 2. FITUR AKADEMIK & NILAI
    // ==========================================
    public function simpanAkademik()
    {
        $this->validate([
            'nilai_ujian' => 'required|numeric|min:0|max:100',
            'jadwal_ujian' => 'nullable|date',
            'lokasi_ujian' => 'nullable|string|max:255',
            'nilai_wawancara' => 'nullable|numeric|min:0|max:100',
            'jadwal_wawancara' => 'nullable|date',
            'rekomendasi_prodi' => 'nullable|string|max:255',
            'catatan_seleksi' => 'nullable|string',
        ], [
            'nilai_ujian.required' => 'Nilai ujian akademik mutlak wajib diinput!',
            'nilai_ujian.max' => 'Nilai ujian tidak logis. Maksimal adalah 100.',
            'nilai_ujian.min' => 'Nilai ujian tidak boleh minus/negatif.',
            'nilai_wawancara.max' => 'Nilai wawancara tidak logis. Maksimal 100.'
        ]);

        $pendaftar = $this->pendaftar;
        
        $pendaftar->update([
            'nilai_ujian' => $this->nilai_ujian,
            'jadwal_ujian' => $this->jadwal_ujian,
            'lokasi_ujian' => $this->lokasi_ujian,
            'nilai_wawancara' => $this->nilai_wawancara,
            'jadwal_wawancara' => $this->jadwal_wawancara,
            'rekomendasi_prodi' => $this->rekomendasi_prodi,
            'catatan_seleksi' => $this->catatan_seleksi,
        ]);

        Logger::record('UPDATE', 'Input Akademik', "Admin mengupdate nilai dan rekomendasi prodi pendaftar #{$pendaftar->id}");
        session()->flash('success', 'Data Akademik & Rekomendasi Prodi berhasil disimpan!');
    }

    // ==========================================
    // 3. FITUR UBAH BERKAS OLEH ADMIN
    // ==========================================
    public function openUploadModal($id, $label)
    {
        $this->upload_tipe = $id;
        $this->upload_label = $label;
        $this->showUploadModal = true;
        $this->upload_file = null;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->reset('upload_file'); // Pastikan menghapus file temporary yang nyangkut di memori Livewire
    }

    public function gantiBerkasAdmin()
    {
        $this->validate([
            'upload_file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120' // Max 5MB
        ], [
            'upload_file.required' => 'Kesalahan: Anda belum memilih file apapun!',
            'upload_file.mimes' => 'Keamanan: Format file ditolak! Hanya menerima PDF, JPG, PNG, atau WEBP.',
            'upload_file.max' => 'File terlalu besar! Ukuran maksimal yang diizinkan adalah 5 MB.'
        ]);

        $map = [
            'ktp' => 'ktp_path',
            'akta' => 'akta_path',
            'ijazah' => 'ijazah_path',
            'transkrip' => 'transkrip_path',
            'beasiswa' => 'file_beasiswa',
        ];

        if (!array_key_exists($this->upload_tipe, $map)) {
            session()->flash('error', 'Injeksi terdeteksi: Tipe dokumen tidak valid.');
            return;
        }

        $pendaftar = $this->pendaftar;
        $field = $map[$this->upload_tipe];

        // Hapus berkas fisik lama dari server (Storage) untuk menghemat disk
        if ($pendaftar->$field && Storage::disk('public')->exists($pendaftar->$field)) {
            Storage::disk('public')->delete($pendaftar->$field);
        }

        // Simpan file baru
        $path = $this->upload_file->store('uploads/dokumen_pendaftar', 'public');
        
        $pendaftar->$field = $path;
        
        // Auto-Approve file karena ini tindakan force dari Admin
        $currentStatus = $pendaftar->doc_status ?? [];
        $currentStatus[$this->upload_tipe] = [
            'status' => 'approved',
            'reason' => 'Diunggah ulang (override) dan divalidasi langsung secara manual oleh Admin',
            'date' => now()->toDateTimeString()
        ];
        $pendaftar->doc_status = $currentStatus;
        $pendaftar->save();

        Logger::record('UPDATE', 'Ganti Berkas Admin', "Admin mengganti berkas {$this->upload_tipe} milik pendaftar #{$pendaftar->id}");

        $this->closeUploadModal();
        session()->flash('success', "Sukses: Berkas {$this->upload_label} lama telah dihapus dan ditimpa dengan file baru oleh Admin.");
    }

    public function rejectDocument($docId, $reason)
    {
        // Mencegah penolakan dokumen tanpa alasan yang jelas
        if (empty(trim($reason))) {
            session()->flash('error', 'Gagal menolak: Alasan penolakan mutlak wajib diisi agar pendaftar mengerti kesalahannya!');
            return;
        }

        $map = ['ktp' => 'ktp_path', 'akta' => 'akta_path', 'ijazah' => 'ijazah_path', 'transkrip' => 'transkrip_path', 'beasiswa' => 'file_beasiswa'];
        $field = $map[$docId];
        $pendaftar = $this->pendaftar;
        $path = $pendaftar->$field;

        if ($path) {
            DB::transaction(function () use ($pendaftar, $path, $docId, $reason, $field) {
                // Hapus file yang salah dari server Storage
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }

                $currentStatus = $pendaftar->doc_status ?? [];
                $currentStatus[$docId] = [
                    'status' => 'rejected',
                    'reason' => trim($reason),
                    'date' => now()->toDateTimeString()
                ];

                $pendaftar->doc_status = $currentStatus;
                $pendaftar->$field = null; // Kosongkan path di database agar form upload camaba terbuka lagi
                $pendaftar->status_pendaftaran = 'perbaikan';
                $pendaftar->save();
            });

            // Kirim notifikasi email (diluar transaction agar tidak merollback DB bila SMTP error)
            try {
                Mail::to($pendaftar->user->email)->send(new PmbNotification(
                    $pendaftar->user, 'Perbaikan Dokumen Diperlukan', 'STATUS: PERBAIKAN BERKAS',
                    "Dokumen ($docId) Anda ditolak oleh panitia PMB. Alasan penolakan: \"$reason\". Silakan segera login ke dashboard dan unggah ulang berkas yang benar.",
                    'PERBAIKI SEKARANG', route('camaba.formulir'), 'error'
                ));
            } catch (\Exception $e) {
                // Biarkan saja, aplikasi tidak boleh crash hanya karena masalah SMTP mailer
                Logger::record('ERROR', 'Email Failed', "Gagal mengirim email penolakan berkas ke {$pendaftar->user->email}: " . $e->getMessage());
            }

            Logger::record('UPDATE', 'Tolak Dokumen', "Menolak dokumen $docId milik #{$pendaftar->id}. Status pendaftaran diubah ke Perbaikan.");
            session()->flash('success', "Dokumen berhasil ditolak. Pendaftar telah diminta memperbaiki.");
        }
    }

    // ==========================================
    // 4. KEPUTUSAN KELULUSAN & STATUS
    // ==========================================
    public function updateStatus($status)
    {
        $pendaftar = $this->pendaftar;
        $oldStatus = $pendaftar->status_pendaftaran;

        if ($status == 'lulus' && $pendaftar->status_pembayaran !== 'lunas') {
            session()->flash('error', 'Validasi Gagal: Pendaftar yang belum lunas administrasinya tidak dapat diluluskan!');
            return;
        }

        // Peringatan jika admin mem-push lulus tapi nilainya masih 0
        if ($status == 'lulus' && $pendaftar->nilai_ujian <= 0) {
             session()->flash('error', 'Validasi Gagal: Nilai ujian pendaftar belum diinput atau 0.');
             return;
        }

        $pendaftar->status_pendaftaran = $status;
        
        // Tolak permanen (gagal seleksi) maka kunci data
        if($status == 'gagal') {
            $pendaftar->is_locked = true;
        }

        $pendaftar->save();

        Logger::record('UPDATE', 'Ubah Status Proses', "Status pendaftaran #{$pendaftar->id} diubah: $oldStatus -> $status");
        session()->flash('success', 'Status pendaftaran berhasil diperbarui dalam sistem!');
    }

    public function lulusPilihan($pilihan)
    {
        $pendaftar = $this->pendaftar;

        // --- BACKEND SECURITY CHECK: Cegah bypass dari inspect element / DOM Manipulation ---
        if ($pendaftar->status_pembayaran !== 'lunas') {
            session()->flash('error', 'Tindakan Ditolak Keamanan: Pendaftar belum melunasi biaya pendaftaran!');
            return;
        }

        if ($pendaftar->nilai_ujian <= 0) {
            session()->flash('error', 'Tindakan Ditolak Keamanan: Nilai ujian akademik pendaftar wajib diinput terlebih dahulu!');
            return;
        }

        if ($pendaftar->is_locked) {
            session()->flash('error', 'Tindakan ditolak: Data kelulusan pendaftar ini sudah dikunci secara final.');
            return;
        }
        // -----------------------------------------------------------------------------------

        $prodi = $pilihan == 1 ? $pendaftar->pilihan_prodi_1 : $pendaftar->pilihan_prodi_2;

        DB::transaction(function () use ($pendaftar, $prodi) {
            $pendaftar->status_pendaftaran = 'lulus';
            $pendaftar->prodi_diterima = $prodi;
            $pendaftar->is_locked = true;
            $pendaftar->save();
        });

        try {
            Mail::to($pendaftar->user->email)->send(new PmbNotification(
                $pendaftar->user, 'Hasil Seleksi PMB Diumumkan', 'SELAMAT! ANDA LULUS 🎉',
                "Selamat! Berdasarkan hasil seleksi akademik, Anda dinyatakan secara resmi diterima di program studi $prodi.",
                'LOGIN KE DASHBOARD', route('login'), 'success'
            ));
        } catch (\Exception $e) {
             Logger::record('ERROR', 'Email Kelulusan Failed', "Gagal mengirim pesan lulus ke {$pendaftar->user->email}: " . $e->getMessage());
        }

        Logger::record('UPDATE', 'Keputusan Lulus', "Admin mengunci dan meluluskan #{$pendaftar->id} di prodi $prodi");
        session()->flash('success', "Mahasiswa secara resmi dinyatakan LULUS di prodi $prodi dan data telah dikunci.");
    }

    public function lulusRekomendasi()
    {
        $pendaftar = $this->pendaftar;

        if ($pendaftar->status_pembayaran !== 'lunas') {
            session()->flash('error', 'Tindakan Ditolak: Pendaftar belum melunasi biaya pendaftaran!');
            return;
        }

        if ($pendaftar->nilai_ujian <= 0) {
            session()->flash('error', 'Tindakan Ditolak: Nilai ujian akademik wajib diinput terlebih dahulu!');
            return;
        }

        if ($pendaftar->is_locked) {
            session()->flash('error', 'Tindakan ditolak: Data kelulusan pendaftar sudah dikunci.');
            return;
        }

        if (empty($pendaftar->rekomendasi_prodi)) {
            session()->flash('error', 'Tindakan Ditolak: Anda belum memilih Prodi Rekomendasi di form Akademik.');
            return;
        }

        DB::transaction(function () use ($pendaftar) {
            $pendaftar->status_pendaftaran = 'lulus';
            $pendaftar->prodi_diterima = $pendaftar->rekomendasi_prodi;
            $pendaftar->is_locked = true;
            $pendaftar->save();
        });

        try {
            Mail::to($pendaftar->user->email)->send(new PmbNotification(
                $pendaftar->user, 'Hasil Seleksi PMB Diumumkan', 'SELAMAT! ANDA LULUS 🎉',
                "Selamat! Anda dinyatakan diterima di program studi pilihan alternatif/rekomendasi kampus: {$pendaftar->prodi_diterima}.",
                'LOGIN KE DASHBOARD', route('login'), 'success'
            ));
        } catch (\Exception $e) {
             Logger::record('ERROR', 'Email Kelulusan Failed', "Gagal mengirim pesan lulus ke {$pendaftar->user->email}");
        }

        Logger::record('UPDATE', 'Keputusan Lulus Rekomendasi', "Admin meluluskan #{$pendaftar->id} di prodi rekomendasi {$pendaftar->prodi_diterima}");
        session()->flash('success', "Mahasiswa berhasil diluluskan di prodi Alternatif/Rekomendasi: {$pendaftar->prodi_diterima}");
    }

    public function unlockData()
    {
        // Fitur penyelamat (*Life-saver*) jika Admin salah memencet tombol kelulusan
        $pendaftar = $this->pendaftar;
        $pendaftar->is_locked = false;
        $pendaftar->status_pendaftaran = 'verifikasi';
        $pendaftar->prodi_diterima = null;
        $pendaftar->save();

        Logger::record('WARNING', 'Reset Kelulusan (Unlock)', "Super Admin melakukan tindakan UNDO/Reset terhadap status kelulusan #{$pendaftar->id}");
        session()->flash('success', 'Kunci pengaman dibuka! Status pendaftar di-reset kembali ke tahap "Verifikasi".');
    }

    public function syncToSiakad()
    {
        $pendaftar = $this->pendaftar;
        $pendaftar->is_synced = true;
        $pendaftar->save();
        session()->flash('success', 'Simulasi Berhasil: Data mahasiswa ini siap untuk di-push ke SIAKAD Utama.');
    }

    public function render()
    {
        return view('livewire.admin.pendaftar-detail', [
            'pendaftar' => $this->pendaftar,
            'prodiList' => StudyProgram::all()
        ]);
    }
}