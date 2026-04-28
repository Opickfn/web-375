<?php

namespace App\Livewire\Users;

use App\Models\Location;
use App\Models\User;
use App\Models\Gedung;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;


#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public string $sortBy = 'full_name';
    public string $sortDir = 'asc';
    public int $perPage = 15;

    // Unified form state
    public bool $showForm = false;
    public string $formMode = 'create';
    public ?int $editId = null;

    // Shared form fields
    public string $createName = '';
    public string $createEmail = '';
    public string $createPassword = '';
    public string $createRole = 'reporter';
    public bool $createShowNameOnLanding = true;
    public string $createUserType = 'umum';
    public array $createSelectedLocations = [];  // Multi-select location IDs
    // Tambahkan properti ini di bagian atas class
    public array $expandedBranches = []; // Level Gedung/Infrastruktur
    public array $expandedFloors = [];   // Level Lantai

    public string $editName = '';
    public string $editEmail = '';
    public string $editPassword = '';
    public string $editRole = '';
    public bool $editShowNameOnLanding = true;
    public string $editUserType = '';
    public array $editSelectedLocations = [];    // Multi-select location IDs

    // Location tree expand state (stored as JSON string for Livewire compatibility)
    public array $expandedCampuses = [];

    public function updatingSearch(): void { $this->resetPage(); }

    // ─── Create User ─────────────────────────

    public function openCreate(): void
    {
        $this->formMode = 'create';
        $this->showForm = true;
        $this->resetCreate();
        // Expand all campuses by default when opening form
        $this->expandedCampuses = Location::type('campus')->active()->pluck('id')->toArray();
    }

    public function resetCreate(): void
    {
        $this->createName = '';
        $this->createEmail = '';
        $this->createPassword = '';
        $this->createRole = 'reporter';
        $this->createShowNameOnLanding = true;
        $this->createUserType = 'umum';
        $this->createSelectedLocations = [];
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
        $this->editSelectedLocations = [];
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->formMode = 'create';
        $this->expandedCampuses = [];
        $this->expandedBranches = []; 
        $this->expandedFloors = [];   
        $this->resetCreate();
        $this->resetEdit();
    }

    public function toggleCampus(int $id): void
    {
        if (in_array($id, $this->expandedCampuses)) {
            $this->expandedCampuses = array_values(array_filter($this->expandedCampuses, fn ($c) => $c !== $id));
        } else {
            $this->expandedCampuses[] = $id;
        }
    }

    public function toggleBranch(int $id): void
    {
        if (in_array($id, $this->expandedBranches)) {
            $this->expandedBranches = array_values(array_filter($this->expandedBranches, fn ($c) => $c !== $id));
        } else {
            $this->expandedBranches[] = $id;
        }
    }

    public function toggleFloor(int $id): void
    {
        if (in_array($id, $this->expandedFloors)) {
            $this->expandedFloors = array_values(array_filter($this->expandedFloors, fn ($c) => $c !== $id));
        } else {
            $this->expandedFloors[] = $id;
        }
    }

    public function saveCreate(): void
    {
        $rules = [
            'createName'     => 'required|string|max:100',
            'createEmail'    => 'required|email|max:100|unique:users,email',
            'createPassword' => 'required|string|min:8',
            'createRole'     => 'required|in:reporter,admin,pimpinan,spmi,pj_area',
            'createUserType' => 'required|in:mahasiswa,dosen,umum',
        ];

        $this->validate($rules);

        $user = User::create([
            'full_name'            => $this->createName,
            'email'                => $this->createEmail,
            'password'             => Hash::make($this->createPassword),
            'role'                 => $this->createRole,
            'show_name_on_landing' => $this->createShowNameOnLanding,
            'user_type'            => $this->createUserType,
            'gedung_id'            => null,
        ]);

        if (in_array($this->createRole, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $validIds = $this->getValidLocationIds($this->createSelectedLocations);
            $user->assignedLocations()->sync($validIds);
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

    // ─── Edit User ──────────────────────────

    public function openEdit(int $id): void
    {
        $user = User::with('assignedLocations')->findOrFail($id);

        $this->formMode = 'edit';
        $this->showForm = true;
        $this->resetEdit();

        $this->editId    = $user->id;
        $this->editName  = $user->full_name;
        $this->editEmail = $user->email;
        $this->editRole  = $user->role;
        $this->editShowNameOnLanding = $user->show_name_on_landing ?? true;
        $this->editUserType = $user->user_type;

        // Load existing assigned locations as array of IDs
        $this->editSelectedLocations = $user->assignedLocations->pluck('id')->map(fn ($v) => (string) $v)->toArray();

        // Expand all campuses by default so user can see current selections
        $this->expandedCampuses = Location::type('campus')->active()->pluck('id')->toArray();
    }

    public function saveEdit(): void
    {
        $rules = [
            'editName'  => 'required|string|max:100',
            'editEmail' => 'required|email|max:100|unique:users,email,' . $this->editId,
            'editRole'  => 'required|in:reporter,admin,pimpinan,spmi,pj_area',
        ];

        if ($this->editPassword) {
            $rules['editPassword'] = 'string|min:8';
        }

        $this->validate($rules);

        $data = [
            'full_name'            => $this->editName,
            'email'                => $this->editEmail,
            'role'                 => $this->editRole,
            'show_name_on_landing' => $this->editShowNameOnLanding,
            'gedung_id'            => null,
        ];

        if ($this->editPassword) {
            $data['password'] = Hash::make($this->editPassword);
        }

        User::where('id', $this->editId)->update($data);

        $user = User::findOrFail($this->editId);
        if (in_array($this->editRole, ['pj_area', 'pimpinan', 'spmi'], true)) {
            $validIds = $this->getValidLocationIds($this->editSelectedLocations);
            $user->assignedLocations()->sync($validIds);
        } else {
            $user->assignedLocations()->detach();
        }

        $this->closeForm();
        session()->flash('success', 'User berhasil diperbarui.');
    }

    // ─── Location Helpers ────────────────────

    /**
     * Return only valid integer location IDs that actually exist.
     */
    private function getValidLocationIds(array $ids): array
    {
        $intIds = array_filter(array_map('intval', $ids));
        if (empty($intIds)) {
            return [];
        }
        return Location::whereIn('id', $intIds)->pluck('id')->toArray();
    }

    /**
     * All branch-level locations (gedung + infrastruktur) grouped by campus,
     * used to build the checkbox tree in the form.
     */
    public function getLocationTreeProperty(): \Illuminate\Support\Collection
    {
        return Location::type('campus')
            ->with(['children' => function ($q) {
                $q->whereIn('type', ['gedung', 'infrastruktur'])
                ->active()
                ->orderBy('name')
                ->with(['children' => function ($q2) {
                    $q2->where('type', 'lantai')
                        ->active()
                        ->orderBy('name')
                        ->with(['children' => function ($q3) {
                            $q3->whereIn('type', ['ruangan', 'area'])
                            ->active()
                            ->orderBy('name');
                        }]);
                }]);
            }])
            ->active()
            ->orderBy('name')
            ->get();
    }

    // ─── Sort & Export ───────────────────────

    public function sort(string $column): void
    {
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy  = $column;
        $this->resetPage();
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $users = $this->buildUserQuery()->get();

        return response()->streamDownload(function () use ($users) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Nama', 'Email', 'Tipe', 'ID/NIM/NIDN', 'Role', 'Area', 'Tanggal Daftar']);
            foreach ($users as $u) {
                fputcsv($h, [
                    $u->full_name,
                    $u->email,
                    $u->user_type_label,
                    $u->nim ?? $u->nomor_dosen ?? '—',
                    $u->role,
                    $u->assigned_location_labels ?: '—',
                    $u->created_at->format('d/m/Y'),
                ]);
            }
            fclose($h);
        }, 'kelola-user-' . now()->format('Ymd') . '.csv');
    }

    private function buildUserQuery()
    {
        $q = User::query();

        if ($this->search) {
            $q->where(function ($s) {
                $s->where('full_name',    'ILIKE', "%{$this->search}%")
                  ->orWhere('email',       'ILIKE', "%{$this->search}%")
                  ->orWhere('nim',         'ILIKE', "%{$this->search}%")
                  ->orWhere('nomor_dosen', 'ILIKE', "%{$this->search}%");
            });
        }

        if ($this->filterRole) {
            $q->where('role', $this->filterRole);
        }

        $allowed = ['full_name', 'email', 'role', 'user_type', 'created_at'];
        $col = in_array($this->sortBy, $allowed) ? $this->sortBy : 'full_name';
        $q->orderBy($col, $this->sortDir);

        return $q->with(['assignedLocations', 'gedungRelation']);
    }

    public function render()
    {
        $users = $this->buildUserQuery()->paginate($this->perPage);

        return view('livewire.users.user-management', [
            'users'        => $users,
            'locationTree' => $this->locationTree,
        ])->title('Kelola User');
    }
}