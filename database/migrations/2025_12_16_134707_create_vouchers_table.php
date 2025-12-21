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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percent', 'fixed'])->default('fixed');
            $table->bigInteger('discount_value');
            $table->bigInteger('min_purchase')->default(0);
            $table->bigInteger('max_discount')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->integer('quota')->default(0)->comment('0 for unlimited');
            $table->integer('used_count')->default(0);
            $table->timestamps();
            
            $table->index('code');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
