<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('health', function () {
    $this->info('ok');
})->purpose('Health check');
