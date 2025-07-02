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
        Schema::table('cars', function (Blueprint $table) {
            // 'users' tablosuna bağlı bir user_id sütunu ekler.
            // Aracı ekleyen kullanıcı silinirse, araba kaydı da silinir (onDelete('cascade')).
            // Bu sütun 'status' sütunundan sonra gelsin.
            $table->foreignId('user_id')->nullable()->after('status')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Geri alma işlemi için sütunu ve anahtarı kaldırır.
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
