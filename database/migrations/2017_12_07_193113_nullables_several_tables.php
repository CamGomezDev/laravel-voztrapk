<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullablesSeveralTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fila_electorals', function (Blueprint $table) {
          $table->integer('potencialelectoral')->nullable()->change();
          $table->integer('votostotales')->nullable()->change();
          $table->integer('votospartido')->nullable()->change();
          $table->integer('votoscandidato')->nullable()->change();
        });

        Schema::table('liders', function (Blueprint $table) {
          $table->string('cedula')->nullable()->change();
          $table->string('correo')->nullable()->change();
          $table->string('telefono')->nullable()->change();
          $table->string('nivel')->nullable()->change();
          $table->string('tipolider')->nullable()->change();
        });

        Schema::table('compromisos', function (Blueprint $table) {
          $table->string('nombre')->nullable()->change();
          $table->string('descripcion')->nullable()->change();
          $table->integer('costo')->nullable()->change();
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
        $table->integer('potencialelectoral')->nullable(false)->change();
        $table->integer('votostotales')->nullable(false)->change();
        $table->integer('votospartido')->nullable(false)->change();
        $table->integer('votoscandidato')->nullable(false)->change();
      });

      Schema::table('liders', function (Blueprint $table) {
        $table->string('cedula')->nullable(false)->change();
        $table->string('correo')->nullable(false)->change();
        $table->string('telefono')->nullable(false)->change();
        $table->string('nivel')->nullable(false)->change();
        $table->string('tipolider')->nullable(false)->change();
      });

      Schema::table('compromisos', function (Blueprint $table) {
        $table->string('nombre')->nullable(false)->change();
        $table->string('descripcion')->nullable(false)->change();
        $table->integer('costo')->nullable(false)->change();
      });
    }
}
