<?php
namespace App\Http\Controllers\Api\V1\Hsba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaService;


// 3rd party library
use Carbon\Carbon;

class HsbaController extends APIController
{
    public function __construct(HsbaService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        //$data = $this->service->getDataPatient($request);
        
        //return $data;
    }
    
    public function getListThuNgan($benhVienId, Request $request)
    {
        // main params
        $limit = $request->query('limit', 1000);
        $page = $request->query('page', 1);
        
        // optional params        
        $thoiGianVaoVienFrom = $request->query('thoi_gian_vao_vien_from',null);
        $thoiGianVaoVienTo = $request->query('thoi_gian_vao_vien_to',null);
        $thoiGianRaVienFrom = $request->query('thoi_gian_ra_vien_from',null);
        $thoiGianRaVienTo = $request->query('thoi_gian_ra_vien_to',null);
        $keyword = $request->query('keyword', '');
        $status = $request->query('statusHsba', null);
        $loaiVienPhi = $request->query('loaiVienPhi', null);
        $loaiBenhAn = $request->query('loaiBenhAn', null);
        
        $options = [
            'keyword'                   => $keyword,
            'loai_vien_phi'             => $loaiVienPhi,
            'loai_benh_an'              => $loaiBenhAn,
            'status_hsba'               => $status,
            'thoi_gian_vao_vien_from'   => $thoiGianVaoVienFrom,
            'thoi_gian_vao_vien_to'     => $thoiGianVaoVienTo,
            'thoi_gian_ra_vien_from'    => $thoiGianRaVienFrom,
            'thoi_gian_ra_vien_to'      => $thoiGianRaVienTo,
        ];
        
        if($benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $listBenhNhan = $this->service->getList($benhVienId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
}
