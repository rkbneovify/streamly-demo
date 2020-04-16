<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
			$table->integer('id', true);
			$table->integer('role_id')->index('role_id');
			$table->string('email');
			$table->string('password', 255);
			$table->string('first_name', 100);
			$table->string('last_name',100);
			$table->string('phone', 20);
			$table->string('address');
			$table->string('address2');
			$table->string('postal_code', 10);
			$table->string('state', 50);
			$table->string('country', 50);
			$table->char('active', 1);
			$table->dateTime('created_on');
			$table->dateTime('modified_on');
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
