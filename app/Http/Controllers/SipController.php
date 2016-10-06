<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\SipService;


class SipController extends Controller
{
    public function generateStandAlone(Request $request, SipService $sipService)
    {
        $itemId = $request->get('itemId');

        $zipPath = $sipService->generateSip($itemId);

        if ($zipPath !== false) {
            if (file_exists($zipPath)){
                return response()->download($zipPath);
            } else {
                return 'File not created';
            }

        }

    }
}
