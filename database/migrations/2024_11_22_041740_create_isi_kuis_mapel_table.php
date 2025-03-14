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
        Schema::create('isi_kuis_matkul', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matkul')->constrained('matkul')->onDelete('cascade');
            $table->foreignId('id_kuis_matkul')->constrained('kuis_matkul')->onDelete('cascade');
            $table->text('isi_kuis');
            $table->integer('skor')->default(10);
            $table->string('A');
            $table->string('B');
            $table->string('C');
            $table->string('D');
            $table->enum('jawaban', ['A', 'B', 'C', 'D']);
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
        Schema::dropIfExists('isi_kuis_mapel');
    }
};