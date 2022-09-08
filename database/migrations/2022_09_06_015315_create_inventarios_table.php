<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id()->unique();
            $table->float('precio');
            $table->integer('unidad');
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_eliminacion')->nullable(true);
            $table->dateTime('fecha_actualizacion')->nullable(true);
            $table->foreignId('id_pelicula_fk')->references('id')->on('peliculas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}
