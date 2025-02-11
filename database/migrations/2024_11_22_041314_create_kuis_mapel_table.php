<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuis_matkul', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matkul')->constrained('matkul')->onDelete('cascade');
            $table->foreignId('id_materi_matkul_before')->constrained('materi_matkul')->onDelete('cascade');
            $table->foreignId('id_materi_matkul_after')->constrained('materi_matkul')->onDelete('cascade');
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
        Schema::dropIfExists('kuis_mapel');
    }
};