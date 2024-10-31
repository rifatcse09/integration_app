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
        Schema::create('custom_webhooks', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->string('unique_code')->unique();
            $table->tinyInteger('status')->default(Status::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_webhooks');
    }
};
