<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRejectionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rejections', function(Blueprint $table)
		{
			$table->increments('id');

			//Descripcion del rechazo de propuesta
			$table->longText('description');

			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			//Enlazando descripcion del rechazo a la propuesta
			$table->integer('proposition_id')->unsigned()->index();
			$table->foreign('proposition_id')->references('id')->on('propositions')->onDelete('cascade');

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
		Schema::drop('rejections');
	}

}
