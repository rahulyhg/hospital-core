<?php
namespace App\Http\Controllers\Api\V1\DanhMuc;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucTongHopService;
use App\Services\DanhMucTrangThaiService;
use App\Services\DanhMucThuocVatTuService;
use App\Services\NhomDanhMucService;
use App\Http\Requests\DanhMucDichVuFormRequest;
use App\Http\Requests\DanhMucTongHopFormRequest;
use App\Http\Requests\DanhMucTrangThaiFormRequest;

class DanhMucController extends APIController
{
    public function __construct
    (
        DanhMucDichVuService $dmdvService,
        DanhMucTongHopService $dmthService, 
        DanhMucTrangThaiService $dmttService, 
        DanhMucThuocVatTuService $dmtvtService,
        NhomDanhMucService $nhomDanhMucService
    )
    {
        $this->dmdvService = $dmdvService;
        $this->dmthService = $dmthService;
        $this->dmttService = $dmttService;
        $this->dmtvtService = $dmtvtService;
        $this->nhomDanhMucService = $nhomDanhMucService;
    }
    
    public function getListDanhMucDichVu(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $loaiNhom = $request->query('loai_nhom', 0);
        
        $data = $this->dmdvService->getListDanhMucDichVu($limit, $page, $loaiNhom);
        return $this->respond($data);
    }
    
    public function getDmdvById($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $data = $this->dmdvService->getDmdvById($dmdvId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createDanhMucDichVu(DanhMucDichVuFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->dmdvService->createDanhMucDichVu($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucDichVu($dmdvId, DanhMucDichVuFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($dmdvId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmdvService->updateDanhMucDichVu($dmdvId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $this->dmdvService->deleteDanhMucDichVu($dmdvId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getYLenhByLoaiNhom($loaiNhom)
    {
        $isNumeric = is_numeric($loaiNhom);
        
        if($isNumeric) {
            $data = $this->dmdvService->getYLenhByLoaiNhom($loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getDanhMucDichVuPhongOc() {
        $data = $this->dmdvService->getDanhMucDichVuPhongOc();
        return $this->respond($data);
    }
    
    public function getListDanhMucTongHop(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $dienGiai = $request->query('dien_giai', '');
        $khoa = $request->query('khoa', '');
        
        $data = $this->dmthService->getListDanhMucTongHop($limit, $page, $dienGiai, $khoa);
        return $this->respond($data);
    }
    
    public function getDmthById($dmthId)
    {
        $isNumericId = is_numeric($dmthId);
        
        if($isNumericId) {
            $data = $this->dmthService->getDmthById($dmthId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getDanhMucTongHopTheoKhoa(Request $request, $khoa) {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        if($khoa === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        $data = $this->dmthService->getDanhMucTongHopTheoKhoa($khoa, $limit, $page);
        
        if(empty($data)) {
            $this->setStatusCode(400);
            $data = [];
        }
        return $this->respond($data);
    }
    
    public function createDanhMucTongHop(DanhMucTongHopFormRequest $request) {
        $input = $request->all();
        
        $id = $this->dmthService->createDanhMucTongHop($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucTongHop($dmthId, DanhMucTongHopFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($dmthId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmthService->updateDanhMucTongHop($dmthId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucTongHop($dmthId)
    {
        $isNumericId = is_numeric($dmthId);
        
        if($isNumericId) {
            $this->dmthService->deleteDanhMucTongHop($dmthId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getAllKhoaDanhMucTongHop()
    {
        $data = $this->dmthService->getAllKhoa();
        return $this->respond($data);  
    }
    
    public function getListDanhMucTrangThai(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $dienGiai = $request->query('dien_giai', '');
        $khoa = $request->query('khoa', '');
        
        $data = $this->dmttService->getListDanhMucTrangThai($limit, $page, $dienGiai, $khoa);
        return $this->respond($data);
    }
    
    public function getDanhMucTrangThaiTheoKhoa(Request $request, $khoa) {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        if($khoa === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        $data = $this->dmttService->getDanhMucTrangThaiTheoKhoa($khoa, $limit, $page);
        
        if(empty($data)) {
            $this->setStatusCode(400);
            $data = [];
        }
        return $this->respond($data);
    }
    
    public function getDmttById($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $data = $this->dmttService->getDanhMucTrangThaiById($dmdvId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createDanhMucTrangThai(DanhMucTrangThaiFormRequest $request) {
        $input = $request->all();
        
        $id = $this->dmttService->createDanhMucTrangThai($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucTrangThai($dmttId, DanhMucTrangThaiFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($dmttId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmttService->updateDanhMucTrangThai($dmttId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        $isNumericId = is_numeric($dmttId);
        
        if($isNumericId) {
            $this->dmttService->deleteDanhMucTrangThai($dmttId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getAllKhoaDanhMucTrangThai()
    {
        $data = $this->dmttService->getAllKhoa();
        return $this->respond($data);  
    }
    
    public function getThuocVatTuByLoaiNhom($loaiNhom)
    {
        $isNumeric = is_numeric($loaiNhom);
        
        if($isNumeric) {
            $data = $this->dmtvtService->getThuocVatTuByLoaiNhom($loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getThuocVatTuByCode($maNhom, $loaiNhom)
    {
        if($maNhom) {
            $data = $this->dmtvtService->getThuocVatTuByCode($maNhom, $loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListNhomDanhMuc()
    {
        $data = $this->nhomDanhMucService->getListNhomDanhMuc();
        return $this->respond($data);
    }
    
    public function getNhomDmById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->nhomDanhMucService->getNhomDmById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createNhomDanhMuc(Request $request) {
        $input = $request->all();
        
        $id = $this->nhomDanhMucService->createNhomDanhMuc($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateNhomDanhMuc($id, Request $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->nhomDanhMucService->updateNhomDanhMuc($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}