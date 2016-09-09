<?php

namespace Database\Repositories;

use App\Item;

class ItemRepository {

    public function getMastersCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'image')
                ->where('masterRoot', 'like', '_MASTER%')
                ->count();
        return $count;
    }

    public function getComastersCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'image')
                ->where('fromRoot', 'like', '_COMASTER%')
                ->count();
        return $count;
    }

    public function getHiresCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'image')
                ->where('wroot', 'like', '_DAMx%')
                ->count();
        return $count;
    }

    public function getStdresCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'image')
                ->where('lroot', 'like', '_DAMl%')
                ->count();
        return $count;
    }

    public function getPreviewCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'image')
                ->where('proot', 'like', '_DAMp%')
                ->count();
        return $count;
    }

    public function getThumbnailCount()
    {
        $count = \DB::table('item')
                ->where('assetType', 'image')
                ->where('itemType', 'image')
                ->where('troot', 'like', '_DAMt%')
                ->count();
        return $count;
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
                ->join('collection','item.itemID', '=', 'collection.collectionID')
                ->where('assetType', 'image')
                ->where('itemType', 'Album')
                ->count();
        return $count;
    }


}
