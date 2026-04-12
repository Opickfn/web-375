<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Gedung;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';

    // Create form
    public bool $showCreate = false;
    public string $createName = '';
    public string $createEmail = '';
    public string $createPassword = '';
    public string $createRole = 'reporter';
    public string $createUserType = 'umum';
    public string $createGedungId = '';

    // Edit form
    public bool $showEdit = false;
    public ?int $editId = null;
    public string $editName = '';
    public string $editEmail = '';
    public string $editRole = '';
    public string $editUserType = '';
    public string $editGedungId = '';

    public function updatingSearch(): void { $this->resetPage(); }

    // ─── Create User ─────────────────────────

    public function openCreate(): void
    {
        $this->resetCreate();
        $this->showCreate = true;
    }

    public function resetCreate(): void
    {
        $this->createName = '';
        $this->createEmail = '';
        $this->createPassword = '';
        $this->createRole = 'reporter';
        $this->createUserType = 'umum';
        $this->createGedungId = '';
    }

    public function closeCreate(): void
    {
        $this->showCreate = false;
        $this->resetCreate();
    }

    public function saveCreate(): void
    {
        $rules = [
            'createName' => 'required|string|max:100',
            'createEmail' => 'required|email|max:100|unique:users,email',
            'createPassword' => 'required|string|min:8',
            'createRole' => 'required|in:reporter,manager,admin',
            'createUserType' => 'required|in:mahasiswa,dosen,umum',
        ];

        if ($this->createRole === 'manager') {
            $rules['createGedungId'] = 'required|exists:gedungs,id';
        }

        $this->validate($rules);

        User::create([
            'full_name' => $this->createName,
            'email' => $this->createEmail,
            'password' => Hash::make($this->createPassword),
            'role' => $this->createRole,
            'user_type' => $this->createUserType,
            'gedung_id' => $this->createRole === 'manager' ? $this->createGedungId : null,
        ]);

        $this->closeCreate();
        session()->flash('success', 'User berhasil ditambahkan.');
    }

    // ─── Edit User ──────────────────────────

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->editName = $user->full_name;
        $this->editEmail = $user->email;
        $this->editRole = $user->role;
        $this->editUserType = $user->user_type;
        $this->editGedungId = (string) ($user->gedung_id ?? '');
        $this->showEdit = true;
    }

    public function closeEdit(): void { $this->showEdit = false; }

    public function saveEdit()
    {
        $rules = [
            'editName' => 'required|string|max:100',
            'editRole' => 'required|in:reporter,manager,admin',
        ];

        if ($this->editRole === 'manager') {
            $rules['editGedungId'] = 'required|exists:gedungs,id';
        }

        $this->validate($rules);

        $data = [
            'full_name' => $this->editName,
            'role' => $this->editRole,
        ];

        if ($this->editRole === 'manager') {
            $data['gedung_id'] = $this->editGedungId;
        } else {
            $data['gedung_id'] = null;
        }

        User::where('id', $this->editId)->update($data);

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
        $gedungs = Gedung::active()->orderBy('nama')->get();

        return view('livewire.users.user-management', compact('users', 'gedungs'))
            ->layout('layouts.app')
            ->title('Kelola User');
    }
}

