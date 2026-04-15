<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use App\Services\Logger;
use Illuminate\Support\Facades\DB;
use App\Notifications\Admin\InterviewNotification;

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

    // Fitur Toggle WA (Default Aktif)
    public $send_wa_notification = true;

    // --- FITUR SINGLE EDIT ---
    public $selectedId;
    public $jadwal_wawancara;
    public $pewawancara;
    public $nilai_wawancara;
    public $catatan_wawancara;
    public $isModalOpen = false;

    // Reset jika pindah halaman/search/filter
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

        try {
            DB::transaction(function () {
                $targets = Pendaftar::with('user')->whereIn('id', $this->selected)->get();

                foreach ($targets as $p) {
                    $p->update([
                        'jadwal_wawancara' => $this->bulk_jadwal_wawancara,
                        'pewawancara' => $this->bulk_pewawancara,
                    ]);

                    // Kirim Notifikasi WA & Email (Try-Catch agar error WA tidak rollback DB)
                    if ($this->send_wa_notification && $p->user) {
                        try {
                            $p->user->notify(new InterviewNotification('schedule', $p));
                        } catch (\Exception $e) {
                            Logger::record('ERROR', 'WA Gagal', "Gagal kirim jadwal wawancara ke {$p->user->name}: " . $e->getMessage());
                        }
                    }
                }
            });

            $count = count($this->selected);

            Logger::record(
                'UPDATE',
                'Wawancara Massal',
                "Menjadwalkan wawancara massal untuk {$count} peserta. Jadwal: {$this->bulk_jadwal_wawancara}, Pewawancara: {$this->bulk_pewawancara}"
            );

            $this->resetSelection();
            $this->reset(['bulk_jadwal_wawancara', 'bulk_pewawancara']);

            // Pindah filter ke 'sudah_jadwal' agar admin bisa lihat hasilnya
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

        try {
            $p = Pendaftar::with('user')->find($this->selectedId);

            // Deteksi Pintar: Apa yang diubah oleh admin?
            $isScheduleChanged = ($p->jadwal_wawancara != $this->jadwal_wawancara || $p->pewawancara != $this->pewawancara) && !empty($this->jadwal_wawancara);
            $isScoreChanged = ($p->nilai_wawancara != $this->nilai_wawancara) && $this->nilai_wawancara > 0;

            $p->update([
                'jadwal_wawancara' => $this->jadwal_wawancara,
                'pewawancara' => $this->pewawancara,
                'nilai_wawancara' => $this->nilai_wawancara,
                'catatan_wawancara' => $this->catatan_wawancara,
            ]);

            // Kirim Notifikasi sesuai konteks perubahan
            if ($this->send_wa_notification && $p->user) {
                try {
                    if ($isScheduleChanged) {
                        $p->user->notify(new InterviewNotification('schedule', $p));
                    } elseif ($isScoreChanged) {
                        $p->user->notify(new InterviewNotification('score', $p));
                    }
                } catch (\Exception $e) {
                    Logger::record('ERROR', 'WA Gagal', "Gagal kirim update wawancara ke {$p->user->name}");
                }
            }

            Logger::record(
                'UPDATE',
                'Data Wawancara',
                "Update data wawancara Pendaftar #{$p->id} ({$p->user->name}). Nilai: {$this->nilai_wawancara}, Pewawancara: {$this->pewawancara}"
            );

            $this->isModalOpen = false;
            session()->flash('message', 'Data wawancara berhasil disimpan' . (($isScheduleChanged || $isScoreChanged) && $this->send_wa_notification ? ' & notifikasi terkirim.' : '.'));
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
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
