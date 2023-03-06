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
        Schema::create('t_inventory_features', function (Blueprint $table) {
            $table->id('Id');
            $table->integer('InventoryId', false, true)->length(50);
            $table->unsignedBigInteger('FeatureId');
            $table->string('Value', 100);
            $table->boolean('Active')->default(true);
            $table->foreign('InventoryId')->references('Id')->on('t_inventario');            
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
        Schema::dropIfExists('t_inventory_features');
    }
};
