<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReferralReward;
use App\Models\ReferralScheme;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReferralManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filter Properties
    public $search = '';
    public $filterStatus = '';
    public $perPage = 15;

    // Form Properties
    public $rewardId;
    public $pendaftar_id;
    public $referral_scheme_id;
    public $reward_amount;
    public $status = 'eligible';

    // UI States
    public $showModal = false;
    public $isEdit = false;

    protected function rules()
    {
        return [
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'referral_scheme_id' => 'required|exists:referral_schemes,id',
            'reward_amount' => 'required|numeric|min:0',
            'status' => 'required|in:eligible,paid,cancelled',
        ];
    }

    public function mount()
    {
        // Pastikan role sesuai (sesuaikan dengan logic middleware Anda)
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'keuangan'])) {
            abort(403);
        }
    }

    // --- FIX UTAMA: Reset Pagination saat Filter Berubah ---
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    // -------------------------------------------------------

    public function render()
    {
        $rewards = ReferralReward::query()
            ->with(['pendaftar.user', 'scheme'])
            ->when($this->search, function ($q) {
                $q->whereHas('pendaftar.user', function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%");
                })->orWhereHas('pendaftar', function ($sub) {
                    $sub->where('nama_referensi', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate($this->perPage);

        // Data untuk Dropdown di Modal
        $pendaftars = [];
        $schemes = [];
        
        if($this->showModal) {
            $pendaftars = Pendaftar::query()
                ->join('users', 'pendaftars.user_id', '=', 'users.id')
                ->orderBy('users.name')
                ->select('pendaftars.id', 'users.name')
                ->get();
            
            $schemes = ReferralScheme::orderBy('name')->get();
        }

        return view('livewire.admin.referral-manager', [
            'rewards' => $rewards,
            'pendaftars' => $pendaftars,
            'schemes' => $schemes,
        ])->layout('layouts.admin');
    }

    public function resetForm()
    {
        $this->reset(['rewardId', 'pendaftar_id', 'referral_scheme_id', 'reward_amount', 'status']);
        $this->status = 'eligible';
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $reward = ReferralReward::findOrFail($id);

        $this->rewardId = $reward->id;
        $this->pendaftar_id = $reward->pendaftar_id;
        $this->referral_scheme_id = $reward->referral_scheme_id;
        $this->reward_amount = $reward->reward_amount;
        $this->status = $reward->status;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            ReferralReward::updateOrCreate(
                ['id' => $this->rewardId],
                [
                    'pendaftar_id' => $this->pendaftar_id,
                    'referral_scheme_id' => $this->referral_scheme_id,
                    'reward_amount' => $this->reward_amount,
                    'status' => $this->status,
                    'paid_at' => $this->status === 'paid' ? now() : null,
                    'processed_by' => Auth::id(),
                ]
            );
        });

        $this->showModal = false;
        session()->flash('success', 'Data Reward berhasil disimpan.');
    }

    public function markAsPaid($id)
    {
        ReferralReward::where('id', $id)->update([
            'status' => 'paid',
            'paid_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        session()->flash('success', 'Status berhasil diubah menjadi PAID.');
    }

    public function delete($id)
    {
        // ReferralReward::findOrFail($id)->delete();
        session()->flash('success', 'Data tidak bisa dihapus.');
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
}