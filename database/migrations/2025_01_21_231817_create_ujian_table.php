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
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matkul')->constrained('matkul')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->enum('jenis', ['UTS', 'UAS']);
            $table->date('tanggal_ujian');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
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
        Schema::dropIfExists('ujian');
    }
};
