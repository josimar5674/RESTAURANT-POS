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
    Schema::create('halls', function (Blueprint $table) {

        $table->id();

        $table->uuid('uuid')->unique();

        $table->string('name');

        $table->string('description')->nullable();

        /*
        |--------------------------------------------------------------------------
        | Tamaño del plano de Konva
        |--------------------------------------------------------------------------
        */

        $table->integer('canvas_width')->default(1600);

        $table->integer('canvas_height')->default(900);

        /*
        |--------------------------------------------------------------------------
        | Apariencia
        |--------------------------------------------------------------------------
        */

        $table->string('background_color')->default('#FFFFFF');

        /*
        |--------------------------------------------------------------------------
        | Configuración
        |--------------------------------------------------------------------------
        */

        $table->boolean('active')->default(true);

        $table->unsignedInteger('sort_order')->default(1);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halls');
    }
};
