<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseCoordinatorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::create('course_coordinators', function(Blueprint $table){
		// 	$table->increments('id');
		// 	$table->integer('professors_id')->unsigned()->index();
		// 	$table->foreign('professors_id')->references('id')->on('professors')->onDelete('cascade');
		// 	$table->timestamps();
		// });

		Schema::create('course_course_coordinator', function(Blueprint $table)
		{
            $table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
			$table->integer('professor_id')->unsigned()->index();
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
			$table->timestamps();
		});

		// Schema::create('course_coordinator_professor', function(Blueprint $table){
		// 	$table->integer('professor_id')->unsigned()->index();
        //     $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
		// 	$table->integer('course_coordinator_id')->unsigned()->index();
        //     $table->foreign('course_coordinator_id')->references('id')->on('course_coordinators')->onDelete('cascade');
		// 	$table->timestamps();
		// });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('course_course_coordinator');
		// Schema::drop('course_coordinator_professor');
		// Schema::drop('course_coordinators');
	}

}
