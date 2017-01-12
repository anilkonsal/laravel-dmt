<?php

namespace Database\Repositories;

use App\Item;
use Carbon\Carbon;

class ItemRepository {

    const TYPE_ALL = '*';
    const TYPE_ALBUM = 'AL';

    const REP_MASTER = 'masterRoot';
    const REP_COMASTER = 'fromRoot';
    const REP_HIRES = 'wroot';
    const REP_STDRES = 'lroot';
    const REP_PREVIEW = 'proot';
    const REP_THUMBNAIL = 'troot';

    const REP_FOLDER_MASTER = 'master';
    const REP_FOLDER_COMASTER = 'comaster';
    const REP_FOLDER_HIRES = 'highres';
    const REP_FOLDER_STDRES = 'screenres';


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
    const REASON_ALREADY_MARKED_MIGRATED = 'Item already marked Migrated in item table!';
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

    /**
     * Function to get the count of Masters
     * @param  string $type Album or All
     * @return integer  The count
     */
    public function getMastersCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_MASTER);
        return $count;
    }

    /**
     * Function to get the count of Co-Masters
     * @param  string $type Album or All
     * @return integer  The count
     */
    public function getComastersCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_COMASTER);
        return $count;
    }

    /**
     * Function to get the count of High Resolution
     * @param  string $type Album or All
     * @return integer  The count
     */
    public function getHiresCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_HIRES);
        return $count;
    }

    /**
     * Function to get the count of Low resolution
     * @param  string $type Album or All
     * @return integer  The count
     */
    public function getStdresCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_STDRES);
        return $count;
    }

    /**
     * Function to get the count of Preview
     * @param  string $type Album or All
     * @return integer  The count
     */
    public function getPreviewCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_PREVIEW);
        return $count;
    }

    /**
     * Function to get the count of thumbnails
     * @param  string $type Album or All
     * @return integer  The count
     */
    public function getThumbnailCount($type = self::TYPE_ALL)
    {
        $count = $this->_getCount($type, self::REP_THUMBNAIL);
        return $count;
    }

    /**
     * Function to get the count based on the type (Album/Standalone) and represenation
     * @param  string $type  Album or all
     * @param  string $representation
     * @return integer   The count
     */
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

    /**
     * Function to fetch the Root Level ACMS Rows count
     * @return Integer          Count of all the root level ACMS rows
     */
    public function getRootLevelAcmsRowsCount() : int
    {
        $count = \DB::table('item')
                    ->whereNotIn('itemID', function($query) {
                        $query->select('collection.itemID')
                                ->from('collection');
                        })
                    ->where('assetType', 'acms')
                    ->where('itemType', 'collection')
                    ->count();

        return $count;
    }


    /**
     * Function to fetch the Root Level ACMS Rows
     * @param  integer $offset Starting offset
     * @param  integer $limit  Limit
     * @return Eloquent
     */
    public function getRootLevelAcmsRows(int $offset = 0, int $limit = 1000)
    {
        $rows = \DB::table('item')
                    ->whereNotIn('itemID', function($query) {
                        $query->select('collection.itemID')
                                ->from('collection');
                        })
                    ->where('assetType', 'acms')
                    ->where('itemType', 'collection')
                    ->offset($offset)
                    ->limit($limit)                    
                    ->pluck('itemID');
        return $rows;

    }

    /**
     * Function to get the Albums count
     * @return integer The count
     */
    public function getAlbumsCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'Album')
                ->count();
        return $count;
    }

    /**
     * Function to get the count of Images in albums
     * @return integer The count
     */
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


    /**
     * Function to traverse the tree for a given item id and fill the different counts recursively
     * @param  integer  $itemID  The item id
     * @param  boolean $debug    Whether to show the itemized summary of traversal
     * @param  array  $itemizedCounts
     * @return array  The array of counts
     */
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

        $albumRow = null;
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

    /**
     * Function to get the count Album images migrated/not-migrated
     * @return array  The counts
     */
    public function getAlbumImagesNotMigratedCounts()
    {
        $counts = \DB::select('call album_images_not_migrated()');
        return $counts;
    }

    /**
     * Function to get the count Standalone images migrated/not-migrated
     * @return array  The counts
     */
    public function getStandaloneImagesNotMigratedCounts()
    {
        $counts = \DB::select('call standalone_images_not_migrated()');
        return $counts;
    }

    /**
     * Function to get the count ACMS Albums migrated/not-migrated
     * @return array  The counts
     */
    public function acmsAlbumsMigrationCounts()
    {
        $counts = \DB::select('call acms_albums_not_migrated()');
        return $counts;
    }

    /**
     * Function to get the counts of millenium albums migrated/no-migrated
     * @return array The counts
     */
    public function milleniumAlbumsMigrationCounts()
    {
        $counts = \DB::select('call millenium_albums_not_migrated()');
        return $counts;
    }


    /**
     * Function to get the Represeantion count by digital id
     * @param  integer $digitalId      The digital id
     * @param  string $representation The represenations
     * @return integer The count
     */
    protected function _getRepCountByDigitalID($digitalId, $representation) : int
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

    /**
     * Function to get the Representation count by inputting the collection id
     * @param  integer $collectionID   Id of the collection
     * @param  string $representation The represenation for which the count is to be found
     * @return integer  Count
     */
    protected function _getRepCountByCollectionID($collectionID, $representation) : int
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

    /**
     * Function to add two arrays by keys
     * @param  array $array1 First Array
     * @param  array $array2 Second Array
     * @return array Resulatant array returned after totalling
     */
    protected function _array_sum_by_key($array1, $array2) {
        $array = [];
        foreach ($array1 as $key => $value) {
            $array[$key] = $value + $array2[$key];
        }
        return $array;
    }

    /**
     * Function to generate the SIP data for an individual Album
     * @param  integer $itemId ACMS item id for the album
     * @param  string $logFile Path of the log file to be written
     * @param  boolean $forceGeneration Whether to generate the marked migrated items
     * @return mixed  Array of data of all the images belonging to this album or False
     */
    public function getSipDataForAlbum($itemId, $logFile, $forceGeneration = false, $missing = false, $filesArr = [])
    {
        $data = [];
        $isMigrated = false;

        $this->_log = $logFile;

        $this->_writeLog('<h2><u>Started with Album with ACMS item Id: '. $itemId.'</u></h2>');

        $albumAcmsRow = Item::where('itemID', $itemId)->first();

        if (!$forceGeneration && $this->_checkIfMigrated($albumAcmsRow)) {
            $this->_writeLog('<div style="background:red; color: white;">Album already marked migrated in database, so skipping this album.</div>');
            return false;
        }

        $itemTextRow = \DB::table('itemtext')
                        ->where('itemID', $itemId)->first();

        $albumId = $itemTextRow->album_id;

        $albumRow = \DB::table('item')
                        ->where('itemID', $albumId)->first();

        $albumItemTextRow = \DB::table('itemtext')
                                ->where('itemID', $albumId)->first();

        $supress = $itemTextRow->cb;

        $imageRows = \DB::table('item')
                        ->join('collection', 'item.itemID', '=', 'collection.itemID')
                        ->whereIn('item.itemID', function($query) use ($albumId) {
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
                        ->orderBy(\DB::raw('cast(collection.itemIndex as decimal(10,4))'))
                        ->get()
                        ->keyBy('itemID');

        

        $imageItemTextRows = \DB::table('itemtext')
                                ->whereIn('itemID', function ($query) use ($albumId) {
                                    $query->select('itemID')
                                        ->from('collection')
                                        ->where('collectionID', $albumId);
                                })
                                ->get()
                                ->keyBy('itemID');
        
        /**
            NOTE:
            In some cases like itemID = 1022861, there is one image row which does
            not have the corresponding row in itemtext row. In this case, we have to
            skip the album 1022861. (in this example case, there are 10 images for this album
            but only 9 have entries in itemtext row)
        **/

        if (count($imageRows) != count($imageItemTextRows)) {
            return false;
        }
        

        $collectionRows = \DB::table('collection')
                                ->where('collectionID', $albumId)
                                ->get()
                                ->keyBy('itemID');

        $this->_writeLog('Album Id: '. $albumId);
        $this->_writeLog('Number of images: '. count($imageRows));
        $this->_writeLog('Image ids: '. implode(', ', array_keys($imageRows->all())));

        /*
        Check if Album Row exists, if not, them return false
         */
        if (!$albumRow) {
            $this->_writeLog('Album row does not exist!');
            return false;
        }

        /*
        If there are no images in this album, then return false
         */
        if(count($imageRows) < 1) {
            $this->_writeLog('No images in album');
            return false;
        }

        /*
        Check if closed == no for acms row and album row, if not, then return false
         */
        if ($albumAcmsRow->closed != 'No' || $albumRow->closed != 'No') {
            $this->_writeLog('ACMS Row closed = '. $albumAcmsRow->closed);
            $this->_writeLog('Album Row closed = '. $albumRow->closed);
            return false;
        }


        /*
        In some rare cases, the suppress field is empty, in those cases, we just skip this album
         */
        if (empty($supress)) {
            $this->_writeLog('<div style="background:red; color:white">Skipping this album as Supress Field is empty!</div>');
            return false;
        }

        /*
        Loop through all the images belonging to this album and get the data back
         */
        foreach ($imageRows as $itemId => $imageRow) {
            $this->_writeLog('<h3>Staring with Image Row with Id: '.$itemId.'</h3>');

            /*
            Check if the item has already been migrated, if yes, then skip this item
             */
            $isMigrated[$itemId] = $this->_isMigrated($imageRow, $albumAcmsRow, $missing);
            if($isMigrated[$itemId]) {
                $this->_writeLog('<h3>Image already migrated, so skipping this Album</h3>');
                return false;
            }

            /*
            Check if the images are active, if yes, then generate data for this album
            otherwise, skip this image in the xml
             */
            if ($imageRow->status != 'active') {
                $this->_writeLog('Image status is not ACTIVE');
                continue;
            }

            /*
            Check if the images are not closed, if yes, then skip the closed images
             */
            if ($imageRow->closed != 'No') {
                $this->_writeLog('Image closed not equal to NO');
                continue;
            }
            
            $data[$itemId] = $this->_getDataForImage($itemTextRow, $albumItemTextRow, $imageRow, $imageItemTextRows[$itemId], $collectionRows[$itemId], $missing, $filesArr);
            
        }

        
        /*
        If all the images are found for this album, mark this album as migrated
         */
        $key = array_search(false, $data);

        if ( $key === false) {
            $this->_markAsMigrated($albumAcmsRow);
        } else {
            $this->_writeLogTop('<div style="background:red; color: white;">Album SIP will not be generated as Files not found for Image with itemID: '. $key .'</div>');
            return false;
        }

        // dd($data);
        return $data;

    }

/**
 * Function to populate the data for an individual image belonfing to an album
 * @param  EloquentRowObject $itemTextRow      [description]
 * @param  EloquentRowObject $albumItemTextRow      [description]
 * @param  EloquentRowObject $imageRow         [description]
 * @param  EloquentRowObject $imageItemTextRow [description]
 * @param  EloquentRowObject $collectionRow    [description]
 * @return Mixed data of all the images or false
 */
    protected function _getDataForImage($itemTextRow, $albumItemTextRow, $imageRow, $imageItemTextRow, $collectionRow, $missing = false, $filesArr = [])
    {
        
        // dd($filesArr);

        $itemId = $itemTextRow->itemID;
        $supress = $itemTextRow->cb;

        $imageRow->masterRoot = str_replace('\\', '/', $imageRow->masterRoot);
        $imageRow->fromRoot = str_replace('\\', '/', $imageRow->fromRoot);
        $imageRow->wroot = str_replace('\\', '/', $imageRow->wroot);

        $fileName = $imageRow->itemKey;

        

        $artist = '';

        if(!empty($itemTextRow->at)) {
            $artistID = (int)trim($itemTextRow->at, ',');

            if (!empty($artistID)) {
                $artistRow = \DB::table('artist')
                                ->where('artistID', $artistID)
                                ->first();

                if ($artistRow) {
                    $artist = $artistRow->artist;
                }
            }
        }

        $imageRow->masterRoot = str_replace('\\', '/', $imageRow->masterRoot);
        $imageRow->fromRoot = str_replace('\\', '/', $imageRow->fromRoot);
        $imageRow->wroot = str_replace('\\', '/', $imageRow->wroot);
        $imageRow->lroot = str_replace('\\', '/', $imageRow->lroot);

        $types = $this->_getDcType($itemTextRow->al);

        if (!empty($itemTextRow->cl)) {
            $ieDmdIsFormatOf = $itemTextRow->cl;
        } elseif (!empty($itemTextRow->bk)) {
            $ieDmdIsFormatOf = $itemTextRow->bk;
        } elseif (!empty($albumItemTextRow->cl)) {
            $ieDmdIsFormatOf = $albumItemTextRow->cl;
        } elseif (!empty($albumItemTextRow->bk)) {
            $ieDmdIsFormatOf = $albumItemTextRow->bk;
        } else {
            $ieDmdIsFormatOf = '';
        }

        $title = !empty($imageItemTextRow->ab) ? $imageItemTextRow->ab : $itemTextRow->ab;

        $data['ie_dmd_identifier'] = $itemId;
        $data['ie_dmd_title'] = $itemTextRow->ab;
        $data['ie_dmd_creator'] = $artist;
        $data['ie_dmd_source'] = $itemTextRow->ao;

        if (!empty($types)) {
                foreach($types as $type) {
                    $data['ie_dmd_type'][] = $type;
                }
        } else {
            $data['ie_dmd_type'] = '';
        }

       
        $data['ie_dmd_accessRights'] = $itemTextRow->cb;
        $data['ie_dmd_date'] = $this->_getDatePart($itemTextRow->ah);
        $data['ie_dmd_isFormatOf'] = $ieDmdIsFormatOf;
        $data['ie_dmd_isFormatOf'] = $this->_getUrlPart($data['ie_dmd_isFormatOf']);

        $data['fid1_1_dmd_title'] = $title;
        $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_dmd_identifier'] = $imageRow->itemID;
        $data['fid1_1_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
        $data['fid1_1_dmd_tableOfContents'] = $collectionRow->itemIndex;
        $data['fid1_1_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
        $data['fid1_1_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_1_dmd_isFormatOf']);

        $data['fid1_2_dmd_title'] = $title;
        $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_dmd_identifier'] = $imageRow->itemID;
        $data['fid1_2_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
        $data['fid1_2_dmd_tableOfContents'] = $collectionRow->itemIndex;
        $data['fid1_2_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
        $data['fid1_2_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_2_dmd_isFormatOf']);

        $data['fid1_3_dmd_title'] = $title;
        // $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_source'] = $imageRow->wroot . "/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/". $imageRow->wroot .'/' . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_dmd_identifier'] = $imageRow->itemID;
        $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
        $data['fid1_3_dmd_tableOfContents'] = $collectionRow->itemIndex;
        $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
        $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

        $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "h." . $imageRow->wtype;
        $data['fid1_3_amd_label'] = $title;
        $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

        if (!$missing) {
            $data['rep3_amd_url'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
        } else {
            $data['rep3_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_HIRES, $fileName);
        }
        

        if ($supress  == 'Image') {
            $data['fid1_3_dmd_title'] = $title;
            // $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_source'] = $imageRow->lroot . "/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->lroot .'/'. $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_dmd_identifier'] = $imageRow->itemID;
            $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
            $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);
            $data['fid1_3_dmd_tableOfContents'] = $collectionRow->itemIndex;

            $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "r." . $imageRow->ltype;
            $data['fid1_3_amd_label'] = $title;
            $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

            $data['rep3_amd_rights'] = 'AR_EVERYONE';

             if (!$missing) {
                    $data['rep3_amd_url'] = $data['fid1_3_amd_fileOriginalPath'];
                } else {
                    $data['rep3_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_STDRES, $fileName);
                }

        } elseif ($supress == 'No') {
            $data['rep3_amd_rights'] = 'AR_EVERYONE';
        } elseif ($supress == 'Yes') {
            $data['rep3_amd_rights'] = '1062';
        }

        /*
        Extract years from masterRoot and fromRoot fields
         */
        $masterYear = substr($imageRow->masterRoot, -4);
        $comasterYear = substr($imageRow->fromRoot, -4);

        $data['fid1_1_amd_fileOriginalPath'] = "/permanent_storage/legacy/master/" . $masterYear . "/" . $imageRow->masterFolder ."/" .  $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_amd_fileOriginalName'] = $imageRow->masterKey . "u." . $imageRow->fromType;
        $data['fid1_1_amd_label'] = !empty($imageItemTextRow->ab) ? $imageItemTextRow->ab : $itemTextRow->ab;
        $data['fid1_1_amd_groupID'] = $imageRow->itemKey;

        $data['fid1_2_amd_fileOriginalPath'] = "/permanent_storage/legacy/comaster/". $comasterYear . "/"  . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_amd_fileOriginalName'] = $imageRow->fromKey . "." . $imageRow->fromType;
        $data['fid1_2_amd_label'] = !empty($imageItemTextRow->ab) ? $imageItemTextRow->ab : $itemTextRow->ab;
        $data['fid1_2_amd_groupID'] = $imageRow->itemKey;

         if (!$missing) {
            $data['rep1_amd_url'] = $data['fid1_1_amd_fileOriginalPath'];
            $data['rep2_amd_url'] = $data['fid1_2_amd_fileOriginalPath'];
        } else {
            $data['rep1_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_MASTER, $fileName);
            $data['rep2_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_COMASTER, $fileName);
        }
        $data['rep1_1_label'] = $title;
        $data['rep2_1_label'] = $title;
        $data['rep3_1_label'] = $title;

        
        if (!$missing) {
            /*
            Check on the Permanent storage if all the three files exist, if any one file does
            not exist, then return false (which leads to this image being skipped for sip generation)
            */

            $this->_writeLog('<h4>Checking files on Permanent Storage.</h4>');

            $result1 = $this->_doesFileExistsOnPermStorage($data['fid1_1_amd_fileOriginalPath'], $itemId, 'm', 'a');
            $result2 = $this->_doesFileExistsOnPermStorage($data['fid1_2_amd_fileOriginalPath'], $itemId, 'c', 'a');
            $result3 = $this->_doesFileExistsOnPermStorage($data['fid1_3_amd_fileOriginalPath'], $itemId, 'o', 'a');

            $doFilesExistInPermStorage = $result1['found'] && $result2['found'] && $result3['found'];


            /*
            If files exist on the Permanent Storage, then replace the paths in
            data array as there may be case that variations exist
            */
            if ($doFilesExistInPermStorage) {

                $basename1 = basename($result1['filePath']);
                $basename2 = basename($result2['filePath']);
                $basename3 = basename($result3['filePath']);

                $data['rep1_amd_url'] = $data['fid1_1_amd_fileOriginalPath'] = $result1['filePath'];
                $data['rep2_amd_url'] = $data['fid1_2_amd_fileOriginalPath'] = $result2['filePath'];
                $data['rep3_amd_url'] = $data['fid1_3_amd_fileOriginalPath'] = $result3['filePath'];

                $data['fid1_1_amd_fileOriginalName'] = $basename1;
                $data['fid1_2_amd_fileOriginalName'] = $basename2;
                $data['fid1_3_amd_fileOriginalName'] = $basename3;

                $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $basename1;
                $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $basename1;

                $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $basename2;
                $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $basename2;

                if ($supress  == 'Image') {
                    $data['fid1_3_dmd_source'] = $imageRow->lroot . "/" . $imageRow->wpath . "/" . $basename3;
                    $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->lroot .'/'. $imageRow->wpath . "/" . $basename3;
                } else {
                    $data['fid1_3_dmd_source'] = $imageRow->wroot . "/" . $imageRow->wpath . "/" . $basename3;
                    $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/". $imageRow->wroot .'/' . $imageRow->wpath . "/" . $basename3;
                }

            } else {
                return false;
            }
         } else {
                 if ($imageRow->fromType != 'pdf') {
                    $data['rep1_amd_rights'] = $data['rep2_amd_rights'] = '1062';
                } else {
                    $data['rep2_amd_rights'] = $data['rep1_amd_rights'] = 'AR_EVERYONE';
                }
            }

        return $data;
    }

    public function getAllPDFrecords()
    {
        $acmsRows = \DB::table('item')
                    ->where('fromType', 'pdf')
                    ->where('assetType', 'acms')
                    ->where('itemType', 'collection')
                    ->where('status','active')
                    ->get();
        return $acmsRows;
    }


    public function deleteRowsFormMissingOnPermanentStorage($itemId)
    {
        \DB::table('missing_files_on_permanent_storage')
                                ->where('item_id', $itemId)
                                ->delete();
        return true;
    }

    public function checkIfAllMissingFilesExist($itemId, $logFile)
    {
        $this->_log = $logFile;
        $rows = \DB::table('missing_files_on_permanent_storage')
                                ->where('item_id', $itemId)
                                ->get();
                        
        $totalMissingRowsForItemCount = count($rows);
        $totalFoundCount = 0;
        $missingRowsArr = [];

        $this->_writeLog('<h2><u>Started with item Id: '. $itemId.'</u></h2>');

        $repsFound = [];

        $albumStandalone = $rows[0]->album_standalone;

        if ($albumStandalone == 's') {
            $row = $rows[0];

            $acmsRow = \DB::table('item')->where('itemId', $itemId)->first();

            $digitalId = $acmsRow->fromKey;

            $imageRow = \DB::table('item')
                    ->where('fromKey', $digitalId)
                    ->where('assetType', 'image')
                    ->where('itemType', 'image')
                    ->first();

            // $imageRow = \DB::table('item')->where('itemId', $itemId)->first();  

    
            $fileBaseName = $this->_getFileBaseName($row);

            $year = substr($imageRow->masterRoot ,-4);

            $yfk = '/' . $year .'/'. $imageRow->masterFolder . '/' . $imageRow->masterKey;

            $masterSuffix = $imageRow->fromType != 'pdf' ? 'u' : '';
            $extension = $imageRow->fromType != 'pdf' ? 'tif' : 'pdf';

            $masterPath = '/mnt/digitarchive/master'. $yfk;
            $comasterPath = '/mnt/digitarchive/comaster'. $yfk;
            $highresPath = '/mnt/digitarchive/derivatives/highres/image/'. $imageRow->wpath . '/'. $fileBaseName;
            $stdresPath = '/mnt/digitarchive/derivatives/screenres/image/'. $imageRow->wpath . '/'. $fileBaseName;
            
            // dd($masterPath, $comasterPath, $highresPath, $stdresPath);

            $missingRows = \DB::table('digit_archive')
                                ->where('path', 'like', $masterPath.'%')
                                ->orWhere('path', 'like', $comasterPath.'%')
                                ->orWhere('path', 'like', $highresPath.'%')
                                ->orWhere('path', 'like', $stdresPath.'%')
                                ->get();


            if ($missingRows) {
                foreach ($missingRows as $missingRow) {
                    if (preg_match('/(master|comaster|highres|screenres)/',$missingRow->path, $matches)) {
                        $repsFound[] = $matches[0];
                        
                    }
                    $missingRowsArr[$missingRow->path] = $missingRow->file_name;
                }
            }

            dd($missingRowsArr);

            if (!(in_array(self::REP_FOLDER_MASTER, $repsFound) && in_array(self::REP_FOLDER_COMASTER, $repsFound) 
                && (in_array(self::REP_FOLDER_HIRES, $repsFound) || in_array(self::REP_FOLDER_STDRES, $repsFound)))) {
                    
                    $this->_writeLog('<div style="background:red; color:white;">Not all 3 required representations were found on Digit Archive</div>' );
                    $this->_writeLog('Reps Found are:<ul><li>' . implode( '</li><li>', array_flip($missingRowsArr)) . '</li></ul>');
                    return false;    
                }
            
        } else {

            $itemTextRow = \DB::table('itemtext')
                        ->where('itemID', $itemId)->first();

            $albumId = $itemTextRow->album_id;

            $imageRows = \DB::table('item')
                        ->join('collection', 'item.itemID', '=', 'collection.itemID')
                        ->whereIn('item.itemID', function($query) use ($albumId) {
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
                        ->orderBy(\DB::raw('cast(collection.itemIndex as decimal(10,4))'))
                        ->get()
                        ->keyBy('itemID');          

            // dd ($imageRows);

            foreach ($imageRows as $itemRow) {
                
        
                $fileBaseName = $itemRow->masterKey;

                $year = substr($itemRow->masterRoot ,-4);

                $yfk = '/' . $year .'/'. $itemRow->masterFolder . '/' . $itemRow->masterKey;

                $masterSuffix = $itemRow->fromType != 'pdf' ? 'u' : '';
                $extension = $itemRow->fromType != 'pdf' ? 'tif' : 'pdf';

                $masterPath = '/mnt/digitarchive/master'. $yfk;
                $comasterPath = '/mnt/digitarchive/comaster'. $yfk;
                $highresPath = '/mnt/digitarchive/derivatives/highres/image/'. $itemRow->wpath . '/'. $fileBaseName;
                $stdresPath = '/mnt/digitarchive/derivatives/screenres/image/'. $itemRow->wpath . '/'. $fileBaseName;
                
                // dd($masterPath, $comasterPath, $highresPath, $stdresPath);

                $missingRows = \DB::table('digit_archive')
                                    ->where('path', 'like', $masterPath.'%')
                                    ->orWhere('path', 'like', $comasterPath.'%')
                                    ->orWhere('path', 'like', $highresPath.'%')
                                    ->orWhere('path', 'like', $stdresPath.'%')
                                    ->get();

                                    

                if ($missingRows) {
                    foreach ($missingRows as $missingRow) {
                        if (preg_match('/(master|comaster|highres|screenres)/',$missingRow->path, $matches)) {
                            $repsFound[] = $matches[0];
                        }
                        $missingRowsArr[$missingRow->path] = $missingRow->file_name;
                    }
                }

            

                if (!(in_array(self::REP_FOLDER_MASTER, $repsFound) && in_array(self::REP_FOLDER_COMASTER, $repsFound) 
                    && (in_array(self::REP_FOLDER_HIRES, $repsFound) || in_array(self::REP_FOLDER_STDRES, $repsFound)))) {
                        $this->_writeLog('<div style="background:red; color:white;">Not all 3 required representations were found on Digit Archive</div>' );
                        $this->_writeLog('Reps Found are:<ul><li>' . implode( '</li><li>', array_flip($missingRowsArr)) . '</li></ul>');
                        return false;    
                    }
                }

        }
       
        
        $totalFoundCount = count($missingRowsArr);

        if ($totalMissingRowsForItemCount <= $totalFoundCount) {
            // All Images found on Digit Archive
            return [
                'status'            =>  1,
                'album_standalone'  =>  $rows[0]->album_standalone,
                'missingRows'        =>  $missingRowsArr,
            ];
        } else {
            return false;
        }
    }

    public function checkIfAllMissingFilesExist1($itemId, $logFile)
    {
        $rows = \DB::table('missing_files_on_permanent_storage')
                                ->where('item_id', $itemId)
                                ->get();
                        
        $totalMissingRowsForItemCount = count($rows);
        $totalFoundCount = 0;
        $missingRowsArr = [];

        foreach ($rows as $row) {

            $fileBaseName = $this->_getFileBaseName($row);


            $search = '/permanent_storage/legacy';
            $replace = '/mnt/digitarchive';

            $path = str_replace($search, $replace, dirname($row->file_path));
            $path = str_replace(['master', 'comaster', 'derivatives/highres/images/', 'derivatives/screenres/images'], '%', $path);

            // $patterns = ['/master/', '/comaster/', '/derivatives\/highres\/image\/(\d+)\/(\d+)/', '/derivatives\/screenres\/image\/(\d+)\/(\d+)/'];
            // $path = preg_replace($patterns, '%', $path);


            echo $path .'/'. $fileBaseName . "\n"; 
            $missingRows = \DB::table('digit_archive')
                                ->where('path', 'like', $path . '/' . $fileBaseName .'%')
                                ->get();

            if ($missingRows) {
                foreach ($missingRows as $missingRow) {
                    $missingRowsArr[$missingRow->file_name] = $missingRow->path;
                }
            }
        }
       
        
        $totalFoundCount = count($missingRowsArr);

        dd($missingRowsArr);

        if ($totalMissingRowsForItemCount <= $totalFoundCount) {
            // All Images found on Digit Archive
            return [
                'status'            =>  1,
                'album_standalone'  =>  $rows[0]->album_standalone,
                'missingRows'        =>  $missingRowsArr,
            ];
        } else {
            return false;
        }
    }

    protected function _getFileBaseName($row)
    {
        list($fileName, $ext) = explode('.', $row->file_name);

        if ($row->representation === 'm') {
            if (substr($fileName, -1) === 'u') {
                return substr($fileName, 0, -1);
            } elseif (substr($fileName, -2) === '_m') {
                return substr($fileName, 0, -2);
            }
        } elseif ($row->representation === 'c') {
            if (substr($fileName, -2) === '_c') {
                return substr($fileName, 0, -2);
            }
            return $fileName;
        } elseif ($row->representation === 'h' || $row->representation === 'l') {
            return substr($fileName, 0, -1);
        }
    }

   
   /**
     * Function to get the SIP data for a single standalone image
     * @param  Integer $itemId The item ID of the ACMS Row
     * @param  boolean $forceGeneration Whether the items marked migrated in database should be regenerated?
     * @return Array         Data field with differnt items needed to fill in XML
     */
    public function getSipDataForStandAlone($itemId, $logFile, $forceGeneration = false, $missing = false, $filesArr = [])
    {

        $data = [];
        $reason = NULL;
        $doFilesExistInPermStorage = true;

        $this->_log = $logFile;

        $acmsRow = Item::where('itemID', $itemId)->first();

        $digitalId = $acmsRow->fromKey;

        $imageRow = \DB::table('item')
                    ->where('fromKey', $digitalId)
                    ->where('assetType', 'image')
                    ->where('itemType', 'image')
                    ->first();


        $this->_writeLog('<h2><u>Started with item Id: '. $itemId.'</u></h2>');
        $this->_writeLog('ACMS item Id: '. $itemId);
        $this->_writeLog('Image item Id: '. $imageRow->itemID);

        if (!$forceGeneration) {

            /*
            Check if the item has already been marked as migrated in item table, if yes, then skip this item
             */
            $isDbMigrated = $this->_checkIfMigrated($acmsRow);
            $this->_writeLog('Marked Migrated in Item table: ' . ($isDbMigrated ? 'Yes' : 'No'));
        }

        /*
        Check if the item has already been migrated, if yes, then skip this item
         */
        $isMigrated = $this->_isMigrated($imageRow, $acmsRow, $missing);
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
            $artistID = (int)trim($itemTextRow->at, ',');

            if (!empty($artistID)) {
                $artistRow = \DB::table('artist')
                                ->where('artistID', $artistID)
                                ->first();

                if ($artistRow) {
                    $artist = $artistRow->artist;
                }
            }


            $supress = $itemTextRow->cb;

            $imageRow->masterRoot = str_replace('\\', '/', $imageRow->masterRoot);
            $imageRow->fromRoot = str_replace('\\', '/', $imageRow->fromRoot);
            $imageRow->wroot = str_replace('\\', '/', $imageRow->wroot);
            $imageRow->lroot = str_replace('\\', '/', $imageRow->lroot);
            
            $fileName = $imageRow->itemKey;

            $types = $this->_getDcType($itemTextRow->al);
            
            if (!empty($types)) {
                foreach($types as $type) {
                    $data['ie_dmd_type'][] = $type;
                }
            } else {
                $data['ie_dmd_type'] = '';
            }

            $data['ie_dmd_identifier'] = $itemId;
            $data['ie_dmd_title'] = $itemTextRow->ab;
            $data['ie_dmd_creator'] = $artist;
            $data['ie_dmd_source'] = $itemTextRow->ao;
            $data['ie_dmd_accessRights'] = $itemTextRow->cb;
            $data['ie_dmd_date'] = $this->_getDatePart($itemTextRow->ah);
            $data['ie_dmd_isFormatOf'] = !empty($itemTextRow->cl) ? $itemTextRow->cl : $itemTextRow->bk;
            
            $data['ie_dmd_isFormatOf'] = $this->_getUrlPart($data['ie_dmd_isFormatOf']);

            $data['fid1_1_dmd_title'] = $itemTextRow->ab;
            $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $imageRow->masterKey . "u." . $imageRow->fromType;
            $data['fid1_1_dmd_identifier'] = $itemId;
            $data['fid1_1_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
            $data['fid1_1_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_1_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_1_dmd_isFormatOf']);

            $data['fid1_2_dmd_title'] = $itemTextRow->ab;
            $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_dmd_identifier'] = $itemId;
            $data['fid1_2_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
            $data['fid1_2_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_2_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_2_dmd_isFormatOf']);

            $data['fid1_3_dmd_title'] = $itemTextRow->ab;
            // $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_dmd_source'] = $imageRow->wroot . "/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/". $imageRow->wroot .'/' . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_dmd_identifier'] = $itemId;
            $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
            $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
            $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

            $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "h." . $imageRow->wtype;
            $data['fid1_3_amd_label'] = $itemTextRow->ab;
            $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

            if (!$missing) {
                $data['rep3_amd_url'] = "/permanent_storage/legacy/derivatives/highres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "h." . $imageRow->wtype;
            } else {
                $data['rep3_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_HIRES, $fileName);
            }
            

            if ($supress  == 'Image') {
                $data['fid1_3_dmd_title'] = $itemTextRow->ab;
                // $data['fid1_3_dmd_source'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_dmd_source'] = $imageRow->lroot . "/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->lroot .'/'. $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_dmd_identifier'] = $itemId;
                $data['fid1_3_dmd_date'] = $this->_getDatePart($imageItemTextRow->ah, $itemTextRow->ah);
                $data['fid1_3_dmd_isFormatOf'] = !empty($imageItemTextRow->cl) ? $imageItemTextRow->cl : $imageItemTextRow->bk;
                $data['fid1_3_dmd_isFormatOf'] = $this->_getUrlPart($data['fid1_3_dmd_isFormatOf']);

                $data['fid1_3_amd_fileOriginalPath'] = "/permanent_storage/legacy/derivatives/screenres/image/" . $imageRow->wpath . "/" . $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_amd_fileOriginalName'] = $imageRow->itemKey . "r." . $imageRow->ltype;
                $data['fid1_3_amd_label'] = $itemTextRow->ab;
                $data['fid1_3_amd_groupID'] = $imageRow->itemKey;

                $data['rep3_amd_rights'] = 'AR_EVERYONE';

                if (!$missing) {
                    $data['rep3_amd_url'] = $data['fid1_3_amd_fileOriginalPath'];
                } else {
                    $data['rep3_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_STDRES, $fileName);
                }
                

            } elseif ($supress == 'No') {
                $data['rep3_amd_rights'] = 'AR_EVERYONE';
            } elseif ($supress == 'Yes') {
                $data['rep3_amd_rights'] = '1062';
            } elseif (empty($supress)) {
                    $this->_writeLog('<div style="background:red; color:white">Skipping this Image as Supress Field is empty!</div>');
                    return false;
            }

            /*
            Extract years from masterYear and comasterYear fields
             */
            $masterYear = substr($imageRow->masterRoot, -4);
            $comasterYear = substr($imageRow->fromRoot, -4);

            $masterSuffix = ($imageRow->fromType != 'pdf') ? 'u' : '';

            $data['fid1_1_amd_fileOriginalPath'] = "/permanent_storage/legacy/master/" . $masterYear . "/" . $imageRow->masterFolder ."/" .  $imageRow->masterKey . $masterSuffix . "." . $imageRow->fromType;
            $data['fid1_1_amd_fileOriginalName'] = $imageRow->masterKey . $masterSuffix . "." . $imageRow->fromType;
            $data['fid1_1_amd_label'] = $itemTextRow->ab;
            $data['fid1_1_amd_groupID'] = $imageRow->itemKey;

            $data['fid1_2_amd_fileOriginalPath'] = "/permanent_storage/legacy/comaster/". $comasterYear . "/"  . $imageRow->fromFolder . "/" . $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_amd_fileOriginalName'] = $imageRow->fromKey . "." . $imageRow->fromType;
            $data['fid1_2_amd_label'] = $itemTextRow->ab;
            $data['fid1_2_amd_groupID'] = $imageRow->itemKey;

            if (!$missing) {
                $data['rep1_amd_url'] = $data['fid1_1_amd_fileOriginalPath'];
                $data['rep2_amd_url'] = $data['fid1_2_amd_fileOriginalPath'];
            } else {
                $data['rep1_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_MASTER, $fileName);
                $data['rep2_amd_url'] = "file://" . $this->getImageName($filesArr, self::REP_FOLDER_COMASTER, $fileName);
            }

            $data['rep1_1_label'] = $itemTextRow->ab;
            $data['rep2_1_label'] = $itemTextRow->ab;
            $data['rep3_1_label'] = $itemTextRow->ab;

                

            /*
            Check on the Permanent storage if all the three files exist, if any one file does
            not exist, then return false (which leads to this image being skipped for sip generation)
            */

            if (!$missing) {

                $this->_writeLog('<h4>Checking files on Permanent Storage.</h4>');

                $result1 = $this->_doesFileExistsOnPermStorage($data['fid1_1_amd_fileOriginalPath'], $itemId, 'm', 's');
                $result2 = $this->_doesFileExistsOnPermStorage($data['fid1_2_amd_fileOriginalPath'], $itemId, 'c', 's');
                $result3 = $this->_doesFileExistsOnPermStorage($data['fid1_3_amd_fileOriginalPath'], $itemId, 'o', 's');

                if ($imageRow->fromType != 'pdf') {
                    $doFilesExistInPermStorage = $result1['found'] && $result2['found'] && $result3['found'];
                    $data['rep1_amd_rights'] = $data['rep2_amd_rights'] = '1062';
                } else {
                    $doFilesExistInPermStorage = $result1['found'] || $result2['found'] || $result3['found'];
                    $data['rep2_amd_rights'] = $data['rep1_amd_rights'] = 'AR_EVERYONE';
                }


                if ($doFilesExistInPermStorage) {

                    $basename1 = basename($result1['filePath']);
                    $basename2 = basename($result2['filePath']);
                    $basename3 = basename($result3['filePath']);

                    $data['rep1_amd_url'] = $data['fid1_1_amd_fileOriginalPath'] = $result1['filePath'];
                    $data['rep2_amd_url'] = $data['fid1_2_amd_fileOriginalPath'] = $result2['filePath'];
                    $data['rep3_amd_url'] = $data['fid1_3_amd_fileOriginalPath'] = $result3['filePath'];

                    $data['fid1_1_amd_fileOriginalName'] = $result1['found'] ? $basename1 : '';
                    $data['fid1_2_amd_fileOriginalName'] = $result2['found'] ? $basename2 : '';
                    $data['fid1_3_amd_fileOriginalName'] = $result3['found'] ? $basename3 : '';

                    $data['fid1_1_dmd_source'] = $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $basename1;
                    $data['fid1_1_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->masterRoot . "/" . $imageRow->masterFolder . "/" . $basename1;

                    $data['fid1_2_dmd_source'] = $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $basename2;
                    $data['fid1_2_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->fromRoot . "/" . $imageRow->fromFolder . "/" . $basename2;

                    if ($supress  == 'Image') {
                        $data['fid1_3_dmd_source'] = $imageRow->lroot . "/" . $imageRow->wpath . "/" . $basename3;
                        $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/" . $imageRow->lroot .'/'. $imageRow->wpath . "/" . $basename3;
                    } else {
                        $data['fid1_3_dmd_source'] = $imageRow->wroot . "/" . $imageRow->wpath . "/" . $basename3;
                        $data['fid1_3_dmd_description'] = "http://acms.sl.nsw.gov.au/". $imageRow->wroot .'/' . $imageRow->wpath . "/" . $basename3;
                    }

                }
            } else {
                 if ($imageRow->fromType != 'pdf') {
                    $data['rep1_amd_rights'] = $data['rep2_amd_rights'] = '1062';
                } else {
                    $data['rep2_amd_rights'] = $data['rep1_amd_rights'] = 'AR_EVERYONE';
                }
            }

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
         } elseif (!$forceGeneration && $isDbMigrated) {
             $reason = self::REASON_ALREADY_MARKED_MIGRATED;
         }


         $this->_writeLog('<h3>Conclusion</h3>');

         if (!empty($reason)) {
             $this->_writeLog('<div style="background-color:red; color:#fff">SIP not be generated for item id: '. $itemId.'</div>');
             $this->_writeLog('Reason: <b>'. $reason.'</b>');
             $this->_closeLog();
             return false;
         } else {
             $this->_markAsMigrated($acmsRow);
             $this->_writeLog('<div style="background-color:#15a545; color: #fff">SIP generated for item id: '. $itemId.'</div>');
         }
         $this->_closeLog();

        return $data;
    }


    public function getImageName($filesArr, $repFolder, $file = '')
    {
        if (empty($filesArr)) {
            throw new \Exception('Files Array should not be empty!');
        }
       
        foreach ($filesArr as $filePath => $fileName) {
            if(preg_match("/\/".$repFolder."\/.*\/".$file."/", $filePath, $matches)){
                return $fileName;
            }
        }
    }



    /**
     * Function to mark the row as migrated
     * @param  EloquentRowObject $row
     * @return Void
     */
    protected function _markAsMigrated($row)
    {
        $row->is_migrated = 1;
        $row->save();
    }

    /**
     * Function to check the row if it is migrated
     * @param  EloquentRowObject $row
     * @return boolean
     */
    protected function _checkIfMigrated($row) : bool
    {
        return $row->is_migrated == 1 ? true : false;
    }


    /**
     * Function to get the DC:Type Full form
     * @param  String $type The two character type
     * @return String       The full form of the type
     */
    protected function _getDcType($type) : array
    {
        if (empty($type)) {
            return [];
        }
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

        $type = trim($type,' ,');

        $typesArr = explode(',', $type);

        $dcTypes = [];


        foreach ($typesArr as $typeItem) {
            $dcTypes[] = $typeMap[$typeItem];
        }

        return $dcTypes;
    }

    /**
     * Function to get the date part from the datetime field
     * @param  String $datetime The datetime field
     * @param  String $datetime The datetime field to be taken if the the first argument is empty
     * @return String           The date part
     */
    protected function _getDatePart($datetime, $datetime2='') : string
    {
        $dt = empty($datetime) ? $datetime2 : $datetime;

        if(empty($dt)) {
            return '';
        }
        list($date, $time) = explode(' ', $dt);

        $date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');

        return $date;
    }

    /**
     * Function to get the URL part from a mixed string (using Regex)
     * @param  String $string The input string from which URL has to be extracted
     * @return String  Either the extracted URL or the input string itself
     */
    protected function _getUrlPart($string) : string
    {
        $regex = '/http?:\/\/[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/))/';
        preg_match($regex, $string, $matches);
        if (count($matches)) {
            return  $matches[0];
        }
        return $string ? : '';
    }

    /**
     *
     * Function to check if the file exists on the Permanent Strorage
     * @param  string $filePath Path of file to be searched
     * @return Array  Array with 'found' and 'filePath' keys
     */
    protected function _doesFileExistsOnPermStorage($filePath, $itemId, $representation, $albumStandalone) : array
    {

        $result = $this->_findOnPermanentStorage($filePath);


        if ($result['count'] == 0) {
            /**
             * The file was not found in permanent storage, so lets look with variations
             */

             $pi = pathinfo($filePath);

             $dirName = $pi['dirname'];
             $fileName = $pi['filename'];
             $extension = $pi['extension'];



             /*
             If this is a master or comaster representation, then
             */
             if (in_array($representation, ['m','c'])) {

                 /*
                 Try changing the u with _m in file name for master and adding _c in the fileName
                 for the co master
                 */
                 if ($representation === 'm') {
                     $newFilePath = $dirName .'/'. $fileName . '_m' .'.'.$extension;
                 } elseif ($representation == 'c') {
                     $newFilePath = $dirName .'/'. $fileName . '_c' .'.'.$extension;
                 }

                 $count = $this->_findOnPermanentStorage($newFilePath)['count'];
                 if ($count > 0) {
                     return [
                         'found'    =>  true,
                         'filePath' =>  $newFilePath
                     ];
                }

                /*
                Try changing the extension of the the file if its tif,
                then try with jpg and vice versa
                */
                if (in_array($extension, ['tif', 'TIF'])) {
                    $newExtension = 'jpg';
                } elseif (in_array($extension, ['jpg', 'JPG'])) {
                    $newExtension = 'tif';
                } else {
                    $newExtension = $extension;
                }
                $newFilePath = $dirName . '/'. $fileName .'.'.$newExtension;

                $count = $this->_findOnPermanentStorage($newFilePath)['count'];
                if ($count > 0) {
                    return [
                        'found'    =>  true,
                        'filePath' =>  $newFilePath
                    ];
                }
             }

             /*
             In any case, check with the case insensitive version of filename and extension
              */
             $newFilePath = $dirName . '/' . strtolower($fileName) . '.' . strtolower($extension);

             $result = $this->_findOnPermanentStorage($newFilePath, true);

             if ($result['count'] > 0) {
                 return [
                     'found'    =>  true,
                     'filePath' =>  $result['filePath']
                 ];
             }


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

        return [
            'found'    =>  $result['count'],
            'filePath' =>  $result['filePath']
        ];
    }

    /**
     * Function to check if the specified file exists in Permanent Storage
     * @param  string $filePath The path to be checked on file system
     * @return Array containting 'count' and 'filePath' keys
     */
    protected function _findOnPermanentStorage($filePath, $applyLower = false) : array
    {

        $fieldName = !$applyLower ? 'file_path' : 'lower_file_path';

        $row = \DB::table('rosetta_permanent_storage_legacy')
                    ->where($fieldName, $filePath)
                    ->first();


        if (empty($row)) {
            $this->_writeLog('File variation: ' . $filePath.' does not exist on Permanent Storage');
            return [
                'count' =>  0,
                'filePath' => $filePath
            ];
        }

        $this->_writeLog('File variation: ' . $filePath.' exists on Permanent Storage');
        return [
            'count' =>  1,
            'filePath' => $row->file_path
        ];
    }

    /**
     * Function to check if the image has already been migrated or not
     * @param  EloquentRowObject  $imageItemRow The image row
     * @return boolean
     */
    protected function _isMigrated($imageItemRow, $acmsRow, $missing = false) : bool
    {
        $year = substr($imageItemRow->masterRoot ,-4);

        $yfk = '/' . $year .'/'. $imageItemRow->masterFolder . '/' . $imageItemRow->masterKey;

        $masterSuffix = $imageItemRow->fromType != 'pdf' ? 'u' : '';
        $extension = $imageItemRow->fromType != 'pdf' ? 'tif' : 'pdf';

        $masterPath = '/permanent_storage/legacy/master'. $yfk . $masterSuffix .'.'. $extension;
        $comasterPath = '/permanent_storage/legacy/comaster'. $yfk .'.'. $extension;
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
            
            if ($missing) {
                \DB::table('missing_files_on_permanent_storage')
                    ->where('item_id', $acmsRow->itemID)
                    ->update(['not_deleted_reason' => 'partially or fully migrated to rosetta already']);
            }

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
    protected function _isStatusActive($acmsItemRow, $imageItemRow) : bool
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
    protected function _isClosedEqualToNo($acmsItemRow, $imageItemRow) : bool
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
    protected function _writeLogTop($string)
    {
        if (substr($string, -1) != '>') {
            $string .= "<br />";
        }

        $content = $string . file_get_contents($this->_log);

        file_put_contents($this->_log, $content);
    }

    protected function _closeLog()
    {
        $html = '<!doctype html><head><style>body {font-family:sans-serif}</style></head><html><body>';
        $endHtml = '</body></html>';

        $html = $html . (file_get_contents($this->_log)) .$endHtml;
        file_put_contents($this->_log, $html);
    }



}
