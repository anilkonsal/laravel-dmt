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

    public function standalone(ItemService $itemService)
    {

        $mastersCount = $itemService->getMastersCount();
        $comastersCount = $itemService->getComastersCount();
        $hiresCount = $itemService->getHiresCount();
        $stdresCount = $itemService->getStdresCount();
        $previewCount = $itemService->getPreviewCount();
        $thumbnailCount = $itemService->getThumbnailCount();

        $albumMastersCount = $itemService->getAlbumMastersCount();
        $albumComastersCount = $itemService->getAlbumComastersCount();
        $albumHiresCount = $itemService->getAlbumHiresCount();
        $albumStdresCount = $itemService->getAlbumStdresCount();
        $albumPreviewCount = $itemService->getAlbumPreviewCount();
        $albumThumbnailCount = $itemService->getAlbumThumbnailCount();

        $totalImagesCount = $mastersCount + $comastersCount + $hiresCount + $stdresCount + $previewCount + $thumbnailCount;
        $totalAlbumImagesCount = $albumMastersCount + $albumComastersCount + $albumHiresCount + $albumStdresCount + $albumPreviewCount + $albumThumbnailCount;

        $standaloneImagesCount = $totalImagesCount - $totalAlbumImagesCount;

        return view('report.standalone', [
            'masters_count' => $mastersCount - $albumMastersCount,
            'comasters_count' => $comastersCount - $albumComastersCount,
            'hires_count' => $hiresCount - $albumHiresCount,
            'stdres_count' => $stdresCount - $albumStdresCount,
            'preview_count' => $previewCount - $albumPreviewCount,
            'thumbnail_count' => $thumbnailCount - $albumThumbnailCount,
            'standalone_images_count'    =>  $standaloneImagesCount
        ]);
    }

    public function details(Request $request, ItemService $itemService)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'item_id' => 'required|integer',
            ]);

            $itemID = $request->input('item_id');


            if (empty($itemID)) {
                throw new \InvalidArgumentException( 'Please provide the item ID', '400');
            }

            $count = $itemService->getDetails($itemID);
            return view('report.details', ['item_id' => $itemID, 'count' => $count]);
        }
        return view('report.details');


    }

}
