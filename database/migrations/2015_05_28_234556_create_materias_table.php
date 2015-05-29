<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMateriasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('materias', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('nombre');
            $table->integer('creditos');
            $table->integer('semestre');
            $table->integer('centro_id')->nullable()->references('id')->on('centros')->onDelete('cascade');
			$table->timestamps();
		});

        Schema::create('materia_prelacion', function(Blueprint $table){
            $table->integer('materia_id')->unsigned()->index();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->integer('prelacion_id')->unsigned()->index();
            $table->foreign('prelacion_id')->references('id')->on('materias')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('materias');
	}

}
