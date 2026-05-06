<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Nambahin kolom lat & lng di sini
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
        });
    }

   
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Wajib ada ini buat ngehapus kolom kalau sewaktu-waktu di-rollback
            $table->dropColumn(['lat', 'lng']);
        });
    }
};