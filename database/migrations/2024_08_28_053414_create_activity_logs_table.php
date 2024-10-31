<?php

use App\Enums\LogStatus;
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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->unique()->index();
            $table->integer('shop_id')->index();
            $table->integer('integration_id')->index();
            $table->string('title', 180)->nullable();
            $table->string('description')->nullable();
            $table->json('log_payload')->nullable();
            $table->json('trigger_payload')->nullable();
            $table->tinyInteger('status')->default(LogStatus::FAILED->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
