<?php

declare(strict_types=1);

namespace App\Domain\Strategies;

use App\Domain\Entities\Transaction;
use App\Domain\Pipelines\RiskContext;
use App\Domain\Services\DeviceReputationService;

final class DeviceReputationStrategy implements RiskScoringStrategy
{
    public function __construct(private DeviceReputationService $reputationService)
    {
    }

    public function name(): string
    {
        return 'device_reputation';
    }

    public function score(Transaction $transaction, RiskContext $context): int
    {
        $score = $context->feature('device_reputation_score');
        if ($score === null) {
            $score = $this->reputationService->getReputationScore(
                $transaction->device()->deviceId(),
                $transaction->device()->ipAddress()
            );
            $context->addFeature('device_reputation_score', $score);
        }

        $risk = (100 - $score) * 5; // 0-500
        return max(0, min(1000, $risk));
    }

    public function reasons(Transaction $transaction, RiskContext $context): array
    {
        $score = $context->feature('device_reputation_score');
        if ($score === null) {
            $score = $this->reputationService->getReputationScore(
                $transaction->device()->deviceId(),
                $transaction->device()->ipAddress()
            );
            $context->addFeature('device_reputation_score', $score);
        }

        if ($score < 40) {
            return ['Low device reputation score.'];
        }
        if ($score < 70) {
            return ['Moderate device reputation score.'];
        }
        return [];
    }
}
