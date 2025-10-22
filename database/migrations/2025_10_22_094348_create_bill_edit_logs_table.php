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
        Schema::create('bill_edit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('bill_type'); // 'merchant_bill' or 'customer_bill'
            $table->unsignedBigInteger('bill_id'); // ID of the bill being edited
            $table->unsignedBigInteger('user_id'); // Who made the edit
            $table->string('action'); // 'created', 'updated', 'deleted'
            $table->json('old_data')->nullable(); // Previous data (for updates)
            $table->json('new_data')->nullable(); // New data
            $table->text('changes_summary')->nullable(); // Human readable summary of changes
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['bill_type', 'bill_id']);
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_edit_logs');
    }
};