<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;

class HelpdeskManager extends Component
{
    use WithPagination;

    public $filterStatus = 'open'; // Default tampilkan yang belum selesai
    public $search = '';

    public $activeTicket;
    public $replyMessage;
    public $isChatOpen = false;

    public function render()
    {
        $tickets = Ticket::with('user')
            ->where('status', 'like', '%' . ($this->filterStatus == 'all' ? '' : $this->filterStatus) . '%')
            ->where(function($q) {
                $q->where('subject', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function($u) {
                      $u->where('name', 'like', '%'.$this->search.'%');
                  });
            })
            ->latest('updated_at') // Tiket dengan aktivitas terbaru di atas
            ->paginate(10);

        return view('livewire.admin.helpdesk-manager', [
            'tickets' => $tickets
        ]);
    }

    // Buka Chat Room
    public function openTicket($id)
    {
        $this->activeTicket = Ticket::with('replies.user', 'user')->findOrFail($id);
        $this->isChatOpen = true;
    }

    // Kirim Balasan
    public function sendReply()
    {
        $this->validate(['replyMessage' => 'required']);

        TicketReply::create([
            'ticket_id' => $this->activeTicket->id,
            'user_id' => Auth::id(), // ID Admin
            'message' => $this->replyMessage
        ]);

        // Update status tiket jadi 'answered' (Sudah dibalas)
        $this->activeTicket->update(['status' => 'answered', 'updated_at' => now()]);

        $this->replyMessage = '';
        $this->activeTicket->refresh(); // Refresh chat
    }

    // Tutup Tiket (Masalah Selesai)
    public function closeTicket()
    {
        if ($this->activeTicket) {
            $this->activeTicket->update(['status' => 'closed']);
            $this->isChatOpen = false; // Kembali ke list
            session()->flash('message', 'Tiket berhasil ditutup.');
        }
    }

    public function backToList()
    {
        $this->isChatOpen = false;
        $this->activeTicket = null;
    }
}