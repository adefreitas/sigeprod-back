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
			$table->boolean('mode_option_1_coordinator');
			$table->boolean('mode_option_1_theory');
			$table->boolean('mode_option_1_practice');
			$table->boolean('mode_option_1_laboratory');
			$table->string('schedule_1_option_1');
			$table->string('schedule_2_option_1');
			$table->string('course_option_2');
			$table->boolean('mode_option_2_coordinator');
			$table->boolean('mode_option_2_theory');
			$table->boolean('mode_option_2_practice');
			$table->boolean('mode_option_2_laboratory');
			$table->string('schedule_1_option_2');
			$table->string('schedule_2_option_2');
			$table->string('course_option_3');
			$table->boolean('mode_option_3_coordinator');
			$table->boolean('mode_option_3_theory');
			$table->boolean('mode_option_3_practice');
			$table->boolean('mode_option_3_laboratory');	
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
