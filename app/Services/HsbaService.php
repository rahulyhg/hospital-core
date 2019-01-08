<?php
namespace App\Services;

use App\Http\Resources\HsbaResource;
use App\Http\Resources\PatientResource;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\HanhChinhRepository;
use App\Models\ValueObjects\NhomNguoiThan;
use Validator;

class HsbaService
{
    private $dataNhomNguoiThan = null;
    
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
            try{
                $tinh=$this->hanhChinhRepository->getDataTinhById($input['tinh_thanh_pho_id']);
                $huyen=$this->hanhChinhRepository->getDataHuyenById($input['tinh_thanh_pho_id'],$input['quan_huyen_id']);
                $xa=$this->hanhChinhRepository->getDataXaById($input['tinh_thanh_pho_id'],$input['quan_huyen_id'],$input['phuong_xa_id']);
                $input['ten_tinh_thanh_pho']=$tinh->ten_tinh;
                $input['ten_quan_huyen']=$huyen->ten_huyen;
                $input['ten_phuong_xa']=$xa->ten_xa;
                $this->dataNhomNguoiThan = new NhomNguoiThan($input['loai_nguoi_than'], $input['ten_nguoi_than'], $input['dien_thoai_nguoi_than']);
                $input['nguoi_than'] = $this->dataNhomNguoiThan->toJsonEncoded();
                unset($input['loai_nguoi_than'],$input['ten_nguoi_than'],$input['dien_thoai_nguoi_than']);                

            } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
            }
        }
        
        $this->hsbaRepository->updateHsba($hsbaId, $input);
    }
    
    
}