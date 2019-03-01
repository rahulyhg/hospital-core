<?php
namespace App\Http\Controllers\Api\V1\NoiTru;

use Illuminate\Http\Request;
use App\Services\NoiTruService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class NoiTruController extends APIController {
    public function __construct(
        NoiTruService $noiTruService
    )
    {
        $this->noiTruService = $noiTruService;
    }
    
    public function luuNhapKhoa(Request $request)
    {
        $input = $request->all();
        $data = $this->noiTruService->luuNhapKhoa($input);
        return $this->respond($data);
    }
}