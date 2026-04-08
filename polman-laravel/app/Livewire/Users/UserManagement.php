<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';

    // Edit form
    public bool $showEdit = false;
    public ?int $editId = null;
    public string $editName = '';
    public string $editEmail = '';
    public string $editRole = '';
    public string $editUserType = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->editName = $user->full_name;
        $this->editEmail = $user->email;
        $this->editRole = $user->role;
        $this->editUserType = $user->user_type;
        $this->showEdit = true;
    }

    public function closeEdit(): void { $this->showEdit = false; }

    public function saveEdit()
    {
        $this->validate([
            'editName' => 'required|string|max:100',
            'editRole' => 'required|in:reporter,manager,admin',
        ]);

        User::where('id', $this->editId)->update([
            'full_name' => $this->editName,
            'role' => $this->editRole,
        ]);

        $this->closeEdit();
        session()->flash('success', 'User berhasil diperbarui.');
    }

    public function render()
    {
        $query = User::query();
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('full_name', 'ILIKE', "%{$this->search}%")
                  ->orWhere('email', 'ILIKE', "%{$this->search}%")
                  ->orWhere('nim', 'ILIKE', "%{$this->search}%")
                  ->orWhere('nomor_dosen', 'ILIKE', "%{$this->search}%");
            });
        }
        if ($this->filterRole) { $query->where('role', $this->filterRole); }

        $users = $query->orderBy('full_name')->paginate(15);

        return view('livewire.users.user-management', compact('users'))
            ->layout('layouts.app')
            ->title('Kelola User');
    }
}
