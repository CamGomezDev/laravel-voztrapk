<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyColumnPuestoVotacionIdOnLiders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liders', function (Blueprint $table) {
          $table->integer('puesto_votacion_id')->unsigned()->nullable();
          $table->foreign('puesto_votacion_id')->references('id')->on('puesto_votacions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liders', function (Blueprint $table) {
          $table->dropColumn('puesto_votacion_id');
        });
    }
}
