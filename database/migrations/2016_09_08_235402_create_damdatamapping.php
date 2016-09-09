<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDamdatamapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damdatamapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('damdatamappingid');
            $table->string('damfields',10)->nullable();
            $table->string('acmsfields',10)->nullable();
            $table->string('damfieldlabel',100)->nullable();
            $table->string('acmsfieldlabel',100)->nullable();
            $table->string('datetimestamp',30)->nullable();
            $table->integer('acms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('damdatamapping');
    }
}
