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
        Schema::create('inventory2023', function (Blueprint $table) {
            $table->id('Id');
            $table->integer('WarehouseKey');
            $table->string('Warehouse', 255);
            $table->string('ItemKey', 50);
            $table->string('Item', 255);
            $table->string('ItemLine', 255);
            $table->string('Measure', 30);
            $table->float('Quantity', 8, 2);
            $table->float('ValidatedQuantity', 8, 2);
            $table->integer('LastUpdateUser');
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
        Schema::dropIfExists('inventory2023');
    }
};
