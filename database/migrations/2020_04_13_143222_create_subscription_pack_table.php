<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_pack', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('name');
			$table->integer('duration')->unsigned();
			$table->decimal('rate', 10);
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
		Schema::drop('subscription_pack');
	}

}
