<?php

namespace App\Services;

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
}
