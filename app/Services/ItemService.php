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

    public function doIngestQa($ies)
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
    protected function _parseHTML(string $url)
    {
        include_once public_path().'/../thirdparty/simple_html_dom.php';

        $html = file_get_html($url);

        $pTags = $html->find('div.acms-content p');

        $image = $html->find('div[id=tabs] div.image img');

        $labels = $html->find('div.acms-content div.label');

        $i = ($labels[1]->innertext == 'Creator') ? 4 : 3;

        $file = $image[0]->src;
        $nFile = str_replace(['_DAMt','t.jpg','\\'], ['_DAMx','h.jpg','/'], $file);

        $arr = [
            'title'         =>  strip_tags($pTags[0]->innertext),
            'type'          =>  strip_tags($pTags[$i]->innertext),
            'source'        =>  strip_tags($pTags[$i+1]->innertext),
            'file'          =>  $nFile
        ];

        return $arr;
    }


    /**
     * Function to parse the METS XML and return array of information extracted
     * @param  string $xmlStr [description]
     * @return mixed Array of information or False otherwise
     */
    protected function _parseMETSXml(string $xmlStr)
    {
        $xmlDoc = simplexml_load_string($xmlStr);
        $xmlDoc->registerXPathNamespace("mets", "http://www.loc.gov/METS/");
        $xmlDoc->registerXPathNamespace("dc", "http://purl.org/dc/elements/1.1/");

        /*
        Extract the identifier field
         */
        $identifier = $xmlDoc->xpath('//mets:mets/mets:dmdSec/mets:mdWrap/mets:xmlData/dc:record/dc:identifier');
        $strIdentifier = (string) $identifier[0][0];

        /*
        Extract the title field
         */
        $title = $xmlDoc->xpath('//mets:mets/mets:dmdSec/mets:mdWrap/mets:xmlData/dc:record/dc:title');
        $strTitle = (string) $title[0][0];

        /*
        Extract the Type field
         */
        $type = $xmlDoc->xpath('//mets:mets/mets:dmdSec/mets:mdWrap/mets:xmlData/dc:record/dc:type');
        $strType = (string) $type[0][0];

        /*
        Extract the source field
         */
        $source = $xmlDoc->xpath('//mets:mets/mets:dmdSec/mets:mdWrap/mets:xmlData/dc:record/dc:source');
        $strSource = (string) $source[3][0];

        $strFileSource = (string) $source[2][0];

        $arr = [
            'identifier'    =>  $strIdentifier,
            'title'         =>  $strTitle,
            'type'          =>  $strType,
            'source'        =>  $strSource,
            'file'          =>  'http://acms.sl.nsw.gov.au/'. $strFileSource
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
