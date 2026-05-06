<?php
// database/migrations/xxxx_create_deliveries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_id')->nullable();
            $table->unsignedBigInteger('donor_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->unsignedBigInteger('volunteer_id')->nullable();
            $table->enum('status', ['pending', 'on_delivery', 'delivered', 'failed'])->default('pending');
            $table->datetime('pickup_time')->nullable();
            $table->integer('eta_minutes')->nullable();
            $table->boolean('is_expiring')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
