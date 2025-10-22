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
        Schema::create('merchant_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained()->onDelete('cascade');
            $table->date('bill_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('merchant_bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_bill_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->decimal('misc_adjustment', 10, 2)->default(0);
            $table->decimal('net_quantity', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('customer_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('bill_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_bill_id')->constrained()->onDelete('cascade');
            $table->foreignId('merchant_bill_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_bill_items');
        Schema::dropIfExists('customer_bills');
        Schema::dropIfExists('merchant_bill_items');
        Schema::dropIfExists('merchant_bills');
    }
};