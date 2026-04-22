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

    // Filter & State
    public $search = '';
    public $filterStatus = ''; 
    public $activeTab = 'camaba'; 

    // Modals Toggle
    public $isEditModalOpen = false;
    public $isCreateModalOpen = false;
    public $isDeleteModalOpen = false; 
    public $confirmingUserReset = false;

    // Interaction IDs
    public $userIdBeingEdited;
    public $userIdBeingDeleted; 
    public $userNameBeingDeleted;
    public $userToResetId;
    
    // Form Fields
    public $name, $email, $nomor_hp, $role, $password, $newPassword;

    // Powerful Features: Bulk Action & Verification
    public $selectedUsers = [];
    public $selectAll = false;

    // --- UX POWER UP: Custom Validation Messages ---
    protected $messages = [
        'name.required' => '⚠️ Nama lengkap wajib diisi!',
        'name.max' => '⚠️ Nama terlalu panjang (maksimal 255 karakter).',
        'email.required' => '⚠️ Alamat email tidak boleh kosong!',
        'email.email' => '⚠️ Penulisan email tidak valid (contoh: budi@gmail.com).',
        'email.unique' => '🚫 Email ini sudah terdaftar! Gunakan email lain.',
        'nomor_hp.required' => '⚠️ Nomor handphone/WA wajib diisi!',
        'nomor_hp.numeric' => '⚠️ Nomor handphone hanya boleh berisi angka!',
        'role.required' => '⚠️ Anda harus memilih Role (Hak Akses).',
        'role.in' => '⚠️ Pilihan Role tidak valid.',
        'password.required' => '⚠️ Password awal wajib diisi!',
        'password.min' => '⚠️ Password terlalu pendek (minimal 8 karakter).',
        'newPassword.required' => '⚠️ Password baru wajib diisi!',
        'newPassword.min' => '⚠️ Password baru harus memiliki minimal 8 karakter.',
    ];

    // Reset pagination on filter change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingActiveTab() { 
        $this->resetPage(); 
        $this->search = ''; 
        $this->selectedUsers = []; 
        $this->selectAll = false;
    }

    // Toggle Select All
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = User::query()
                ->where(function($q) {
                    if ($this->activeTab === 'camaba') {
                        $q->where('role', 'camaba');
                    } else {
                        $q->whereIn('role', ['admin', 'keuangan', 'akademik']);
                    }
                })
                ->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
                })
                ->pluck('id')
                ->map(fn($id) => (string)$id)
                ->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function render()
    {
        $query = User::query();

        if ($this->activeTab === 'camaba') {
            $query->where('role', 'camaba');
            if ($this->filterStatus === 'sudah_isi') {
                $query->has('pendaftar');
            } elseif ($this->filterStatus === 'belum_isi') {
                $query->doesntHave('pendaftar');
            } elseif ($this->filterStatus === 'belum_verifikasi') {
                $query->whereNull('email_verified_at');
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

    // --- VERIFICATION POWER TOOLS ---

    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->update(['email_verified_at' => now()]);
        
        Logger::record('UPDATE', 'Manajemen User', "Verifikasi email manual: {$user->name}");
        session()->flash('message', "Email {$user->name} berhasil diverifikasi.");
    }

    public function unverifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->update(['email_verified_at' => null]);
        
        Logger::record('UPDATE', 'Manajemen User', "Mencabut verifikasi email: {$user->name}");
        session()->flash('message', "Status verifikasi {$user->name} dibatalkan.");
    }

    public function verifySelected()
    {
        if (empty($this->selectedUsers)) return;

        User::whereIn('id', $this->selectedUsers)
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);

        Logger::record('UPDATE', 'Manajemen User', "Verifikasi masal " . count($this->selectedUsers) . " akun");
        
        $this->selectedUsers = [];
        $this->selectAll = false;
        session()->flash('message', "Semua akun terpilih berhasil diverifikasi.");
    }

    // --- STANDARD CRUD ---

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
            'role' => 'required|in:keuangan,akademik,camaba',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'nomor_hp' => $this->nomor_hp,
            'role' => $this->role,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(), // Admin create auto-verified
        ]);

        Logger::record('CREATE', 'Manajemen User', "Tambah akun: {$this->name} sebagai {$this->role}");
        $this->closeModal();
        session()->flash('message', 'Akun berhasil ditambahkan.');
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
            session()->flash('error', 'Tidak bisa menghapus akun sendiri!');
            return;
        }
        $user = User::findOrFail($id);
        $this->userIdBeingDeleted = $id;
        $this->userNameBeingDeleted = $user->name;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        $user = User::find($this->userIdBeingDeleted);
        if ($user) {
            $name = $user->name;
            $user->delete();
            Logger::record('DELETE', 'Manajemen User', "Hapus user: {$name}");
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
        $this->validate([
            'newPassword' => 'required|min:8'
        ]);
        
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