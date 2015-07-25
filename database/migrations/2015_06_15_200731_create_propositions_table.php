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
			$table->string('course_option_1');
			$table->json('mode_option_1');
			$table->string('schedule_1_option_1');
			$table->string('schedule_2_option_1');
			$table->string('course_option_2');
			$table->json('mode_option_2');
			$table->string('schedule_1_option_2');
			$table->string('schedule_2_option_2');
			$table->string('course_option_3');
			$table->json('mode_option_3');
			$table->string('schedule_1_option_3');
			$table->string('schedule_2_option_3');
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
