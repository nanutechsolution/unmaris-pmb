<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SiteSetting;

class SiteSettings extends Component
{
    public $nama_kampus, $singkatan_kampus, $alamat_kampus;
    public $biaya_pendaftaran, $nama_bank, $nomor_rekening, $atas_nama_rekening;
    public $no_wa_admin, $email_admin;

    public function mount()
    {
        $setting = SiteSetting::first();
        
        $this->nama_kampus = $setting->nama_kampus;
        $this->singkatan_kampus = $setting->singkatan_kampus;
        $this->alamat_kampus = $setting->alamat_kampus;
        
        $this->biaya_pendaftaran = $setting->biaya_pendaftaran;
        $this->nama_bank = $setting->nama_bank;
        $this->nomor_rekening = $setting->nomor_rekening;
        $this->atas_nama_rekening = $setting->atas_nama_rekening;
        
        $this->no_wa_admin = $setting->no_wa_admin;
        $this->email_admin = $setting->email_admin;
    }

    public function update()
    {
        $this->validate([
            'nama_kampus' => 'required|string',
            'biaya_pendaftaran' => 'required|numeric',
            'nomor_rekening' => 'required|string',
            'no_wa_admin' => 'required|string',
        ]);

        $setting = SiteSetting::first();
        $setting->update([
            'nama_kampus' => $this->nama_kampus,
            'singkatan_kampus' => $this->singkatan_kampus,
            'alamat_kampus' => $this->alamat_kampus,
            'biaya_pendaftaran' => $this->biaya_pendaftaran,
            'nama_bank' => $this->nama_bank,
            'nomor_rekening' => $this->nomor_rekening,
            'atas_nama_rekening' => $this->atas_nama_rekening,
            'no_wa_admin' => $this->no_wa_admin,
            'email_admin' => $this->email_admin,
        ]);

        session()->flash('message', 'Pengaturan website berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.admin.site-settings');
    }
}