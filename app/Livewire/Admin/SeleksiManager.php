<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use Illuminate\Support\Str;

class SeleksiManager extends Component
{
    use WithPagination;

    public $search = '';
    
    // --- FITUR FILTER (AGAR TIDAK PUSING) ---
    public $filterStatus = 'belum_jadwal'; // Default tampilkan yang perlu dikerjakan duluan
    
    // --- FITUR BULK ACTION ---
    public $selected = []; 
    public $selectAll = false; 
    public $bulk_jadwal_ujian;
    public $bulk_lokasi_ujian;

    // --- FITUR SINGLE EDIT ---
    public $selectedPendaftarId;
    public $jadwal_ujian;
    public $lokasi_ujian;
    public $nilai_ujian;
    public $catatan_penguji;
    public $isModalOpen = false;

    // Hook: Reset seleksi & page jika filter berubah
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
            // Ambil ID sesuai query filter saat ini (Smart Select)
            $this->selected = $this->getPendaftarQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    // Query Pusat yang Lebih Pintar
    private function getPendaftarQuery()
    {
        $query = Pendaftar::with('user')
            // Hanya ambil status yang relevan dengan ujian
            ->whereIn('status_pendaftaran', ['submit', 'verifikasi', 'lulus', 'gagal']);

        // --- FILTER PINTAR ---
        if ($this->filterStatus == 'belum_jadwal') {
            $query->whereNull('jadwal_ujian');
        } elseif ($this->filterStatus == 'sudah_jadwal') {
            $query->whereNotNull('jadwal_ujian')->where('nilai_ujian', 0);
        } elseif ($this->filterStatus == 'sudah_nilai') {
            $query->where('nilai_ujian', '>', 0);
        }

        // Search Logic
        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            });
        }
        
        // Sorting: Prioritaskan yang belum selesai
        return $query->latest(); 
    }

    public function render()
    {
        return view('livewire.admin.seleksi-manager', [
            'peserta' => $this->getPendaftarQuery()->paginate(10)
        ]);
    }

    // --- EKSEKUSI JADWAL MASSAL ---
    public function applyBulkSchedule()
    {
        $this->validate([
            'bulk_jadwal_ujian' => 'required|date',
            'bulk_lokasi_ujian' => 'required|string',
            'selected' => 'required|array|min:1'
        ]);

        Pendaftar::whereIn('id', $this->selected)->update([
            'jadwal_ujian' => $this->bulk_jadwal_ujian,
            'lokasi_ujian' => $this->bulk_lokasi_ujian
        ]);

        $count = count($this->selected);
        $this->resetSelection();
        $this->reset(['bulk_jadwal_ujian', 'bulk_lokasi_ujian']);
        
        // Ubah filter ke 'sudah_jadwal' agar admin bisa lihat hasilnya
        $this->filterStatus = 'sudah_jadwal'; 

        session()->flash('message', "Sukses! $count peserta berhasil dijadwalkan.");
    }

    // --- SINGLE EDIT ---
    public function edit($id)
    {
        $p = Pendaftar::find($id);
        $this->selectedPendaftarId = $id;
        $this->jadwal_ujian = $p->jadwal_ujian ? $p->jadwal_ujian->format('Y-m-d\TH:i') : null;
        $this->lokasi_ujian = $p->lokasi_ujian;
        $this->nilai_ujian = $p->nilai_ujian;
        $this->catatan_penguji = $p->catatan_penguji;
        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'jadwal_ujian' => 'nullable|date',
            'lokasi_ujian' => 'nullable|string',
            'nilai_ujian' => 'required|integer|min:0|max:100',
        ]);

        $p = Pendaftar::find($this->selectedPendaftarId);
        
        $p->update([
            'jadwal_ujian' => $this->jadwal_ujian,
            'lokasi_ujian' => $this->lokasi_ujian,
            'nilai_ujian' => $this->nilai_ujian,
            'catatan_penguji' => $this->catatan_penguji,
        ]);

        $this->isModalOpen = false;
        session()->flash('message', 'Data seleksi berhasil diperbarui.');
    }

    // Fitur Pintar: Set Lokasi Cepat
    public function setQuickLocation($location)
    {
        $this->lokasi_ujian = $location;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }
}