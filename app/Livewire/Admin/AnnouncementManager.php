<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Announcement;

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

        Announcement::create([
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'is_active' => true
        ]);

        $this->reset();
        session()->flash('message', 'Pengumuman berhasil diterbitkan!');
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