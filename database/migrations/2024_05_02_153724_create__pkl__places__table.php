<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePklPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pkl_places', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('nib_siup')->nullable(); // opsional
            $table->string('alamat_pkl');
            $table->unsignedBigInteger('guru_pembimbing_id'); // Foreign key for the teacher
            $table->string('lokasi_pkl_lat');
            $table->string('lokasi_pkl_long');
            $table->string('hari_kerja_efektif');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('guru_pembimbing_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pkl_places');
    }
}
