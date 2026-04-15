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
                    $pendaftar->foto_path, $pendaftar->ktp_path, 
                    $pendaftar->akta_path, $pendaftar->ijazah_path, 
                    $pendaftar->transkrip_path, $pendaftar->bukti_pembayaran,
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
                        $pendaftar->foto_path, $pendaftar->ktp_path, 
                        $pendaftar->akta_path, $pendaftar->ijazah_path, 
                        $pendaftar->transkrip_path, $pendaftar->bukti_pembayaran,
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
            session()->flash('error', 'Tidak ada data valid yang bisa dikirim (Hanya yang Lulus & Belum Sync).');
            return;
        }

        $successCount = 0;
        $failCount = 0;
        $errorMessages = [];

        $urlSiakad = env('SIAKAD_API_URL') . '/api/v1/pmb/sync';

        foreach ($targets as $pendaftar) {
            try {
                $response = Http::timeout(10)->post($urlSiakad, [
                    'name'            => $pendaftar->user->name,
                    'email'           => $pendaftar->user->email,
                    'nomor_hp'        => $pendaftar->user->nomor_hp,
                    'nik'             => $pendaftar->nik,
                    'nisn'            => $pendaftar->nisn,
                    'asal_sekolah'    => $pendaftar->asal_sekolah,
                    'tahun_lulus'     => (int) $pendaftar->tahun_lulus,
                    'nama_ayah'       => $pendaftar->nama_ayah,
                    'nama_ibu'        => $pendaftar->nama_ibu,
                    'pilihan_prodi_1' => $pendaftar->pilihan_prodi_1,
                    'pilihan_prodi_2' => $pendaftar->pilihan_prodi_2,
                    'jalur_masuk'     => $pendaftar->jalur_pendaftaran,
                    'secret_key'      => env('SIAKAD_API_SECRET'),
                ]);

                $result = $response->json();

                if ($response->successful() && ($result['status'] ?? '') === 'success') {
                    $pendaftar->update(['is_synced' => true]);

                    Logger::record(
                        'SYNC',
                        'SIAKAD Integration',
                        "Berhasil sync {$pendaftar->user->name}, NIM: " . ($result['data']['nim_sementara'] ?? '-')
                    );

                    $successCount++;
                } else {
                    $failCount++;
                    $errorMessages[] = "{$pendaftar->user->name} → " . ($result['message'] ?? 'Respon gagal dari server SIAKAD');

                    Logger::record(
                        'ERROR',
                        'SIAKAD Integration',
                        "Gagal sync {$pendaftar->user->name}. HTTP {$response->status()} | " . json_encode($result)
                    );
                }
            } catch (\Throwable $e) {
                $failCount++;
                $errorMessages[] = "{$pendaftar->user->name} → " . $e->getMessage();

                Logger::record(
                    'ERROR',
                    'SIAKAD Integration',
                    "Exception sync {$pendaftar->user->name}: " . $e->getMessage()
                );
            }
        }

        $this->resetSelection();

        if ($successCount > 0) {
            session()->flash('success', "Berhasil sync $successCount mahasiswa. Gagal: $failCount");
        }

        if ($failCount > 0) {
            session()->flash('error', "Detail error:\n" . implode("\n", $errorMessages));
        }
    }

    public function render()
    {
        return view('livewire.admin.pendaftar-index', [
            'pendaftars' => $this->getPendaftarQuery()->paginate(10)
        ]);
    }
}