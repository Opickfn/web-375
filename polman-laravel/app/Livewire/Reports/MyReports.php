<?php

namespace App\Livewire\Reports;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyReports extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterKategori = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Report::with('location')->where('reporter_id', Auth::id());

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->filterStatus) {
            $query->byStatus($this->filterStatus);
        }

        if ($this->filterKategori) {
            $query->byKategori($this->filterKategori);
        }

        $reports = $query->latest()->paginate(10);

        return view('livewire.reports.my-reports', compact('reports'))
            ->layout('layouts.app')
            ->title('Laporan Saya');
    }
}
