<?php

use App\Enums\PlanStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->tinyInteger('status')->default(PlanStatus::ACTIVE->value);
            $table->string('title')->nullable();
            $table->json('meta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('plans', 'title')) {
                $table->dropColumn('title');
            }
            
            if (Schema::hasColumn('plans', 'meta')) {
                $table->dropColumn('meta');
            }
           
        });
    }
};
