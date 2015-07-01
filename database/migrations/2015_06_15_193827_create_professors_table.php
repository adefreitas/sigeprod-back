<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('professors', function(Blueprint $table)
		{
		    $table->increments('id');
		    $table->string('dedication');
		    $table->integer('center_id')->nullable()->references('id')->on('centers')->onDelete('cascade');
		    $table->string('status');
			$table->integer('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('professors');
	}

}
