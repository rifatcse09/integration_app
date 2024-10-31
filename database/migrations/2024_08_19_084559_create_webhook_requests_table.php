<?php

use App\Enums\Status;
use App\Enums\WebhookType;
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
        Schema::create('webhook_requests', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->unique()->index();
            $table->string('request_id')->nullable();
            $table->integer('shop_id');
            $table->integer('custom_webhook_id')->nullable();
            $table->string('provider')->default(WebhookType::SHOPIFY->value);
            $table->string('topic')->nullable();
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->tinyInteger('status')->default(Status::ACTIVE->value);
            $table->string('payload_hash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_requests');
    }
};
