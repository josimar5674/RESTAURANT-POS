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
   Schema::create('restaurant_areas', function (Blueprint $table) {

    $table->id();

    $table->uuid('uuid')->unique();

    $table->foreignId('restaurant_id')->nullable();

    $table->string('name');

    $table->string('description')->nullable();

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
        Schema::dropIfExists('restaurant_areas');
    }
};
