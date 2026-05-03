<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('header_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 50);
            $table->string('type', 30);
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('action_label')->nullable();
            $table->string('action_url')->nullable();
            $table->string('priority', 20)->default('normal');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('header_items');
    }
};
