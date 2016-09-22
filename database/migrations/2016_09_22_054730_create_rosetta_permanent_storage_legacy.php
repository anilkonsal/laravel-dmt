<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRosettaPermanentStorageLegacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosetta_permanent_storage_legacy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_path', 255);
            $table->string('file_name');

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
        Schema::dropIfExists('rosetta_permanent_storage_legacy');
    }
}
