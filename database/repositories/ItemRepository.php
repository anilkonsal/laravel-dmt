<?php

namespace Database\Repositories;

use App\Item;

class ItemRepository {

    const TYPE_ALL = '*';
    const TYPE_ALBUM = 'AL';

    const REP_MASTER = 'masterRoot';
    const REP_COMASTER = 'fromRoot';
    const REP_HIRES = 'wroot';
    const REP_STDRES = 'lroot';
    const REP_PREVIEW = 'proot';
    const REP_THUMBNAIL = 'troot';

    protected $types = [
        self::TYPE_ALL   =>  [
            'assetType' =>  'image',
            'itemType'  =>  'image'
        ],
        self::TYPE_ALBUM    =>  [
            'assetType' =>  'image',
            'itemType'  =>  'album'
        ]
    ];

    protected $representations = [
        self::REP_MASTER        =>  '_MASTER',
        self::REP_COMASTER      =>  '_COMASTER',
        self::REP_HIRES         =>  '_DAMx',
        self::REP_STDRES        =>  '_DAMl',
        self::REP_PREVIEW       =>  '_DAMp',
        self::REP_THUMBNAIL     =>  '_DAMt',
    ];

    protected $suffixes = [
        self::REP_MASTER        =>  'u || _m',
        self::REP_COMASTER      =>  ' || _c',
        self::REP_HIRES         =>  'h',
        self::REP_STDRES        =>  'r',
        self::REP_PREVIEW       =>  'p',
        self::REP_THUMBNAIL     =>  't',
    ];

    protected $extensions = [
        self::REP_MASTER        =>  'tif',
        self::REP_COMASTER      =>  'tif',
        self::REP_HIRES         =>  'jpg',
        self::REP_STDRES        =>  'jpg',
        self::REP_PREVIEW       =>  'jpg',
        self::REP_THUMBNAIL     =>  'jpg',
    ];

    /**
     * Function to get the total number of Albums in the DAM
     * and total number of not empty albums in the DAM
     * @return Array
     */
    public function getTotalAlbumCounts()
    {
        $totalAlbumCount = $this->getAlbumsCount();

        $notEmptyAlbumCount = \DB::table('item')
                                ->whereIn('itemID', function($query) {
                                    $query->select('item.itemID')
                                        ->from('item')
                                        ->join('collection','item.itemID', '=', 'collection.collectionID')
                                        ->where('assetType', 'image')
                                        ->where('itemType', 'album');
                                })
                                ->where('assetType', 'image')
                                ->where('itemType', 'album')
                                ->count();

        return [
                'totalAlbumCount'  =>  $totalAlbumCount,
                'notEmptyAlbumCount' => $notEmptyAlbumCount
            ];
    }

    public function getMastersCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_MASTER);
        return $count;
    }

    public function getComastersCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_COMASTER);
        return $count;
    }

    public function getHiresCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_HIRES);
        return $count;
    }

    public function getStdresCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_STDRES);
        return $count;
    }

    public function getPreviewCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_PREVIEW);
        return $count;
    }

    public function getThumbnailCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_THUMBNAIL);
        return $count;
    }

    protected function _getCount($type, $representation) {

        if ($type == self::TYPE_ALBUM) {
            $count = \DB::table('item')
                        ->whereIn('itemID', function($query) {
                            $query->select('collection.itemID')
                                ->from('item')
                                ->join('collection','item.itemID', '=', 'collection.collectionID')
                                ->where('assetType', 'image')
                                ->where('itemType', 'Album');
                            })
                        ->where('assetType', 'image')
                        ->where('itemType', 'Image')
                        ->where($representation, 'like', $this->representations[$representation].'%')
                        ->where('status', '<>', 'rejected')
                        ->count();
            return $count;
        } else {
            $sql = \DB::table('item')
                    ->where('assetType', $this->types[$type]['assetType'])
                    ->where('itemType', $this->types[$type]['itemType'])
                    ->where($representation, 'like', $this->representations[$representation].'%')
                    ->where('status','<>','rejected');
            $count = $sql->count();
            return $count;
        }
    }

    public function getAlbumsCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'Album')
                ->count();
        return $count;
    }

    public function getImagesInAlbumsCount()
    {
        $count = \DB::table('item')
                    ->whereIn('itemID', function($query) {
                        $query->select('collection.itemID')
                            ->from('item')
                            ->join('collection','item.itemID', '=', 'collection.collectionID')
                            ->where('assetType', 'image')
                            ->where('itemType', 'Album')
                            ;

                    })
                    ->where('assetType', 'image')
                    ->where('itemType', 'Image')
                    ->where('status', '<>', 'rejected')
                    ->count();
        return $count;
    }


    public function getDetails_old($itemID, $debug = false, &$itemizedCounts=[])
    {
        $counts = [
            'masterCount'       =>  0,
            'comasterCount'     =>  0,
            'hiresCount'        =>  0,
            'stdresCount'       =>  0,
            'previewCount'      =>  0,
            'thumbnailCount'    =>  0,

            'albumsCount'       =>  0,
            'albumMasterCount'   =>  0,
            'albumComasterCount' =>  0,
            'albumHiresCount'    =>  0,
            'albumStdresCount'   =>  0,
            'albumPreviewCount'  =>  0,
            'albumThumbnailCount'=>  0,
        ];
        $albumsCount = 0;
        // Fetch the item row
        $itemRow = \DB::table('item')
                    ->where('itemID',$itemID)
                    ->first();

        if (!$itemRow) {
            throw new \InvalidArgumentException( 'Row not found for this itemID');
        }

        // get the digital id
        $digitalId = $itemRow->masterKey;


        if ($digitalId != 'NULL') {

            // search for albums of this digital id
            $albumRow = \DB::table('item')
                        ->where('masterKey', $digitalId)
                        ->where('assetType', 'image')
                        ->where('itemType', 'album')
                        ->first();


            // if no row found
            if (!$albumRow) {
                $masterCount = $this->_getRepCountByDigitalID($digitalId, self::REP_MASTER);
                $comasterCount = $this->_getRepCountByDigitalID($digitalId, self::REP_COMASTER);
                $previewCount = $this->_getRepCountByDigitalID($digitalId, self::REP_PREVIEW);
                $hiresCount = $this->_getRepCountByDigitalID($digitalId, self::REP_HIRES);
                $stdresCount = $this->_getRepCountByDigitalID($digitalId, self::REP_STDRES);
                $thumbnailCount = $this->_getRepCountByDigitalID($digitalId, self::REP_THUMBNAIL);

            } else {
                $albumID = $albumRow->itemID;

                $albumsCount++;
                $albumMasterCount = $this->_getRepCountByCollectionID($albumID, self::REP_MASTER);
                $albumComasterCount = $this->_getRepCountByCollectionID($albumID, self::REP_COMASTER);
                $albumPreviewCount = $this->_getRepCountByCollectionID($albumID, self::REP_PREVIEW);
                $albumHiresCount = $this->_getRepCountByCollectionID($albumID, self::REP_HIRES);
                $albumStdresCount = $this->_getRepCountByCollectionID($albumID, self::REP_STDRES);
                $albumThumbnailCount = $this->_getRepCountByCollectionID($albumID, self::REP_THUMBNAIL);

            }

            $counts = [
                'masterCount'       =>  isset($masterCount) ? $masterCount : 0,
                'comasterCount'     =>  isset($comasterCount) ? $comasterCount : 0,
                'hiresCount'        =>  isset($hiresCount) ? $hiresCount : 0,
                'stdresCount'       =>  isset($stdresCount) ? $stdresCount : 0,
                'previewCount'      =>  isset($previewCount) ? $previewCount : 0,
                'thumbnailCount'    =>  isset($thumbnailCount) ? $thumbnailCount : 0,

                'albumsCount'        =>  isset($albumsCount) ? $albumsCount : 0,
                'albumMasterCount'   =>  isset($albumMasterCount) ? $albumMasterCount : 0,
                'albumComasterCount' =>  isset($albumComasterCount) ? $albumComasterCount : 0,
                'albumHiresCount'    =>  isset($albumHiresCount) ? $albumHiresCount : 0,
                'albumStdresCount'   =>  isset($albumStdresCount) ? $albumStdresCount : 0,
                'albumPreviewCount'  =>  isset($albumPreviewCount) ? $albumPreviewCount : 0,
                'albumThumbnailCount'=>  isset($albumThumbnailCount) ? $albumThumbnailCount : 0,
            ];

        }


            $itemizedCounts[$itemID] = [
                'albumsCount' => $counts['albumsCount'],
                'albumImagesCount' => $counts['albumPreviewCount'],
                'standaloneImagesCount'   => $counts['masterCount']
            ];


            $children = \DB::table('collection')
                            ->where('collectionID', $itemID)
                            ->get();


            foreach ($children as $child) {

                $itemID = $child->itemID;

                $nCounts = $this->getDetails($itemID, $debug, $itemizedCounts);
                $mCounts = $nCounts['counts'];

                $counts = $this->_array_sum_by_key($counts, $mCounts);


        }

        return ['counts' => $counts, 'itemizedCounts' => $itemizedCounts];

    }


    public function getDetails($itemID, $debug = false, &$itemizedCounts=[])
    {
        $counts = [
            'masterCount'       =>  0,
            'comasterCount'     =>  0,
            'hiresCount'        =>  0,
            'stdresCount'       =>  0,
            'previewCount'      =>  0,
            'thumbnailCount'    =>  0,

            'albumsCount'       =>  0,
            'albumMasterCount'   =>  0,
            'albumComasterCount' =>  0,
            'albumHiresCount'    =>  0,
            'albumStdresCount'   =>  0,
            'albumPreviewCount'  =>  0,
            'albumThumbnailCount'=>  0,
        ];
        $albumsCount = 0;
        // Fetch the item row
        $itemRow = \DB::table('item')
                    ->where('itemID',$itemID)
                    ->first();

        if (!$itemRow) {
            throw new \InvalidArgumentException( 'Row not found for this itemID');
        }

        $alId = false;
        // get the digital id
        $digitalId = $itemRow->masterKey;

        $itemTextRow = \DB::table('itemtext')
                        ->where('itemID', $itemID)
                        ->first();

        if ($itemTextRow) {
            $alId = $itemTextRow->album_id;

            if ($alId) {
            $albumRow = \DB::table('item')
                        ->where('itemID',$alId)
                        ->first();

                    }
        }



        if ($digitalId != 'NULL') {


            // if no row found
            if (!$alId) {
                $masterCount = $this->_getRepCountByDigitalID($digitalId, self::REP_MASTER);
                $comasterCount = $this->_getRepCountByDigitalID($digitalId, self::REP_COMASTER);
                $previewCount = $this->_getRepCountByDigitalID($digitalId, self::REP_PREVIEW);
                $hiresCount = $this->_getRepCountByDigitalID($digitalId, self::REP_HIRES);
                $stdresCount = $this->_getRepCountByDigitalID($digitalId, self::REP_STDRES);
                $thumbnailCount = $this->_getRepCountByDigitalID($digitalId, self::REP_THUMBNAIL);

            } else {
                $albumID = $albumRow->itemID;

                $albumsCount++;
                $albumMasterCount = $this->_getRepCountByCollectionID($albumID, self::REP_MASTER);
                $albumComasterCount = $this->_getRepCountByCollectionID($albumID, self::REP_COMASTER);
                $albumPreviewCount = $this->_getRepCountByCollectionID($albumID, self::REP_PREVIEW);
                $albumHiresCount = $this->_getRepCountByCollectionID($albumID, self::REP_HIRES);
                $albumStdresCount = $this->_getRepCountByCollectionID($albumID, self::REP_STDRES);
                $albumThumbnailCount = $this->_getRepCountByCollectionID($albumID, self::REP_THUMBNAIL);

            }

            $counts = [
                'masterCount'       =>  isset($masterCount) ? $masterCount : 0,
                'comasterCount'     =>  isset($comasterCount) ? $comasterCount : 0,
                'hiresCount'        =>  isset($hiresCount) ? $hiresCount : 0,
                'stdresCount'       =>  isset($stdresCount) ? $stdresCount : 0,
                'previewCount'      =>  isset($previewCount) ? $previewCount : 0,
                'thumbnailCount'    =>  isset($thumbnailCount) ? $thumbnailCount : 0,

                'albumsCount'        =>  isset($albumsCount) ? $albumsCount : 0,
                'albumMasterCount'   =>  isset($albumMasterCount) ? $albumMasterCount : 0,
                'albumComasterCount' =>  isset($albumComasterCount) ? $albumComasterCount : 0,
                'albumHiresCount'    =>  isset($albumHiresCount) ? $albumHiresCount : 0,
                'albumStdresCount'   =>  isset($albumStdresCount) ? $albumStdresCount : 0,
                'albumPreviewCount'  =>  isset($albumPreviewCount) ? $albumPreviewCount : 0,
                'albumThumbnailCount'=>  isset($albumThumbnailCount) ? $albumThumbnailCount : 0,
            ];

        }


            $itemizedCounts[$itemID] = [
                'albumsCount' => $counts['albumsCount'],
                'albumImagesCount' => $counts['albumPreviewCount'],
                'standaloneImagesCount'   => $counts['masterCount']
            ];


            $children = \DB::table('collection')
                            ->where('collectionID', $itemID)
                            ->get();


            foreach ($children as $child) {

                $itemID = $child->itemID;

                $nCounts = $this->getDetails($itemID, $debug, $itemizedCounts);
                $mCounts = $nCounts['counts'];

                $counts = $this->_array_sum_by_key($counts, $mCounts);


        }

        return ['counts' => $counts, 'itemizedCounts' => $itemizedCounts];

    }


    public function getAlbumImagesNotMigratedCounts()
    {
        $counts = \DB::select('call album_images_not_migrated()');
        return $counts;
    }

    public function getStandaloneImagesNotMigratedCounts()
    {
        $counts = \DB::select('call standalone_images_not_migrated()');
        return $counts;
    }

    public function acmsAlbumsMigrationCounts()
    {
        $counts = \DB::select('call acms_albums_not_migrated()');
        return $counts;
    }

    public function milleniumAlbumsMigrationCounts()
    {
        $counts = \DB::select('call millenium_albums_not_migrated()');
        return $counts;
    }



    protected function _getRepCountByDigitalID($digitalId, $representation)
    {
        $count = \DB::table('item')
                    ->where('masterKey', $digitalId)
                    ->where('assetType', 'image')
                    ->where('itemType', 'image')
                    ->where($representation, 'like', $this->representations[$representation].'%')
                    ->where('status', '<>', 'rejected')
                    ->count();
        return $count;
    }

    protected function _getRepCountByCollectionID($collectionID, $representation)
    {
        $count = \DB::table('item')
                    ->whereIn('itemID', function($query) use ($collectionID) {
                        $query->select('collection.itemID')
                            ->from('item')
                            ->join('collection','item.itemID', '=', 'collection.collectionID')
                            ->where('collection.collectionID', $collectionID)
                            ->where('assetType', 'image')
                            ->where('itemType', 'Album')
                            ;
                    })

                    ->where('assetType', 'image')
                    ->where('itemType', 'Image')
                    ->where($representation, 'like', $this->representations[$representation].'%')
                    ->where('status', '<>', 'rejected')
                    ->count();


        return $count;
    }

    protected function _array_sum_by_key($array1, $array2) {
        $array = [];
        foreach ($array1 as $key => $value) {
            $array[$key] = $value + $array2[$key];
        }
        return $array;
    }



    public function getSipDataForStandAlone($itemId)
    {
        $data = [];

        $acmsRow = \DB::table('item')
                    ->where('itemID', $itemId)->first();

        //dd($acmsRow);

        $digitalId = $acmsRow->fromKey;

        $imageRow = \DB::table('item')
                    ->where('fromKey', $digitalId)
                    ->where('assetType', 'image')
                    ->where('itemType', 'image')
                    ->first()
                    ;


        $itemTextRow = \DB::table('itemtext')
                        ->where('itemID', $itemId)
                        ->first();

        $imageItemTextRow = \DB::table('itemtext')
                        ->where('itemID', $imageRow->itemID)
                        ->first();

        $artist = '';
        if (!empty($itemTextRow->at)) {
            $artistRow = \DB::table('artist')
                            ->where('artistID', $itemTextRow->at)
                            ->first();
            if ($artistRow) {
                $artist = $artistRow->artist;
            }
        }

        $supress = $itemTextRow->cb;

        $imageRow->masterRoot = str_replace('\\', '/', $imageRow->masterRoot);
        $imageRow->fromRoot = str_replace('\\', '/', $imageRow->fromRoot);



        $data['ie_dmd_identifier'] = $itemId;
        $data['ie_dmd_title'] = $itemTextRow->ab;
        $data['ie_dmd_creator'] = $artist;
        $data['ie_dmd_source'] = $itemTextRow->ao;
        $data['ie_dmd_type'] = $itemTextRow->al;
        $data['ie_dmd_accessRights'] = $itemTextRow->cb;
        $data['ie_dmd_date'] = $itemTextRow->ah;
        $data['ie_dmd_isFormatOf'] = !empty($itemTextRow->cl) ? $itemTextRow->cl : $itemTextRow->bk;


        $data['fid1_1_dmd_title'] = $itemTextRow->ab;
        $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "." . $imageRow->fromType;
        $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "." . $imageRow->fromType;
        $data['fid1_1_dmd_identifier'] = $itemId;
        $data['fid1_1_dmd_date'] = $imageItemTextRow->ah;
        $data['fid1_1_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;

        $data['fid1_2_dmd_title'] = $itemTextRow->ab;
        $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_dmd_identifier'] = $itemId;
        $data['fid1_2_dmd_date'] = $imageItemTextRow->ah;
        $data['fid1_2_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;


        $data['fid1_3_dmd_title'] = $itemTextRow->ab;
        $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au//permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_identifier'] = $itemId;
        $data['fid1_3_dmd_date'] = $imageItemTextRow->ah;
        $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;

        $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_amd_label'] = $itemTextRow->ab;;
        $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

        $data['rep3_amd_url'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;

        if ($supress  == 'Image') {
            $data['fid1_3_dmd_title'] = $itemTextRow->ab;
            $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au//permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_identifier'] = $itemId;
            $data['fid1_3_dmd_date'] = $imageItemTextRow->ah;
            $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;

            $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->wtype;
            $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "r." . $imageRow->wtype;
            $data['fid1_3_amd_label'] = $itemTextRow->ab;
            $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

            $data['rep3_amd_rights'] = 'AR_EVERYONE';

            $data['rep3_amd_url'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->wtype;

        } elseif ($supress == 'No') {
            $data['rep3_amd_rights'] = 'AR_EVERYONE';
        } elseif ($supress == 'Yes') {
            $data['rep3_amd_rights'] = '1062';
        }




        $data['fid1_1_amd_fileOriginalPath'] = "/permanent_storage/legacy/master/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "." . $imageRow->fromType;
        $data['fid1_1_amd_fileOriginalName'] = $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_amd_label'] = $itemTextRow->ab;
        $data['fid1_1_amd_groupID'] = $imageRow->itemKey;

        $data['fid1_2_amd_fileOriginalPath'] = "/permanent_storage/legacy/comaster/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_amd_fileOriginalName'] = $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_amd_label'] = $itemTextRow->ab;
        $data['fid1_2_amd_groupID'] = $imageRow->itemKey;



        $data['rep1_amd_url'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "." . $imageRow->fromType;
        $data['rep2_amd_url'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;


        $data['rep1_1_label'] = $itemTextRow->ab;
        $data['rep2_1_label'] = $itemTextRow->ab;
        $data['rep3_1_label'] = $itemTextRow->ab;


        return $data;

    }




}
