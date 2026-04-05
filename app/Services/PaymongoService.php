<?php

namespace App\Services;

use App\Models\PaymentOrder;
use Illuminate\Support\Facades\Http;

class PaymongoService
{
    public function headers(): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode(config('services.paymongo.secret_key') . ':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function chargeSource(string $sourceId, int $amount): void
    {
        $payment_order = PaymentOrder::query()->where('source_id', $sourceId)->first();

        if ($payment_order->status === 'paid') {
            return;
        }

        $response = Http::withHeaders($this->headers())->post(config('app.paymongo_url') . "/payments", [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'currency' => 'PHP',
                    'source' => [
                        'id' => $sourceId,
                        'type' => 'source',
                    ],
                    'description' => 'Payment via GCash/GrabPay/Maya/Bank',
                ],
            ],
        ]);

        if ($response->successful()) {
            $payment_order->update([
                'status' => data_get($response->json('data'), 'attributes.status'),
            ]);
        }
    }

    public function payMongoData()
    {
        $payment_order = PaymentOrder::query()->latest()->first();

        $source = Http::withHeaders($this->headers())
            ->get(config('app.paymongo_url') . "/sources/{$payment_order->source_id}")
            ->json('data');

        $status = data_get($source, 'attributes.status');

        $payment_order->update([
            'status' => $status,
        ]);

        return [
            $payment_order,
            $source,
            $status
        ];
    }
}
