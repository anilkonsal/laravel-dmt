<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemID');
            $table->integer('batchID')->nullable();
            $table->biginteger('sizeInBytes')->nullable();
            $table->string('assetType',20)->nullable();
            $table->string('itemType',50)->nullable();
            $table->string('status',50)->nullable();
            $table->string('ttstatus',50)->nullable();
            $table->integer('lock')->nullable();
            $table->string('dateStatus',30)->nullable();
            $table->string('itemDesc',255)->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->integer('masterServerKey')->nullable();
            $table->string('masterRoot',100)->nullable();
            $table->string('masterFolder',100)->nullable();
            $table->string('masterKey',100)->nullable();
            $table->integer('fromServerKey')->nullable();
            $table->string('fromRoot',50)->nullable();
            $table->string('fromFolder',100)->nullable();
            $table->string('fromKey',100)->nullable();
            $table->string('fromType',5)->nullable();
            $table->string('itemKey',20)->nullable();
            $table->integer('wserverKey')->nullable();
            $table->string('wroot',50)->nullable();
            $table->string('wpath',100)->nullable();
            $table->string('wtype',5)->nullable();
            $table->integer('tserverKey')->nullable();
            $table->string('troot',50)->nullable();
            $table->string('ttype',5)->nullable();
            $table->integer('pserverKey')->nullable();
            $table->string('proot',50)->nullable();
            $table->string('ptype',5)->nullable();
            $table->integer('lserverKey')->nullable();
            $table->string('lroot',50)->nullable();
            $table->string('ltype',5)->nullable();
            $table->string('guid',60)->nullable();
            $table->string('dateCreated',30)->nullable();
            $table->string('collectionType',50)->nullable();
            $table->string('child',500)->nullable();
            $table->string('parent',255)->nullable();
            $table->string('series',255)->nullable();
            $table->string('errDescription',255)->nullable();
            $table->string('TAG',50)->nullable();
            $table->string('workorder',50)->nullable();
            $table->integer('ingestionID');
            $table->string('userName', 30)->nullable();
            $table->string('closed', 5)->nullable();
            $table->string('lastupdate', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
}
