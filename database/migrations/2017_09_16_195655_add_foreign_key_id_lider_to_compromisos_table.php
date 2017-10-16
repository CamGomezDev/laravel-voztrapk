<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyIdLiderToCompromisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compromisos', function (Blueprint $table) {
            $table->foreign('id_lider')->references('id')->on('lideres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compromisos', function(Blueprint $table) {
            $table->dropForeign('compromisos_id_lider_foreign');
        });
    }
}
