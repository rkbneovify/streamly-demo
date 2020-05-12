<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipcodesTable extends Migration
{
    public function up()
    {
        Schema::create('zipcodes', function (Blueprint $table) {

		$table->integer('id',10);
		$table->char('zipcode',5)->nullable()->default('NULL');
		$table->string('city',50)->nullable()->default('NULL');
		$table->string('state',50)->nullable()->default('NULL');
		$table->char('state_abbr',2)->nullable()->default('NULL');
		$table->string('county_area',50)->nullable()->default('NULL');
		$table->char('code',3)->nullable()->default('NULL');
		->nullable()->default('NULL');
		->nullable()->default('NULL');
		$table->tinyInteger('some_field',4)->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('zipcodes');
    }
}