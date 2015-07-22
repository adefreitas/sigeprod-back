<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();

			//Usuario emisor
			$table->integer('creator_id')->unsigned()->index();
			$table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');

			$table->string('creator_role');

			//Usuario receptor
			$table->integer('receptor_id')->unsigned()->index();
			$table->foreign('receptor_id')->references('id')->on('users')->onDelete('cascade');

			//Bandera de leido
			$table->boolean('read');

			//Redireccion, a donde redirige la aplicacion
			$table->string('redirection');

			//Mensaje
			$table->string('message');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
