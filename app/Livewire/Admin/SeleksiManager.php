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
    
    // --- FITUR BULK ACTION (AKSI MASSAL) ---
    public $selected = []; // Menyimpan ID yang dicentang
    public $selectAll = false; // Checkbox "Pilih Semua"
    
    // Variable Form Massal
    public $bulk_jadwal_ujian;
    public $bulk_lokasi_ujian;

    // --- FITUR SINGLE EDIT ---
    public $selectedPendaftarId;
    public $jadwal_ujian;
    public $lokasi_ujian;
    public $nilai_ujian;
    public $catatan_penguji;
    public $isModalOpen = false;

    // Hook: Reset seleksi jika pindah halaman atau search
    public function updatingPage() { $this->resetSelection(); }
    public function updatingSearch() { $this->resetSelection(); }

    public function resetSelection()
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    // Logic "Pilih Semua"
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Ambil semua ID dari query saat ini (semua halaman atau halaman ini saja tergantung kebutuhan logic)
            // Di sini kita ambil halaman ini saja agar performa aman
            $this->selected = $this->getPendaftarQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    // Query Pusat
    private function getPendaftarQuery()
    {
        $query = Pendaftar::with('user')
            // Tampilkan yang sudah diverifikasi, lulus, atau gagal
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
        
        session()->flash('message', "Berhasil menjadwalkan ujian untuk $count peserta sekaligus!");
    }

    // --- SINGLE EDIT FUNCTIONS ---
    public function edit($id)
    {
        $p = Pendaftar::find($id);
        $this->selectedPendaftarId = $id;
        // Format untuk input datetime-local HTML5
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

        // Otomatis LULUS jika nilai >= 75 (Opsional, matikan jika ingin manual)
        /*
        if ($this->nilai_ujian >= 75) {
            $p->update(['status_pendaftaran' => 'lulus']);
        }
        */

        $this->isModalOpen = false;
        session()->flash('message', 'Data seleksi peserta berhasil disimpan.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }
}