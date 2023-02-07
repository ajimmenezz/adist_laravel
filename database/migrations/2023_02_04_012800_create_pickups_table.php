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
        Schema::create('l_pickups', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('BranchId',);
            $table->unsignedBigInteger('UserId');
            $table->unsignedBigInteger('StatusId');
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
        Schema::dropIfExists('l_pickups');
    }
};
