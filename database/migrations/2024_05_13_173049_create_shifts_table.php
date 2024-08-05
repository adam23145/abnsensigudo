<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pkl_place_id');
            $table->string('shift_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->foreign('pkl_place_id')->references('id')->on('pkl_places')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
