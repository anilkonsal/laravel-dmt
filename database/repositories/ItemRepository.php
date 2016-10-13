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



    const REASON_ALREADY_MIGRATED = 'Already Migrated!';
    const REASON_ITEMTEXT_ROW_NOT_FOUND = 'Row does not exist in ItemText table';
    const REASON_STATUS_INACTIVE = 'Status Inactive';
    const REASON_CLOSED_IS_YES = 'Closed is Yes';
    const REASON_FILES_DO_NOT_EXIST_ON_PERM_STORAGE = 'File(s) do not exist on Permanent Storage';

    protected $_log = [];

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
                'albumId' => $alId,
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

    public function getSipDataForAlbum($itemId)
    {
        $data = [];

        $albumAcmsRow = \DB::table('item')
                    ->where('itemID', $itemId)->first();


        $itemTextRow = \DB::table('itemtext')
                        ->where('itemID', $itemId)->first();

        $albumId = $itemTextRow->album_id;

        $imageRows = \DB::table('item')
                        ->whereIn('itemID', function($query) use ($albumId) {
                            $query->select('collection.itemID')
                                ->from('item')
                                ->join('collection','item.itemID', '=', 'collection.collectionID')
                                ->where('assetType', 'image')
                                ->where('itemType', 'Album')
                                ->where('collection.collectionID', $albumId);
                        })
                        ->where('assetType', 'image')
                        ->where('itemType', 'Image')
                        ->where('status', '<>', 'rejected')
                        ->get()->keyBy('itemID');


        $imageItemTextRows = \DB::table('itemtext')
                                ->whereIn('itemID', function ($query) use ($albumId) {
                                    $query->select('itemID')
                                        ->from('collection')
                                        ->where('collectionID', $albumId);
                                })->get()->keyBy('itemID');



        foreach ($imageRows as $itemId => $imageRow) {
            $data[$itemId] = $this->_getDataForImage($itemTextRow, $imageRow, $imageItemTextRows[$itemId]);
        }

        // dd($data);

        return $data;


    }

    protected function _getDataForImage($itemTextRow, $imageRow, $imageItemTextRow)
    {
        $itemId = $itemTextRow->itemID;
        $supress = $itemTextRow->cb;

        $imageRow->masterRoot = str_replace('\\', '/', $imageRow->masterRoot);
        $imageRow->fromRoot = str_replace('\\', '/', $imageRow->fromRoot);


        $artist = '';

        if (!empty($itemTextRow->at)) {
            $artistRow = \DB::table('artist')
                            ->where('artistID', $itemTextRow->at)
                            ->first();
            if ($artistRow) {
                $artist = $artistRow->artist;
            }
        }


        $data['ie_dmd_identifier'] = $itemId;
        $data['ie_dmd_title'] = $itemTextRow->ab;
        $data['ie_dmd_creator'] = $artist;
        $data['ie_dmd_source'] = $itemTextRow->ao;
        $data['ie_dmd_type'] = $this->_getDcType($itemTextRow->al);
        $data['ie_dmd_accessRights'] = $itemTextRow->cb;
        $data['ie_dmd_date'] = $this->_getDatePart($itemTextRow->ah);
        $data['ie_dmd_isFormatOf'] = !empty($itemTextRow->cl) ? $itemTextRow->cl : $itemTextRow->bk;
        $data['ie_dmd_isFormatOf'] = $this->_getUrlPart($data['ie_dmd_isFormatOf']);

        $data['fid1_1_dmd_title'] = $itemTextRow->ab;
        $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_dmd_identifier'] = $imageRow->itemID;
        $data['fid1_1_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
        $data['fid1_1_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
        $data['fid1_1_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_1_dmd_isFormatOf']);

        $data['fid1_2_dmd_title'] = $itemTextRow->ab;
        $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_dmd_identifier'] = $imageRow->itemID;
        $data['fid1_2_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
        $data['fid1_2_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
        $data['fid1_2_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_2_dmd_isFormatOf']);

        $data['fid1_3_dmd_title'] = $itemTextRow->ab;
        $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_identifier'] = $imageRow->itemID;
        $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
        $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
        $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

        $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_amd_label'] = $itemTextRow->ab;;
        $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

        $data['rep3_amd_url'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;

        if ($supress  == 'Image') {
            $data['fid1_3_dmd_title'] = $itemTextRow->ab;
            $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_identifier'] = $imageRow->itemID;
            $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
            $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

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


        $data['fid1_1_amd_fileOriginalPath'] = "/permanent_storage/legacy/master/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_amd_fileOriginalName'] = $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_amd_label'] = $itemTextRow->ab;
        $data['fid1_1_amd_groupID'] = $imageRow->itemKey;

        $data['fid1_2_amd_fileOriginalPath'] = "/permanent_storage/legacy/comaster/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_amd_fileOriginalName'] = $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_amd_label'] = $itemTextRow->ab;
        $data['fid1_2_amd_groupID'] = $imageRow->itemKey;

        $data['rep1_amd_url'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['rep2_amd_url'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;

        $data['rep1_1_label'] = $itemTextRow->ab;
        $data['rep2_1_label'] = $itemTextRow->ab;
        $data['rep3_1_label'] = $itemTextRow->ab;

        // $this->_writeAndFlushLog('Finished generating data', $itemId);

        return $data;
    }




    /**
     * Function to get the SIP data for a single standalone image
     * @param  Integer $itemId The item ID of the ACMS Row
     * @return Array         Data field with differnt items needed to fill in XML
     */
    public function getSipDataForStandAlone($itemId, $logFile)
    {
        $data = [];
        $reason = NULL;

        $this->_log = $logFile;

        $acmsRow = \DB::table('item')
                    ->where('itemID', $itemId)->first();

        $digitalId = $acmsRow->fromKey;

        $imageRow = \DB::table('item')
                    ->where('fromKey', $digitalId)
                    ->where('assetType', 'image')
                    ->where('itemType', 'image')
                    ->first();


        $this->_writeLog('<h2><u>Started with item Id: '. $itemId.'</u></h2>');
        $this->_writeLog('ACMS item Id: '. $itemId);
        $this->_writeLog('Image item Id: '. $imageRow->itemID);

        /*
        Check if the item has already been migrated, if yes, then skip this item
         */
        $isMigrated = $this->_isMigrated($imageRow);
        $this->_writeLog('Migrated: ' . ($isMigrated ? 'Yes' : 'No'));

        /*
        Check if the status of both the ACMS and Image Row is active, if no, then skip
         */

        $isStatusActive = $this->_isStatusActive($acmsRow, $imageRow);
        $this->_writeLog('Status Active : '. ($isStatusActive ? 'Yes' : 'No'));


        /*
        Check if the closed field is 'no' for both the ACMS and Image Row, if no, then skip
         */

        $isClosedEqualToNo = $this->_isClosedEqualToNo($acmsRow, $imageRow);
        $this->_writeLog('Closed: ' . ($isClosedEqualToNo ? 'No' : 'Yes'));

        $itemTextRowFound =  true;

        $itemTextRow = \DB::table('itemtext')
                        ->where('itemID', $itemId)
                        ->first();

        $imageItemTextRow = \DB::table('itemtext')
                        ->where('itemID', $imageRow->itemID)
                        ->first();

        if (!empty($itemTextRow)) {


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

            $itemTextRow->al = $this->_getDcType($itemTextRow->al);

            $data['ie_dmd_identifier'] = $itemId;
            $data['ie_dmd_title'] = $itemTextRow->ab;
            $data['ie_dmd_creator'] = $artist;
            $data['ie_dmd_source'] = $itemTextRow->ao;
            $data['ie_dmd_type'] = $itemTextRow->al;
            $data['ie_dmd_accessRights'] = $itemTextRow->cb;
            $data['ie_dmd_date'] = $this->_getDatePart($itemTextRow->ah);
            $data['ie_dmd_isFormatOf'] = !empty($itemTextRow->cl) ? $itemTextRow->cl : $itemTextRow->bk;
            $data['ie_dmd_isFormatOf'] = $this->_getUrlPart($data['ie_dmd_isFormatOf']);

            $data['fid1_1_dmd_title'] = $itemTextRow->ab;
            $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['fid1_1_dmd_identifier'] = $itemId;
            $data['fid1_1_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
            $data['fid1_1_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_1_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_1_dmd_isFormatOf']);

            $data['fid1_2_dmd_title'] = $itemTextRow->ab;
            $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_dmd_identifier'] = $itemId;
            $data['fid1_2_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
            $data['fid1_2_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_2_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_2_dmd_isFormatOf']);

            $data['fid1_3_dmd_title'] = $itemTextRow->ab;
            $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_dmd_identifier'] = $itemId;
            $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
            $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

            $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_amd_label'] = $itemTextRow->ab;;
            $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

            $data['rep3_amd_url'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;

            if ($supress  == 'Image') {
                $data['fid1_3_dmd_title'] = $itemTextRow->ab;
                $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_dmd_identifier'] = $itemId;
                $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah);
                $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
                $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

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


            $data['fid1_1_amd_fileOriginalPath'] = "/permanent_storage/legacy/master/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['fid1_1_amd_fileOriginalName'] = $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['fid1_1_amd_label'] = $itemTextRow->ab;
            $data['fid1_1_amd_groupID'] = $imageRow->itemKey;

            $data['fid1_2_amd_fileOriginalPath'] = "/permanent_storage/legacy/comaster/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_amd_fileOriginalName'] = $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_amd_label'] = $itemTextRow->ab;
            $data['fid1_2_amd_groupID'] = $imageRow->itemKey;

            $data['rep1_amd_url'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['rep2_amd_url'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;

            $data['rep1_1_label'] = $itemTextRow->ab;
            $data['rep2_1_label'] = $itemTextRow->ab;
            $data['rep3_1_label'] = $itemTextRow->ab;



            /*
            Check on the Permanent storage if all the three files exist, if any one file does
            not exist, then return false (which leads to this image being skipped for sip generation)
            */

             $this->_writeLog('<h4>Checking files on Permanent Storage.</h4>');

             $result1 = $this->_doesFileExistsOnPermStorage($data['fid1_1_amd_fileOriginalPath'], $itemId, 'm', 's');
             $result2 = $this->_doesFileExistsOnPermStorage($data['fid1_2_amd_fileOriginalPath'], $itemId, 'c', 's');
             $result3 = $this->_doesFileExistsOnPermStorage($data['fid1_3_amd_fileOriginalPath'], $itemId, 'o', 's');

             $doFilesExistInPermStorage = $result1 && $result2 && $result3;
        } else {
            $itemTextRowFound = false;
        }

        if (!$itemTextRowFound) {
             $reason = self::REASON_ITEMTEXT_ROW_NOT_FOUND;
        } elseif ($isMigrated) {
             $reason = self::REASON_ALREADY_MIGRATED;
         } elseif (!$isStatusActive) {
             $reason = self::REASON_STATUS_INACTIVE;
         } elseif (!$isClosedEqualToNo) {
             $reason = self::REASON_CLOSED_IS_YES;
         } elseif (!$doFilesExistInPermStorage) {
             $reason = self::REASON_FILES_DO_NOT_EXIST_ON_PERM_STORAGE;
         }

         $this->_writeLog('<h3>Conclusion</h3>');


         if (!empty($reason)) {
             $this->_writeLog('<div style="color:red">SIP not be generated for item id: '. $itemId.'</div>');
             $this->_writeLog('Reason: <b>'. $reason.'</b>');
             $this->_closeLog();
             return false;
         } else {
             $this->_writeLog('<div style="color:#15a545">SIP generated for item id: '. $itemId.'</div>');
         }
         $this->_closeLog();

        return $data;
    }





    /**
     * Function to get the DC:Type Full form
     * @param  String $type The two character type
     * @return String       The full form of the type
     */
    protected function _getDcType($type)
    {
        $type = trim($type,' ,');

        $typeMap = [
             'MM'   =>  'Multiple Media',
             'TR'   =>  'Textual Records',
             'GM'   =>  'Graphic Materials',
             'CM'   =>  'Cartographic Materials',
             'AT'   =>  'Architectural and Technical Drawings',
             'MI'   =>  'Moving Images',
             'SR'   =>  'Sound Recordings',
             'OB'   =>  'Objects',
             'PR'   =>  'Philatelic Records',
             'MS'   =>  'Music'
        ];
        return $typeMap[$type];
    }

    /**
     * Function to get the date part from the datetime field
     * @param  String $datetime The datetime field
     * @return String           The date part
     */
    protected function _getDatePart($datetime)
    {
        if(empty($datetime)) {
            return;
        }
        list($date, $time) = explode(' ', $datetime);
        return $date;
    }

    /**
     * Function to get the URL part from a mixed string (using Regex)
     * @param  String $string The input string from which URL has to be extracted
     * @return String  Either the extracted URL or the input string itself
     */
    protected function _getUrlPart($string)
    {
        $regex = '/http?:\/\/[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/))/';
        preg_match($regex, $string, $matches);
        if (count($matches)) {
            return  $matches[0];
        }
        return $string;
    }

    /**
     *
     * Function to check if the file exists on the Permanent Strorage
     * @param  string $filePath Path of file to be searched
     * @return  boolean     True if it is found, False otherwise
     */
    protected function _doesFileExistsOnPermStorage($filePath, $itemId, $representation, $albumStandalone)
    {
        $count = \DB::table('rosetta_permanent_storage_legacy')
                    ->where('file_path', $filePath)
                    ->count();

        if ($count == 0) {
            $existing = \DB::table('missing_files_on_permanent_storage')
                        ->where('file_path', $filePath)
                        ->count();

            if ($existing == 0) {
                \DB::table('missing_files_on_permanent_storage')
                    ->insert([
                        'item_id'           =>  $itemId,
                        'file_path'         =>  $filePath,
                        'representation'    =>  $representation,
                        'album_standalone'  =>  $albumStandalone
                    ]);
            }
        }


        $this->_writeLog('Image Path: '.$filePath.' found on Permanent Storage: '. $count);

        return $count > 0 ? true : false;
    }

    /**
     * Function to check if the image has already been migrated or not
     * @param  EloquentRowObject  $imageItemRow The image row
     * @return boolean
     */
    protected function _isMigrated($imageItemRow)
    {

        $year = str_replace('_MASTER\\','', $imageItemRow->masterRoot);

        $yfk = '/' . $year .'/'. $imageItemRow->masterFolder . '/' . $imageItemRow->masterKey;

        $masterPath = '/permanent_storage/legacy/master'. $yfk .'u.tif';
        $comasterPath = '/permanent_storage/legacy/comaster'. $yfk .'.tif';
        $highresPath = '/permanent_storage/legacy/derivatives/highres/image/'. $imageItemRow->wpath . '/'. $imageItemRow->masterKey . 'h.jpg';
        $stdresPath = '/permanent_storage/legacy/derivatives/screenres/image/'. $imageItemRow->wpath . '/'. $imageItemRow->masterKey . 'r.jpg';


        $this->_writeLog('<h4>Entered isMigrated Function</h4>');
        $this->_writeLog('Master Path: '.$masterPath);
        $this->_writeLog('Co Master Path: '.$comasterPath);
        $this->_writeLog('Hires Path: '.$highresPath);
        $this->_writeLog('Stdres Path: '.$stdresPath);


        $masterCount = \DB::table('rosetta_migrated')
                            ->where('file_path', $masterPath)
                            ->count();

        $comasterCount = \DB::table('rosetta_migrated')
                            ->where('file_path', $comasterPath)
                            ->count();

        $highresCount = \DB::table('rosetta_migrated')
                            ->where('file_path', $highresPath)
                            ->count();

        $stdresCount = \DB::table('rosetta_migrated')
                            ->where('file_path', $stdresPath)
                            ->count();

        $this->_writeLog('Master already migrated: '. $masterCount);
        $this->_writeLog('Co Master already migrated: '. $comasterCount);
        $this->_writeLog('Hires already migrated: '. $highresCount);
        $this->_writeLog('Std Res already migrated: '. $stdresCount);


        if ($masterCount || $comasterCount || $highresCount || $stdresCount) {
            return true;
        }

        return false;
    }

    /**
     * Function to check if the status is active for both ACMS
     * row and the image row
     * @param  EloquentRowObject  $acmsItemRow
     * @param  EloquentRowObject  $imageItemRow
     * @return boolean
     */
    protected function _isStatusActive($acmsItemRow, $imageItemRow)
    {

        $this->_writeLog('<h4>Entered isStatusActive Function</h4>');
        $this->_writeLog('ACMS Row Status: '.$acmsItemRow->status);
        $this->_writeLog('Image Row Status: '.$imageItemRow->status);

        if ($acmsItemRow->status == 'active' && $imageItemRow->status == 'active') {
            return true;
        }
        return false;
    }

    /**
     * Function to check if the closed status is No for both ACMS
     * row and the image row
     * @param  EloquentRowObject  $acmsItemRow
     * @param  EloquentRowObject  $imageItemRow
     * @return boolean
     */
    protected function _isClosedEqualToNo($acmsItemRow, $imageItemRow)
    {

        $this->_writeLog('<h4>Entered isClosedEqualToNo Function</h4>');
        $this->_writeLog('ACMS Row Closed: '.$acmsItemRow->closed);
        $this->_writeLog('Image Row Closed: '.$imageItemRow->closed);

        if ($acmsItemRow->closed == 'No' && $imageItemRow->closed == 'No') {
            return true;
        }
        return false;
    }

    /**
     * Function to write the a line in the log file
     * @param  String $string The line to be written in the Log file
     * @return void
     */
    protected function _writeLog($string)
    {
        if (substr($string, -1) != '>') {
            $string .= "<br />";
        }
        file_put_contents($this->_log, $string , FILE_APPEND);
    }

    protected function _closeLog()
    {
        $html = '<!doctype html><head><style>body {font-family:sans-serif}</style></head><html><body>';
        $endHtml = '</body></html>';

        $html = $html . (file_get_contents($this->_log)) .$endHtml;
        file_put_contents($this->_log, $html);
    }

}
