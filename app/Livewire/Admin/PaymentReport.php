<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use Carbon\Carbon;
use App\Models\SiteSetting;

class PaymentReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $filterStatus = ''; // lunas, menunggu_verifikasi, ditolak

    public function mount()
    {
        // Default: Bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $setting = SiteSetting::first();
        $nominalBiaya = $setting->biaya_pendaftaran ?? 200000;

        $query = Pendaftar::with('user')
            ->where('status_pembayaran', '!=', 'belum_bayar') // Hanya yang sudah ada interaksi bayar
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate);

        if ($this->filterStatus) {
            $query->where('status_pembayaran', $this->filterStatus);
        }

        // Hitung Summary
        $summary = [
            'total_uang_masuk' => (clone $query)->where('status_pembayaran', 'lunas')->count() * $nominalBiaya,
            'potensi_uang' => (clone $query)->where('status_pembayaran', 'menunggu_verifikasi')->count() * $nominalBiaya,
            'jumlah_transaksi' => (clone $query)->count(),
        ];

        return view('livewire.admin.payment-report', [
            'transaksi' => $query->latest()->paginate(15),
            'summary' => $summary,
            'nominal' => $nominalBiaya
        ])->layout('layouts.admin');
    }

    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }
    public function updatedFilterStatus() { $this->resetPage(); }
}