<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tenant_id', 64);
            $table->string('transaction_id', 64);
            $table->string('idempotency_key', 64)->nullable();
            $table->unsignedBigInteger('amount_minor');
            $table->char('currency', 3);
            $table->string('payment_method', 32);
            $table->json('device');
            $table->json('location');
            $table->json('metadata')->nullable();
            $table->timestamp('occurred_at');
            $table->unsignedSmallInteger('risk_score');
            $table->string('risk_level', 16);
            $table->timestamps();

            $table->unique(['tenant_id', 'transaction_id']);
            $table->unique(['tenant_id', 'idempotency_key']);
            $table->index(['tenant_id', 'occurred_at']);
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'risk_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
