<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreapprovedUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::create('preapproved_users', function(Blueprint $table)
		// {
		// 	$table->integer('id')->unsigned()->index();
		// 	$table->string('email');
		// 	$table->string('name');
		// 	$table->string('lastname');
		// 	$table->boolean('activated')->default('false');
		// 	$table->timestamps();
		// });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists('contest_results');
		// Schema::drop('preapproved_users');
	}

}
