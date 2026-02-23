<?php

declare(strict_types=1);

namespace App\Application\Commands;

interface CommandHandler
{
    public function handle(object $command): mixed;
}
