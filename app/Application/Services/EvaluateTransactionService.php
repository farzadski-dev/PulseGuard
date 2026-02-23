<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Commands\CommandHandler;
use App\Application\Commands\EvaluateTransactionCommand;
use App\Application\DTO\RiskEvaluationResponseDTO;
use App\Domain\Pipelines\RiskContext;
use App\Domain\Pipelines\RiskPipeline;
use App\Domain\Pipelines\Steps\AggregationStep;
use App\Domain\Pipelines\Steps\EnrichmentStep;
use App\Domain\Pipelines\Steps\RuleEngineStep;
use App\Domain\Pipelines\Steps\StrategyScoringStep;
use App\Domain\Repositories\IdempotencyRepository;
use App\Domain\Rules\RuleEngineFactory;
use App\Domain\Strategies\RiskScoringStrategy;

final class EvaluateTransactionService implements CommandHandler
{
    private const IDEMPOTENCY_TTL_SECONDS = 86400;

    /** @param iterable<RiskScoringStrategy> $strategies */
    public function __construct(
        private IdempotencyRepository $idempotencyRepository,
        private RuleEngineFactory $ruleEngineFactory,
        private iterable $strategies,
        private AsyncTransactionStore $asyncTransactionStore
    ) {
    }

    public function handle(object $command): RiskEvaluationResponseDTO
    {
        if (!$command instanceof EvaluateTransactionCommand) {
            throw new \InvalidArgumentException('Invalid command type.');
        }

        if ($command->idempotencyKey !== null) {
            $cached = $this->idempotencyRepository->get($command->idempotencyKey);
            if ($cached !== null) {
                return RiskEvaluationResponseDTO::fromArray($cached);
            }
        }

        $transaction = $command->transaction->toDomain();
        $ruleEngine = $this->ruleEngineFactory->forTenant($transaction->tenantId());

        $pipeline = new RiskPipeline([
            new EnrichmentStep(),
            new StrategyScoringStep($this->strategies),
            new RuleEngineStep($ruleEngine),
            new AggregationStep(),
        ]);

        $context = $pipeline->handle(new RiskContext($transaction));
        $result = $context->result();

        if ($result === null) {
            throw new \RuntimeException('Risk evaluation pipeline did not produce a result.');
        }

        $response = RiskEvaluationResponseDTO::fromRiskResult($result);

        if ($command->idempotencyKey !== null) {
            $this->idempotencyRepository->put(
                $command->idempotencyKey,
                $response->toArray(),
                self::IDEMPOTENCY_TTL_SECONDS
            );
        }

        $this->asyncTransactionStore->store($command->transaction, $response);

        return $response;
    }
}
