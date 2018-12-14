<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\APIController;
use Illuminate\Http\Response as IlluminateResponse;
use Response;
use Illuminate\Http\Request;
use App\Models\Notify;
use App\Notifications\NotifyToSlackChannel;

/**
 * Base API Controller.
 */
class SlackController extends APIController
{
    public function getPostReq(Request $request) 
    {
        $splits = explode(" ", $request->get('text'));
        $result = 'benhVienId=' . $splits[0] . ', phongId=' . $splits[1];
        $data = [
            'text' => $result,
            'date' => $request->get('date') ? $request->get('date') : ''
        ];
        (new Notify())->notify(new NotifyToSlackChannel($request->get('text').'---'));
        return Response::json($data, 201); // Status code her
    }
}