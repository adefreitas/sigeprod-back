<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterPlanningTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('semester_planning', function(Blueprint $table)
		{
			$table->increments('id');

			//Descripcion del rechazo de propuesta
			$table->json('content');
			$table->boolean('active');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('semester_planning');
	}

}
