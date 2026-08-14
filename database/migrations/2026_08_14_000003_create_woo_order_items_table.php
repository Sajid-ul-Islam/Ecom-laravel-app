<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('woo_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('woo_order_id')->constrained('woo_orders')->cascadeOnDelete();
            $table->unsignedBigInteger('woo_id')->unique();
            $table->unsignedBigInteger('woo_product_id')->nullable()->index();
            $table->unsignedBigInteger('woo_variation_id')->nullable();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->decimal('total_tax', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('woo_order_items');
    }
};
