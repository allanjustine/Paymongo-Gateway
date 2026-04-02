<?php

namespace App\Http\Controllers;

use App\Services\PaymongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymongoController extends Controller
{
    public function __construct(private PaymongoService $paymongo) {}

    public function success(Request $request)
    {
        $sourceId = $request->query('source_id') ?? session('paymongo_source_id');

        if ($sourceId) {
            $source = Http::withHeaders($this->paymongo->headers())
                ->get(config('app.paymongo_url') . "/sources/{$sourceId}")
                ->json('data');

            dd($source);

            if (data_get($source, 'attributes.status') === 'chargeable') {
                $this->chargeSource($sourceId, data_get($source, 'attributes.amount'));
            }
        }

        session()->forget('paymongo_source_id');

        return view('payment-result', ['status' => 'success', 'message' => 'Payment successful! Thank you.']);
    }

    public function failed()
    {
        return view('payment-result', ['status' => 'failed', 'message' => 'Payment failed or was cancelled.']);
    }

    public function webhook(Request $request)
    {
        $eventType = data_get($request->all(), 'data.attributes.type');

        if ($eventType === 'source.chargeable') {
            $source = data_get($request->all(), 'data.attributes.data');
            $this->chargeSource(data_get($source, 'id'), data_get($source, 'attributes.amount'));
        }

        return response()->json(['received' => true]);
    }

    private function chargeSource(string $sourceId, int $amount): void
    {
        $cacheKey = "paymongo_charged_{$sourceId}";

        if (cache()->has($cacheKey)) {
            return;
        }

        $response = Http::withHeaders($this->paymongo->headers())->post(config('app.paymongo_url') . "/payments", [
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
            cache()->put($cacheKey, true, now()->addHours(24));
        }
    }
}
