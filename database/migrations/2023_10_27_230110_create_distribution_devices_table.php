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
        Schema::create('adl_warehouse_distribution_devices', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('DistributionId')->constrained('adl_warehouse_distributions','Id');
            $table->integer('BranchId');
            $table->integer('InventoryId');
            $table->integer('AreaId')->nullable();
            $table->integer('StatusId');
            $table->integer('CurrentTransfer')->nullable();
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
        Schema::dropIfExists('adl_warehouse_distribution_devices');
    }
};
