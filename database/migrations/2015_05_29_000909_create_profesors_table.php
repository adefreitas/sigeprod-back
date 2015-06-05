<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfesorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profesors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
		});

        Schema::create('materia_profesor', function(Blueprint $table){
            $table->integer('materia_id')->unsigned()->index();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->integer('profesor_id')->unsigned()->index();
            $table->foreign('profesor_id')->references('id')->on('profesors')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('coordinador_materia', function(Blueprint $table){
            $table->integer('profesor_id')->unsigned()->index();
            $table->foreign('profesor_id')->references('id')->on('profesors')->onDelete('cascade');
            $table->integer('materia_id')->unsigned()->index();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('centro_coordinador', function(Blueprint $table){
            $table->integer('centro_id')->unsigned()->index();
            $table->foreign('centro_id')->references('id')->on('centros')->onDelete('cascade');
            $table->timestamps();
            $table->integer('profesor_id')->unsigned()->index();
            $table->foreign('profesor_id')->references('id')->on('profesors')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('materia_profesor');
		Schema::drop('coordinador_materia');
		Schema::drop('centro_coordinador');
		Schema::drop('profesors');
	}

}
