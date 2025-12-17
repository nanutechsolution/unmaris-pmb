<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Scholarship;

class ScholarshipManager extends Component
{
    public $name, $description, $quota, $start_date, $end_date;
    public $is_active = true;
    public $selectedId;
    public $isModalOpen = false;

    public function render()
    {
        return view('livewire.admin.scholarship-manager', [
            'scholarships' => Scholarship::withCount('pendaftars')->latest()->get()
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Scholarship::updateOrCreate(['id' => $this->selectedId], [
            'name' => $this->name,
            'description' => $this->description,
            'quota' => $this->quota,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active
        ]);

        $this->isModalOpen = false;
        $this->resetInput();
        session()->flash('message', 'Program beasiswa berhasil disimpan.');
    }

    public function edit($id)
    {
        $s = Scholarship::find($id);
        $this->selectedId = $id;
        $this->name = $s->name;
        $this->description = $s->description;
        $this->quota = $s->quota;
        $this->start_date = $s->start_date->format('Y-m-d');
        $this->end_date = $s->end_date->format('Y-m-d');
        $this->is_active = $s->is_active;
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Scholarship::find($id)->delete();
        session()->flash('message', 'Program beasiswa dihapus.');
    }
    
    public function toggleActive($id)
    {
        $s = Scholarship::find($id);
        $s->update(['is_active' => !$s->is_active]);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->reset(['name', 'description', 'quota', 'start_date', 'end_date', 'is_active', 'selectedId']);
    }
}