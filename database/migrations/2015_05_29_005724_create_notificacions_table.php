<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notificaciones', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('emisor_id')->unsigned()->index();
            $table->foreign('emisor_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('referencia'); //Objeto al que hace referencia (Planificacion, propuesta, etc.)
            $table->string('enlace'); //Enlace al que redireccionara
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
		Schema::drop('notificaciones');
	}

}
