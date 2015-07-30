<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contests', function(Blueprint $table)
		{
			$table->increments('id');

			//ID del profesor que solicito el concurso
			$table->integer('professor_id')->unsigned()->index();
			$table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');

			//Cantidad de Preparadores 2
			$table->integer('teacher_helpers_2');

			//Cantidad de Preparadores 1
			$table->integer('teacher_helpers_1');

			$table->integer('teacher_assistants');

			/*
				Estado de la solicitud
				1 = Solicitud enviada
				2 = Solicitud aceptada
				3 = Solicitud rechazada
				4 = Concurso finalizado
				5 = Concurso descartado
			*/

			$table->integer('status');
			$table->timestamps();
		});

		Schema::create('contest_course', function(Blueprint $table)
		{
			$table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
			$table->integer('contest_id')->unsigned()->index();
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('center_contest', function(Blueprint $table)
		{
			$table->integer('center_id')->unsigned()->index();
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
			$table->integer('contest_id')->unsigned()->index();
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('preapproved_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unique('id');
			$table->integer('personal_id')->unsigned()->index();
			$table->string('email');
			$table->string('name');
			$table->string('lastname');
			$table->boolean('activated')->default('false');
			$table->string('type');
			$table->integer('contest_id')->unsigned()->index();
			$table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('contest_results', function(Blueprint $table)
		{
			$table->integer('contest_id')->unsigned()->index();
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade');

			//Usuario preaprobado
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('preapproved_users')->onDelete('cascade');

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
		Schema::drop('contest_course');
		Schema::drop('center_contest');
		Schema::drop('contest_results');
		Schema::drop('preapproved_users');
		Schema::drop('contests');
	}

}
