<?php

namespace App\Livewire\Rewards;

use App\Models\RewardPeriod;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]


class RewardPeriods extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $startDate = '';
    public string $endDate = '';

    public function openForm(): void { 
        $this->resetForm();
        $this->showForm = true; 
    }

    public function edit(int $id): void
    {
        $period = \App\Models\RewardPeriod::findOrFail($id);
        $this->editingId = $id;
        $this->name = $period->name;
        $this->startDate = $period->start_date->format('Y-m-d');
        $this->endDate = $period->end_date->format('Y-m-d');
        $this->showForm = true;
    }

    public function closeForm(): void { 
        $this->resetForm(); 
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->reset(['name','startDate','endDate']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
        ]);

        if ($this->editingId) {
            $period = RewardPeriod::findOrFail($this->editingId);
            $period->update([
                'name' => $this->name,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);
            session()->flash('success', 'Periode reward berhasil diupdate.');
        } else {
            RewardPeriod::create([
                'name' => $this->name,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);
            session()->flash('success', 'Periode reward berhasil dibuat.');
        }

        $this->closeForm();
    }

    public function delete(int $id)
    {
        $period = RewardPeriod::findOrFail($id);
        $period->delete();
        session()->flash('success', 'Periode reward berhasil dihapus.');
    }

    public function toggle(int $id)
    {
        $period = RewardPeriod::findOrFail($id);
        $period->toggleActive(!$period->isActive());
        $status = $period->isActive() ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Periode reward berhasil {$status}.");
    }

    // Legacy closePeriod kept for compatibility, use toggle instead
    public function closePeriod(int $id)
    {
        $period = RewardPeriod::findOrFail($id);
        $period->toggleActive(false);
        session()->flash('success', 'Periode reward ditutup.');
    }

    public function render()
    {
        $periods = RewardPeriod::latest()->paginate(10);

        return view('livewire.rewards.reward-periods', compact('periods'))
            ->title('Periode Reward');
    }

}
