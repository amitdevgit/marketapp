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
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('balance', 10, 2)->default(0)->after('customer_type');
            $table->decimal('total_purchased', 10, 2)->default(0)->after('balance');
            $table->decimal('total_paid', 10, 2)->default(0)->after('total_purchased');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['balance', 'total_purchased', 'total_paid']);
        });
    }
};
