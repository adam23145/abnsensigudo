<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->double('check_in_latitude')->nullable(); // Latitude saat check-in
            $table->double('check_in_longitude')->nullable(); // Longitude saat check-in
            $table->double('check_out_latitude')->nullable(); // Latitude saat check-out
            $table->double('check_out_longitude')->nullable(); // Longitude saat check-out
            $table->date('date');
            $table->unsignedBigInteger('guru_pembimbing');
            $table->unsignedBigInteger('pkl_places');
            $table->unsignedBigInteger('status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('guru_pembimbing')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('pkl_places')->references('id')->on('pkl_places')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance');
    }
}
