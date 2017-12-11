<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuestoVotacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puesto_votacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('barrio_id')->unsigned();
            $table->string('nombre');
            $table->integer('mesas')->nullable();
            $table->timestamps();

            $table->foreign('barrio_id')->references('id')->on('barrios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('puesto_votacions');
    }
}
