<?php
namespace App\Http\Controllers\Api\V1\Hsba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;


// 3rd party library
use Carbon\Carbon;

class HsbaKhoaPhongController extends APIController
{
    public function __construct(HsbaKhoaPhongService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        //$data = $this->service->getDataPatient($request);
        
        //return $data;
    }
    
    public function getListKhoaKhamBenh($benhVienId, Request $request)
    {
        // main params
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $phongId = $request->query('phongId', null);
        
        // optional params        
        $thoiGianVaoVienFrom = $request->query('thoi_gian_vao_vien_from',null);
        $thoiGianVaoVienTo = $request->query('thoi_gian_vao_vien_to',null);
        $thoiGianRaVienFrom = $request->query('thoi_gian_ra_vien_from',null);
        $thoiGianRaVienTo = $request->query('thoi_gian_ra_vien_to',null);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        $loaiBenhAn = $request->query('loaiBenhAn', null);
        
        $options = [
            'keyword'                   => $keyword,
            'status_hsba_khoa_phong'    => $status,
            'loai_benh_an'              => $loaiBenhAn,
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
            $listBenhNhan = $this->service->getListKhoaKhamBenh($benhVienId,$phongId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
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
            $listBenhNhan = $this->service->getListThuNgan($benhVienId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
}
