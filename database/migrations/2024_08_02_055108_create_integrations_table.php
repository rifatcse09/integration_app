<?php

use App\Enums\Status;
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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->unique()->index();
            $table->string('name')->nullable();
            $table->integer('shop_id')->index();
            $table->integer('trigger_id')->index();
            $table->foreign('trigger_id')->references('id')->on('apps')->onDelete('cascade');
            $table->integer('action_id')->index();
            $table->foreign('action_id')->references('id')->on('apps')->onDelete('cascade');
            $table->integer('action_credential_id')->nullable();
            $table->integer('trigger_credential_id')->nullable();
            $table->integer('event_id')->index();
            $table->foreign('event_id')->references('id')->on('webhook_events');
            $table->json('payload');
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
        Schema::dropIfExists('integrations');
    }
};
