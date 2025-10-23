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
        // Email is already nullable in the original table creation
        // No columns need to be dropped as they were never created
        // This migration is now redundant but kept for historical consistency
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
        });
    }
};
