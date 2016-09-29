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
        CREATE PROCEDURE `standalone_images_not_migrated`()
    BEGIN
	DECLARE done INT DEFAULT FALSE;
    DECLARE mCount, cCount, hCount, lCount, pCount, tCount INT DEFAULT 0;
    DECLARE totalMasterCountAcms, totalCoMasterCountAcms,totalHiResCountAcms, totalStdResCountAcms, totalPreviewCountAcms, totalThumbnailCountAcms INT DEFAULT 0;
    DECLARE totalMasterCountMill, totalCoMasterCountMill,totalHiResCountMill, totalStdResCountMill, totalPreviewCountMill, totalThumbnailCountMill INT DEFAULT 0;
    DECLARE mastersCountAcms,comastersCountAcms, hiresCountAcms, stdresCountAcms, previewCountAcms, thumbnailCountAcms INT DEFAULT 0;
    DECLARE mastersCountMill,comastersCountMill, hiresCountMill, stdresCountMill, previewCountMill, thumbnailCountMill INT DEFAULT 0;
    DECLARE mRoot, mFolder, mYear, cKey, digitalId, subfolders varchar(100);
    DECLARE aMType varchar(1);
    DECLARE yfk varchar(100);


    DECLARE StandAloneImages CURSOR FOR
        select
			masterKey, masterFolder, masterRoot, wpath, acms_mill
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
    and i.acms_mill is null
	and masterRoot like '_MASTER%' into totalMasterCountAcms;

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
    and i.acms_mill = 'm'
	and masterRoot like '_MASTER%' into totalMasterCountMill;




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
    and i.acms_mill is null
	and fromRoot like '_COMASTER%' into totalCoMasterCountAcms;

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
    and i.acms_mill = 'm'
	and fromRoot like '_COMASTER%' into totalCoMasterCountMill;

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
    and i.acms_mill is null
	and wroot like '_DAMx%' into totalHiResCountAcms;

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
    and i.acms_mill = 'm'
	and wroot like '_DAMx%' into totalHiResCountMill;


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
    and i.acms_mill is null
	and lroot like '_DAMl%' into totalStdResCountAcms;

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
    and i.acms_mill = 'm'
	and lroot like '_DAMl%' into totalStdResCountMill;


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
    and i.acms_mill is null
	and proot like '_DAMp%' into totalPreviewCountAcms;

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
    and i.acms_mill = 'm'
	and proot like '_DAMp%' into totalPreviewCountMill;



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
    and i.acms_mill is null
	and troot like '_DAMt%' into totalThumbnailCountAcms;

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
    and i.acms_mill = 'm'
	and troot like '_DAMt%' into totalThumbnailCountMill;




    OPEN StandAloneImages;

    read_loop: LOOP
		FETCH StandAloneImages into digitalId, mFolder, mRoot, subfolders, aMType;
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


       IF (aMType = 'm') THEN
			SET mastersCountMill = mastersCountMill + mCount;
            SET comastersCountMill = comastersCountMill + cCount;
            SET hiresCountMill = hiresCountMill + hCount;
            SET stdresCountMill = stdresCountMill + lCount;
		ELSE
			SET mastersCountAcms = mastersCountAcms + mCount;
            SET comastersCountAcms = comastersCountAcms + cCount;
            SET hiresCountAcms = hiresCountAcms + hCount;
            SET stdresCountAcms = stdresCountAcms + lCount;
		END IF;



       #insert into temp_debug (`mkey`, `mCount`, `cCount`, `hCount`, `lCount`, `pCount`, `tCount`) values (mKey, mCount, cCount, hCount, lCount, pCount, tCount);

    END LOOP;

    close StandAloneImages;

    SET mastersCountAcms = totalMasterCountAcms - mastersCountAcms;
    SET mastersCountMill = totalMasterCountMill - mastersCountMill;
    SET comastersCountAcms = totalCoMasterCountAcms - comastersCountAcms;
    SET comastersCountMill = totalCoMasterCountMill - comastersCountMill;
    SET hiresCountAcms = totalHiResCountAcms - hiresCountAcms;
    SET hiresCountMill = totalHiResCountMill - hiresCountMill;
    SET stdresCountAcms = totalStdResCountAcms - stdresCountAcms;
    SET stdresCountMill = totalStdResCountMill - stdresCountMill;
    SET previewCountAcms = totalPreviewCountAcms - previewCountAcms;
    SET previewCountMill = totalPreviewCountMill - previewCountMill;
    SET thumbnailCountAcms = totalThumbnailCountAcms - thumbnailCountAcms;
    SET thumbnailCountMill = totalThumbnailCountMill - thumbnailCountMill;

    select
		mastersCountAcms,
        mastersCountMill,
        comastersCountAcms,
        comastersCountMill,
        hiresCountAcms,
        hiresCountMill,
        stdresCountAcms,
        stdresCountMill,
        previewCountAcms,
        previewCountMill,
        thumbnailCountAcms,
        thumbnailCountMill,

        totalMasterCountAcms,
        totalMasterCountMill,
        totalCoMasterCountAcms,
        totalCoMasterCountMill,
        totalHiResCountAcms,
        totalHiResCountMill,
        totalStdResCountAcms,
        totalStdResCountMill,
        totalPreviewCountAcms,
        totalPreviewCountMill,
        totalThumbnailCountAcms,
        totalThumbnailCountMill;

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
