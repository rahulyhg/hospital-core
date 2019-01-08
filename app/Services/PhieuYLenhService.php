<?php

namespace App\Services;

use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\Auth\AuthUsersRepository;
use App\Repositories\DieuTri\DieuTriRepository;
use App\Repositories\PhongRepository;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class PhieuYLenhService {
  
    public function __construct(PhieuYLenhRepository $phieuYLenhRepository,
                                AuthUsersRepository $authUsersRepository,
                                DieuTriRepository $dieuTriRepository,
                                PhongRepository $phongRepository)
    {
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->authUsersRepository = $authUsersRepository;
        $this->dieuTriRepository = $dieuTriRepository;
        $this->phongRepository = $phongRepository;
    }

    public function getListPhieuYLenh($hsbaId)
    {
        $data = $this->phieuYLenhRepository->getListPhieuYLenh($hsbaId);
        foreach($data as $item){
            $inforUser = $this->authUsersRepository->getInforAuthUserById($item['auth_users_id']);
            $inforDieuTri = $this->dieuTriRepository->getInforDieuTriById($item['dieu_tri_id']);
            $inforPhong = $this ->phongRepository->getDataById($item['phong_id']);
            $item['auth_users_name'] = $inforUser->fullname;
            $item['thoi_gian_chi_dinh'] = $inforDieuTri->thoi_gian_chi_dinh;
            $item['phong'] = $inforPhong->ten_phong;
        }
        return $data;
    }  

}