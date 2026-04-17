<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_maps', function (Blueprint $table) {
            $table->id();
            $table->string('stall_number', 25)->unique();
            $table->unsignedTinyInteger('floor_level')->default(1);
            $table->string('zone_section');
            $table->string('qr_location_code')->unique();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_maps');
    }
};
