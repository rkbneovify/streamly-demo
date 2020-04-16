<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionTableTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('session_table', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('users_id')->index('users_id');
			$table->string('access_token');
			$table->string('user_ip', 50);
			$table->dateTime('logintime');
			$table->string('mac_address', 50);
			$table->integer('connected_devices')->unsigned();
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
		Schema::drop('session_table');
	}

}
