<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserFilterExport;
use Illuminate\Validation\Rule;
use App\Services\Logger;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = ''; 
    public $activeTab = 'camaba'; 

    public $isEditModalOpen = false;
    public $isCreateModalOpen = false;
    public $isDeleteModalOpen = false; 
    public $confirmingUserReset = false;

    public $userIdBeingEdited;
    public $userIdBeingDeleted; 
    public $userNameBeingDeleted; // Properti baru untuk menyimpan nama
    public $userToResetId;
    
    public $name, $email, $nomor_hp, $role, $password, $newPassword;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); $this->search = ''; }

    public function render()
    {
        $query = User::query();

        if ($this->activeTab === 'camaba') {
            $query->where('role', 'camaba');
            if ($this->filterStatus === 'sudah_isi') {
                $query->has('pendaftar');
            } elseif ($this->filterStatus === 'belum_isi') {
                $query->doesntHave('pendaftar');
            }
        } else {
            $query->whereIn('role', ['admin', 'keuangan', 'akademik']);
        }

        $query->where(function($q) {
            $q->where('name', 'like', '%'.$this->search.'%')
              ->orWhere('email', 'like', '%'.$this->search.'%')
              ->orWhere('nomor_hp', 'like', '%'.$this->search.'%');
        });

        return view('livewire.admin.user-manager', [
            'users' => $query->with('pendaftar')->latest()->paginate(10)
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->role = 'akademik'; 
        $this->isCreateModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_hp' => 'required|numeric',
            'role' => 'required|in:keuangan,akademik',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'role' => $this->role,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(), 
        ]);

        Logger::record('CREATE', 'Manajemen User', "Menambahkan petugas: {$this->name}");
        $this->closeModal();
        session()->flash('message', 'Petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userIdBeingEdited = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nomor_hp = $user->nomor_hp;
        $this->role = $user->role;
        $this->isEditModalOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userIdBeingEdited)],
            'nomor_hp' => 'required|numeric',
            'role' => 'required|in:admin,keuangan,akademik,camaba',
        ]);

        $user = User::findOrFail($this->userIdBeingEdited);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'role' => $this->role,
        ]);

        Logger::record('UPDATE', 'Manajemen User', "Update user #{$user->id}");
        $this->closeModal();
        session()->flash('message', 'Data berhasil diperbarui.');
    }

    public function openDeleteModal($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun sendiri!');
            return;
        }

        $user = User::findOrFail($id);
        $this->userIdBeingDeleted = $id;
        $this->userNameBeingDeleted = $user->name; // Ambil nama user
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        $user = User::find($this->userIdBeingDeleted);
        if ($user) {
            $name = $user->name;
            $user->delete();
            Logger::record('DELETE', 'Manajemen User', "Menghapus user: {$name}");
            session()->flash('message', 'Akun berhasil dihapus.');
        }
        $this->closeModal();
    }

    public function confirmReset($id)
    {
        $this->userToResetId = $id;
        $this->newPassword = '';
        $this->confirmingUserReset = true;
    }

    public function resetPassword()
    {
        $this->validate(['newPassword' => 'required|min:8']);
        $user = User::find($this->userToResetId);
        $user->update(['password' => Hash::make($this->newPassword)]);
        
        Logger::record('SECURITY', 'Manajemen User', "Reset password: {$user->name}");
        $this->closeModal();
        session()->flash('message', "Password berhasil direset.");
    }

    public function closeModal()
    {
        $this->isEditModalOpen = false;
        $this->isCreateModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->confirmingUserReset = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = $this->email = $this->nomor_hp = $this->role = $this->password = $this->newPassword = '';
        $this->userIdBeingEdited = $this->userIdBeingDeleted = $this->userNameBeingDeleted = $this->userToResetId = null;
    }

    public function exportFiltered()
    {
        $query = User::where('role', 'camaba')
            ->when($this->filterStatus === 'sudah_isi', fn($q) => $q->has('pendaftar'))
            ->when($this->filterStatus === 'belum_isi', fn($q) => $q->doesntHave('pendaftar'));

        Logger::record('EXPORT', 'Manajemen User', "Export data camaba");
        return Excel::download(new UserFilterExport($query), 'data_camaba_'.date('Ymd').'.xlsx');
    }
}