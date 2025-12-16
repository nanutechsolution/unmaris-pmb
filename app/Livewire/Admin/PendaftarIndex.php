<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url; 
use App\Models\Pendaftar;

class PendaftarIndex extends Component
{
    use WithPagination;

    // Tambahkan #[Url] agar status tersimpan di URL browser
    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filterStatus = '';

    // Hook: Jalankan saat $search berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Hook: Jalankan saat $filterStatus berubah
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Mulai Query
        $query = Pendaftar::with('user')->latest();

        // 1. Logic Search
        if ($this->search) {
            dd($this->search);
            $query->where(function($q) {
                $q->where('nisn', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%'.$this->search.'%');
                  });
            });
        }

        // 2. Logic Filter Status
        // Pastikan dicek tidak kosong
        if (!empty($this->filterStatus)) {
            $query->where('status_pendaftaran', $this->filterStatus);
        }

        return view('livewire.admin.pendaftar-index', [
            'pendaftars' => $query->paginate(10)
        ]);
    }
}