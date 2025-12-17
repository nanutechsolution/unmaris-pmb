<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Announcement;
use App\Models\User; // Import Model User
use Illuminate\Support\Facades\Mail; // Import Facade Mail
use App\Mail\PmbNotification; // Import Mailable Class

class AnnouncementManager extends Component
{
    public $title, $content, $type = 'info';
    public $deleteId;

    public function render()
    {
        return view('livewire.admin.announcement-manager', [
            'announcements' => Announcement::latest()->get()
        ]);
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,danger',
        ]);

        // 1. Simpan Pengumuman ke Database
        Announcement::create([
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'is_active' => true
        ]);

        // 2. Kirim Notifikasi Email ke Semua Mahasiswa (Camaba)
        // Menggunakan 'chunk' untuk memproses data dalam kelompok kecil agar hemat memori
        User::where('role', 'camaba')->chunk(50, function ($users) {
            foreach ($users as $user) {
                try {
                    // Gunakan Mail::to(...)->send(...) untuk kirim langsung
                    // Atau gunakan ->queue(...) jika Anda sudah setup Queue Worker
                    Mail::to($user->email)->send(new PmbNotification(
                        $user,
                        '📢 Info Terbaru: ' . $this->title, // Subject Email
                        $this->title, // Judul di dalam Email
                        $this->content, // Isi Pengumuman
                        'CEK DASHBOARD', // Tombol
                        route('camaba.dashboard'), // Link
                        $this->type // Warna (Info/Warning/Danger)
                    ));
                } catch (\Exception $e) {
                    // Tangkap error agar jika 1 email gagal, proses tidak berhenti total
                    // Log::error("Gagal kirim ke {$user->email}");
                }
            }
        });

        $this->reset();
        session()->flash('message', 'Pengumuman berhasil diterbitkan dan notifikasi email sedang dikirim!');
    }

    public function toggleStatus($id)
    {
        $a = Announcement::find($id);
        $a->update(['is_active' => !$a->is_active]);
    }

    public function delete($id)
    {
        Announcement::find($id)->delete();
    }
}