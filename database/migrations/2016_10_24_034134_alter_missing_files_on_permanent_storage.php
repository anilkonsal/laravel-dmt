<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMissingFilesOnPermanentStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missing_files_on_permanent_storage', function (Blueprint $table) {
            $table->char('acms_mill', 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missing_files_on_permanent_storage', function (Blueprint $table) {
            $table->dropColumn('acms_mill');
        });
    }
}
