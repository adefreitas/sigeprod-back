<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration {

	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();

			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->string('activity');
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::drop('logs');
	}

}
