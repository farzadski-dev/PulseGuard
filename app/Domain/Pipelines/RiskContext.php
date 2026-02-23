<?php

declare(strict_types=1);

namespace App\Domain\Pipelines;

use App\Domain\Entities\RiskResult;
use App\Domain\Entities\Transaction;
use App\Domain\Rules\RuleResult;

final class RiskContext
{
    /** @var array<string, mixed> */
    private array $features = [];

    /** @var array<string, int> */
    private array $strategyScores = [];

    /** @var array<int, string> */
    private array $reasons = [];

    private ?RuleResult $ruleResult = null;
    private ?RiskResult $result = null;
    private int $finalScore = 0;
    private string $riskLevel = 'low';

    public function __construct(private Transaction $transaction)
    {
    }

    public function transaction(): Transaction
    {
        return $this->transaction;
    }

    public function addFeature(string $key, mixed $value): void
    {
        $this->features[$key] = $value;
    }

    public function feature(string $key): mixed
    {
        return $this->features[$key] ?? null;
    }

    /** @return array<string, mixed> */
    public function features(): array
    {
        return $this->features;
    }

    public function addStrategyScore(string $name, int $score): void
    {
        $this->strategyScores[$name] = $score;
    }

    /** @return array<string, int> */
    public function strategyScores(): array
    {
        return $this->strategyScores;
    }

    public function addReason(string $reason): void
    {
        if ($reason !== '') {
            $this->reasons[] = $reason;
        }
    }

    /** @return array<int, string> */
    public function reasons(): array
    {
        return $this->reasons;
    }

    public function setRuleResult(RuleResult $ruleResult): void
    {
        $this->ruleResult = $ruleResult;
    }

    public function ruleResult(): ?RuleResult
    {
        return $this->ruleResult;
    }

    public function setFinalScore(int $score): void
    {
        $this->finalScore = $score;
    }

    public function finalScore(): int
    {
        return $this->finalScore;
    }

    public function setRiskLevel(string $riskLevel): void
    {
        $this->riskLevel = $riskLevel;
    }

    public function riskLevel(): string
    {
        return $this->riskLevel;
    }

    public function setResult(RiskResult $result): void
    {
        $this->result = $result;
    }

    public function result(): ?RiskResult
    {
        return $this->result;
    }
}
