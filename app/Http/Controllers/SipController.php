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

            $itemID = trim($request->input('item_id'));
            $debug = $request->input('debug');

            if (empty($itemID)) {
                throw new \InvalidArgumentException( 'Please provide the item ID', '400');
            }

            // $allCounts = $itemService->getDetails($itemID, $debug);
            // $counts = $allCounts['counts'];
            // $itemizedCounts = $allCounts['itemizedCounts'];


            $zipPath = $sipService->generateSip($itemID);



            if ($zipPath !== false) {
                return view('sip.standalone', ['item_id' => $itemID, 'standAloneZipPath'  =>  $zipPath, 'debug' => false]);
            }


        }
        return view('sip.standalone');

    }
}
