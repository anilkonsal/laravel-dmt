<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\SipService;


class SipController extends Controller
{
    public function generateStandAlone(Request $request, SipService $sipService)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'item_id' => 'required|integer',
            ]);

            $itemId = trim($request->input('item_id'));
            $forceGeneration = $request->input('force_generation');

            if (empty($itemId)) {
                throw new \InvalidArgumentException( 'Please provide the item ID', '400');
            }

            $logFile = public_path().'/downloads/sips/log-'.$itemId.'.html';
            $logFileUrl = '/downloads/sips/log-'.$itemId.'.html';
            $zipPath = $sipService->generateSip($itemId, $logFile, $forceGeneration);

            return view('sip.standalone', [
                'itemId' => $itemId,
                'standAloneZipPath'  =>  $zipPath,
                'forceGeneration' => $forceGeneration,
                'logFile'   =>  $logFileUrl
            ]);
        }
        return view('sip.standalone');

    }

    public function generateAlbum(Request $request, SipService $sipService)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'item_id' => 'required|integer',
            ]);

            $itemId = trim($request->input('item_id'));
            $debug = $request->input('debug');

            if (empty($itemId)) {
                throw new \InvalidArgumentException( 'Please provide the item ID', '400');
            }

            $logFile = public_path().'/downloads/sips/log-'.$itemId.'.html';
            $logFileUrl = '/downloads/sips/log-'.$itemId.'.html';
            $zipPath = $sipService->generateAlbumSip($itemId, $logFile);

            return view('sip.album', [
                'itemId' => $itemId,
                'albumZipPath'  =>  $zipPath,
                'debug' => false,
                'logFile'   => (file_exists($logFile)) ?  $logFileUrl : ''
            ]);
        }
        return view('sip.album');
    }
}
