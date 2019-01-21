<?php
namespace App\Http\Controllers\Api\V1\KhoaPhong;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\KhoaService;
use App\Services\PhongService;
use App\Http\Requests\KhoaFormRequest;
use App\Http\Requests\PhongFormRequest;

class KhoaPhongController extends APIController
{
    public function __construct(KhoaService $khoaService, PhongService $phongService)
    {
        $this->khoaService = $khoaService;
        $this->phongService = $phongService;
    }
    
    public function createKhoa($benhVienId, KhoaFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->khoaService->createKhoa($benhVienId, $input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }

    public function updateKhoa($id, KhoaFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->except('ten_benh_vien', 'loai_khoa');
            
            if($isNumericId) {
                $this->khoaService->updateKhoa($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function deleteKhoa($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->khoaService->deleteKhoa($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function createPhong($khoaId, PhongFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->phongService->createPhong($khoaId, $input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }

    public function updatePhong($id, PhongFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            
            if($isNumericId) {
                $this->phongService->updatePhong($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function deletePhong($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->phongService->deletePhong($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getListKhoaByBenhVienIdKeywords($benhVienId, Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords');
        
        $data = $this->khoaService->getListKhoaByBenhVienIdKeywords($benhVienId, $limit, $page, $keyWords);
        return $this->respond($data);
    }
    
    public function getKhoaById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->khoaService->getKhoaById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getPhongById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->phongService->getPhongById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }

}