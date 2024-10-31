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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->unique()->index();
            $table->string('name');
            $table->string('logo');
            $table->string('icon');
            $table->string('disk',25)->default('public');
            $table->string('pointer', 100)->nullable()->index();
            $table->enum('type', \App\Enums\AppType::values()); // 0 for trigger, 1 for action, 2 for both
            $table->tinyInteger('status')->default(Status::ACTIVE);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
};
