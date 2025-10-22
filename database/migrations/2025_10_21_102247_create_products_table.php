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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // vegetables, fruits, herbs, etc.
            $table->string('subcategory')->nullable(); // leafy, root, etc.
            $table->text('description')->nullable();
            $table->string('unit'); // kg, piece, bunch, etc.
            $table->decimal('price_per_unit', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(10);
            $table->string('supplier_name')->nullable();
            $table->date('harvest_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('quality_grade')->default('A'); // A, B, C
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
