<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\Pipelines\RiskContext;

interface Rule
{
    public function name(): string;

    public function applies(RiskContext $context): bool;

    public function adjustment(): int;

    public function reason(): string;
}
