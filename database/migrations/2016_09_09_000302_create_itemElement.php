<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemElement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemElement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemElementID');
            $table->integer('metaID')->nullable();
            $table->string('picman',100)->nullable();
            $table->string('itemcolumn',5)->nullable();
            $table->integer('maxlen')->nullable();
            $table->string('dateType',50)->nullable();
            $table->string('elementType',50)->nullable();
            $table->string('elementSize',50)->nullable();
            $table->string('itemType',50)->nullable();
            $table->string('ttName',50)->nullable();
            $table->string('ttColumn',50)->nullable();
            $table->string('itemTypeName',50)->nullable();
            $table->string('itemTypeDesc',50)->nullable();
            $table->string('itemTypeTitle',50)->nullable();
            $table->string('titleACMS',100)->nullable();
            $table->string('titleCollection',100)->nullable();
            $table->string('example',300)->nullable();
            $table->longtext('strDefault')->nullable();
            $table->string('indexed',5)->nullable();
            $table->string('searchAdvanced',5)->nullable();
            $table->string('showedit',5)->nullable();
            $table->string('pageshow',50)->nullable();
            $table->string('ingestion',5);
            $table->string('roleName',255)->nullable();
            $table->integer('listorder')->nullable();
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
        Schema::dropIfExists('itemElement');
    }
}
