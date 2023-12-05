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
        Schema::create('adl_warehouse_distribution_devices_history', function (Blueprint $table) {
            $table->id('Id');
            $table->integer('DistributionDeviceId');
            $table->integer('StatusId');
            $table->integer('WarehouseId');
            $table->integer('TransferId')->nullable();
            $table->integer('UserId');
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
        Schema::dropIfExists('adl_warehouse_distribution_devices_history');
    }
};
