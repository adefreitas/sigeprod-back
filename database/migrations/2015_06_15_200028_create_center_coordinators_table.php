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

		Schema::create('center_coordinators', function(Blueprint $table){
			$table->increments('id');
			$table->integer('professors_id')->unsigned()->index();
			$table->foreign('professors_id')->references('id')->on('professors')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('center_center_coordinator', function(Blueprint $table){
			$table->integer('center_id')->unsigned()->index();
			$table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
			$table->integer('center_coordinator_id')->unsigned()->index();
			$table->foreign('center_coordinator_id')->references('id')->on('professors')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('center_coordinator_professor', function(Blueprint $table){
			$table->integer('professor_id')->unsigned()->index();
            $table->foreign('professor_id')->references('id')->on('professors')->onDelete('cascade');
			$table->integer('center_coordinator_id')->unsigned()->index();
            $table->foreign('center_coordinator_id')->references('id')->on('center_coordinators')->onDelete('cascade');
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
		Schema::drop('center_coordinator_professor');
		Schema::drop('center_center_coordinator');
		Schema::drop('center_coordinators');
	}

}
