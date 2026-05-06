<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable()->after('address');
            $table->string('vehicle_plate')->nullable()->after('vehicle_type');
        });
    }
    public function down(): void {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['vehicle_type', 'vehicle_plate']);
        });
    }
};