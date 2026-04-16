<?php

namespace App\Livewire\Users;

use App\Models\Location;
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
    public bool $createShowNameOnLanding = true;
    public string $createUserType = 'umum';
    public string $createLocationCampusId = '';
    public string $createLocationBranchId = '';
    public string $createLocationBranchType = '';
    public string $createLocationFloorId = '';
    public string $createLocationSpaceId = '';

    // Unified form state
    public bool $showForm = false;
    public string $formMode = 'create';
    public bool $showEdit = false;
    public ?int $editId = null;

    // Edit form
    public string $editName = '';
    public string $editEmail = '';
    public string $editPassword = '';
    public string $editRole = '';
    public bool $editShowNameOnLanding = true;
    public string $editUserType = '';
    public string $editLocationCampusId = '';
    public string $editLocationBranchId = '';
    public string $editLocationBranchType = '';
    public string $editLocationFloorId = '';
    public string $editLocationSpaceId = '';

    public function updatingSearch(): void { $this->resetPage(); }

    // ─── Create User ─────────────────────────

    public function openCreate(): void
    {
        $this->formMode = 'create';
        $this->showForm = true;
        $this->showCreate = true;
        $this->showEdit = false;
        $this->resetCreate();
    }

    public function resetCreate(): void
    {
        $this->createName = '';
        $this->createEmail = '';
        $this->createPassword = '';
        $this->createRole = 'reporter';
        $this->createShowNameOnLanding = true;
        $this->createUserType = 'umum';
        $this->createLocationCampusId = '';
        $this->createLocationBranchId = '';
        $this->createLocationBranchType = '';
        $this->createLocationFloorId = '';
        $this->createLocationSpaceId = '';
    }

    public function resetEdit(): void
    {
        $this->editId = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editPassword = '';
        $this->editRole = '';
        $this->editShowNameOnLanding = true;
        $this->editUserType = '';
        $this->editLocationCampusId = '';
        $this->editLocationBranchId = '';
        $this->editLocationBranchType = '';
        $this->editLocationFloorId = '';
        $this->editLocationSpaceId = '';
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->showCreate = false;
        $this->showEdit = false;
        $this->resetCreate();
        $this->resetEdit();
    }

    public function saveCreate(): void
    {
        $rules = [
            'createName' => 'required|string|max:100',
            'createEmail' => 'required|email|max:100|unique:users,email',
            'createPassword' => 'required|string|min:8',
            'createRole' => 'required|in:reporter,admin,pimpinan,spmi,pj_area',
            'createUserType' => 'required|in:mahasiswa,dosen,umum',
        ];

        if (in_array($this->createRole, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $rules['createLocationCampusId'] = 'nullable|exists:locations,id';
            $rules['createLocationBranchId'] = 'nullable|exists:locations,id';
        }

        if ($this->createLocationBranchType === 'gedung') {
            $rules['createLocationFloorId'] = 'nullable|exists:locations,id';
            $rules['createLocationSpaceId'] = 'nullable|exists:locations,id';
        }

        $this->validate($rules);

        $user = User::create([
            'full_name' => $this->createName,
            'email' => $this->createEmail,
            'password' => Hash::make($this->createPassword),
            'role' => $this->createRole,
            'show_name_on_landing' => $this->createShowNameOnLanding,
            'user_type' => $this->createUserType,
            'gedung_id' => null,
        ]);

        if (in_array($this->createRole, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $locationId = $this->resolveCreateLocationId();
            $user->assignedLocations()->sync($locationId ? [$locationId] : []);
        }

        $this->closeForm();
        session()->flash('success', 'User berhasil ditambahkan.');
    }

    public function saveForm(): void
    {
        if ($this->formMode === 'edit') {
            $this->saveEdit();
            return;
        }

        $this->saveCreate();
    }

    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();

        if ($this->formMode === 'edit' && $this->editId === $id) {
            $this->closeForm();
        }

        session()->flash('success', 'User berhasil dihapus.');
    }

    public function updatedCreateLocationCampusId(): void
    {
        $this->createLocationBranchId = '';
        $this->createLocationBranchType = '';
        $this->createLocationFloorId = '';
        $this->createLocationSpaceId = '';
    }

    public function updatedCreateLocationBranchId(): void
    {
        if (! is_numeric($this->createLocationBranchId)) {
            $this->createLocationBranchType = '';
        } else {
            $this->createLocationBranchType = Location::find((int) $this->createLocationBranchId)?->type ?? '';
        }

        $this->createLocationFloorId = '';
        $this->createLocationSpaceId = '';
    }

    public function updatedCreateLocationFloorId(): void
    {
        $this->createLocationSpaceId = '';
    }

    public function updatedCreateRole(): void
    {
        $this->createLocationCampusId = '';
        $this->createLocationBranchId = '';
        $this->createLocationBranchType = '';
        $this->createLocationFloorId = '';
        $this->createLocationSpaceId = '';
    }

    public function updatedEditLocationCampusId(): void
    {
        $this->editLocationBranchId = '';
        $this->editLocationBranchType = '';
        $this->editLocationFloorId = '';
        $this->editLocationSpaceId = '';
    }

    public function updatedEditLocationBranchId(): void
    {
        if (! is_numeric($this->editLocationBranchId)) {
            $this->editLocationBranchType = '';
        } else {
            $this->editLocationBranchType = Location::find((int) $this->editLocationBranchId)?->type ?? '';
        }

        $this->editLocationFloorId = '';
        $this->editLocationSpaceId = '';
    }

    public function updatedEditRole(): void
    {
        $this->editLocationCampusId = '';
        $this->editLocationBranchId = '';
        $this->editLocationBranchType = '';
        $this->editLocationFloorId = '';
        $this->editLocationSpaceId = '';
    }

    private function resolveCreateLocationId(): ?int
    {
        if ($this->createLocationBranchType === 'gedung' && $this->createLocationSpaceId) {
            return (int) $this->createLocationSpaceId;
        }

        if ($this->createLocationBranchId) {
            return (int) $this->createLocationBranchId;
        }

        return $this->createLocationCampusId ? (int) $this->createLocationCampusId : null;
    }

    private function resolveEditLocationId(): ?int
    {
        if ($this->editLocationBranchType === 'gedung' && $this->editLocationSpaceId) {
            return (int) $this->editLocationSpaceId;
        }

        if ($this->editLocationBranchId) {
            return (int) $this->editLocationBranchId;
        }

        return $this->editLocationCampusId ? (int) $this->editLocationCampusId : null;
    }

    public function getCreateLocationCampusesProperty()
    {
        return Location::type('campus')->active()->orderBy('name')->get();
    }

    public function getCreateLocationBranchesProperty()
    {
        if (! $this->createLocationCampusId) {
            return collect();
        }

        return Location::where('parent_id', $this->createLocationCampusId)
            ->whereIn('type', ['gedung', 'infrastruktur'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getCreateLocationFloorsProperty()
    {
        if (! $this->createLocationBranchId || $this->createLocationBranchType !== 'gedung') {
            return collect();
        }

        return Location::where('parent_id', $this->createLocationBranchId)
            ->where('type', 'lantai')
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getCreateLocationSpacesProperty()
    {
        if (! $this->createLocationFloorId) {
            return collect();
        }

        if ($this->createLocationFloorId === 'all') {
            $floorIds = Location::where('parent_id', $this->createLocationBranchId)
                ->where('type', 'lantai')
                ->pluck('id');

            return Location::whereIn('parent_id', $floorIds)
                ->whereIn('type', ['ruangan', 'area'])
                ->active()
                ->orderBy('name')
                ->get();
        }

        return Location::where('parent_id', $this->createLocationFloorId)
            ->whereIn('type', ['ruangan', 'area'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getEditLocationCampusesProperty()
    {
        return Location::type('campus')->active()->orderBy('name')->get();
    }

    public function getEditLocationBranchesProperty()
    {
        if (! $this->editLocationCampusId) {
            return collect();
        }

        return Location::where('parent_id', $this->editLocationCampusId)
            ->whereIn('type', ['gedung', 'infrastruktur'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getEditLocationFloorsProperty()
    {
        if (! $this->editLocationBranchId || $this->editLocationBranchType !== 'gedung') {
            return collect();
        }

        return Location::where('parent_id', $this->editLocationBranchId)
            ->where('type', 'lantai')
            ->active()
            ->orderBy('name')
            ->get();
    }

    public function getEditLocationSpacesProperty()
    {
        if (! $this->editLocationFloorId) {
            return collect();
        }

        if ($this->editLocationFloorId === 'all') {
            $floorIds = Location::where('parent_id', $this->editLocationBranchId)
                ->where('type', 'lantai')
                ->pluck('id');

            return Location::whereIn('parent_id', $floorIds)
                ->whereIn('type', ['ruangan', 'area'])
                ->active()
                ->orderBy('name')
                ->get();
        }

        return Location::where('parent_id', $this->editLocationFloorId)
            ->whereIn('type', ['ruangan', 'area'])
            ->active()
            ->orderBy('name')
            ->get();
    }

    // ─── Edit User ──────────────────────────

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->formMode = 'edit';
        $this->showForm = true;
        $this->showEdit = true;
        $this->showCreate = false;
        $this->resetEdit();
        $this->editId = $user->id;
        $this->editName = $user->full_name;
        $this->editEmail = $user->email;
        $this->editRole = $user->role;
        $this->editShowNameOnLanding = $user->show_name_on_landing;
        $this->editUserType = $user->user_type;
        $this->editLocationCampusId = '';
        $this->editLocationBranchId = '';
        $this->editLocationBranchType = '';
        $this->editLocationFloorId = '';
        $this->editLocationSpaceId = '';

        if (in_array($user->role, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $firstLocation = $user->assignedLocations()->with('parent.parent.parent')->first();
            if ($firstLocation) {
                if (in_array($firstLocation->type, ['ruangan', 'area'], true)) {
                    $branch = $firstLocation->parent;
                    $this->editLocationSpaceId = (string) $firstLocation->id;
                    $this->editLocationFloorId = (string) ($branch->id ?? '');
                    $this->editLocationBranchId = (string) ($branch->parent->id ?? '');
                    $this->editLocationCampusId = (string) ($branch->parent->parent->id ?? '');
                    $this->editLocationBranchType = $branch?->type ?? '';
                } elseif ($firstLocation->type === 'lantai') {
                    $branch = $firstLocation->parent;
                    $this->editLocationFloorId = (string) $firstLocation->id;
                    $this->editLocationBranchId = (string) ($branch->id ?? '');
                    $this->editLocationCampusId = (string) ($branch->parent->id ?? '');
                    $this->editLocationBranchType = $branch?->type ?? '';
                } elseif (in_array($firstLocation->type, ['gedung', 'infrastruktur'], true)) {
                    $this->editLocationBranchId = (string) $firstLocation->id;
                    $this->editLocationBranchType = $firstLocation->type;
                    $this->editLocationCampusId = (string) ($firstLocation->parent->id ?? '');
                } elseif ($firstLocation->type === 'campus') {
                    $this->editLocationCampusId = (string) $firstLocation->id;
                }
            }
        }

        $this->showEdit = true;
    }

    public function closeEdit(): void { $this->showEdit = false; }

    public function saveEdit()
    {
        $rules = [
            'editName' => 'required|string|max:100',
            'editEmail' => 'required|email|max:100|unique:users,email,' . $this->editId,
            'editPassword' => 'nullable|string|min:8',
            'editRole' => 'required|in:reporter,admin,pimpinan,spmi,pj_area',
        ];

        if (in_array($this->editRole, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $rules['editLocationCampusId'] = 'nullable|exists:locations,id';
            $rules['editLocationBranchId'] = 'nullable|exists:locations,id';
        }

        if ($this->editLocationBranchType === 'gedung') {
            $rules['editLocationFloorId'] = 'nullable|exists:locations,id';
            $rules['editLocationSpaceId'] = 'nullable|exists:locations,id';
        }

        $this->validate($rules);

        $data = [
            'full_name' => $this->editName,
            'email' => $this->editEmail,
            'role' => $this->editRole,
            'show_name_on_landing' => $this->editShowNameOnLanding,
        ];

        if ($this->editPassword) {
            $data['password'] = Hash::make($this->editPassword);
        }

        $data['gedung_id'] = null;

        User::where('id', $this->editId)->update($data);

        $user = User::findOrFail($this->editId);
        if (in_array($this->editRole, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $locationId = $this->resolveEditLocationId();
            $user->assignedLocations()->sync($locationId ? [$locationId] : []);
        } else {
            $user->assignedLocations()->detach();
        }

        $this->closeForm();
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

        $users = $query->with(['assignedLocations', 'gedungRelation'])->orderBy('full_name')->paginate(15);
        $gedungs = Gedung::active()->orderBy('nama')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('livewire.users.user-management', compact('users', 'gedungs', 'locations'))
            ->layout('layouts.app')
            ->title('Kelola User');
    }
}

