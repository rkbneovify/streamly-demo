<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChannelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('channel', function(Blueprint $table)
		{
			$table->foreign('language_id', 'channel_ibfk_1')->references('id')->on('language')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('genre_id', 'channel_ibfk_2')->references('id')->on('genre')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('channel', function(Blueprint $table)
		{
			$table->dropForeign('channel_ibfk_1');
			$table->dropForeign('channel_ibfk_2');
		});
	}

}
