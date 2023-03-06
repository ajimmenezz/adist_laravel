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
        Schema::create('t_censos_device_features', function (Blueprint $table) {
            $table->id('Id');
            $table->integer('CensoId', false, true)->length(100);
            $table->unsignedBigInteger('FeatureId');
            $table->string('Value',190);
            $table->boolean('Active')->default(true);
            $table->foreign('CensoId')->references('Id')->on('t_censos');
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
        Schema::dropIfExists('t_censos_device_features');
    }
};
