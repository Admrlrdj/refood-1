<?php
// database/migrations/xxxx_create_receivers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['orphanage','foundation','community','school','other'])->default('other');
            $table->string('pic_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->unsignedInteger('capacity_people')->nullable();
            $table->unsignedTinyInteger('need_level')->default(50); // 0-100
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('receivers'); }
};
