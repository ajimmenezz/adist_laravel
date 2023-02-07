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
        Schema::create('l_pickup_boxed_items', function (Blueprint $table) {
            $table->id('Id');
            $table->integer('UserId');
            $table->unsignedBigInteger('PickupId');
            $table->integer('BoxNumber');
            $table->integer('CensoId')->nullable();
            $table->integer('Quantity');
            $table->integer('ModelId');
            $table->integer('ComponentId')->nullable();
            $table->string('SerialNumber');
            $table->string('Comments')->nullable();
            $table->foreign('PickupId')->references('Id')->on('l_pickups');
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
        Schema::dropIfExists('l_pickup_boxed_items');
    }
};
