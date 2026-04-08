<?php

namespace App\Livewire\Rewards;

use App\Models\RewardPeriod;
use Livewire\Component;

class RewardPeriods extends Component
{
    public bool $showForm = false;
    public string $name = '';
    public string $startDate = '';
    public string $endDate = '';

    public function openForm(): void { $this->showForm = true; }
    public function closeForm(): void { $this->showForm = false; $this->reset(['name','startDate','endDate']); }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
        ]);

        RewardPeriod::create([
            'name' => $this->name,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->closeForm();
        session()->flash('success', 'Periode reward berhasil dibuat.');
    }

    public function closePeriod(int $id)
    {
        RewardPeriod::where('id', $id)->update(['status' => 'closed']);
        session()->flash('success', 'Periode reward ditutup.');
    }

    public function render()
    {
        $periods = RewardPeriod::latest()->get();

        return view('livewire.rewards.reward-periods', compact('periods'))
            ->layout('layouts.app')
            ->title('Periode Reward');
    }
}
