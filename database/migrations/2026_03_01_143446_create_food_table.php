<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->unsignedInteger('portion')->nullable();
            $table->unsignedBigInteger('donor_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->enum('status', ['available', 'taken', 'delivered', 'invalid'])->default('available');
            $table->datetime('collection_date')->nullable();
            $table->string('photo')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};