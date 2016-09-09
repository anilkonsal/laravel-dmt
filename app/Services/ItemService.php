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

    public function getAlbumsCount()
    {
        return $this->itemRepository->getAlbumsCount();
    }

    public function getImagesInAlbumsCount()
    {
        return $this->itemRepository->getImagesInAlbumsCount();
    }

}
