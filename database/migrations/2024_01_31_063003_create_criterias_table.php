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
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->decimal('weight', 5, 2);
            $table->enum('type', ['Benefit', 'Cost'])->default('Benefit');
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->enum('sub_criterias', ['Iya', 'Tidak']);
            $table->timestamps();

            $table->index(['code', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('criterias');
    }
};
