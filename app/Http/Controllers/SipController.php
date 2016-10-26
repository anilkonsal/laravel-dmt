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

            $logFileName = 'log-'.$itemId.'-standalone.html';

            $logFile = public_path().'/downloads/sips/'.$logFileName;
            $logFileUrl = '/downloads/sips/'.$logFileName;
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
            $forceGeneration = $request->input('force_generation');

            if (empty($itemId)) {
                throw new \InvalidArgumentException( 'Please provide the item ID', '400');
            }


            $logFileName = 'log-'.$itemId.'-album.html';

            $logFile = public_path().'/downloads/sips/'.$logFileName;
            $logFileUrl = '/downloads/sips/'.$logFileName;
            $zipPath = $sipService->generateAlbumSip($itemId, $logFile, $forceGeneration);

            return view('sip.album', [
                'itemId' => $itemId,
                'albumZipPath'  =>  $zipPath,
                'forceGeneration' => $forceGeneration,
                'logFile'   => (file_exists($logFile)) ?  $logFileUrl : ''
            ]);
        }
        return view('sip.album');
    }
}
