<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpAlbumsNotMigrated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql =<<<EOSP
        DROP PROCEDURE IF EXISTS 'albums_not_migrated';
        CREATE PROCEDURE `albums_not_migrated`()
BEGIN
	DECLARE done INT DEFAULT FALSE;
    DECLARE mCount, aCount, totalAlbumsCount, albumsMigrated, albumsNotMigrated INT DEFAULT 0;
    DECLARE digitalId varchar(20);


    DECLARE Albums CURSOR FOR select
		 fromKey
	from
		item i
	Where
		i.assetType = 'image'
		and i.itemType='album'
        and i.status<>'rejected';


	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;


    select
		 count(id)
	from
		item i
	Where
		i.assetType = 'image'
		and i.itemType='album'
        and i.status<>'rejected' into totalAlbumsCount;

    OPEN Albums;

    read_loop: LOOP
		FETCH Albums into digitalId;
        IF done = 1 THEN
			LEAVE read_loop;
		END IF;

        SELECT count(id) from rosetta_migrated where file_name like concat(digitalId,'%.%') into mCount;

        if mCount > 1 then
			SET mCount = 1;
		END IF;

	   SET albumsMigrated = albumsMigrated + mCount;

    END LOOP;

    close Albums;

    SET albumsNotMigrated = totalAlbumsCount - albumsMigrated;

    select totalAlbumsCount, albumsMigrated, albumsNotMigrated;

END
EOSP;

    \DB::unprepared($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "DROP PROCEDURE IF EXISTS albums_not_migrated";
        \DB::unprepared($sql);
    }
}
