<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class ActivityLogViewer extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->where(function($q) {
                $q->where('action', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%'.$this->search.'%');
                  });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.activity-log-viewer', [
            'logs' => $logs
        ]);
    }
}