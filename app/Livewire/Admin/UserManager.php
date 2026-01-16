<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserFilterExport; // Pastikan buat export class nanti

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = ''; // Filter: 'sudah_isi', 'belum_isi', atau '' (semua)

    // Untuk fitur Reset Password
    public $confirmingUserReset = false;
    public $userToResetId;
    public $newPassword;

    // Reset pagination saat search/filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('role', 'camaba')
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('nomor_hp', 'like', '%' . $this->search . '%');
            })
            // Filter berdasarkan status pendaftaran
            ->when($this->filterStatus === 'sudah_isi', function ($q) {
                $q->has('pendaftar');
            })
            ->when($this->filterStatus === 'belum_isi', function ($q) {
                $q->doesntHave('pendaftar');
            })
            ->with('pendaftar')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users
        ]);
    }

    // --- EXPORT FUNCTION ---
    public function exportFiltered()
    {
        // Gunakan logic query yang sama untuk export
        $query = User::where('role', 'camaba')
            ->when($this->filterStatus === 'sudah_isi', function ($q) {
                $q->has('pendaftar');
            })
            ->when($this->filterStatus === 'belum_isi', function ($q) {
                $q->doesntHave('pendaftar');
            });

        // Nama file dinamis
        $statusName = $this->filterStatus ? $this->filterStatus : 'semua';
        $fileName = 'data_camaba_' . $statusName . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new UserFilterExport($query), $fileName);
    }

    // --- RESET PASSWORD FUNCTION ---
    public function confirmReset($id)
    {
        $this->userToResetId = $id;
        $this->newPassword = '';
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
        if ($user) {
            $user->delete();
            session()->flash('message', 'Akun pengguna berhasil dihapus permanen.');
        }
    }

    public function closeModal()
    {
        $this->confirmingUserReset = false;
    }


    // Tambahkan properti baru
    public $isEditModalOpen = false;
    public $editUserId, $editName, $editEmail, $editPhone;

    // Method Edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editUserId = $id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editPhone = $user->nomor_hp;
        $this->isEditModalOpen = true;
    }

    // Method Update
    public function updateUser()
    {
        $this->validate([
            'editName' => 'required',
            'editEmail' => 'required|email|unique:users,email,' . $this->editUserId,
            'editPhone' => 'required|numeric',
        ]);

        $user = User::find($this->editUserId);
        $user->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
            'nomor_hp' => $this->editPhone,
        ]);

        $this->isEditModalOpen = false;
        session()->flash('message', 'Data pengguna berhasil diperbarui.');
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }
}
