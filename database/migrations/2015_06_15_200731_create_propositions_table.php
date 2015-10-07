<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propositions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('professor_id')->references('id')->on('professors')->onDelete('cascade');
			$table->json('course_option_1');
			$table->json('mode_option_1');
			$table->json('schedule_option_1');
			$table->json('course_option_2');
			$table->json('mode_option_2');
			$table->json('schedule_option_2');
			$table->json('course_option_3');
			$table->json('mode_option_3');
			$table->json('schedule_option_3');
			$table->string('viewer');
			$table->integer('status');
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
		Schema::drop('propositions');
	}

}
