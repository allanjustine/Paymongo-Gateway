<?php

namespace App\Livewire;

use App\Services\PaymongoService;
use Livewire\Component;

class Success extends Component
{
    public function mount(PaymongoService $paymongoService)
    {
        [$payment_order, $source, $status] = $paymongoService->payMongoData();

        if ($status === 'pending') {
            return redirect(data_get($source, 'attributes.redirect.checkout_url'));
        }

        if ($payment_order->status !== 'chargeable' || $status !== 'chargeable') {
            abort(404);
        }

        if ($status === 'chargeable') {
            $paymongoService->chargeSource($payment_order->source_id, data_get($source, 'attributes.amount'));
        }
    }
    public function render()
    {
        return view('livewire.success');
    }
}
