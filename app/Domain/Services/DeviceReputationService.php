<?php

declare(strict_types=1);

namespace App\Domain\Services;

interface DeviceReputationService
{
    /**
     * Returns a reputation score from 0-100 (100 = best).
     */
    public function getReputationScore(string $deviceId, string $ipAddress): int;
}
