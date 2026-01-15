<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\FacilitySlide;
use Illuminate\Support\Facades\Storage;

class FacilityManager extends Component
{
    use WithFileUploads;

    public $slides;
    public $slideId;
    public $title, $description, $icon;
    public $is_active = true;
    
    // Penanganan Gambar
    public $newImages = []; // Untuk upload baru (Temporary)
    public $oldImages = []; // Untuk menyimpan path gambar lama saat edit

    public $isModalOpen = false;

    public function render()
    {
        $this->slides = FacilitySlide::orderBy('sort_order', 'asc')->get();
        return view('livewire.admin.facility-manager')->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required|string|max:5', // Emoji biasanya 1-4 bytes
            'newImages.*' => 'image|max:2048', // Max 2MB per foto
        ]);

        $imagePaths = [];

        // Proses Upload Multiple Images
        if ($this->newImages) {
            foreach ($this->newImages as $image) {
                $imagePaths[] = $image->store('facility-slides', 'public');
            }
        }

        FacilitySlide::create([
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'images' => $imagePaths,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Fasilitas berhasil ditambahkan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $slide = FacilitySlide::findOrFail($id);
        $this->slideId = $id;
        $this->title = $slide->title;
        $this->description = $slide->description;
        $this->icon = $slide->icon;
        $this->is_active = $slide->is_active;
        $this->oldImages = $slide->images ?? []; // Muat gambar lama
        
        $this->isModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required',
        ]);

        $slide = FacilitySlide::findOrFail($this->slideId);
        
        // 1. Ambil gambar lama yang masih dipertahankan user
        $finalImages = $this->oldImages;

        // 2. Jika ada upload baru, simpan dan gabungkan arraynya
        if ($this->newImages) {
            foreach ($this->newImages as $image) {
                $finalImages[] = $image->store('facility-slides', 'public');
            }
        }

        $slide->update([
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'images' => $finalImages, // Simpan array gabungan
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Data fasilitas berhasil diperbarui.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $slide = FacilitySlide::find($id);
        if($slide) {
            $slide->delete(); // File fisik dihapus otomatis oleh Model Event (booted)
            session()->flash('message', 'Fasilitas dihapus.');
        }
    }

    // Fitur: Hapus satu foto spesifik saat mode Edit
    public function removePhoto($index)
    {
        // Hapus file fisik
        if(isset($this->oldImages[$index])) {
            Storage::disk('public')->delete($this->oldImages[$index]);
            // Hapus dari array
            unset($this->oldImages[$index]);
            // Re-index array supaya urutan json rapi (0,1,2 bukan 0,2,3)
            $this->oldImages = array_values($this->oldImages);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->icon = '';
        $this->newImages = [];
        $this->oldImages = [];
        $this->slideId = null;
        $this->is_active = true;
    }
}