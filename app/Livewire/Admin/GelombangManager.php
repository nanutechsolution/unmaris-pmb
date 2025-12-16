<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Gelombang;

class GelombangManager extends Component
{
    // Form Variables
    public $nama_gelombang, $tgl_mulai, $tgl_selesai;

    public function render()
    {
        return view('livewire.admin.gelombang-manager', [
            'gelombangs' => Gelombang::orderBy('tgl_mulai', 'asc')->get()
        ]);
    }

    public function store()
    {
        $this->validate([
            'nama_gelombang' => 'required|string|max:255',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ]);

        Gelombang::create([
            'nama_gelombang' => $this->nama_gelombang,
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_selesai' => $this->tgl_selesai,
            'is_active' => false // Default mati
        ]);

        $this->reset(['nama_gelombang', 'tgl_mulai', 'tgl_selesai']);
        session()->flash('message', 'Gelombang baru berhasil ditambahkan.');
    }

    public function toggleActive($id)
    {
        $gelombang = Gelombang::find($id);
        
        if ($gelombang->is_active) {
            // Jika mau dimatikan, matikan saja
            $gelombang->update(['is_active' => false]);
        } else {
            // Jika mau dihidupkan, matikan YANG LAIN dulu (Hanya 1 aktif)
            Gelombang::where('id', '!=', $id)->update(['is_active' => false]);
            $gelombang->update(['is_active' => true]);
        }
    }

    public function delete($id)
    {
        Gelombang::find($id)->delete();
    }
}