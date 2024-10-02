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
        Schema::create('budget_books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price');
            $table->bigInteger('ppn');
            $table->year('year')->unique();
            $table->timestamps();

            $table->index('year');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_books');
    }
};
