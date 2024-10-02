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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('category_id')->constrained('category_books');
            $table->foreignId('publisher_id')->nullable();
            $table->string('title');
            $table->string('publisher')->nullable();
            $table->string('isbn');
            $table->string('image');
            $table->year('publication_date');
            $table->enum('type_book', ['E-Book', 'Cetak']);
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Terealisasi']);
            $table->string('author');
            $table->bigInteger('price')->nullable();
            $table->bigInteger('available_stock');
            $table->longText('abstract');
            $table->timestamps();

            $table->index('title');
            $table->index('isbn');
            $table->index('publication_date');
            $table->index('author');
            $table->index('price');
            $table->index('publisher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
