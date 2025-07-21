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
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('model')->nullable();
            $table->foreignId('category_id')->constrained('instrument_categories')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('weekly_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->integer('quantity_available')->default(1);
            $table->integer('quantity_total')->default(1);
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->year('year_made')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('specifications')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'is_available', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};
