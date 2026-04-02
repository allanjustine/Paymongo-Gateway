<?php

namespace App\Livewire;

use App\Services\PaymongoService;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Validate;

class Payment extends Component
{
    #[Validate(['required', 'numeric', 'min:1'])]
    public $amount;
    #[Validate(['required', 'in:gcash,grab_pay,maya,qrph,bpi,unionbank,metrobank'])]
    public $type = 'gcash';

    public function createSource(PaymongoService $paymongoService)
    {
        $this->validate();

        $amountInCentavos = (int) ($this->amount * 100);

        $response = Http::withHeaders($paymongoService->headers())->post(config('app.paymongo_url') . "/sources", [
            'data' => [
                'attributes' => [
                    'amount' => $amountInCentavos,
                    'currency' => 'PHP',
                    'type' => $this->type,
                    'redirect' => [
                        'success' => url('/payment/success'),
                        'failed' => url('/payment/failed'),
                    ],
                    'metadata' => [
                        'items' => 'items is here!',
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            $errorDetail = $response->json('errors.0.detail') ?? $response->body();

            $this->addError('error', "Payment initiation failed:  {$errorDetail}");

            return;
        }

        $checkoutUrl = $response->json('data.attributes.redirect.checkout_url');
        $sourceId = $response->json('data.id');

        session(['paymongo_source_id' => $sourceId]);

        return $this->redirect($checkoutUrl);
    }

    public function render()
    {
        return view('livewire.payment');
    }
}
