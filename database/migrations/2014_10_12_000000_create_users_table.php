<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
    		$table->string('lastname');
			$table->string('email')->unique();
			$table->string('alternate_email')->nullable();
			$table->string('password', 60);
			$table->string('local_phone')->nullable();
			$table->string('cell_phone')->nullable();
			$table->string('state')->nullable();
			$table->string('municipality')->nullable();
			$table->string('address')->nullable();
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
