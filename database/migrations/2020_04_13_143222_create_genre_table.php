<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGenreTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('genre', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 50);
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
		Schema::drop('genre');
	}

}
