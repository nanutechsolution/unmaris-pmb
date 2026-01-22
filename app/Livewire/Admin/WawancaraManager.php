<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use App\Services\Logger; // 1. Import Logger

class WawancaraManager extends Component
{
    use WithPagination;

    public $search = '';

    // --- FITUR FILTER (SMART FILTER) ---
    public $filterStatus = 'belum_jadwal'; // Default: Tampilkan yang belum dijadwalkan

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

    // Reset jika pindah halaman/search/filter
    public function updatingPage() { $this->resetSelection(); }
    public function updatingSearch() { $this->resetSelection(); }
    public function updatingFilterStatus() { $this->resetSelection(); $this->resetPage(); }

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
            ->whereIn('status_pendaftaran', ['verifikasi', 'lulus', 'gagal']);

        // --- SMART FILTER LOGIC ---
        if ($this->filterStatus == 'belum_jadwal') {
            $query->whereNull('jadwal_wawancara');
        } elseif ($this->filterStatus == 'sudah_jadwal') {
            $query->whereNotNull('jadwal_wawancara')->where('nilai_wawancara', 0);
        } elseif ($this->filterStatus == 'sudah_nilai') {
            $query->where('nilai_wawancara', '>', 0);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        
        // Sorting: Prioritaskan yang belum selesai (Null jadwal di atas)
        return $query->orderByRaw('jadwal_wawancara IS NULL DESC')->latest();
    }

    public function render()
    {
        return view('livewire.admin.wawancara-manager', [
            'peserta' => $this->getPendaftarQuery()->paginate(10)
        ])->layout('layouts.admin');
    }

    // --- EKSEKUSI JADWAL MASSAL ---
    public function applyBulkSchedule()
    {
        $this->validate([
            'bulk_jadwal_wawancara' => 'required|date',
            'bulk_pewawancara' => 'required|string',
            'selected' => 'required|array|min:1'
        ]);

        // Mencegah duplikasi jadwal yang tidak disengaja (opsional: bisa dihapus jika ingin override)
        // Di sini kita menimpa (override) jadwal yang dipilih, asumsinya admin sadar memilih
        Pendaftar::whereIn('id', $this->selected)->update([
            'jadwal_wawancara' => $this->bulk_jadwal_wawancara,
            'pewawancara' => $this->bulk_pewawancara,
        ]);

        $count = count($this->selected);

        // 2. LOGGING BULK ACTION
        Logger::record(
            'UPDATE',
            'Wawancara',
            "Menjadwalkan wawancara massal untuk {$count} peserta. Jadwal: {$this->bulk_jadwal_wawancara}, Pewawancara: {$this->bulk_pewawancara}"
        );

        $this->resetSelection();
        $this->reset(['bulk_jadwal_wawancara', 'bulk_pewawancara']);
        
        // Pindah filter ke 'sudah_jadwal' agar admin bisa lihat hasilnya
        // Ini mencegah admin mengira data hilang (karena filter 'belum_jadwal' akan menyembunyikan yang baru diupdate)
        $this->filterStatus = 'sudah_jadwal';

        session()->flash('message', "Sukses! $count peserta berhasil dijadwalkan wawancara.");
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

        $p = Pendaftar::find($this->selectedId);
        
        $p->update([
            'jadwal_wawancara' => $this->jadwal_wawancara,
            'pewawancara' => $this->pewawancara,
            'nilai_wawancara' => $this->nilai_wawancara,
            'catatan_wawancara' => $this->catatan_wawancara,
        ]);

        // 3. LOGGING SINGLE UPDATE
        Logger::record(
            'UPDATE',
            'Wawancara',
            "Update data wawancara Pendaftar #{$p->id} ({$p->user->name}). Nilai: {$this->nilai_wawancara}, Pewawancara: {$this->pewawancara}"
        );

        $this->isModalOpen = false;
        session()->flash('message', 'Data wawancara berhasil disimpan.');
    }

    // Fitur Pintar: Set Pewawancara Cepat
    public function setQuickInterviewer($name)
    {
        $this->pewawancara = $name;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }
}