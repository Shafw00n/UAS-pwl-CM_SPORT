<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('produk_views')) {
            Schema::create('produk_views', function (Blueprint $table) {
                $table->id(); // primary key
                $table->unsignedBigInteger('produk_id');
                $table->integer('view_count')->default(1)->comment('Number of views for this day');
                $table->date('viewed_at')->comment('Date of views');
                $table->timestamps(); // created_at & updated_at

                // Indexes
                $table->index('produk_id');
                $table->index('viewed_at');
                $table->index(['produk_id', 'viewed_at']); // Composite index
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_views');
    }
};
