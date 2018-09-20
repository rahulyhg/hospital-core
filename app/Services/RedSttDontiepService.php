<?php

namespace App\Services;

use App\Models\RedSttDontiep;
use App\Http\Resources\RedSttDontiepResource;
use App\Repositories\SttDontiep\RedSttDontiepRepository;
use Illuminate\Http\Request;
use Validator;

class RedSttDontiepService {
    public function __construct(RedSttDontiepRepository $RedSttDontiepRepository)
    {
        $this->RedSttDontiepRepository = $RedSttDontiepRepository;
    }
    
    // public function showSttDontiep($id){
    //     $stt_dontiep = $this->repository->showSttDontiep($id);
        
    //     return new RedSttDontiepResource($stt_dontiep);
    // }
    
    // public function makeSttDontiep(Request $request)
    // {
    //     $this->repository->create($request->all());
    //     $stt_dontiep = RedSttDontiep::orderBy('id', 'desc')->first();
        
    //     return new RedSttDontiepResource($stt_dontiep);
    // }
    
    // public function updateSttDontiep(Request $request, $id){
    //     $stt_dontiep = $this->repository->showSttDontiep($id);
    //     $this->repository->update($stt_dontiep, $request->all());
    // }
   
    public function getInfoPatientByStt($stt, $id_phong, $id_benh_vien){
        $data = $this->RedSttDontiepRepository->getInfoPatientByStt($stt, $id_phong, $id_benh_vien);
        
        return new RedSttDontiepResource($data);
    }
    
    public function getListPatientByKhoaPhong($loaibenhanid, $departmentid, $id_benh_vien){
        
        
        //return new RedSttDontiepResource($data);
    }
}