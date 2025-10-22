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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'subcategory', 
                'description',
                'price_per_unit',
                'stock_quantity',
                'min_stock_level',
                'supplier_name',
                'harvest_date',
                'expiry_date',
                'quality_grade'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_per_unit', 10, 2);
            $table->integer('stock_quantity');
            $table->integer('min_stock_level');
            $table->string('supplier_name')->nullable();
            $table->date('harvest_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('quality_grade', ['A', 'B', 'C']);
        });
    }
};