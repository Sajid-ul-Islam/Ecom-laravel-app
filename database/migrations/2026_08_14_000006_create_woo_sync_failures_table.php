<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('woo_sync_failures', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type')->index();
            $table->unsignedBigInteger('woo_id')->nullable()->index();
            $table->json('payload')->nullable();
            $table->text('error_message');
            $table->json('error_context')->nullable();
            $table->unsignedInteger('attempts')->default(1);
            $table->timestamp('last_attempted_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'resolved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('woo_sync_failures');
    }
};
