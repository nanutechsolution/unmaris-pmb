<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Helpdesk extends Component
{
    #[Layout('layouts.camaba')]

    public $viewState = 'list'; // 'list', 'create', 'detail'
    
    // Form Create
    public $subject, $category = 'umum', $message;
    
    // Detail View
    public $activeTicket;
    public $replyMessage;

    public function render()
    {
        return view('livewire.camaba.helpdesk', [
            'tickets' => Ticket::where('user_id', Auth::id())->latest()->get()
        ]);
    }

    // --- FITUR BUAT TIKET ---
    public function openCreate() { $this->viewState = 'create'; }
    public function cancel() { $this->viewState = 'list'; $this->reset(['subject', 'message', 'replyMessage']); }

    public function store()
    {
        $this->validate([
            'subject' => 'required|min:5',
            'category' => 'required',
            'message' => 'required|min:10',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject,
            'category' => $this->category,
            'status' => 'open'
        ]);

        // Simpan pesan pertama sebagai reply
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $this->message
        ]);

        session()->flash('message', 'Tiket bantuan berhasil dikirim! Tunggu balasan admin.');
        $this->cancel();
    }

    // --- FITUR DETAIL & BALAS ---
    public function show($id)
    {
        $this->activeTicket = Ticket::with('replies.user')->where('user_id', Auth::id())->findOrFail($id);
        $this->viewState = 'detail';
    }

    public function sendReply()
    {
        $this->validate(['replyMessage' => 'required']);

        TicketReply::create([
            'ticket_id' => $this->activeTicket->id,
            'user_id' => Auth::id(),
            'message' => $this->replyMessage
        ]);

        // Jika status closed, buka lagi karena ada chat baru
        if ($this->activeTicket->status == 'closed') {
            $this->activeTicket->update(['status' => 'open']);
        }

        $this->replyMessage = '';
        $this->activeTicket->refresh(); // Refresh data
    }
}