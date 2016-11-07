<?php

namespace App\Services;

use Database\Repositories\ItemRepository;

class SipService {
    protected $_itemRepository;


    public function __construct(ItemRepository $itemRepository)
    {
        $this->_itemRepository = $itemRepository;
    }


    public function generateItemSip($itemId, $logFile, $forceGeneration = false)
    {

        $data = $this->_itemRepository->getSipDataForStandAlone($itemId, $logFile, $forceGeneration);

        if ($data === false) {
            return false;
        }
        $mainFolder = $this->_generateFolders($itemId);

        $xml = view('xml.sip.standalone', [
            'ie_dmd_identifier'     => $data['ie_dmd_identifier'],
            'ie_dmd_title'          => $data['ie_dmd_title'],
            'ie_dmd_creator'        => $data['ie_dmd_creator'],
            'ie_dmd_source'         => $data['ie_dmd_source'],
            'ie_dmd_type'           => $data['ie_dmd_type'],
            'ie_dmd_accessRights'   => $data['ie_dmd_accessRights'],
            'ie_dmd_date'           => $data['ie_dmd_date'],
            'ie_dmd_isFormatOf'     => $data['ie_dmd_isFormatOf'],

            'fid1_1_dmd_title'      => $data['fid1_1_dmd_title'],
            'fid1_1_dmd_source'     => $data['fid1_1_dmd_source'],
            'fid1_1_dmd_description'=> $data['fid1_1_dmd_description'],
            'fid1_1_dmd_identifier' => $data['fid1_1_dmd_identifier'],
            'fid1_1_dmd_date'       => $data['fid1_1_dmd_date'],
            'fid1_1_dmd_isFormatOf' => $data['fid1_1_dmd_isFormatOf'],

            'fid1_2_dmd_title'      => $data['fid1_2_dmd_title'],
            'fid1_2_dmd_source'     => $data['fid1_2_dmd_source'],
            'fid1_2_dmd_description'=> $data['fid1_2_dmd_description'],
            'fid1_2_dmd_identifier' => $data['fid1_2_dmd_identifier'],
            'fid1_2_dmd_date'       => $data['fid1_2_dmd_date'],
            'fid1_2_dmd_isFormatOf' => $data['fid1_2_dmd_isFormatOf'],

            'fid1_3_dmd_title'      => $data['fid1_3_dmd_title'],
            'fid1_3_dmd_source'     => $data['fid1_3_dmd_source'],
            'fid1_3_dmd_description'=> $data['fid1_3_dmd_description'],
            'fid1_3_dmd_identifier' => $data['fid1_3_dmd_identifier'],
            'fid1_3_dmd_date'       => $data['fid1_3_dmd_date'],
            'fid1_3_dmd_isFormatOf' => $data['fid1_3_dmd_isFormatOf'],

            'fid1_1_amd_fileOriginalPath'   => $data['fid1_1_amd_fileOriginalPath'],
            'fid1_1_amd_fileOriginalName'   => $data['fid1_1_amd_fileOriginalName'],
            'fid1_1_amd_label'              => $data['fid1_1_amd_label'],
            'fid1_1_amd_groupID'            => $data['fid1_1_amd_groupID'],

            'fid1_2_amd_fileOriginalPath'   => $data['fid1_2_amd_fileOriginalPath'],
            'fid1_2_amd_fileOriginalName'   => $data['fid1_2_amd_fileOriginalName'],
            'fid1_2_amd_label'              => $data['fid1_2_amd_label'],
            'fid1_2_amd_groupID'            => $data['fid1_2_amd_groupID'],

            'fid1_3_amd_fileOriginalPath'   => $data['fid1_3_amd_fileOriginalPath'],
            'fid1_3_amd_fileOriginalName'   => $data['fid1_3_amd_fileOriginalName'],
            'fid1_3_amd_label'              => $data['fid1_3_amd_label'],
            'fid1_3_amd_groupID'            => $data['fid1_3_amd_groupID'],

            'rep1_amd_url'                  => $data['rep1_amd_url'],
            'rep2_amd_url'                  => $data['rep2_amd_url'],
            'rep3_amd_url'                  => $data['rep3_amd_url'],

            'rep1_1_label'                  => $data['rep1_1_label'],
            'rep2_1_label'                  => $data['rep2_1_label'],
            'rep3_1_label'                  => $data['rep3_1_label'],

            'rep1_amd_rights'               => $data['rep1_amd_rights'],
            'rep2_amd_rights'               => $data['rep2_amd_rights'],
            'rep3_amd_rights'               => $data['rep3_amd_rights'],

        ]);

        file_put_contents($mainFolder.'/content/ie.xml', $xml);
        return $mainFolder;
    }

    /**
     *
     * Function to generate the sip for an individual album and its images
     * @param  integer $itemId ACMS item id for the album
     * @param  String $logFile Log file name
     * @param  boolean $generateAlbumSip Whether to generate the marked migrated items
     * @return mixed   Folder where the xml file was generated and placed
     *                 False otherwise
     */
    public function generateAlbumItemSip($itemId, $logFile, $generateAlbumSip = false) {
        $data = $this->_itemRepository->getSipDataForAlbum($itemId, $logFile, $generateAlbumSip);

        if ($data === false) {
            return false;
        }
        $mainFolder = $this->_generateFolders($itemId);

        $xml = $this->_generateXMLForAlbumSip($data);

        file_put_contents($mainFolder.'/content/ie.xml', $xml);
        return $mainFolder;
    }

    /**
     * Function to generate the XML for Album SIP
     * @param  array $data Array of information
     * @return xml   The final generated xml
     */
    protected function _generateXMLForAlbumSip($data)
    {

        $itemData = $data[array_keys($data)[0]];

        $ieDmdXml = view('xml.sip.album.partials.ie-dmd', [
            'ie_dmd_identifier'     => $itemData['ie_dmd_identifier'],
            'ie_dmd_title'          => $itemData['ie_dmd_title'],
            'ie_dmd_creator'        => $itemData['ie_dmd_creator'],
            'ie_dmd_source'         => $itemData['ie_dmd_source'],
            'ie_dmd_type'           => $itemData['ie_dmd_type'],
            'ie_dmd_accessRights'   => $itemData['ie_dmd_accessRights'],
            'ie_dmd_date'           => $itemData['ie_dmd_date'],
            'ie_dmd_isFormatOf'     => $itemData['ie_dmd_isFormatOf'],
        ])->render();

        $fidDmdXml = '';
        $fidAmdXml = '';

        $fileSecAmdXml = '';
        $structMapRepXml = '';

        for($y=1; $y<=3; $y++) {

            $x=1;
            $fileSecChildAmdXml = '';
            $structMapRepChildXml = '';

            foreach ($data as $item) {

                $prefix1 = "fid".$x."-".$y;
                $prefix2 = "fid1_".$y;

                $fidx_y = 'fid'.$x.'-'.$y;

                $fidDmdXml .= view('xml.sip.album.partials.fid-dmd', [
                    "fidx_y"                    =>  $fidx_y,
                    'fidx_y_dmd_title'          =>  $item[$prefix2.'_dmd_title'],
                    'fidx_y_dmd_source'         =>  $item[$prefix2.'_dmd_source'],
                    'fidx_y_dmd_description'    =>  $item[$prefix2.'_dmd_description'],
                    'fidx_y_dmd_identifier'     =>  $item[$prefix2.'_dmd_identifier'],
                    'fidx_y_dmd_date'           =>  $item[$prefix2.'_dmd_date'],
                    'fidx_y_dmd_tableOfContents'=>  $item[$prefix2.'_dmd_tableOfContents'],
                    'fidx_y_dmd_isFormatOf'     =>  $item[$prefix2.'_dmd_isFormatOf'],
                ])->render();

                $fidAmdXml .= view('xml.sip.album.partials.fid-amd', [
                    "fidx_y"                        =>  $fidx_y,
                    'fidx_y_amd_fileOriginalPath'   =>  $item[$prefix2.'_amd_fileOriginalPath'],
                    'fidx_y_amd_fileOriginalName'   =>  $item[$prefix2.'_amd_fileOriginalName'],
                    'fidx_y_amd_label'              =>  $item[$prefix2.'_amd_label'],
                    'fidx_y_amd_groupID'            =>  $item[$prefix2.'_amd_groupID'],
                ])->render();

                $fileSecChildAmdXml .= view('xml.sip.album.partials.filesec-rep-child-amd', [
                    "fidx_y"                        =>  $fidx_y,
                    'fidx_y_amd_fileOriginalPath'   =>  $item[$prefix2.'_amd_fileOriginalPath']
                ])->render();

                $structMapRepChildXml .= view('xml.sip.album.partials.structmap-rep-child', [
                    "fidx_y"            =>  $fidx_y,
                    'repx_y_label'      =>  $item[$prefix2.'_amd_label']
                ])->render();

                $x++;
            }

            $fileSecAmdXml .= view('xml.sip.album.partials.filesec-rep-amd', [
                "repx"                        =>  'rep'.$y,
                'filesec_rep_child_amd'       =>  $fileSecChildAmdXml
            ])->render();

            $structMapRepXml .= view('xml.sip.album.partials.structmap-rep', [
                "repx_y"            =>  'rep'.$y.'-1',
                'rep_label'    =>       $this->_getRepLabel($y),
                'structmap_rep_child'      =>  $structMapRepChildXml
            ])->render();

        }

        $fileSecXml = view('xml.sip.album.partials.filesec-rep-wrapper', [
            'filesec_rep_amd'       =>  $fileSecAmdXml
        ])->render();

        $ieAmdXml = view('xml.sip.album.partials.ie-amd')->render();

        $repAmdXml = view('xml.sip.album.partials.rep-amd', [
                'rep3_amd_rights'   =>  $itemData['rep3_amd_rights']
            ])->render();

        $xml = view('xml.sip.album.album', [
            'xml'   =>  $ieDmdXml .
                        $fidDmdXml .
                        $ieAmdXml .
                        $repAmdXml .
                        $fidAmdXml .
                        $fileSecXml .
                        $structMapRepXml
        ])->render();

        return $xml  ;
    }



    /**
     * Function to generate the SIP for all albums belonging to an item id
     * @param  Integer $itemId ACMS item id
     * @param  String $logFile log file name
     * @param  Boolean $generateAlbumSip Whether to force regenerate the marked migrated items
     * @return mixed    Zip file path on success otherwise False
     */
    public function generateAlbumSip($itemId, $logFile, $generateAlbumSip = false)
    {
        $itemizedCounts = $this->_itemRepository->getDetails($itemId)['itemizedCounts'];
        $folders = [];

        if(file_exists($logFile)) {
            unlink($logFile);
        }



        foreach ($itemizedCounts as $childItemId => $counts) {
            if ($counts['albumsCount'] > 0) {
                $result = $this->generateAlbumItemSip($childItemId, $logFile, $generateAlbumSip);
                if ($result !== false) {
                    $folders[] = $result;
                }
            }
        }

        if (count($folders) > 0) {
            return $this->_generateZip($itemId, $folders);
        }

        return false;
    }

    /**
     * Function to generate sip f standalone images
     * @param  integer $itemId  ACMS row id for the item
     * @param  string $logFile Path of the log file to be generated
     * @return mixed   Path of zip file on success or false otherwise
     */
    public function generateSip($itemId, $logFile, $forceGeneration = false)
    {
        $itemizedCounts = $this->_itemRepository->getDetails($itemId)['itemizedCounts'];
        $folders = [];

        if (file_exists($logFile)) {
            unlink($logFile);
        }

        foreach ($itemizedCounts as $childItemId => $counts) {
            if ($counts['standaloneImagesCount'] > 0) {
                $result = $this->generateItemSip($childItemId, $logFile, $forceGeneration);
                if ($result !== false) {
                    $folders[] = $result;
                }
            }
        }

        if (count($folders) > 0) {
            return $this->_generateZip($itemId, $folders);
        }

        return false;
    }

    /**
     * Function to generate sip of records with PDFs as master or comanster
     * @param  string $logFile Path of the log file to be generated
     * @return mixed   Path of zip file on success or false otherwise
     */
    public function generatePDFSip($logFile, $forceGeneration = false)
    {
        $acmsRows = $this->_itemRepository->getAllPDFrecords();
        $folders = [];

        if (file_exists($logFile)) {
            unlink($logFile);
        }

        foreach ($acmsRows as $row) {
            $result = $this->generateItemSip($row->itemID, $logFile, $forceGeneration);
            if ($result !== false) {
                $folders[] = $result;
            }
        }

        if (count($folders) > 0) {
            return $this->_generateZip('PDFs', $folders);
        }

        return false;
    }

    /**
     * Function to generate the required folder structure for the Sips
     * @param  integer $itemId
     * @return array Paths of the Main folder;
     */
    protected function _generateFolders($itemId) : string
    {
        $sipFolder = public_path().'/downloads/sips';

        $dcIdentifierFolder = $sipFolder.'/dc_identifier_'.$itemId;
        $contentFolder = $dcIdentifierFolder.'/content';
        $streamFolder = $contentFolder.'/streams';

        @mkdir($dcIdentifierFolder, 0775, true);
        @mkdir($contentFolder, 0775, true);
        @mkdir($streamFolder, 0775, true);

        return $dcIdentifierFolder;
    }

    /**
     * Function to generate the Zip file
     * @param  String $itemId
     * @param  Array  $folders Folders to be zipped
     * @return string Zip file URL
     */
    protected function _generateZip($itemId, Array $folders) : string
    {
        $zip = new \ZipArchive();
        $zipRelativePath = '/downloads/sips/sips-'.$itemId.'-'.date('YmdHis').'.zip';

        $zipFilePath = public_path(). $zipRelativePath;
        $zipFileUrl =  $zipRelativePath;

        $zip->open( $zipFilePath, \ZipArchive::CREATE);

        foreach($folders as $source) {
            $baseSource = basename($source);
            $zip->addEmptyDir($source);

            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
               $file = str_replace('\\', '/', $file);
               if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                   continue;

               $file = realpath($file);
               if (is_dir($file) === true) {
                   $zip->addEmptyDir($baseSource.'/'.str_replace($source . '/', '', $file .'/'));
               } else if (is_file($file) === true) {
                  $zip->addFromString($baseSource.'/'.str_replace($source . '/', '', $file), file_get_contents($file));
               }
           }
        }
        $zip->close();
        return $zipFileUrl;
    }

    /**
     * Function to get the Label of Representation based on the Rep number
     * @param  integer $i the Representation number
     * @return string    [description]
     */
    protected function _getRepLabel($i) : string
    {
        if ($i == 1) {
            return 'PRESERVATION MASTER';
        } elseif ($i == 2) {
            return 'COMASTER';
        } elseif ($i ==3) {
            return 'SCREEN';
        }
    }

}
