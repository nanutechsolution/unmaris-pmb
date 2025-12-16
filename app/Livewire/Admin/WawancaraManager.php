<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;

class WawancaraManager extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Variables
    public $selectedId;
    public $jadwal_wawancara;
    public $pewawancara;
    public $nilai_wawancara;
    public $catatan_wawancara;
    public $isModalOpen = false;

    public function render()
    {
        // Tampilkan peserta yang sudah lolos tahap verifikasi berkas
        $query = Pendaftar::with('user')
            ->whereIn('status_pendaftaran', ['verifikasi', 'lulus', 'gagal'])
            ->latest();

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            });
        }

        return view('livewire.admin.wawancara-manager', [
            'peserta' => $query->paginate(10)
        ]);
    }

    public function edit($id)
    {
        $p = Pendaftar::find($id);
        $this->selectedId = $id;
        $this->jadwal_wawancara = $p->jadwal_wawancara ? $p->jadwal_wawancara->format('Y-m-d\TH:i') : null;
        $this->pewawancara = $p->pewawancara;
        $this->nilai_wawancara = $p->nilai_wawancara;
        $this->catatan_wawancara = $p->catatan_wawancara;
        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'jadwal_wawancara' => 'nullable|date',
            'pewawancara' => 'nullable|string',
            'nilai_wawancara' => 'required|integer|min:0|max:100',
        ]);

        Pendaftar::find($this->selectedId)->update([
            'jadwal_wawancara' => $this->jadwal_wawancara,
            'pewawancara' => $this->pewawancara,
            'nilai_wawancara' => $this->nilai_wawancara,
            'catatan_wawancara' => $this->catatan_wawancara,
        ]);

        $this->isModalOpen = false;
        session()->flash('message', 'Data wawancara berhasil disimpan.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }
}