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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->unique()->index();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('app_id');
            $table->string('name');
            $table->text('scopes')->nullable();
            $table->text('scope_hash')->nullable();
            $table->json('secrets')->nullable();;
            $table->string('source');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
