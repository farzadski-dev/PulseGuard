<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tenant_id', 64);
            $table->string('transaction_id', 64);
            $table->unsignedSmallInteger('score');
            $table->string('level', 16);
            $table->json('strategy_scores');
            $table->json('rule_adjustments');
            $table->json('reasons');
            $table->timestamp('evaluated_at');
            $table->timestamps();

            $table->unique(['tenant_id', 'transaction_id']);
            $table->index(['tenant_id', 'score']);
            $table->index(['tenant_id', 'evaluated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_results');
    }
};
