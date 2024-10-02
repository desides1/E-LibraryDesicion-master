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
        Schema::create('purchase_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('publisher_id')->constrained('publishers');
            $table->foreignId('major_id')->constrained('majors');
            $table->bigInteger('book_price');
            $table->integer('book_quantity');
            $table->bigInteger('book_result');
            $table->date('purchase_date');
            $table->enum('status', ['Proses', 'Terealisasi']);
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
        Schema::dropIfExists('purchase_books');
    }
};
