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
            $table->dropColumn(['email', 'city', 'state', 'pincode', 'shop_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('shop_name')->nullable();
        });
    }
};
