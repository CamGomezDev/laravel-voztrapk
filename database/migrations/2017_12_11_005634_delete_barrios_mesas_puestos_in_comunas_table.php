<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteBarriosMesasPuestosInComunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunas', function (Blueprint $table) {
          $table->dropColumn('puestos');
          $table->dropColumn('mesas');
          $table->dropColumn('barrios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('comunas', function (Blueprint $table) {
        $table->integer('puestos')->unsigned();
        $table->integer('mesas')->unsigned();
        $table->integer('barrios')->unsigned();
      });
    }
}
