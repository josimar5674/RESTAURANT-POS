<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->uuid()->unique();

        $table->foreignId('order_id')
            ->constrained('orders')
            ->restrictOnDelete();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->string('method', 30);

        $table->decimal('amount', 12, 2);

        $table->string('reference')->nullable();

        $table->text('notes')->nullable();

        $table->timestamp('paid_at')->nullable();

        $table->timestamps();

        $table->index(['order_id', 'paid_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('payments');
}
};
