<?php

use App\Http\Controllers\PaymongoController;
use App\Livewire\Payment;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'payment');

Route::get('/payment', Payment::class)->name('payment.form');
Route::get('/payment/success', [PaymongoController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymongoController::class, 'failed'])->name('payment.failed');
Route::post('/payment/webhook', [PaymongoController::class, 'webhook'])->name('payment.webhook')->withoutMiddleware(['web']);
