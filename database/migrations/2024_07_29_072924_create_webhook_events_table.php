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
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->unique()->index();
            $table->integer('app_id')->index();
            $table->foreign('app_id')->references('id')->on('apps');
            $table->string('type')->default(WebhookType::SHOPIFY->value);
            $table->integer('custom_webhook_id')->nullable();
            $table->string('name', 60)->nullable();
            $table->string('topic', 60)->nullable();
            $table->json('payload')->nullable();
            $table->tinyInteger('status')->default(Status::ACTIVE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_events');
    }
};
