<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Services\PaymongoService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function store(Request $request, PaymongoService $paymongoService)
    {
        $payment_order = PaymentOrder::query()->latest()->first();

        $eventType = data_get($request->all(), 'data.attributes.type');

        if ($eventType === 'source.chargeable') {
            $source = data_get($request->all(), 'data.attributes.data');

            $payment_order->update([
                'status' => data_get($source, 'attributes.status'),
            ]);

            $paymongoService->chargeSource(data_get($source, 'id'), data_get($source, 'attributes.amount'));
        }

        return response()->json(['received' => true]);
    }
}
