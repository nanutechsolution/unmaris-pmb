<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\Logger;
use App\Notifications\Admin\ExamNotification;

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
    
    // Fitur Toggle WA (Default Aktif)
    public $send_wa_notification = true; 

    // --- FITUR SINGLE EDIT ---
    public $selectedPendaftarId;
    public $jadwal_ujian;
    public $lokasi_ujian;
    public $nilai_ujian;
    public $catatan_penguji;
    public $isModalOpen = false;

    // Hook: Reset seleksi & page jika filter berubah
    public function updatingPage()
    {
        $this->resetSelection();
    }
    public function updatingSearch()
    {
        $this->resetSelection();
    }
    public function updatingFilterStatus()
    {
        $this->resetSelection();
        $this->resetPage();
    }

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
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Sorting: Prioritaskan yang belum selesai
        return $query->latest();
    }

    public function render()
    {
        return view('livewire.admin.seleksi-manager', [
            'peserta' => $this->getPendaftarQuery()->paginate(10)
        ])->layout('layouts.admin');
    }

    // --- EKSEKUSI JADWAL MASSAL ---
    public function applyBulkSchedule()
    {
        $this->validate([
            'bulk_jadwal_ujian' => 'required|date',
            'bulk_lokasi_ujian' => 'required|string',
            'selected' => 'required|array|min:1'
        ]);

        try {
            DB::transaction(function () {
                $targets = Pendaftar::with('user')->whereIn('id', $this->selected)->get();

                foreach ($targets as $p) {
                    // Update Database
                    $p->update([
                        'jadwal_ujian' => $this->bulk_jadwal_ujian,
                        'lokasi_ujian' => $this->bulk_lokasi_ujian
                    ]);

                    // Kirim Notifikasi WA & Email (Try-Catch agar tidak membatalkan DB Transaction jika server WA down)
                    if ($this->send_wa_notification && $p->user) {
                        try {
                            $p->user->notify(new ExamNotification('schedule', $p));
                        } catch (\Exception $e) {
                            Logger::record('ERROR', 'WA Gagal', "Gagal kirim jadwal ke {$p->user->name}: " . $e->getMessage());
                        }
                    }
                }
            });

            $count = count($this->selected);
            Logger::record('UPDATE', 'Jadwal Massal', "Menetapkan jadwal ujian untuk $count peserta.");

            $this->resetSelection();
            $this->reset(['bulk_jadwal_ujian', 'bulk_lokasi_ujian']);

            // Ubah filter ke 'sudah_jadwal' agar admin bisa lihat hasilnya
            $this->filterStatus = 'sudah_jadwal';

            session()->flash('message', "Sukses! $count peserta berhasil dijadwalkan" . ($this->send_wa_notification ? " & dinotifikasi via WA." : "."));

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan sistem saat menjadwalkan: ' . $e->getMessage());
        }
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

        try {
            $p = Pendaftar::with('user')->find($this->selectedPendaftarId);

            // Deteksi Pintar: Apa yang diubah oleh admin?
            $isScheduleChanged = ($p->jadwal_ujian != $this->jadwal_ujian || $p->lokasi_ujian != $this->lokasi_ujian) && !empty($this->jadwal_ujian);
            $isScoreChanged = ($p->nilai_ujian != $this->nilai_ujian) && $this->nilai_ujian > 0;

            // Simpan Perubahan
            $p->update([
                'jadwal_ujian' => $this->jadwal_ujian,
                'lokasi_ujian' => $this->lokasi_ujian,
                'nilai_ujian' => $this->nilai_ujian,
                'catatan_penguji' => $this->catatan_penguji,
            ]);

            // Kirim Notifikasi sesuai konteks perubahan
            if ($this->send_wa_notification && $p->user) {
                try {
                    if ($isScheduleChanged) {
                        $p->user->notify(new ExamNotification('schedule', $p));
                    } elseif ($isScoreChanged) {
                        $p->user->notify(new ExamNotification('score', $p));
                    }
                } catch (\Exception $e) {
                    Logger::record('ERROR', 'WA Gagal', "Gagal kirim update ujian ke {$p->user->name}");
                }
            }

            Logger::record('UPDATE', 'Data Seleksi', "Memperbarui data ujian {$p->user->name}");

            $this->isModalOpen = false;
            session()->flash('message', 'Data seleksi berhasil diperbarui' . (($isScheduleChanged || $isScoreChanged) && $this->send_wa_notification ? ' & notifikasi terkirim.' : '.'));

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
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