<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpMilleniumAlbumsNotMigrated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql =<<<EOSP
        DROP PROCEDURE IF EXISTS `millenium_albums_not_migrated`;
        CREATE PROCEDURE `millenium_albums_not_migrated`()
BEGIN

	DECLARE done, ndone INT DEFAULT FALSE;
	DECLARE mCount, albumsMigrated, totalAlbumsCount, albumsNotMigratedCount,almigrated INT DEFAULT 0;
	DECLARE digitalId, iid, adigitalId varchar(20);
	DECLARE AlbumImages CURSOR FOR
    select
	 co.collectionID, fromKey
	from
		item i
		inner join collection co on co.itemID = i.itemID

	where i.itemID in (
			select c.itemID from item i
			inner join collection c on i.itemID = c.collectionID
			where i.assetType='image' and i.itemType='album'
			and c.collectionID in (
				select
					itemID
				from
					item
				where
					acms_mill = 'm'
                    and assetType='image' and itemType='album'
			)
		)
	and i.assetType = 'image'
	and i.itemType='image';

	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;


    select
		 count(id)
	from
		item
    where
		acms_mill = 'm'
        and assetType='image'
        and i.itemType='album'
		and status <> 'rejected' into totalAlbumsCount;

    delete from albums_migrated where type = 'm';

    OPEN AlbumImages;

    read_loop: LOOP
		FETCH AlbumImages into iid, digitalId;
        IF done = 1 THEN
			LEAVE read_loop;
		END IF;



		SELECT count(id) from rosetta_migrated where
		file_name = concat(digitalId,'.tif')
		or file_name = concat(digitalId,'.jpg')
		or file_name = concat(digitalId,'u.tif')
		or file_name = concat(digitalId,'u.jpg')
		or file_name = concat(digitalId,'h.jpg')
		or file_name = concat(digitalId,'r.jpg')
		into mCount;

        select fromKey from item where itemID = iid into adigitalId;

		if mCount > 1 then
			SET mCount = 1;
		END IF;

        IF (mCount = 1) THEN
			select count(id) from albums_migrated where item_id = iid into almigrated;
			IF (almigrated < 1) THEN
				insert into albums_migrated (item_id, digital_id, type, is_migrated) values (iid, adigitalId, 'm', mCount);
                SET albumsMigrated = albumsMigrated + mCount;
            END IF;
		END IF;

    END LOOP;

    close AlbumImages;

    SET albumsNotMigratedCount = totalAlbumsCount - albumsMigrated;

    select totalAlbumsCount, albumsMigrated, albumsNotMigratedCount ;

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
        $sql = "DROP PROCEDURE IF EXISTS `millenium_albums_not_migrated`";
        \DB::unprepared($sql);
    }
}
