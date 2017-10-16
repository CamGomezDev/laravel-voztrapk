<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilasElectoralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filas_electorales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_municipio')->unsigned();
            $table->integer('id_corporacion')->unsigned();
            $table->integer('votostotales');
            $table->integer('votoscandidato');
            $table->integer('votospartido');
            $table->integer('potencialelectoral');
            $table->integer('anio');
            $table->timestamps();
            $table->foreign('id_municipio')->references('id')->on('municipios');
            $table->foreign('id_corporacion')->references('id')->on('corporaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filas_electorales');
    }
}
