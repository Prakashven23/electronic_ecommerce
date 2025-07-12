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
        Schema::table('offers', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('category_id');
            $table->string('code')->unique()->after('name');
            $table->decimal('minimum_order_value', 10, 2)->default(0)->after('value');
            $table->integer('discount_percentage')->after('minimum_order_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['active', 'code', 'minimum_order_value', 'discount_percentage']);
        });
    }
};
