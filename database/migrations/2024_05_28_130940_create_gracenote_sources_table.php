<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGracenoteSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gracenote_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('stationName')->nullable();
            $table->integer('prgSvcId')->nullable()->unique();
            $table->string('callSign')->nullable();
            $table->string('enhancedCallSign')->nullable();
            $table->string('timeZone')->nullable();
            $table->string('url')->nullable();
            $table->longText('assets')->nullable();
            $table->timestamps();
            // Foreign key constraint
            // $table->foreign('prgSvcId')->references('prgSvcId')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gracenote_sources');
    }
}
