<?php

namespace App\Livewire;

use App\Services\PaymongoService;
use Livewire\Component;

class Failed extends Component
{
    public function mount(PaymongoService $paymongoService)
    {
        $paymongoService->payMongoData();
    }

    public function render()
    {
        return view('livewire.failed');
    }
}
