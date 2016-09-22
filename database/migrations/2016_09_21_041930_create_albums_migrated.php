<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsMigrated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums_migrated', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->string('digital_id')->nullable();
            $table->string('type', 1);
            $table->boolean('is_migrated');
            $table->timestamps();

            $table->index('item_id');
            $table->index('digital_id');
            $table->index('type');
            $table->index('is_migrated');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums_migrated');
    }
}
