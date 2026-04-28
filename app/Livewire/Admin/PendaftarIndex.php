<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Pendaftar;
use App\Models\User;
use App\Services\Logger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftarIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filterStatus = '';

    #[Url(history: true)]
    public $filterPembayaran = '';

    #[Url(history: true)]
    public $filterSync = ''; // Filter Baru: Status Sync SIAKAD

    // --- BULK ACTION ---
    public $selected = [];
    public $selectAll = false;

    // Reset seleksi jika filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingFilterPembayaran()
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingFilterSync()
    {
        $this->resetPage();
        $this->resetSelection();
    }
    public function updatingPage()
    {
        $this->resetSelection();
    }

    public function resetSelection()
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Ambil ID dari query saat ini (halaman ini)
            $this->selected = $this->getPendaftarQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    // --- TAMBAHAN FUNGSI CETAK MASSAL ---
    public function redirectCetakMassal()
    {
        if (empty($this->selected)) {
            session()->flash('error', 'Pilih minimal satu data untuk dicetak.');
            return;
        }

        $ids = implode(',', $this->selected);
        return redirect()->route('admin.pendaftar.cetak-massal', ['ids' => $ids]);
    }
    // ------------------------------------

    private function getPendaftarQuery()
    {
        $query = Pendaftar::with('user')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nisn', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if (!empty($this->filterStatus)) {
            $query->where('status_pendaftaran', $this->filterStatus);
        }

        if (!empty($this->filterPembayaran)) {
            $query->where('status_pembayaran', $this->filterPembayaran);
        }

        if ($this->filterSync !== '') {
            $query->where('is_synced', $this->filterSync);
        }

        return $query;
    }

    // --- FITUR BARU: HAPUS SATUAN ---
    public function deletePendaftar($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $pendaftar = Pendaftar::findOrFail($id);
                $userId = $pendaftar->user_id;
                $userName = $pendaftar->user->name;

                // 1. Hapus file fisik (Storage) agar disk tidak penuh
                $files = [
                    $pendaftar->foto_path,
                    $pendaftar->ktp_path,
                    $pendaftar->akta_path,
                    $pendaftar->ijazah_path,
                    $pendaftar->transkrip_path,
                    $pendaftar->bukti_pembayaran,
                    $pendaftar->file_beasiswa
                ];

                foreach ($files as $file) {
                    if ($file && Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }

                // 2. Hapus User (Otomatis menghapus Pendaftar karena ON DELETE CASCADE)
                User::findOrFail($userId)->delete();

                Logger::record('DELETE', 'Hapus Data', "Menghapus pendaftar: $userName");
            });

            // Pastikan jika ID yang dihapus ada di array selected, kita buang
            $this->selected = array_diff($this->selected, [$id]);

            session()->flash('success', 'Data pendaftar dan file berkasnya berhasil dihapus permanen.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // --- FITUR BARU: BULK HAPUS (MASSAL) ---
    public function bulkDelete()
    {
        if (empty($this->selected)) {
            session()->flash('error', 'Pilih minimal satu data untuk dihapus.');
            return;
        }

        try {
            $deletedCount = 0;

            DB::transaction(function () use (&$deletedCount) {
                $pendaftars = Pendaftar::with('user')->whereIn('id', $this->selected)->get();

                foreach ($pendaftars as $pendaftar) {
                    // Hapus file fisik (Storage)
                    $files = [
                        $pendaftar->foto_path,
                        $pendaftar->ktp_path,
                        $pendaftar->akta_path,
                        $pendaftar->ijazah_path,
                        $pendaftar->transkrip_path,
                        $pendaftar->bukti_pembayaran,
                        $pendaftar->file_beasiswa
                    ];

                    foreach ($files as $file) {
                        if ($file && Storage::disk('public')->exists($file)) {
                            Storage::disk('public')->delete($file);
                        }
                    }

                    // Hapus User (Cascade ke tabel pendaftars)
                    if ($pendaftar->user) {
                        $pendaftar->user->delete();
                        $deletedCount++;
                    }
                }
            });

            Logger::record('DELETE', 'Bulk Hapus', "Menghapus $deletedCount data pendaftar sekaligus.");

            $this->resetSelection();
            session()->flash('success', "$deletedCount data pendaftar dan berkasnya berhasil dihapus massal.");
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus massal: ' . $e->getMessage());
        }
    }

    public function syncToSiakadBulk()
    {
        $targets = Pendaftar::with('user')
            ->whereIn('id', $this->selected)
            ->where('status_pendaftaran', 'lulus')
            ->where('is_synced', false)
            ->get();

        if ($targets->isEmpty()) {
            session()->flash('error', 'Tidak ada data valid yang bisa dikirim (Hanya pendaftar yang Lulus & Belum Sinkronisasi).');
            return;
        }

        $successCount = 0;
        $failCount = 0;
        $errorMessages = [];

        // Ambil konfigurasi API dari .env
        $apiUrl = env('SIAKAD_API_URL');
        $pmbKey = env('SIAKAD_API_SECRET', 'default-secret-key-123');

        if (empty($apiUrl)) {
            session()->flash('error', 'URL API SIAKAD belum diatur di file .env sistem PMB.');
            return;
        }
        foreach ($targets as $pendaftar) {
            // Bypass jika prodi belum ditentukan
            if (empty($pendaftar->prodi_diterima)) {
                $failCount++;
                $errorMessages[] = "{$pendaftar->user->name} → Prodi diterima belum ditentukan.";
                continue;
            }

            // Rakit payload sama persis dengan yang ada di PendaftarDetail
            $payload = [
                'nomor_pendaftaran' => 'PMB' . date('Y', strtotime($pendaftar->created_at)) . str_pad($pendaftar->id, 4, '0', STR_PAD_LEFT),
                'nama_lengkap'      => $pendaftar->user->name,
                'nik'               => $pendaftar->nik,
                'email'             => $pendaftar->user->email,
                'nomor_hp'          => $pendaftar->nomor_hp,
                'kode_prodi'        => $pendaftar->prodi_diterima,
                'nama_prodi'        => $pendaftar->prodi_diterima,
                'kode_program'      => 'REG', // Default Reguler
                'tahun_masuk'       => (int) date('Y', strtotime($pendaftar->created_at)),
                'jenis_kelamin'     => $pendaftar->jenis_kelamin,

                // Core Data Identitas
                'tanggal_lahir'     => $pendaftar->tgl_lahir instanceof \DateTime ? $pendaftar->tgl_lahir->format('Y-m-d') : $pendaftar->tgl_lahir,
                'tempat_lahir'      => $pendaftar->tempat_lahir,

                // Data Tambahan
                'agama'             => $pendaftar->agama,
                'alamat'            => $pendaftar->alamat,
                'asal_sekolah'      => $pendaftar->asal_sekolah,
                'nisn'              => $pendaftar->nisn,
                'tahun_lulus'       => $pendaftar->tahun_lulus,

                // Data Orang Tua
                'nama_ayah'         => $pendaftar->nama_ayah,
                'nik_ayah'          => $pendaftar->nik_ayah,
                'pekerjaan_ayah'    => $pendaftar->pekerjaan_ayah,
                'pendidikan_ayah'   => $pendaftar->pendidikan_ayah,

                'nama_ibu'          => $pendaftar->nama_ibu,
                'nik_ibu'           => $pendaftar->nik_ibu,
                'pekerjaan_ibu'     => $pendaftar->pekerjaan_ibu,
                'pendidikan_ibu'    => $pendaftar->pendidikan_ibu,

                'jalur_pendaftaran' => $pendaftar->jalur_pendaftaran,
            ];

            try {
                // Tembak API dengan timeout dan Header yang valid
                $response = Http::timeout(15)
                    ->withHeaders([
                        'X-PMB-KEY' => $pmbKey,
                        'Accept'    => 'application/json',
                    ])
                    ->post($apiUrl, $payload);

                $result = $response->json();

                if ($response->successful() && ($result['status'] ?? '') === 'success') {
                    // Update flag is_synced jika sukses
                    $pendaftar->update(['is_synced' => true]);

                    Logger::record('API', 'Sync SIAKAD Bulk', "Berhasil sync massal {$pendaftar->user->name} ke SIAKAD.");
                    $successCount++;
                } else {
                    $failCount++;
                    $errorMsg = $result['message'] ?? 'Respon gagal dari server SIAKAD';
                    $errorMessages[] = "{$pendaftar->user->name} → " . $errorMsg;

                    Logger::record('ERROR', 'Sync SIAKAD Bulk Failed', "Gagal sync {$pendaftar->user->name}. HTTP {$response->status()}");
                }
            } catch (\Throwable $e) {
                $failCount++;
                // Perbaiki output jika error "cURL error 6" dsb
                $errMsg = $e->getMessage();
                $errorMessages[] = "{$pendaftar->user->name} → Koneksi API Error (" . str(strtok($errMsg, '('))->limit(30) . ")";

                Logger::record('ERROR', 'Sync SIAKAD Bulk Exception', "Exception sync {$pendaftar->user->name}: " . $errMsg);
            }
        }

        // Reset kotak checkbox setelah proses selesai
        $this->resetSelection();

        // Tampilkan pesan Flash
        if ($successCount > 0) {
            session()->flash('success', "Berhasil sinkronisasi $successCount mahasiswa ke SIAKAD." . ($failCount > 0 ? " (Gagal: $failCount)" : ""));
        }

        if ($failCount > 0) {
            session()->flash('error', "Terdapat $failCount data gagal dikirim.\nDetail error:\n" . implode("\n", $errorMessages));
        }
    }


    public function render()
    {
        return view('livewire.admin.pendaftar-index', [
            'pendaftars' => $this->getPendaftarQuery()->paginate(10)
        ]);
    }
}
