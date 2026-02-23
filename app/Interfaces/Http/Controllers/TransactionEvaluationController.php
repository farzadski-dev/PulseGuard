<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Application\Commands\EvaluateTransactionCommand;
use App\Application\DTO\TransactionDTO;
use App\Application\Services\EvaluateTransactionService;
use App\Interfaces\Http\Requests\EvaluateTransactionRequest;
use App\Interfaces\Http\Resources\RiskEvaluationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

final class TransactionEvaluationController
{
    public function __construct(private EvaluateTransactionService $service)
    {
    }

    public function evaluate(EvaluateTransactionRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $command = new EvaluateTransactionCommand(
            TransactionDTO::fromArray($payload),
            $request->header('Idempotency-Key'),
            $request->header('X-Request-Id', (string) Str::uuid())
        );

        $response = $this->service->handle($command);

        return (new RiskEvaluationResource($response))->response();
    }
}
