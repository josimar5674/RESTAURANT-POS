<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('name', 100);

            $table->string('description')->nullable();

            $table->unsignedTinyInteger('min_selections')->default(0);

            $table->unsignedTinyInteger('max_selections')->default(1);

            $table->integer('sort_order')->default(1);

            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modifier_groups');
    }
};