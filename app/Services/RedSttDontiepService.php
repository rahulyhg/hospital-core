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
    public function getSTTKM($age)
    {
        $stt_tk = $this->RedSttDontiepRepository->getSTTTK($age);
        return [
            'so_thu_tu' => $stt_tk,
            'patientdata' => null,
            ];
    }
    public function getCurrentSTTBT(){
        $stt_dontiep_bt = $this->RedSttDontiepRepository->getCurrentSTTBT();
        $data = array(
                'so_thu_tu' => $stt_dontiep_bt,
                'patientname' => null,
        );
        return [
            'so_thu_tu' => $stt_dontiep_bt,
            'patientname' => null,
            ];
    }
    public function insertCurrentSTTBT(array $attributes){
        $this->RedSttDontiepRepository->insertCurrentSTTBT($attributes);
    }
    public function getCurrentSTTUT()
    {
        $stt_dontiep_ut = $this->RedSttDontiepRepository->getCurrentSTTUT();
        return ['so_thu_tu' => $stt_dontiep_ut,
                'patientname' => null,
                ];
    }
    public function insertCurrentSTTUT(array $attributes)
    {
        $this->RedSttDontiepRepository->insertCurrentSTTUT($attributes);
    }
    //public function makeSttDontiep(Request $request)
    //{
     //   $this->repository->create($request->all());
     //   $stt_dontiep = RedSttDontiep::orderBy('id', 'desc')->first();
        
       // return new RedSttDontiepResource($stt_dontiep);
   // }
    
    //public function updateSttDontiep(Request $request, $id){
        //$stt_dontiep = $this->repository->showSttDontiep($id);
        //$this->repository->update($stt_dontiep, $request->all());
    //}
    
   
}