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
        Schema::create('pertanyaan_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ujian')->constrained('ujian')->onDelete('cascade');
            $table->text('text_pertanyaan');
            $table->enum('jenis_pertanyaan', ['pilihan_ganda', 'essay']);
            $table->json('pilihan')->nullable();
            $table->string('jawaban_benar')->nullable();
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
        Schema::dropIfExists('pertanyaan_ujian');
    }
};
