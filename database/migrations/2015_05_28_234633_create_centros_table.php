<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentrosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('centros', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->string('name');
		});

        Schema::create('centro_materia', function(Blueprint $table){
            $table->integer('centro_id')->unsigned()->index();
            $table->foreign('centro_id')->references('id')->on('centros')->onDelete('cascade');
            $table->integer('materia_id')->unsigned()->index();
            $table->foreign('materia_id')->references('id')->on('materias')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('centro_materia');
		Schema::drop('centros');
	}

}
