<?php

namespace App\Livewire\Logs;

use App\Models\Location;
use App\Models\Report;
use App\Models\Warning;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ActionHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $activeTab = 'reports';

    public bool $showDetailModal = false;
    public ?Report $selectedReport = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
    }

    public function showDetail(int $reportId)
    {
        $this->selectedReport = Report::with([
            'reporter', 
            'location', 
            'reviewer', 
            'points'
        ])->find($reportId);

        if ($this->selectedReport) {
            $this->showDetailModal = true;
            
            $this->dispatch('report-detail-opened'); 
        }
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedReport = null;
    }

    private function getPjAreaLocationIds(): array
    {
        $assignedIds = Auth::user()->assignedLocations()->select('locations.id')->pluck('id')->all();
        if (empty($assignedIds)) {
            return [];
        }

        $allIds = $assignedIds;
        $currentIds = $assignedIds;

        while (! empty($currentIds)) {
            $childIds = Location::whereIn('parent_id', $currentIds)->pluck('id')->all();
            $currentIds = array_diff($childIds, $allIds);
            if (empty($currentIds)) {
                break;
            }

            $allIds = array_merge($allIds, $currentIds);
        }

        return array_values($allIds);
    }

    public function render()
    {
        $reportQuery = Report::with(['reviewer', 'reporter', 'location'])
            ->whereIn('status', ['approved', 'rejected'])
            ->orderByDesc('reviewed_at');

        $warningQuery = Warning::with(['creator', 'report'])
            ->orderByDesc('created_at');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // FILTER BERDASARKAN TAG AREA TUGAS
        if ($user && !$user->isAdmin()) {
            $locationIds = $user->getManagedLocationIds();

            // Filter untuk Tab Laporan
            $reportQuery->whereIn('location_id', $locationIds);

            // Filter untuk Tab Peringatan
            $warningQuery->whereHas('report', function ($query) use ($locationIds) {
                $query->whereIn('location_id', $locationIds);
            });
        }

        // ... (Bagian filter search tetap sama) ...

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $reportQuery->where(function ($query) use ($searchTerm) {
                $query->where('deskripsi', 'ILIKE', $searchTerm) // Sesuaikan kolom pencarian
                    ->orWhereHas('reviewer', fn ($q) => $q->where('full_name', 'ILIKE', $searchTerm))
                    ->orWhereHas('reporter', fn ($q) => $q->where('full_name', 'ILIKE', $searchTerm));
            });

            $warningQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'ILIKE', $searchTerm)
                    ->orWhereHas('creator', fn ($q) => $q->where('full_name', 'ILIKE', $searchTerm))
                    ->orWhereHas('report', fn ($q) => $q->where('lokasi', 'ILIKE', $searchTerm));
            });
        }

        $reportLogs = $reportQuery->paginate(10, ['*'], 'reportsPage');
        $warningLogs = $warningQuery->paginate(10, ['*'], 'warningsPage');

        return view('livewire.logs.action-history', compact('reportLogs', 'warningLogs'))
            ->title('Riwayat Log');
    }
}
