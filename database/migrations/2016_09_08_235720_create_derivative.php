<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDerivative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('derivative', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('derivativeID');
            $table->string('derivative',50)->nullable();
            $table->string('prefix',4)->nullable();
            $table->string('suffix',4)->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->string('description',200)->nullable();
            $table->string('closed',3)->nullable();
            $table->string('lastUpdate',30)->nullable();
            $table->integer('creatable')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('derivative');
    }
}
