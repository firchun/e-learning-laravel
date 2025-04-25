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
        Schema::table('matkul', function (Blueprint $table) {
            $table->enum('semester', ['Ganjil', 'Genap'])->after('id')->default('Ganjil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matkul', function (Blueprint $table) {
            //
        });
    }
};