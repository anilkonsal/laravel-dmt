<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemType', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemTypeID');
            $table->string('assetType',20)->nullable();
            $table->string('itemType',20)->nullable();
            $table->string('description',200)->nullable();
            $table->string('defaultShowPage',200)->nullable();
            $table->string('defaultEditPage',200)->nullable();
            $table->string('closed',5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itemType');
    }
}
