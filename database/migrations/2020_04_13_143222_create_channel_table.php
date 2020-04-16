<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('channel', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('language_id')->index('language_id');
			$table->integer('genre_id')->index('genre_id');
			$table->string('title');
			$table->string('description');
			$table->integer('release_year');
			$table->integer('length');
			$table->integer('rating');
			$table->string('stream_url');
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
		Schema::drop('channel');
	}

}
