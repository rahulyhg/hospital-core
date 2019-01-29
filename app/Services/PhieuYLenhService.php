<?php

namespace App\Services;

use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\Auth\AuthUsersRepository;
use App\Repositories\DieuTri\DieuTriRepository;
use App\Repositories\PhongRepository;
use App\Repositories\YLenh\YlenhRepository;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class PhieuYLenhService {
  
    public function __construct(PhieuYLenhRepository $phieuYLenhRepository,
                                AuthUsersRepository $authUsersRepository,
                                DieuTriRepository $dieuTriRepository,
                                PhongRepository $phongRepository,
                                YLenhRepository $yLenhRepository
                                )
    {
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->authUsersRepository = $authUsersRepository;
        $this->dieuTriRepository = $dieuTriRepository;
        $this->phongRepository = $phongRepository;
        $this->yLenhRepository = $yLenhRepository;
    }

    public function getListPhieuYLenh($hsbaId,$type)
    {
        $data = $this->phieuYLenhRepository->getListPhieuYLenh($hsbaId);
        $result = [];
        foreach($data as $itemData){
            $yLenh = $this->yLenhRepository->getDetailPhieuYLenh($itemData->id,$type);
            if(count($yLenh)>0)
                $result[]=$itemData;
        }
        foreach($result as $itemResult){
            $inforUser = $this->authUsersRepository->getInforAuthUserById($itemResult['auth_users_id']);
            $inforDieuTri = $this->dieuTriRepository->getInforDieuTriById($itemResult['dieu_tri_id']);
            $inforPhong = $this ->phongRepository->getDataById($itemResult['phong_id']);
            $itemResult['auth_users_name'] = $inforUser->fullname;
            $itemResult['thoi_gian_chi_dinh'] = $inforDieuTri->thoi_gian_chi_dinh;
            $itemResult['phong'] = $inforPhong->ten_phong;
        }
        return $result;
    }  

}