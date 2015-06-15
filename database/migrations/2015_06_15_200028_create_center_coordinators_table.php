<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCenterCoordinatorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('center_coordinators', function(Blueprint $table)
		{
            $table->integer('center_id')->unsigned()->index();
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
			$table->increments('id');
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
		Schema::drop('center_coordinators');
	}

}
