<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIzinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('izins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('guru_pembimbing_id');
            $table->unsignedBigInteger('pkl_place_id');
            $table->date('date');
            $table->text('description');
            $table->string('photo_path');
            $table->unsignedBigInteger('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('guru_pembimbing_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('pkl_place_id')->references('id')->on('pkl_places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('izins');
    }
}
