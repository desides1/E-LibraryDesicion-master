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
        Schema::create('borroweds', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Dosen', 'Mahasiswa', 'Karyawan']);
            $table->string('name');
            $table->string('number_id');
            $table->string('major');
            $table->timestamps();

            $table->index('name');
            $table->index('number_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borroweds');
    }
};
