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

		Schema::create('center_center_coordinator', function(Blueprint $table){
			$table->integer('center_id')->unsigned()->index();
			$table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
			$table->integer('professor_id')->unsigned()->index();
			$table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
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
		Schema::drop('center_center_coordinator');
	}

}
