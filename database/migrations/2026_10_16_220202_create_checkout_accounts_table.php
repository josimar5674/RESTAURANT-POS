<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::create('checkout_accounts', function (Blueprint $table) {
        $table->id();
        $table->uuid()->unique();

        $table->foreignId('table_id')
            ->constrained('restaurant_objects')
            ->restrictOnDelete();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->string('name')->nullable();

        $table->string('status', 20)->default('open');

        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('tax', 12, 2)->default(0);
        $table->decimal('total', 12, 2)->default(0);

        $table->timestamp('paid_at')->nullable();
        $table->timestamps();

        $table->index(['table_id', 'status']);
    });
}

public function down(): void
{
    Schema::dropIfExists('checkout_accounts');
}


};
