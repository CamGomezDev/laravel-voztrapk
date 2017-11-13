<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnotherForeignKeyToComunasInFilaElectorals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->dropForeign('fila_electorals_id_municipio_foreign');
        });

        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->integer('id_municipio')->unsigned()->nullable()->change();
        });

        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->foreign('id_municipio')->references('id')->on('municipios');
        });

        Schema::table('fila_electorals', function (Blueprint $table) {
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
        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->dropColumn('id_comuna');
            $table->dropForeign('fila_electorals_id_municipio_foreign');
        });

        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->integer('id_municipio')->unsigned()->nullable(false)->change();
        });

        Schema::table('fila_electorals', function (Blueprint $table) {
            $table->foreign('id_municipio')->references('id')->on('municipios');
        });
    }
}
