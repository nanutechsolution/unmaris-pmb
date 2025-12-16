<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    
    // Untuk fitur Reset Password
    public $confirmingUserReset = false;
    public $userToResetId;
    public $newPassword;

    public function render()
    {
        $users = User::where('role', 'camaba') // Hanya ambil data camaba, jangan admin
            ->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->with('pendaftar') // Eager load relasi pendaftar
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users
        ]);
    }

    // --- RESET PASSWORD FUNCTION ---
    public function confirmReset($id)
    {
        $this->userToResetId = $id;
        $this->newPassword = ''; // Reset input
        $this->confirmingUserReset = true;
    }

    public function resetPassword()
    {
        $this->validate([
            'newPassword' => 'required|min:8',
        ]);

        $user = User::find($this->userToResetId);
        $user->update([
            'password' => Hash::make($this->newPassword)
        ]);

        $this->confirmingUserReset = false;
        session()->flash('message', "Password untuk {$user->name} berhasil direset.");
    }

    // --- DELETE USER ---
    public function delete($id)
    {
        $user = User::find($id);
        
        // Hapus data pendaftar & file terkait jika perlu (Optional, biasanya cascade delete di database sudah handle)
        $user->delete();
        
        session()->flash('message', 'Akun pengguna berhasil dihapus permanen.');
    }
    
    public function closeModal()
    {
        $this->confirmingUserReset = false;
    }
}