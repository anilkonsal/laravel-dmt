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

    public function doIngestQa(string $date)
    {
        $pdsHandle = $this->_getPdsHandle();




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
