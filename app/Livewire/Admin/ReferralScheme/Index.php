<?php

namespace App\Livewire\Admin\ReferralScheme;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReferralScheme;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $name, $jalur, $reward_amount, $start_date, $end_date, $target_min;
    public $is_active = true;
    public $editId = null;
    public $search = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'jalur' => 'nullable|string|max:255',
            'reward_amount' => 'required|numeric|min:0',
            'target_min' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function save()
    {
        $this->validate();

        // Jika is_active = true, matikan scheme lain
        if ($this->is_active) {
            ReferralScheme::where('id', '!=', $this->editId)
                ->update(['is_active' => false]);
        }

        ReferralScheme::updateOrCreate(
            ['id' => $this->editId],
            [
                'name' => $this->name,
                'jalur' => $this->jalur,
                'reward_amount' => $this->reward_amount,
                'target_min' => $this->target_min,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('success', 'Scheme berhasil disimpan.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $scheme = ReferralScheme::findOrFail($id);

        $this->editId = $scheme->id;
        $this->name = $scheme->name;
        $this->jalur = $scheme->jalur;
        $this->reward_amount = $scheme->reward_amount;
        $this->target_min = $scheme->target_min;
        $this->start_date = $scheme->start_date->format('Y-m-d');
        $this->end_date = $scheme->end_date?->format('Y-m-d');
        $this->is_active = $scheme->is_active;
    }

    public function delete($id)
    {
        ReferralScheme::findOrFail($id)->delete();
        session()->flash('success', 'Scheme dihapus.');
    }

    public function toggleActive($id)
    {
        ReferralScheme::query()->update(['is_active' => false]);

        ReferralScheme::findOrFail($id)->update(['is_active' => true]);

        session()->flash('success', 'Scheme diaktifkan.');
    }

    public function resetForm()
    {
        $this->reset(['name', 'jalur', 'reward_amount', 'start_date', 'end_date', 'target_min', 'is_active', 'editId']);
        $this->is_active = true;
    }

    public function render()
    {
        $schemes = ReferralScheme::where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(5);

        return view('livewire.admin.referral-scheme.index', compact('schemes'))->layout('layouts.admin');
    }
}
