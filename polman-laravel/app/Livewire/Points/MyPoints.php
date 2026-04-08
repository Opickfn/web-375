<?php

namespace App\Livewire\Points;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyPoints extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();
        $totalPoints = $user->totalPoints();
        $points = $user->points()->with('report')->latest()->paginate(15);

        return view('livewire.points.my-points', compact('totalPoints', 'points'))
            ->layout('layouts.app')
            ->title('Poin Saya');
    }
}
