<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('centers', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name');
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
		Schema::dropIfExists('contests');
		Schema::dropIfExists('contests_observations');
		Schema::drop('centers');
	}

}
