<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_objects', function (Blueprint $table) {

            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('type');

            $table->string('name')->nullable();

            $table->decimal('x',10,2);

            $table->decimal('y',10,2);

            $table->decimal('rotation',8,2)->default(0);

            $table->decimal('width',8,2)->nullable();

            $table->decimal('height',8,2)->nullable();

            $table->string('shape')->nullable();

            $table->string('style')->nullable();

            $table->json('properties')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_objects');
    }
};