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
		Schema::create('center_center_coordinator', function(Blueprint $table)
		{
				$table->timestamps();
				$table->integer('center_id')->unsigned()->index();
				$table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
				$table->integer('center_coordinator_id')->unsigned()->index();
				$table->foreign('center_coordinator_id')->references('id')->on('professors')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('center_center_coordinator');
	}

}
