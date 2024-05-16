<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewGcChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_gc_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->id();
            $table->integer('prgSvcId')->nullable();
            $table->string('name', 225)->index();
            $table->string('callSign', 99)->index()->nullable();
            $table->string('stationName', 192)->nullable();
            $table->string('url', 225)->nullable();
            $table->string('dash_url', 255)->nullable();
            $table->string('smooth_url', 255)->nullable();
            $table->string('genres', 200)->nullable()->comment('1=News,2=Comedy,3=Movies,4=Family,5=Kids,6=Sports');
            $table->string('image', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('zip_code', 255)->default('["ALL"]');
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
        Schema::dropIfExists('new_gc_channels');
    }
}
