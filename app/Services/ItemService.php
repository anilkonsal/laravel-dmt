<?php

namespace App\Services;
use Database\Repositories\ItemRepository;

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

}
