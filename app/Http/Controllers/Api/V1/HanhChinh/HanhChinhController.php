<?php
namespace App\Http\Controllers\Api\V1\HanhChinh;

use Illuminate\Http\Request;
use App\Services\HanhChinhService;
use App\Services\DieuTriService;
use App\Services\HsbaKhoaPhongService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class HanhChinhController extends APIController {
    public function __construct(
        HanhChinhService $hanhChinhService,
        DieuTriService $dieuTriService,
        HsbaKhoaPhongService $hsbaKhoaPhongService
    )
    {
        $this->hanhChinhService = $hanhChinhService;
        $this->dieuTriService = $dieuTriService;
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
    }
    
    public function luuNhapKhoa(Request $request)
    {
        $input = $request->all();
        $this->hanhChinhService->luuNhapKhoa($input);
        $this->setStatusCode(201);
        return $this->respond([]);
    }
    
    public function getListPhongHanhChinh($benhVienId, Request $request)
    {
        // main params
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $phongId = $request->query('phongId', null);
        $khoaId = $request->query('khoaId', null);
        
        // optional params        
        $thoiGianRaVienFrom = $request->query('thoi_gian_ra_vien_from',null);
        $thoiGianRaVienTo = $request->query('thoi_gian_ra_vien_to',null);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        $loaiBenhAn = $request->query('loaiBenhAn', null);
        
        $options = [
            'keyword'                   => $keyword,
            'status_hsba_khoa_phong'    => $status,
            'loai_benh_an'              => $loaiBenhAn,
            'thoi_gian_ra_vien_from'    => $thoiGianRaVienFrom,
            'thoi_gian_ra_vien_to'      => $thoiGianRaVienTo,
        ];
        
        if($benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $listBenhNhan = $this->hsbaKhoaPhongService->getListPhongHanhChinh($benhVienId, $khoaId, $phongId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
    
    public function getPhongChoByHsbaId($hsbaId, $phongId)
    {
        if(is_numeric($hsbaId)) {
            $data = $this->hsbaKhoaPhongService->getPhongChoByHsbaId($hsbaId, $phongId);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
}