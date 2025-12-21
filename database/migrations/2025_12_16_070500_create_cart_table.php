<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cart')) {
            Schema::create('cart', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('produk_id');
                $table->string('session_id', 64)->nullable();
                $table->integer('quantity')->default(1);
                $table->string('ukuran', 50)->nullable();
                $table->string('warna', 50)->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('produk_id');
                $table->unique(['user_id','produk_id','ukuran','warna'], 'cart_unique_item_per_variant');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
