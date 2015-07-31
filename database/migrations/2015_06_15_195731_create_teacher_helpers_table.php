<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherHelpersTable extends Migration {

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
		
		Schema::create('teacher_helpers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('available');
			$table->boolean('reserved')->default('false');
			$table->integer('reserved_for')->unsigned()->nullable()->default(null);
			$table->foreign('reserved_for')->references('id')->on('contests')->onDelete('cascade');
			$table->enum('type', [1, 2, 3]);
			$table->timestamps();
		});

		Schema::create('teacher_helpers_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('teacher_helper_id')->unsigned()->index();
			$table->foreign('teacher_helper_id')->references('id')->on('teacher_helpers')->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->json('available_schedule')->nullable()->default(null);
			$table->boolean('active')->default('true');
			$table->timestamps();
		});

		Schema::create('courses_teacher_helpers', function(Blueprint $table)
		{
			$table->integer('course_id')->unsigned()->index();
			$table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
			$table->integer('helper_id')->unsigned()->index();
			$table->foreign('helper_id')->references('id')->on('teacher_helpers_users')->onDelete('cascade');
			$table->boolean('active')->default('true');
			$table->timestamps();
		});

		Schema::create('centers_teacher_helpers', function(Blueprint $table)
		{
			$table->integer('center_id')->unsigned()->index();
			$table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
			$table->integer('helper_id')->unsigned()->index();
			$table->foreign('helper_id')->references('id')->on('teacher_helpers_users')->onDelete('cascade');
			$table->boolean('active')->default('true');
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
		Schema::drop('courses_teacher_helpers');
		Schema::drop('centers_teacher_helpers');
		Schema::drop('teacher_helpers_users');
		Schema::drop('teacher_helpers');
		Schema::drop('contests');
	}

}
