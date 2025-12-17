<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Pendaftar;
use App\Services\Logger;
use Illuminate\Support\Facades\Http;

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
    } // Reset saat filter sync berubah
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

        // Logic Filter Sync (Baru)
        if ($this->filterSync !== '') {
            $query->where('is_synced', $this->filterSync);
        }

        return $query;
    }

    public function render()
    {
        return view('livewire.admin.pendaftar-index', [
            'pendaftars' => $this->getPendaftarQuery()->paginate(10)
        ]);
    }

    // --- EKSEKUSI SYNC MASSAL ---
    public function syncToSiakadBulk()
    {
        // 1. Ambil data yang valid (Lulus & Belum Sync) dari yang dipilih
        $targets = Pendaftar::with('user')
            ->whereIn('id', $this->selected)
            ->where('status_pendaftaran', 'lulus')
            ->where('is_synced', false)
            ->get();

        if ($targets->isEmpty()) {
            session()->flash('error', 'Tidak ada data valid yang bisa dikirim. Pastikan pilih mahasiswa yang LULUS dan BELUM disinkron.');
            return;
        }

        $successCount = 0;
        $failCount = 0;

        // URL API SIAKAD
        $urlSiakad = env('SIAKAD_API_URL') . '/api/v1/pmb/sync';

        // 2. Loop dan Kirim
        foreach ($targets as $pendaftar) {
            try {
                $response = Http::timeout(5)->post($urlSiakad, [
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
                    'secret_key'      =>  env('SIAKAD_API_SECRET'),
                ]);

                if ($response->successful() && isset($response->json()['status']) && $response->json()['status'] == 'success') {
                    $pendaftar->update(['is_synced' => true]);
                    Logger::record(
                        'SYNC',
                        'SIAKAD Integration',
                        "Mengirim data mahasiswa {$pendaftar->user->name} ke SIAKAD. NIM Sementara: " . ($result['data']['nim_sementara'] ?? '-')
                    );
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        // 3. Feedback
        $this->resetSelection();

        if ($successCount > 0) {
            session()->flash('success', "Berhasil migrasi $successCount mahasiswa ke SIAKAD! (Gagal: $failCount)");
        } else {
            session()->flash('error', "Gagal menghubungkan ke SIAKAD. Pastikan server SIAKAD aktif.");
        }
    }
}
