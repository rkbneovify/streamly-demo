<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGracenoteTable extends Migration
{
    public function up()
    {
        Schema::create('gracenote', function (Blueprint $table) {

		$table->integer('id',8);
		$table->string('StationName',64);
		$table->string('CallSign',32);
		$table->string('Rating',32)->nullable()->default('NULL');
		$table->string('EventType',8)->nullable()->default('NULL');
		$table->integer('EventID',10)->unsigned()->nullable()->default('NULL');
		$table->integer('StartDate',8)->nullable()->default('NULL');
		$table->integer('StartTime',4)->nullable()->default('NULL');
		$table->datetime('StartDateTime')->nullable()->default('NULL');
		$table->integer('RunLenHour',2)->nullable()->default('NULL');
		$table->integer('RunLenMinute',2)->nullable()->default('NULL');
		$table->integer('EventTitle',128)->nullable()->default('NULL');
		$table->string('EventDescription',256)->nullable()->default('NULL');
		$table->string('UnixStartDate',32)->nullable()->default('NULL');
		$table->string('TimeZoneName',64)->nullable()->default('NULL');
		$table->integer('EndDate',11)->nullable()->default('NULL');
		$table->datetime('EndaDateTime')->nullable()->default('NULL');

        });
    }

    public function down()
    {
        Schema::dropIfExists('gracenote');
    }
}