<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Pendaftar;

class PendaftarIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filterStatus = ''; // Filter Status Pendaftaran (Lulus/Gagal)

    #[Url(history: true)]
    public $filterPembayaran = ''; // Filter Status Pembayaran (Lunas/Menunggu)

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterPembayaran() { $this->resetPage(); }

    public function render()
    {
        $query = Pendaftar::with('user')->latest();

        // 1. Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nisn', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // 2. Filter Status Pendaftaran
        if (!empty($this->filterStatus)) {
            $query->where('status_pendaftaran', $this->filterStatus);
        }

        // 3. Filter Status Pembayaran (BARU)
        if (!empty($this->filterPembayaran)) {
            $query->where('status_pembayaran', $this->filterPembayaran);
        }

        return view('livewire.admin.pendaftar-index', [
            'pendaftars' => $query->paginate(10)
        ]);
    }
}