<?php
namespace App\Services;

use App\Http\Resources\HsbaResource;
use App\Http\Resources\PatientResource;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\HanhChinhRepository;
use Validator;

class HsbaService
{
    public function __construct(HsbaRepository $hsbaRepository,HanhChinhRepository $hanhChinhRepository)
    {
        $this->hsbaRepository = $hsbaRepository;
        $this->hanhChinhRepository = $hanhChinhRepository;
    }
    
    public function getHsbaByBenhNhanId($benhNhanId)
    {
        $data = $this->hsbaRepository->getHsbaByBenhNhanId($benhNhanId);
         
        return new HsbaResource($data);
    }
    
    public function getHsbaByHsbaId($hsbaId)
    {
        $data = $this->hsbaRepository->getHsbaByHsbaId($hsbaId);
         
        return $data;
    }
    
    public function updateHsba($hsbaId, array $input)
    {
        if(isset($input['tinh_thanh_pho_id']) && isset($input['quan_huyen_id']) && isset($input['phuong_xa_id'])){
            $tenTinh=$this->hanhChinhRepository->getDataTinhById($input['tinh_thanh_pho_id']);
            $tenHuyen=$this->hanhChinhRepository->getDataHuyenById($input['tinh_thanh_pho_id'],$input['quan_huyen_id']);
            $tenXa=$this->hanhChinhRepository->getDataXaById($input['tinh_thanh_pho_id'],$input['quan_huyen_id'],$input['phuong_xa_id']);
            $input['ten_tinh_thanh_pho']=$tenTinh->ten_tinh;
            $input['ten_quan_huyen']=$tenHuyen->ten_huyen;
            $input['ten_phuong_xa']=$tenXa->ten_xa;
        }
        $this->hsbaRepository->updateHsba($hsbaId, $input);
    }
    
    
}