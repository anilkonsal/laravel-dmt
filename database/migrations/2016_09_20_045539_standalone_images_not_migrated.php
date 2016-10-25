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
    DECLARE itemId, mCount, cCount, hCount, lCount, pCount, tCount INT DEFAULT 0;
    DECLARE mPSCount, cPSCount, hPSCount, lPSCount INT DEFAULT 0;
    DECLARE mMSCount, cMSCount, hMSCount, lMSCount INT DEFAULT 0;
    DECLARE mFDCountAcms, cFDCountAcms, hFDCountAcms, lFDCountAcms INT DEFAULT 0;
    DECLARE mFDCountMill, cFDCountMill, hFDCountMill, lFDCountMill INT DEFAULT 0;

    DECLARE totalMasterCountAcms, totalCoMasterCountAcms,totalHiResCountAcms, totalStdResCountAcms, totalPreviewCountAcms, totalThumbnailCountAcms INT DEFAULT 0;
    DECLARE totalMasterCountMill, totalCoMasterCountMill,totalHiResCountMill, totalStdResCountMill, totalPreviewCountMill, totalThumbnailCountMill INT DEFAULT 0;
    DECLARE mastersCountAcms,comastersCountAcms, hiresCountAcms, stdresCountAcms, previewCountAcms, thumbnailCountAcms INT DEFAULT 0;
    DECLARE mastersCountMill,comastersCountMill, hiresCountMill, stdresCountMill, previewCountMill, thumbnailCountMill INT DEFAULT 0;
    DECLARE mRoot, mFolder, mYear, cKey, digitalId, subfolders varchar(100);
    DECLARE aMType char(1);
    DECLARE yfk, loweryfk varchar(100);


    DECLARE StandAloneImages CURSOR FOR
        select
			i.itemID, masterKey, masterFolder, masterRoot, wpath, acms_mill
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
		FETCH StandAloneImages into itemId, digitalId, mFolder, mRoot, subfolders, aMType;

        IF done = 1 THEN
			LEAVE read_loop;
		END IF;


        SET mYear = replace(mRoot, '_MASTER\\','');

        SET yfk = concat('/',mYear, '/',mFolder,'/',digitalId);
        SET loweryfk = concat('/',mYear, '/',mFolder,'/', lower(digitalId));


        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/master',yfk,'u.tif')
													or file_path = concat('/permanent_storage/legacy/master',yfk,'u.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/master',yfk,'_m.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/master',yfk,'_m.tif')
                                                    into mCount;



        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/comaster',yfk,'.tif')
													or file_path = concat('/permanent_storage/legacy/comaster',yfk,'.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/comaster',yfk,'_c.tif')
                                                    or file_path = concat('/permanent_storage/legacy/comaster',yfk,'_c.jpg')
                                                    into cCount;

        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/derivatives/highres/image/',subfolders,'/',digitalId,'h.jpg') into hCount;

        SELECT count(id) from rosetta_migrated where file_path = concat('/permanent_storage/legacy/derivatives/screenres/image/',subfolders,'/',digitalId,'r.jpg') into lCount;



        IF mCount > 1 THEN
			SET mCount = 1;
		ELSEIF mCount = 0 THEN
			SELECT count(id) from rosetta_permanent_storage_legacy where file_path = concat('/permanent_storage/legacy/master',yfk,'u.tif')
													or file_path = concat('/permanent_storage/legacy/master',yfk,'u.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/master',yfk,'_m.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/master',yfk,'_m.tif')
                                                    or lower_file_path = concat('/permanent_storage/legacy/master',loweryfk,'u.jpg')
                                                    or lower_file_path = concat('/permanent_storage/legacy/master',loweryfk,'u.tif')
                                                    into mPSCount;

			IF mPSCount = 0 THEN
				Select count(id) from missing_files_on_permanent_storage where file_path = concat('/permanent_storage/legacy/master',yfk,'u.tif') into mMSCount;
                IF mMSCount = 0 THEN
					insert into missing_files_on_permanent_storage (item_id, file_path, representation, album_standalone, acms_mill) values (itemId, concat('/permanent_storage/legacy/master',yfk,'u.tif'), 'm', 's', aMType);
                END IF;
			ELSE
				if aMType = 'm' then
					set mFDCountMill = mFDCountMill + 1;
				else
					set mFDCountAcms = mFDCountAcms + 1;
				end if;
            END IF;

		END IF;


        IF cCount > 1 THEN
			SET cCount = 1;
		ELSEIF cCount = 0 THEN

			SELECT count(id) from rosetta_permanent_storage_legacy where file_path = concat('/permanent_storage/legacy/comaster',yfk,'.tif')
													or file_path = concat('/permanent_storage/legacy/comaster',yfk,'.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/comaster',yfk,'_c.jpg')
                                                    or file_path = concat('/permanent_storage/legacy/comaster',yfk,'_c.tif')
                                                    or lower_file_path = concat('/permanent_storage/legacy/comaster',loweryfk,'.jpg')
                                                    or lower_file_path = concat('/permanent_storage/legacy/comaster',loweryfk,'.tif')
                                                    into cPSCount;

			IF cPSCount = 0 then
				Select count(id) from missing_files_on_permanent_storage where file_path = concat('/permanent_storage/legacy/comaster',yfk,'.tif') into cMSCount;
                IF cMSCount = 0 THEN
					insert into missing_files_on_permanent_storage (item_id, file_path, representation, album_standalone, acms_mill) values (itemId, concat('/permanent_storage/legacy/comaster',yfk,'.tif'), 'c', 's', aMType);
                END IF;
			ELSE
				if aMType = 'm' then
					set cFDCountMill = cFDCountMill + 1;
				else
					set cFDCountAcms = cFDCountAcms + 1;
				end if;
            END IF;

		END IF;


        IF hCount > 1 THEN
			SET hCount = 1;
		ELSEIF hCount = 0 THEN

             SELECT count(id) from rosetta_permanent_storage_legacy where file_path = concat('/permanent_storage/legacy/derivatives/highres/image/',subfolders,'/',digitalId,'h.jpg')
													or lower_file_path = concat('/permanent_storage/legacy/derivatives/highres/image/',subfolders,'/',lower(digitalId),'h.jpg') into hPSCount;
			IF hPSCount = 0 THEN
				Select count(id) from missing_files_on_permanent_storage where file_path = concat('/permanent_storage/legacy/derivatives/highres/image/',subfolders,'/',digitalId,'h.jpg') into hMSCount;
                IF hMSCount = 0 THEN
					insert into missing_files_on_permanent_storage (item_id, file_path, representation, album_standalone, acms_mill) values (itemId, concat('/permanent_storage/legacy/derivatives/highres/image/',subfolders,'/',digitalId,'h.jpg'), 'h', 's', aMType);
                END IF;
			ELSE
				if aMType = 'm' then
					set hFDCountMill = hFDCountMill + 1;
				else
					set hFDCountAcms = hFDCountAcms + 1;
                end if;
            END IF;
		END IF;


        IF lCount > 1 THEN
			SET lCount = 1;
		ELSEIF lCount = 0 THEN
			 SELECT count(id) from rosetta_permanent_storage_legacy where file_path = concat('/permanent_storage/legacy/derivatives/screenres/image/',subfolders,'/',digitalId,'r.jpg')
													or lower_file_path = concat('/permanent_storage/legacy/derivatives/screenres/image/',subfolders,'/',lower(digitalId),'r.jpg') into lPSCount;
			 IF lPSCount = 0 THEN
				Select count(id) from missing_files_on_permanent_storage where file_path = concat('/permanent_storage/legacy/derivatives/screenres/image/',subfolders,'/',digitalId,'r.jpg') into lMSCount;
                IF lMSCount = 0 THEN
					insert into missing_files_on_permanent_storage (item_id, file_path, representation, album_standalone, acms_mill) values (itemId, concat('/permanent_storage/legacy/derivatives/screenres/image/',subfolders,'/',digitalId,'r.jpg'), 'l', 's', aMType);
                END IF;
             ELSE
				if aMType = 'm' then
					set lFDCountMill = lFDCountMill + 1;
				else
					set lFDCountAcms = lFDCountAcms + 1;
                end if;
             END IF;
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
        totalThumbnailCountMill,

        mFDCountAcms,
        cFDCountAcms,
        hFDCountAcms,
        lFDCountAcms,
        mFDCountMill,
        cFDCountMill,
        hFDCountMill,
        lFDCountMill;

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
