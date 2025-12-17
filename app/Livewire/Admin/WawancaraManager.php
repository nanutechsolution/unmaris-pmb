<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;

class WawancaraManager extends Component
{
    use WithPagination;

    public $search = '';
    
    // --- FITUR BULK ACTION (AKSI MASSAL) ---
    public $selected = []; 
    public $selectAll = false;
    
    public $bulk_jadwal_wawancara;
    public $bulk_pewawancara;

    // --- FITUR SINGLE EDIT ---
    public $selectedId;
    public $jadwal_wawancara;
    public $pewawancara;
    public $nilai_wawancara;
    public $catatan_wawancara;
    public $isModalOpen = false;

    // Reset jika pindah halaman/search
    public function updatingPage() { $this->resetSelection(); }
    public function updatingSearch() { $this->resetSelection(); }

    public function resetSelection()
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getPendaftarQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    private function getPendaftarQuery()
    {
        $query = Pendaftar::with('user')
            ->whereIn('status_pendaftaran', ['verifikasi', 'lulus', 'gagal'])
            ->latest();

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            });
        }
        return $query;
    }

    public function render()
    {
        return view('livewire.admin.wawancara-manager', [
            'peserta' => $this->getPendaftarQuery()->paginate(10)
        ]);
    }

    // --- EKSEKUSI JADWAL MASSAL ---
    public function applyBulkSchedule()
    {
        $this->validate([
            'bulk_jadwal_wawancara' => 'required|date',
            'bulk_pewawancara' => 'required|string',
            'selected' => 'required|array|min:1'
        ]);

        Pendaftar::whereIn('id', $this->selected)->update([
            'jadwal_wawancara' => $this->bulk_jadwal_wawancara,
            'pewawancara' => $this->bulk_pewawancara
        ]);

        $count = count($this->selected);
        $this->resetSelection();
        $this->reset(['bulk_jadwal_wawancara', 'bulk_pewawancara']);
        
        session()->flash('message', "Berhasil menjadwalkan wawancara untuk $count peserta sekaligus!");
    }

    // --- SINGLE EDIT ---
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