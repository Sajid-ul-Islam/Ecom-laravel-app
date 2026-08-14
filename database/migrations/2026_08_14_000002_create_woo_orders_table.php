<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('woo_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('woo_id')->unique();
            $table->string('number')->nullable()->index();
            $table->string('status')->index();
            $table->string('currency', 10)->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('total_tax', 12, 2)->nullable();
            $table->decimal('shipping_total', 12, 2)->nullable();
            $table->decimal('discount_total', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_title')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_email')->nullable()->index();
            $table->text('customer_note')->nullable();
            $table->json('billing')->nullable();
            $table->json('shipping')->nullable();
            $table->json('raw_payload')->nullable();
            $table->string('sync_status')->default('pending')->index();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('woo_created_at')->nullable();
            $table->timestamp('woo_updated_at')->nullable();
            $table->timestamp('woo_completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('woo_orders');
    }
};
