<?php
namespace App\Http\Controllers\Api\V1\DanhMuc;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\DanhMucDichVuService;

class DanhMucController extends APIController
{
    public function __construct(DanhMucDichVuService $dmdvService)
    {
        $this->dmdvService = $dmdvService;
    }
    
    public function getListDanhMucDichVu(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        $data = $this->dmdvService->getListDanhMucDichVu($limit, $page);
        return $data;
    }
}