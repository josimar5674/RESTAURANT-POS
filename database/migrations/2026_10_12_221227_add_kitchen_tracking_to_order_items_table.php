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
    Schema::table('order_items', function (Blueprint $table) {
        $table->timestamp('sent_to_kitchen_at')->nullable();
        $table->timestamp('received_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropColumn([
            'sent_to_kitchen_at',
            'received_at',
        ]);
    });
}
};
