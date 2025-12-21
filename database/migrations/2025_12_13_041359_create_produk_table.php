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
        // Create kategori table
        if (!Schema::hasTable('kategori')) {
            Schema::create('kategori', function (Blueprint $table) {
                $table->id('kategori_id');
                $table->string('nama_kategori', 100);
                $table->string('slug', 100)->unique();
                $table->text('deskripsi')->nullable();
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }

        // Create produk table
        if (!Schema::hasTable('produk')) {
            Schema::create('produk', function (Blueprint $table) {
                $table->id('produk_id');
                $table->foreignId('kategori_id')->constrained('kategori', 'kategori_id')->onDelete('cascade');
                $table->string('nama_produk', 255);
                $table->text('deskripsi');
                $table->decimal('harga', 12, 2);
                $table->string('gambar')->nullable();
                $table->integer('stok')->default(0);
                $table->string('sku', 100)->nullable()->unique();
                $table->decimal('berat', 8, 2)->nullable()->comment('in kg');
                $table->integer('terjual')->default(0);
                $table->decimal('rating', 3, 2)->default(5.00);
                $table->integer('total_reviews')->default(0);
                $table->integer('discount')->default(0)->comment('percentage');
                $table->boolean('is_new')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                $table->string('kota', 100)->nullable();
                $table->json('ukuran')->nullable()->comment('available sizes');
                $table->json('warna')->nullable()->comment('available colors');
                $table->timestamps();
                
                $table->index('kategori_id');
                $table->index('harga');
                $table->index('is_active');
                $table->index('created_at');
            });
        }

        // Create wishlist table
        if (!Schema::hasTable('wishlist')) {
            Schema::create('wishlist', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('produk_id');
                $table->timestamp('created_at');
                
                $table->unique(['user_id', 'produk_id']);
                $table->index('user_id');
                $table->index('produk_id');
            });
        }

        // Create cart table (optional)
        if (!Schema::hasTable('cart')) {
            Schema::create('cart', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('session_id')->nullable();
                $table->foreignId('produk_id')->constrained('produk', 'produk_id')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->string('ukuran')->nullable();
                $table->string('warna')->nullable();
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('session_id');
            });
        }

        // Create product_images table (for multiple images)
        if (!Schema::hasTable('produk_images')) {
            Schema::create('produk_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('produk_id');
                $table->string('image_path');
                $table->integer('order')->default(0);
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
                
                $table->index('produk_id');
            });
        }

        // Create reviews table
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('produk_id');
                $table->integer('rating')->default(5);
                $table->text('comment')->nullable();
                $table->json('images')->nullable();
                $table->boolean('is_verified_purchase')->default(false);
                $table->timestamps();
                
                $table->index('produk_id');
                $table->index('user_id');
                $table->index('rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('produk_images');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('wishlist');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
    }
};
