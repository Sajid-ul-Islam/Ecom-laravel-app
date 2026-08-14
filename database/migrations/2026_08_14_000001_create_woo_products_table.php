<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('woo_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('woo_id')->unique();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->nullable()->index();
            $table->string('type')->nullable();
            $table->string('status')->nullable()->index();
            $table->string('permalink')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('regular_price', 12, 2)->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->string('stock_status')->nullable();
            $table->boolean('manage_stock')->default(false);
            $table->string('featured_image')->nullable();
            $table->json('raw_payload')->nullable();
            $table->string('sync_status')->default('pending')->index();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('woo_created_at')->nullable();
            $table->timestamp('woo_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('woo_products');
    }
};
