<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tenant_id', 64);
            $table->string('name', 64);
            $table->string('field', 32);
            $table->string('operator', 8);
            $table->string('value', 128);
            $table->integer('adjustment');
            $table->string('reason', 255);
            $table->boolean('enabled')->default(true);
            $table->unsignedSmallInteger('priority')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'enabled', 'priority']);
            $table->index(['tenant_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_rules');
    }
};
