<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_slider', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->id();
            $table->integer('channel_id');
            $table->string('program_name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('background_image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Define foreign key constraint
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
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
        Schema::dropIfExists('channel_slider');
    }
}
