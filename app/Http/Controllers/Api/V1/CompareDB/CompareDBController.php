<?php

namespace App\Http\Controllers\Api\V1\CompareDB;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\CompareDBService;

class CompareDBController extends APIController {
    public function __construct(CompareDBService $compareDBService)
    {
        $this->compareDBService = $compareDBService;
    }
    
    public function getInfoTable(Request $request) {
        $input = $request->all();
        $data = $this->compareDBService->getInfoTable($input);
        return $this->respond($data);
    }
}