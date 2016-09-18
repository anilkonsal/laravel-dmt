<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRosettaMigrated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosetta_migrated', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_pid',20)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('file_name', 50)->nullable();

            $table->index('file_pid');
            $table->index('file_path');
            $table->index('file_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rosetta_migrated');
    }
}
