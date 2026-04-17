<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treasurer_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treasurer_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('collection_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('amount_verified', 10, 2);
            $table->timestamp('verification_date');
            $table->string('official_receipt_number')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasurer_records');
    }
};
