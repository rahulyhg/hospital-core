<?php
namespace App\Http\Controllers\Api\V1\SlackIntegration;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\APIController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SlackIntegrationController extends Controller
{
    public function checkSlack(Request $request) 
    {
        $txt = $request['text'];
        return response()->json($request);
    
    }
}
