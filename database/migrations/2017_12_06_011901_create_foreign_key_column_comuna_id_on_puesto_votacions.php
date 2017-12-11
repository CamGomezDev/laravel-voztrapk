<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyColumnComunaIdOnPuestoVotacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('puesto_votacions', function (Blueprint $table) {
          $table->integer('comuna_id')->unsigned();
          $table->foreign('comuna_id')->references('id')->on('comunas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('puesto_votacions', function (Blueprint $table) {
          $table->dropColumn('comuna_id');
        });
    }
}
