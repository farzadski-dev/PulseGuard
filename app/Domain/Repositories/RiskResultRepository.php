<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\RiskResult;

interface RiskResultRepository
{
    public function save(RiskResult $riskResult): void;
}
