<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcmsLinksInMillenium extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acms_links_in_millenium', function (Blueprint $table) {
            $table->increments('id');
            $table->string('record_id', 20);
            $table->string('lib_aus_no', 20)->nullable();
            $table->string('title');
            $table->string('url',255);
            $table->integer('item_id');
            $table->string('file_name');

            $table->index('url');
            $table->index('item_id');
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
        Schema::table('acms_links_in_millenium', function (Blueprint $table) {
            //
        });
    }
}
