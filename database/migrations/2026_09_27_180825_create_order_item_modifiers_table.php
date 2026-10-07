<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_modifiers', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Order item
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Modificador original
            |--------------------------------------------------------------------------
            |
            | Se conserva la referencia al catálogo, pero la orden también
            | guarda un snapshot de los datos utilizados.
            |
            */

            $table->foreignId('modifier_option_id')
                ->nullable()
                ->constrained('modifier_options')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Snapshot
            |--------------------------------------------------------------------------
            */

            $table->string('modifier_name', 100);

            $table->decimal('price_adjustment', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Cantidad
            |--------------------------------------------------------------------------
            */

            $table->decimal('quantity', 10, 2)
                ->default(1);

            /*
            |--------------------------------------------------------------------------
            | Total
            |--------------------------------------------------------------------------
            */

            $table->decimal('total', 10, 2)
                ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_modifiers');
    }
};