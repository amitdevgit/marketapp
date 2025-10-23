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
        Schema::table('customer_bills', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending')->after('total_amount');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_bills', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_amount']);
        });
    }
};
