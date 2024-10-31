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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_id')->unsigned()->nullable();
            $table->string('uid', 32)->unique();
            $table->string('name', 255);
            $table->string('primary_domain', 255)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('email', 255);
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('development_store')->default(false);
            $table->boolean('app_setup_completed')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
