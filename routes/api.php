<?php

declare(strict_types=1);

use App\Interfaces\Http\Controllers\HealthController;
use App\Interfaces\Http\Controllers\TransactionEvaluationController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::middleware(['throttle:transactions'])
    ->post('/transactions/evaluate', [TransactionEvaluationController::class, 'evaluate']);
