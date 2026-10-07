<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Orden
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Producto
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete();

            $table->foreignId('variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Snapshot del producto
            |--------------------------------------------------------------------------
            */

            $table->string('product_name', 150);

            $table->string('variant_name', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Cantidad y precio
            |--------------------------------------------------------------------------
            */

            $table->decimal('quantity', 10, 2)
                ->default(1);

            $table->decimal('unit_price', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Impuesto
            |--------------------------------------------------------------------------
            */

            $table->decimal('tax_rate', 5, 2)
                ->default(0);

            $table->decimal('tax_amount', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Descuento
            |--------------------------------------------------------------------------
            */

            $table->decimal('discount', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Totales
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 10, 2)
                ->default(0);

            $table->decimal('total', 10, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Notas
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};