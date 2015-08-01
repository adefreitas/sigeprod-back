<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('courses', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name');
            $table->integer('credits');
            $table->string('type');
            $table->integer('semester');
            $table->integer('center_id')->nullable()->references('id')->on('centers')->onDelete('cascade');
            $table->boolean('active');
            $table->timestamps();
		});

        Schema::create('course_prelate', function(Blueprint $table){
            $table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->integer('prelate_id')->unsigned()->index();
            $table->foreign('prelate_id')->references('id')->on('courses')->onDelete('cascade');
        });

        Schema::create('course_professor', function(Blueprint $table){
            $table->integer('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->integer('professor_id')->unsigned()->index();
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
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
		Schema::drop('course_prelate');
    	Schema::drop('course_professor');
		Schema::drop('courses');
	}

}
