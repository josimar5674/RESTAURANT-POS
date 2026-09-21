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
      Schema::create('modifier_options', function (Blueprint $table) {
    $table->id();

    $table->uuid('uuid')->unique();

    $table->foreignId('modifier_group_id')
        ->constrained('modifier_groups')
        ->cascadeOnDelete();

    $table->string('name', 100);

    $table->decimal('price_adjustment', 10, 2)->default(0);

    $table->integer('sort_order')->default(1);

    $table->boolean('active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifier_options');
    }
};
