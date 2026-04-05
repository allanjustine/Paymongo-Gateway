<?php

namespace App\Livewire;

use App\Models\PaymentOrder;
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

        $payment_order = PaymentOrder::query()->create([
            'amount'         => $amountInCentavos / 100,
            'payment_method' => $this->type
        ]);

        $response = Http::withHeaders($paymongoService->headers())->post(config('app.paymongo_url') . "/sources", [
            'data'                       => [
                'attributes'             => [
                    'amount'             => $amountInCentavos,
                    'currency'           => 'PHP',
                    'type'               => $this->type,
                    'redirect'           => [
                        'success'        => route('payment.success'),
                        'failed'         => route('payment.failed'),
                    ],
                    'metadata'           => [
                        'payment_order_' => 'test',
                    ],
                ],
            ],
        ]);

        if ($response->failed()) {
            $errorDetail = $response->json('errors.0.detail') ?? $response->body();

            $this->addError('error', "Payment initiation failed:  {$errorDetail}");
        }

        $checkoutUrl = $response->json('data.attributes.redirect.checkout_url');

        $sourceId = $response->json('data.id');

        $payment_order->update([
            'source_id' => $sourceId,
            'status'    => $response->json('data.attributes.status')
        ]);

        $this->reset();

        $this->redirect($checkoutUrl);

        return;
    }

    public function render()
    {
        return view('livewire.payment');
    }
}
