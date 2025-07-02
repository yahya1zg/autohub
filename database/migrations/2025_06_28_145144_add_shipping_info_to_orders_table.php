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
            // purchase_date sütunundan sonra yeni teslimat bilgisi sütunları ekler.
            $table->string('customer_name')->after('purchase_date');
            $table->string('phone_number')->after('customer_name');
            $table->text('address')->after('phone_number');
            $table->string('city')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'phone_number', 'address', 'city']);
        });
    }
};
