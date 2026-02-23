<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class EvaluateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $tenantId = $this->header('X-Tenant-Id');
        if ($tenantId !== null) {
            $this->merge(['tenant_id' => $tenantId]);
        }

        $idempotencyKey = $this->header('Idempotency-Key');
        if ($idempotencyKey !== null) {
            $this->merge(['idempotency_key' => $idempotencyKey]);
        }
    }

    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'string', 'max:64'],
            'transaction_id' => ['required', 'string', 'max:64'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
            'amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'payment_method' => ['required', 'string', 'max:32'],
            'device' => ['required', 'array'],
            'device.device_id' => ['required', 'string', 'max:128'],
            'device.ip' => ['required', 'ip'],
            'device.user_agent' => ['nullable', 'string', 'max:512'],
            'device.ip_country' => ['nullable', 'string', 'size:2'],
            'location' => ['required', 'array'],
            'location.country' => ['required', 'string', 'size:2'],
            'location.region' => ['nullable', 'string', 'max:64'],
            'location.city' => ['nullable', 'string', 'max:64'],
            'location.lat' => ['nullable', 'numeric', 'between:-90,90'],
            'location.lon' => ['nullable', 'numeric', 'between:-180,180'],
            'occurred_at' => ['required', 'date'],
            'metadata' => ['sometimes', 'array'],
        ];
    }
}
