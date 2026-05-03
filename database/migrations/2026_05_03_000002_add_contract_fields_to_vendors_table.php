<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table): void {
            $table->string('category')->nullable()->after('email');
            $table->date('contract_start_date')->nullable()->after('category');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->decimal('monthly_rent', 10, 2)->nullable()->after('contract_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table): void {
            $table->dropColumn([
                'category',
                'contract_start_date',
                'contract_end_date',
                'monthly_rent',
            ]);
        });
    }
};
