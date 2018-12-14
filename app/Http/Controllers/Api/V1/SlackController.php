<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;
use Response;
use Illuminate\Http\Request;
use App\Models\Notify;
use App\Notifications\NotifyToSlackChannel;

/**
 * Base API Controller.
 */
class SlackController extends Controller
{
    public function getPostReq(Request $request) 
    {
        $splits = explode(" ", $request->get('text'));
        $result = sizeof($splits) > 1 ? 'benhVienId=' . $splits[0] . ', phongId=' . $splits[1] : '';
        $data = [
            'text' => $result
        ];
        (new Notify())->notify(new NotifyToSlackChannel($result));
        return Response::json($data, 201); // Status code her
    }
}