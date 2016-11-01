<?php

namespace App\Services;

use Database\Repositories\ItemRepository;
use GuzzleHttp\Client;

class ItemService {
    public $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        return $this->itemRepository = $itemRepository;
    }

    public function getMastersCount()
    {
        return $this->itemRepository->getMastersCount();
    }

    public function getComastersCount()
    {
        return $this->itemRepository->getComastersCount();
    }

    public function getHiresCount()
    {
        return $this->itemRepository->getHiresCount();
    }

    public function getStdresCount()
    {
        return $this->itemRepository->getStdresCount();
    }

    public function getPreviewCount()
    {
        return $this->itemRepository->getPreviewCount();
    }

    public function getThumbnailCount()
    {
        return $this->itemRepository->getThumbnailCount();
    }

    public function getAlbumMastersCount()
    {
        return $this->itemRepository->getMastersCount(ItemRepository::TYPE_ALBUM);
    }

    public function getAlbumComastersCount()
    {
        return $this->itemRepository->getComastersCount(ItemRepository::TYPE_ALBUM);
    }

    public function getAlbumHiresCount()
    {
        return $this->itemRepository->getHiresCount(ItemRepository::TYPE_ALBUM);
    }

    public function getAlbumStdresCount()
    {
        return $this->itemRepository->getStdresCount(ItemRepository::TYPE_ALBUM);
    }

    public function getAlbumPreviewCount()
    {
        return $this->itemRepository->getPreviewCount(ItemRepository::TYPE_ALBUM);
    }

    public function getAlbumThumbnailCount()
    {
        return $this->itemRepository->getThumbnailCount(ItemRepository::TYPE_ALBUM);
    }

    public function getAlbumsCount()
    {
        return $this->itemRepository->getAlbumsCount();
    }

    public function getImagesInAlbumsCount()
    {
        return $this->itemRepository->getImagesInAlbumsCount();
    }

    public function getDetails($itemID, $debug)
    {
        return $this->itemRepository->getDetails($itemID, $debug);
    }

    public function getAlbumImagesNotMigratedCounts()
    {
        return $this->itemRepository->getAlbumImagesNotMigratedCounts();
    }
    public function getStandaloneImagesNotMigratedCounts()
    {
        return $this->itemRepository->getStandaloneImagesNotMigratedCounts();
    }

    public function acmsAlbumsMigrationCounts()
    {
        return $this->itemRepository->acmsAlbumsMigrationCounts();
    }

    public function milleniumAlbumsMigrationCounts()
    {
        return $this->itemRepository->milleniumAlbumsMigrationCounts();
    }

    public function getTotalAlbumCounts()
    {
        return $this->itemRepository->getTotalAlbumCounts();
    }

    /**
     * Function to do the ingest qa by accepting a list of IE ids
     * @param  String Comma separated list of IEs to be QAd.
     * @return Array An array containing data for parsed API values and HTML values
     */
    public function doIngestQa($ies) : array
    {
        $pdsHandle = $this->_getPdsHandle();

        if (!is_array($ies)) {
            $iesArray = explode(',', $ies);
        } else {
            $iesArray = $ies;
        }

        $data = [];

        if (empty($iesArray)) {
            throw new Exception("Empty IEs array");
        }

        foreach ($iesArray as $ie) {
            $xmlStr = $this->_fetchXmlFromApi($ie, $pdsHandle);

            $data[$ie]['api'] = $this->_parseMETSXml($xmlStr);

            $url = 'http://acmssearch.sl.nsw.gov.au/search/itemDetailPaged.cgi?itemID='.$data[$ie]['api']['identifier'];
            $data[$ie]['html'] = $this->_parseHTML($url);
        }

        return $data;
    }

    /**
     * Function to parse the HTML URL and return array of information extracted
     * @param  string $xmlStr [description]
     * @return mixed Array of information or False otherwise
     */
    protected function _parseHTML(string $url) : array
    {
        include_once public_path().'/../thirdparty/simple_html_dom.php';

        $html = file_get_html($url);

        $pTags = $html->find('div.acms-content p');


        $albumLink = $html->find('div[id=tabs-1] a[class=albumtab]');

        /*
        If this is a standalone image
         */
        if(empty($albumLink)) {
            $image = $html->find('div[id=tabs] div.image img');
            $file = $image[0]->src;
            $nFile = str_replace(['_DAMt','t.jpg','\\'], ['_DAMx','h.jpg','/'], $file);
            $filesArray[] = $nFile;
        } else {
            /*
            If this is an Album
             */

            $htmlStr = (string) $html;
            preg_match_all('/"md_A":\s"[_A-Za-z0-9\/\\\]+"/', $htmlStr, $matchesA);
            preg_match_all('/"md_E":\s"[_A-Za-z0-9\/\\\]+"/', $htmlStr, $matchesE);
            preg_match_all('/"md_M":\s"[_A-Za-z0-9\/\\\]+"/', $htmlStr, $matchesM);
            preg_match_all('/"md_F":\s"[_A-Za-z0-9\/\\\]+"/', $htmlStr, $matchesF);
            preg_match_all('/"sort":\s"[_A-Za-z0-9\/\\\]+"/', $htmlStr, $matchesS);
            $filesArray = [];


            $newA = array_map(function($value){
                $str = trim(explode(' ', $value)[1], '"');
                return str_replace('\\\\', '/', $str);
            }, $matchesA[0]);

            $newE = array_map(function($value){
                $str = trim(explode(' ', $value)[1], '"');
                return $str;
            }, $matchesE[0]);

            $newM = array_map(function($value){
                $str = trim(explode(' ', $value)[1], '"');
                return $str;
            }, $matchesM[0]);

            $newF = array_map(function($value){
                $str = trim(explode(' ', $value)[1], '"');
                return $str;
            }, $matchesF[0]);

            $newS = array_map(function($value){
                $str = trim(explode(' ', $value)[1], '"');
                return $str;
            }, $matchesS[0]);

            if (!empty($newA)) {
                foreach ($newA as $key => $value) {
                    $nFile = 'http://acms.sl.nsw.gov.au/'.$value.'/'.$newE[$key].'/'.$newM[$key].'h.'.$newF[$key];
                    $nFile = str_replace('DAMt', 'DAMx', $nFile);
                    $filesArray[$newS[$key]] = $nFile;
                }
            }
        }

        ksort($filesArray);
        $filesArray = array_values($filesArray);

        $labels = $html->find('div.acms-content div.label');

        $i = ($labels[1]->innertext == 'Creator') ? 4 : 3;

        $arr = [
            'title'         =>  strip_tags($pTags[0]->innertext),
            'type'          =>  strip_tags($pTags[$i]->innertext),
            'source'        =>  strip_tags($pTags[$i+1]->innertext),
            'files'          =>  $filesArray
        ];

        return $arr;
    }



    /**
     * Function to parse the METS XML and return array of information extracted
     * @param  string $xmlStr [description]
     * @return mixed Array of information or False otherwise
     */
    protected function _parseMETSXml(string $xmlStr) : array
    {

        $xmlDoc = simplexml_load_string($xmlStr);
        $xmlDoc->registerXPathNamespace("mets", "http://www.loc.gov/METS/");
        $xmlDoc->registerXPathNamespace("dc", "http://purl.org/dc/elements/1.1/");

        /*
        Extract the identifier field
         */
        $identifier = $xmlDoc->xpath('//mets:mets/mets:dmdSec[contains(@ID,"ie-dmd")]/mets:mdWrap/mets:xmlData/dc:record/dc:identifier');
        $strIdentifier = (string) $identifier[0][0];


        /*
        Extract the title field
         */
        $title = $xmlDoc->xpath('//mets:mets/mets:dmdSec[contains(@ID,"ie-dmd")]/mets:mdWrap/mets:xmlData/dc:record/dc:title');
        $strTitle = (string) $title[0][0];

        /*
        Extract the Type field(s)
         */
        $type = $xmlDoc->xpath('//mets:mets/mets:dmdSec[contains(@ID,"ie-dmd")]/mets:mdWrap/mets:xmlData/dc:record/dc:type');
        $t = [];
        if (!empty($type)) {
            foreach ($type as $value) {
                $t[] = $value[0];
            }
        }
        $strType = implode(', ', $t);

        /*
        Extract Image paths in the order mentioned in structMap part of the XML
         */
        $structMap = $xmlDoc->xpath('//mets:structMap/mets:div[contains(@LABEL,"SCREEN")]//mets:fptr');
        $structArray = [];
        if (!empty ($structMap)) {
            foreach ($structMap as $struct) {
                $fileId = (string) $struct->attributes()->FILEID;
                $structArray[] = $fileId;
                $fileArray[] = (string) $xmlDoc->xpath('//mets:dmdSec[contains(@ID,"'.$fileId.'-dmd")]//dc:description')[0];
            }
        }

        /*
        Extract the source field
         */
        $source = $xmlDoc->xpath('//mets:mets/mets:dmdSec[contains(@ID,"ie-dmd")]/mets:mdWrap/mets:xmlData/dc:record/dc:source');
        $strSource = (string) $source[0][0];



        $arr = [
            'identifier'    =>  $strIdentifier,
            'title'         =>  $strTitle,
            'type'          =>  $strType,
            'source'        =>  $strSource,
            'files'          =>  $fileArray
        ];

        return $arr;
    }

    /**
     * Function to fetcg the METs XML from SOAP API
     * @param  string $ie  The ie number
     * @param  string $pdsHandle The PDS Handle
     * @return string  The XML of MET
     */
    protected function _fetchXmlFromApi(string $ie, string $pdsHandle) : string
    {
        $soapClient = new \SoapClient('http://digital.sl.nsw.gov.au/dpsws/repository/IEWebServices?wsdl');

        $params = [
            'pdsHandle' =>  $pdsHandle,
            'iePid'     => $ie,
            'flags'     =>  0
        ];

        $res = $soapClient->getIE($params);

        $response = $res->getIE;

        return $response;
    }

    /**
     * Function to get the PDS Handle from the response of HTTP Reques to Exlibris
     * @return string The PDS Code
     */
    protected function _getPdsHandle() : string
    {
        $username = config('app.pds.username');
        $password = config('app.pds.password');
        $url = config('app.pds.url');

        $url = str_replace(['[username]', '[password]'], [$username, $password], $url);

        $client = new Client();
        $res = $client->request('GET', $url);
        $content = $res->getBody()->getContents();

        if (empty($content)) {
            throw new Exception( 'Empty response content from api call for fetching PDS Handle');
        }

        $pdsFound = preg_match('/pds_handle=(\w+)/', $content, $matches);

        if (!$pdsFound) {
            throw new Exception('PDS Handle was not found in the Response!');
        }

        $pdsHandle = $matches[1];

        return $pdsHandle;
    }

}
