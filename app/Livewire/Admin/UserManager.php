<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserFilterExport;
use Illuminate\Validation\Rule;
use App\Services\Logger; // 1. Import Logger

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = ''; 
    public $activeTab = 'camaba'; 

    public $isEditModalOpen = false;
    public $isCreateModalOpen = false;
    public $userIdBeingEdited;
    
    public $name, $email, $nomor_hp, $role, $password;

    public $confirmingUserReset = false;
    public $userToResetId;
    public $newPassword;

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

        $users = $query->with('pendaftar')->latest()->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users
        ])->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        // Default role selain admin untuk keamanan
        $this->role = 'akademik'; 
        $this->isCreateModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_hp' => 'required|numeric',
            'role' => 'required|in:keuangan,akademik', // Hapus admin dari validasi create
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'role' => $this->role,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(), 
        ]);

        // 2. Log Activity
        Logger::record('CREATE', 'Manajemen User', "Menambahkan petugas baru: {$this->name} sebagai {$this->role}");

        $this->isCreateModalOpen = false;
        session()->flash('message', 'Petugas baru berhasil ditambahkan.');
        $this->resetInputFields();
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
        // Validasi, ijinkan admin jika sedang edit data admin
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userIdBeingEdited)],
            'nomor_hp' => 'required|numeric',
            'role' => 'required|in:admin,keuangan,akademik,camaba',
        ]);

        $user = User::findOrFail($this->userIdBeingEdited);
        $oldRole = $user->role;
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'role' => $this->role,
        ]);

        // 3. Log Activity
        Logger::record('UPDATE', 'Manajemen User', "Update data user #{$user->id}: {$user->name} (Role: $oldRole -> {$this->role})");

        $this->isEditModalOpen = false;
        session()->flash('message', 'Data pengguna berhasil diperbarui.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            $this->dispatch('error', 'Anda tidak bisa menghapus akun sendiri!');
            return;
        }

        $user = User::find($id);
        if ($user) {
            $userName = $user->name;
            $user->delete();

            // 4. Log Activity
            Logger::record('DELETE', 'Manajemen User', "Menghapus user: {$userName}");

            session()->flash('message', 'Akun pengguna berhasil dihapus permanen.');
        }
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
        
        // 5. Log Activity
        Logger::record('SECURITY', 'Manajemen User', "Reset password user: {$user->name}");

        $this->confirmingUserReset = false;
        session()->flash('message', "Password untuk {$user->name} berhasil direset.");
    }

    public function exportFiltered()
    {
        if ($this->activeTab !== 'camaba') {
             session()->flash('error', 'Fitur export saat ini hanya untuk data Camaba.');
             return;
        }

        $query = User::where('role', 'camaba')
            ->when($this->filterStatus === 'sudah_isi', function($q) {
                $q->has('pendaftar');
            })
            ->when($this->filterStatus === 'belum_isi', function($q) {
                $q->doesntHave('pendaftar');
            });

        $statusName = $this->filterStatus ? $this->filterStatus : 'semua';
        $fileName = 'data_camaba_' . $statusName . '_' . date('Y-m-d') . '.xlsx';
        
        Logger::record('EXPORT', 'Manajemen User', "Export data camaba filter: {$statusName}");

        return Excel::download(new UserFilterExport($query), $fileName);
    }
    
    public function closeModal()
    {
        $this->isEditModalOpen = false;
        $this->isCreateModalOpen = false;
        $this->confirmingUserReset = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->nomor_hp = '';
        $this->role = '';
        $this->password = '';
        $this->userIdBeingEdited = null;
    }
}