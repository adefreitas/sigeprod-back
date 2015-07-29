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

		Schema::create('teacher_helpers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('available');
			$table->enum('type', [1, 2]);
			$table->timestamps();
		});

		Schema::create('teacher_helpers_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('teacher_helper_id')->unsigned()->index();
			$table->foreign('teacher_helper_id')->references('id')->on('teacher_helpers')->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->json('available_schedule')->nullable();
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
	}

}
