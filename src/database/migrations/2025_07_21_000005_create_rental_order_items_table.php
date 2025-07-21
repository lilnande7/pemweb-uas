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
        Schema::create('rental_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_order_id')->constrained('rental_orders')->onDelete('cascade');
            $table->foreignId('instrument_id')->constrained('instruments')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->integer('rental_days');
            $table->decimal('total_price', 12, 2);
            $table->enum('condition_out', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->enum('condition_in', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->text('damage_notes')->nullable();
            $table->decimal('damage_fee', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['rental_order_id', 'instrument_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_order_items');
    }
};
