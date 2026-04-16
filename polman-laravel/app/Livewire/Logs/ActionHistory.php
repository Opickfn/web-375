<?php

namespace App\Livewire\Logs;

use App\Models\Location;
use App\Models\Report;
use App\Models\Warning;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ActionHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $activeTab = 'reports';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
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

        $warningQuery = Warning::with(['creator', 'report.location'])
            ->orderByDesc('created_at');

        if (Auth::user()->isPjArea()) {
            $locationIds = $this->getPjAreaLocationIds();

            if (empty($locationIds)) {
                $reportQuery->whereRaw('0=1');
                $warningQuery->whereRaw('0=1');
            } else {
                $reportQuery->where(function ($query) use ($locationIds) {
                    $query->whereIn('location_id', $locationIds)
                          ->orWhereIn('gedung_id', $locationIds);
                });

                $warningQuery->whereHas('report', function ($query) use ($locationIds) {
                    $query->whereIn('location_id', $locationIds)
                          ->orWhereIn('gedung_id', $locationIds);
                });
            }
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';

            $reportQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'ILIKE', $searchTerm)
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
            ->layout('layouts.app')
            ->title('Log History');
    }
}
