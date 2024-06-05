<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGracenoteScheduleProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gracenote_schedule_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('prgSvcId')->nullable();
            $table->date('date')->nullable();
            $table->string('TMSId')->nullable();
            $table->string('rootId')->nullable();
            $table->time('time')->nullable();
            $table->string('duration')->nullable();
            $table->integer('start')->nullable();
            $table->integer('end')->nullable();
            $table->string('quals')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->longText('assets')->nullable();
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('prgSvcId')->references('prgSvcId')->on('gracenote_sources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gracenote_schedule_program');
    }
}
