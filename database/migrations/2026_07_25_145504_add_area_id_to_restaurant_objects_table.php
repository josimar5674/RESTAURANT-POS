<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_objects', function (Blueprint $table) {

            $table->foreignId('area_id')
                ->after('id')
                ->constrained('restaurant_areas')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('restaurant_objects', function (Blueprint $table) {

            $table->dropForeign(['area_id']);

            $table->dropColumn('area_id');

        });
    }
};