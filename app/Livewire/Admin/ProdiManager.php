<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\StudyProgram;
use Livewire\WithPagination;

class ProdiManager extends Component
{
    use WithPagination;

    public $name, $degree, $tuition_fee, $is_active = true, $prodi_id;
    public $isOpen = false;

    public function render()
    {
        return view('livewire.admin.prodi-manager', [
            'prodis' => StudyProgram::latest()->paginate(10),
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->degree = 'S1';
        $this->tuition_fee = '';
        $this->is_active = true;
        $this->prodi_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'degree' => 'required',
            'tuition_fee' => 'required|numeric',
        ]);

        StudyProgram::updateOrCreate(['id' => $this->prodi_id], [
            'name' => $this->name,
            'degree' => $this->degree,
            'tuition_fee' => $this->tuition_fee,
            'is_active' => $this->is_active
        ]);

        session()->flash('message', $this->prodi_id ? 'Prodi berhasil diperbarui.' : 'Prodi berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $prodi = StudyProgram::findOrFail($id);
        $this->prodi_id = $id;
        $this->name = $prodi->name;
        $this->degree = $prodi->degree;
        $this->tuition_fee = $prodi->tuition_fee;
        $this->is_active = $prodi->is_active;

        $this->openModal();
    }

    public function delete($id)
    {
        StudyProgram::find($id)->delete();
        session()->flash('message', 'Prodi berhasil dihapus.');
    }
}