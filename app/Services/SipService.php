<?php

namespace App\Services;

use Database\Repositories\ItemRepository;

class SipService {
    protected $_itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->_itemRepository = $itemRepository;
    }

    public function generateItemSip($itemId)
    {
        $mainFolder = $this->_generateFolders($itemId);


        $data = $this->_itemRepository->getSipDataForStandAlone($itemId);

        $xml = view('xml.sip.standalone', [
            'ie_dmd_identifier'     => $data['ie_dmd_identifier'],
            'ie_dmd_title'          => $data['ie_dmd_title'],
            'ie_dmd_creator'        => $data['ie_dmd_creator'],
            'ie_dmd_source'         => $data['ie_dmd_source'],
            'ie_dmd_type'           => $data['ie_dmd_type'],
            'ie_dmd_accessRights'   => $data['ie_dmd_accessRights'],
            'ie_dmd_date'           => $data['ie_dmd_date'],
            'ie_dmd_isFormatOf'      => $data['ie_dmd_isFormatOf'],

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

            'rep3_amd_rights'               => $data['rep3_amd_rights'],

        ]);

        file_put_contents($mainFolder.'/content/ie.xml', $xml);
        return $mainFolder;
    }

    public function generateSip($itemId)
    {

        $itemizedCounts = $this->_itemRepository->getDetails($itemId)['itemizedCounts'];
        $folders = [];

        foreach ($itemizedCounts as $childItemId => $counts) {
            if ($counts['standaloneImagesCount'] > 0) {
                $folders[] = $this->generateItemSip($childItemId);
            }
        }



        if (count($folders) > 0) {
            $zip = new \ZipArchive();
            $zipFilePath = public_path().'/downloads/sips/sips-'.$itemId.'-'.date('YmdHis').'.zip';

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

                   if (is_dir($file) === true)
                   {
                       $zip->addEmptyDir($baseSource.'/'.str_replace($source . '/', '', $file .'/'));
                   }
                   else if (is_file($file) === true)
                   {
                          $zip->addFromString($baseSource.'/'.str_replace($source . '/', '', $file), file_get_contents($file));
                   }
               }

            }
            $zip->close();
            return $zipFilePath;
        }
        return false;
    }

    /**
     * Function to generate the required folder structure for the Sips
     * @param  integer $itemId
     * @return array Paths of the Main folder;
     */
    protected function _generateFolders($itemId)
    {
        $sipFolder = public_path().'/downloads/sips';

        $dcIdentifierFolder = $sipFolder.'/dc_identifier_'.$itemId;
        $contentFolder = $dcIdentifierFolder.'/content';
        $streamFolder = $dcIdentifierFolder.'/streams';

        @mkdir($dcIdentifierFolder, 0775, true);
        @mkdir($contentFolder, 0775, true);
        @mkdir($streamFolder, 0775, true);

        return $dcIdentifierFolder;
    }
}
