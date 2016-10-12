<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissingFilesOnPermanentStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_files_on_permanent_storage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->string('file_path', 255);
            $table->string('representation',1);
            $table->string('album_standalone', 1);

            $table->index('item_id');
            $table->index('file_path');
            $table->index('representation');
            $table->index('album_standalone');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missing_files_on_permanent_storage');
    }
}
