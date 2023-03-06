<?php

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
        Schema::create('c_inventory_features_by_lines', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('FeatureId');
            $table->integer('LineId', false, true);
            $table->integer('SubLineId', false, true)->nullable();
            $table->boolean('Active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_inventory_features_by_lines');
    }
};
