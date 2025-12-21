<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id('order_id');
                $table->unsignedBigInteger('user_id');
                $table->string('status', 30)->default('baru');
                $table->bigInteger('subtotal')->default(0);
                $table->bigInteger('tax')->default(0);
                $table->bigInteger('shipping')->default(0);
                $table->bigInteger('total')->default(0);
                $table->timestamps();
                $table->index('created_at');
                $table->index('status');
                $table->index('user_id');
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('produk_id');
                $table->integer('quantity');
                $table->bigInteger('price');
                $table->timestamps();
                $table->index('order_id');
                $table->index('produk_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
