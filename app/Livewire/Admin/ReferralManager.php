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

    public $search = '';
    public $filterStatus = '';
    public $perPage = 15;

    // Form
    public $rewardId;
    public $pendaftar_id;
    public $referral_scheme_id;
    public $reward_amount;
    public $status = 'eligible';

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
        if (!in_array(Auth::user()->role, ['admin', 'keuangan'])) {
            abort(403);
        }
    }

    public function render()
    {
        $rewards = ReferralReward::query()
            ->with(['pendaftar.user', 'scheme'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('pendaftar.user', function ($sub) {
                        $sub->where('name', 'like', "%{$this->search}%");
                    })
                        ->orWhereHas('pendaftar', function ($sub) {
                            $sub->where('nama_referensi', 'like', "%{$this->search}%");
                        });
                });
            })

            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.referral-manager', [
            'rewards' => $rewards,

            'pendaftars' => Pendaftar::query()
                ->join('users', 'pendaftars.user_id', '=', 'users.id')
                ->orderBy('users.name')
                ->select('pendaftars.*')
                ->get(),

            'schemes' => ReferralScheme::orderBy('name')->get(),
        ])->layout('layouts.admin');
    }


    public function resetForm()
    {
        $this->reset([
            'rewardId',
            'pendaftar_id',
            'referral_scheme_id',
            'reward_amount',
            'status'
        ]);
        $this->status = 'eligible';
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
        session()->flash('success', 'Reward berhasil disimpan.');
    }

    public function markAsPaid($id)
    {
        DB::transaction(function () use ($id) {
            $reward = ReferralReward::findOrFail($id);
            $reward->update([
                'status' => 'paid',
                'paid_at' => now(),
                'processed_by' => Auth::id(),
            ]);
        });

        session()->flash('success', 'Reward ditandai sebagai PAID.');
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            ReferralReward::findOrFail($id)->delete();
        });

        session()->flash('success', 'Reward dihapus.');
    }
}
