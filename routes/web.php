<?php

use App\Http\Controllers\WebhookController;
use App\Livewire\Failed;
use App\Livewire\Payment;
use App\Livewire\Success;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'payment');
Route::get('/payment', Payment::class)->name('payment.form');
Route::get('/payment/success', Success::class)->name('payment.success');
Route::get('/payment/failed', Failed::class)->name('payment.failed');
Route::post('/payment/webhook', [WebhookController::class, 'store'])->name('payment.webhook')->withoutMiddleware(['web']);
