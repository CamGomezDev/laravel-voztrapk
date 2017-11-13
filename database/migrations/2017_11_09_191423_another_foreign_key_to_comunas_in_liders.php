<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnotherForeignKeyToComunasInLiders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liders', function (Blueprint $table) {
            $table->dropForeign('lideres_id_municipio_foreign');
        });

        Schema::table('liders', function (Blueprint $table) {
            $table->integer('id_municipio')->unsigned()->nullable()->change();
        });

        Schema::table('liders', function (Blueprint $table) {
            $table->foreign('id_municipio')->references('id')->on('municipios');
        });

        Schema::table('liders', function (Blueprint $table) {
            $table->integer('id_comuna')->unsigned()->nullable();
            $table->foreign('id_comuna')->references('id')->on('comunas');
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
            $table->dropColumn('id_comuna');
            $table->dropForeign('liders_id_municipio_foreign');
        });

        Schema::table('liders', function (Blueprint $table) {
            $table->integer('id_municipio')->unsigned()->nullable(false)->change();
        });

        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->foreign('id_municipio')->references('id')->on('municipios');
        });
    }
}
