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
			$table->integer('professor_id')->nullable()->references('id')->on('professors')->onDelete('cascade');
			$table->string('course_option_1')->nullable();
			$table->enum('mode_option_1', array('coordination', 'theory', 'practice', 'laboratory'))->nullable();
			$table->string('schedule_1_option_1')->nullable();
			$table->string('schedule_2_option_1')->nullable();
			$table->string('course_option_2')->nullable();
			$table->enum('mode_option_2', array('coordination', 'theory', 'practice', 'laboratory'))->nullable();
			$table->string('schedule_1_option_2')->nullable();
			$table->string('schedule_2_option_2')->nullable();
			$table->string('course_option_3')->nullable();
			$table->enum('mode_option_3', array('coordination', 'theory', 'practice', 'laboratory'))->nullable();
			$table->string('schedule_1_option_3')->nullable();
			$table->string('schedule_2_option_3')->nullable();
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
