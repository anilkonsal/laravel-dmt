<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Services\ItemService;
class ReportController extends Controller
{
    public function total(ItemService $itemService)
    {
        $mastersCount = $itemService->getMastersCount();
        $comastersCount = $itemService->getComastersCount();
        $hiresCount = $itemService->getHiresCount();
        $stdresCount = $itemService->getStdresCount();
        $previewCount = $itemService->getPreviewCount();
        $thumbnailCount = $itemService->getThumbnailCount();

        return view('report.total', [
            'masters_count' => $mastersCount,
            'comasters_count' => $comastersCount,
            'hires_count' => $hiresCount,
            'stdres_count' => $stdresCount,
            'preview_count' => $previewCount,
            'thumbnail_count' => $thumbnailCount,
        ]);
    }
    public function album(ItemService $itemService)
    {
        $albumsCount = $itemService->getAlbumsCount();
        $imagesInAlbumsCount = $itemService->getImagesInAlbumsCount();

        $mastersCount = $itemService->getAlbumMastersCount();
        $comastersCount = $itemService->getAlbumComastersCount();
        $hiresCount = $itemService->getAlbumHiresCount();
        $stdresCount = $itemService->getAlbumStdresCount();
        $previewCount = $itemService->getAlbumPreviewCount();
        $thumbnailCount = $itemService->getAlbumThumbnailCount();


        return view('report.album', [
            'masters_count' => $mastersCount,
            'comasters_count' => $comastersCount,
            'hires_count' => $hiresCount,
            'stdres_count' => $stdresCount,
            'preview_count' => $previewCount,
            'thumbnail_count' => $thumbnailCount,
            'albums_count'  =>  $albumsCount,
            'images_in_albums_count' => $imagesInAlbumsCount
        ]);
    }
}
