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
       Schema::create('trusted_devices', function (Blueprint $table) {
    $table->id();

    $table->uuid('device_id')->unique();

    $table->string('name')->nullable();

    $table->foreignId('registered_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->boolean('active')->default(true);

    $table->timestamp('last_seen_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trusted_devices');
    }
};
