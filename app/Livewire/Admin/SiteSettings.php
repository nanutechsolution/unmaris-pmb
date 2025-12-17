<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SiteSetting;
use App\Models\StudyProgram;
use Illuminate\Support\Facades\Http;

class SiteSettings extends Component
{
    public $nama_kampus, $singkatan_kampus, $alamat_kampus;
    public $biaya_pendaftaran;

    // Ganti field bank single menjadi array
    public $bank_accounts = [];

    public $no_wa_admin, $email_admin;

    public function mount()
    {
        $setting = SiteSetting::first();

        $this->nama_kampus = $setting->nama_kampus;
        $this->singkatan_kampus = $setting->singkatan_kampus;
        $this->alamat_kampus = $setting->alamat_kampus;
        $this->biaya_pendaftaran = $setting->biaya_pendaftaran;

        // Load bank accounts
        if ($setting->bank_accounts) {
            $this->bank_accounts = $setting->bank_accounts;
        } else {
            // Fallback: Ambil dari kolom lama jika JSON masih kosong
            $this->bank_accounts = [
                [
                    'bank' => $setting->nama_bank ?? 'BRI',
                    'rekening' => $setting->nomor_rekening ?? '',
                    'atas_nama' => $setting->atas_nama_rekening ?? ''
                ]
            ];
        }

        $this->no_wa_admin = $setting->no_wa_admin;
        $this->email_admin = $setting->email_admin;
    }

    public function addBank()
    {
        $this->bank_accounts[] = ['bank' => '', 'rekening' => '', 'atas_nama' => ''];
    }

    public function removeBank($index)
    {
        unset($this->bank_accounts[$index]);
        $this->bank_accounts = array_values($this->bank_accounts); // Re-index array
    }

    public function update()
    {
        $this->validate([
            'nama_kampus' => 'required|string',
            'biaya_pendaftaran' => 'required|numeric',
            'bank_accounts.*.bank' => 'required|string',
            'bank_accounts.*.rekening' => 'required|string',
            'bank_accounts.*.atas_nama' => 'required|string',
            'no_wa_admin' => 'required|string',
        ]);

        $setting = SiteSetting::first();
        $setting->update([
            'nama_kampus' => $this->nama_kampus,
            'singkatan_kampus' => $this->singkatan_kampus,
            'alamat_kampus' => $this->alamat_kampus,
            'biaya_pendaftaran' => $this->biaya_pendaftaran,
            'bank_accounts' => $this->bank_accounts, // Simpan array ke JSON
            'no_wa_admin' => $this->no_wa_admin,
            'email_admin' => $this->email_admin,
        ]);

        session()->flash('message', 'Pengaturan website berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.admin.site-settings');
    }


    public function syncProdi()
    {
        try {
            // URL API SIAKAD (Sesuaikan port jika perlu)
            $urlSiakad = 'http://localhost:8001/api/v1/ref/prodi';
            // Panggil API
            $response = Http::get($urlSiakad);

            if ($response->successful()) {
                $data = $response->json()['data'];
                $count = 0;

                // Loop dan update database lokal PMB
                foreach ($data as $prodi) {
                    StudyProgram::updateOrCreate(
                        // Kunci unik: Nama & Jenjang
                        ['name' => $prodi['name'], 'degree' => $prodi['degree']],

                        // Data yang disimpan
                        ['name' => $prodi['name'], 'degree' => $prodi['degree']]
                    );
                    $count++;
                }

                session()->flash('message', "Sukses! $count Program Studi berhasil disinkronisasi dari SIAKAD.");
            } else {
                session()->flash('error', 'Gagal menghubungi SIAKAD. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Koneksi Gagal: Pastikan Server SIAKAD Hidup. (' . $e->getMessage() . ')');
        }
    }
}
