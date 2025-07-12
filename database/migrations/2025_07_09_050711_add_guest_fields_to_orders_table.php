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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode')->nullable()->after('state');
            $table->decimal('subtotal', 10, 2)->default(0)->after('pincode');
            $table->decimal('discount', 10, 2)->default(0)->after('subtotal');
            $table->foreignId('offer_id')->nullable()->constrained()->onDelete('set null')->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropColumn(['name', 'email', 'phone', 'address', 'city', 'state', 'pincode', 'subtotal', 'discount', 'offer_id']);
        });
    }
};
