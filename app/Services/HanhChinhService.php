<?php
namespace App\Services;

use App\Repositories\HanhChinhRepository;
use Illuminate\Http\Request;
use Validator;

class HanhChinhService
{
    public function __construct(HanhChinhRepository $hanhChinhRepository)
    {
        $this->hanhChinhRepository = $hanhChinhRepository;
    }
    
    public function getListTinh()
    {
        $data = $this->hanhChinhRepository->getListTinh();
        return $data;
    }
    
    public function getListHuyen($maTinh)
    {
        $data = $this->hanhChinhRepository->getListHuyen($maTinh);
        return $data;
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        $data = $this->hanhChinhRepository->getListXa($maHuyen,$maTinh);
        return $data;
    }  
    
    public function getThxByKey($thxKey)
    {
        $data = $this->hanhChinhRepository->getThxByKey($thxKey);
        return $data;
    }     
    
}