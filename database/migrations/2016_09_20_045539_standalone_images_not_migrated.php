<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StandaloneImagesNotMigrated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql=<<<EOSP
        DROP PROCEDURE IF EXISTS `standalone_images_not_migrated`;
        CREATE  PROCEDURE standalone_images_not_migrated()
BEGIN
	DECLARE done INT DEFAULT FALSE;
    DECLARE mCount, cCount, hCount, lCount, pCount, tCount INT DEFAULT 0;
    DECLARE totalMasterCount, totalCoMasterCount,totalHiResCount, totalStdResCount, totalPreviewCount, totalThumbnailCount INT DEFAULT 0;
    DECLARE mastersCount,comastersCount, hiresCount, stdresCount, previewCount, thumbnailCount INT DEFAULT 0;
    DECLARE mRoot, mFolder, mYear, cKey, digitalId, subfolders varchar(100);
    DECLARE yfk varchar(100);


    DECLARE StandAloneImages CURSOR FOR
        select
			masterKey, masterFolder, masterRoot, wpath
		from
			item i
		where itemID not in(
			select
				i.itemID
			from
				item i
			where i.itemID in (
					select c.itemID from item i
					inner join collection c on i.itemID = c.collectionID
					where i.assetType='image' and i.itemType='album'
				)

			and
				i.assetType = 'image'
				and i.itemType='image'
				 and i.status<>'rejected'
		)
		and i.assetType = 'image'
		and i.itemType='image'
		and i.status<>'rejected';




	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;



    select count(id) from item i where itemID not in(
		select
			i.itemID
		from
			item i
		where i.itemID in (
				select c.itemID from item i
				inner join collection c on i.itemID = c.collectionID
				where i.assetType='image' and i.itemType='album'
			)

		and
			i.assetType = 'image'
			and i.itemType='image'
	)
	and i.assetType = 'image'
	and i.itemType='image'
    and i.status<>'rejected'
	and masterRoot like '_MASTER%' into totalMasterCount;




     select count(id) from item i where itemID not in(
		select
			i.itemID
		from
			item i
		where i.itemID in (
				select c.itemID from item i
				inner join collection c on i.itemID = c.collectionID
				where i.assetType='image' and i.itemType='album'
			)

		and
			i.assetType = 'image'
			and i.itemType='image'
	)
	and i.assetType = 'image'
	and i.itemType='image'
    and i.status<>'rejected'
	and fromRoot like '_COMASTER%' into totalCoMasterCount;

     select count(id) from item i where itemID not in(
		select
			i.itemID
		from
			item i
		where i.itemID in (
				select c.itemID from item i
				inner join collection c on i.itemID = c.collectionID
				where i.assetType='image' and i.itemType='album'
			)

		and
			i.assetType = 'image'
			and i.itemType='image'
	)
	and i.assetType = 'image'
	and i.itemType='image'
    and i.status<>'rejected'
	and wroot like '_DAMx%' into totalHiResCount;

	 select count(id) from item i where itemID not in(
		select
			i.itemID
		from
			item i
		where i.itemID in (
				select c.itemID from item i
				inner join collection c on i.itemID = c.collectionID
				where i.assetType='image' and i.itemType='album'
			)

		and
			i.assetType = 'image'
			and i.itemType='image'
	)
	and i.assetType = 'image'
	and i.itemType='image'
    and i.status<>'rejected'
	and lroot like '_DAMl%' into totalStdResCount;

     select count(id) from item i where itemID not in(
		select
			i.itemID
		from
			item i
		where i.itemID in (
				select c.itemID from item i
				inner join collection c on i.itemID = c.collectionID
				where i.assetType='image' and i.itemType='album'
			)

		and
			i.assetType = 'image'
			and i.itemType='image'
	)
	and i.assetType = 'image'
	and i.itemType='image'
    and i.status<>'rejected'
	and proot like '_DAMp%' into totalPreviewCount;

     select count(id) from item i where itemID not in(
		select
			i.itemID
		from
			item i
		where i.itemID in (
				select c.itemID from item i
				inner join collection c on i.itemID = c.collectionID
				where i.assetType='image' and i.itemType='album'
			)

		and
			i.assetType = 'image'
			and i.itemType='image'
	)
	and i.assetType = 'image'
	and i.itemType='image'
    and i.status<>'rejected'
        and troot like '_DAMt%' into totalThumbnailCount;



    OPEN StandAloneImages;

    read_loop: LOOP
		FETCH StandAloneImages into digitalId, mFolder, mRoot, subfolders;
        IF done = 1 THEN
			LEAVE read_loop;
		END IF;


        SET mYear = replace(mRoot, '_MASTER\\','');

        SET yfk = concat('/',mYear, '/',mFolder,'/',digitalId);



        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/master',yfk,'u.tif') or file_path = concat('/permanent_storage/legacy/master',yfk,'u.jpg') into mCount;
        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/comaster',yfk,'.tif') or file_path = concat('/permanent_storage/legacy/comaster',yfk,'.jpg') into cCount;
        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/derivatives/highres/image/',subfolders,'/',digitalId,'h.jpg') into hCount;
        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/derivatives/screenres/image/',subfolders,'/',digitalId,'r.jpg') into lCount;



        if mCount > 1 then
			SET mCount = 1;
		END IF;

        if cCount > 1 then
			SET cCount = 1;
		END IF;
        if hCount > 1 then
			SET hCount = 1;
		END IF;
        if lCount > 1 then
			SET lCount = 1;
		END IF;



	   SET mastersCount = mastersCount + mCount;
	   SET comastersCount = comastersCount + cCount;
	   SET hiresCount = hiresCount + hCount;
	   SET stdresCount = stdresCount + lCount;


       #insert into temp_debug (`mkey`, `mCount`, `cCount`, `hCount`, `lCount`, `pCount`, `tCount`) values (mKey, mCount, cCount, hCount, lCount, pCount, tCount);

    END LOOP;

    close StandAloneImages;

    SET mastersCount = totalMasterCount - mastersCount;
    SET comastersCount = totalCoMasterCount - comastersCount;
    SET hiresCount = totalHiResCount - hiresCount;
    SET stdresCount = totalStdResCount - stdresCount;
    SET previewCount = totalPreviewCount - previewCount;
    SET thumbnailCount = totalThumbnailCount - thumbnailCount;

    select mastersCount, comastersCount, hiresCount, stdresCount, previewCount, thumbnailCount, totalMasterCount, totalCoMasterCount, totalHiResCount, totalStdResCount, totalPreviewCount, totalThumbnailCount;

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
        \DB::unprepared('DROP PROCEDURE IF EXISTS `standalone_images_not_migrated`;');
    }
}
