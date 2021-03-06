<?php
namespace App\Repositories\Bhyt;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Bhyt;
use App\Models\Hsba;
use App\Http\Resources\HsbaResource;

class BhytRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Bhyt::class;
    }
    
    public function getInfoPatientByBhytCode($bhytCode)
    {
        $result = $this->model->where('ms_bhyt', $bhytCode)->first();
        
        if ($result == null) {
            return ['message' => 'not found'];
        } else {
            $data = Hsba::findOrFail($result['hsba_id']);
            //return new HsbaResource($data);
            return $data;
        }
    }
    
    public function createDataBhyt(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function checkMaSoBhyt($ms_bhyt)
    {
        $column = [
            'benh_nhan_id', 
        ];
        $result = $this->model->where('bhyt.ms_bhyt', $ms_bhyt)
                            ->get($column)
                            ->first(); 
        return $result;
    }
    
    public function updateBhyt($hsbaId, $request)
    {
        $bhyt = $this->model->where('hsba_id', '=', $hsbaId);
		$bhyt->update($request->all());
    }
    
    public function getMaBhytTreEm($maTinh)
    {
        // $result = $this->model->where('ms_bhyt', 'LIKE', 'TE1'.'%')
        //                 ->orderBy('ms_bhyt','desc')
        //                 ->first();
        $data = $this->model->where('ms_bhyt', 'LIKE', 'TE1'.'%')
                        ->get();
        $array = [];
        if($data){
            foreach ($data as $item){
                $array[] = substr($item->ms_bhyt,7,8);
            }
        }
        $maxValue = max($array);
        if($maxValue){
            //$result = substr($result->ms_bhyt,7,8)+1;
            $bhytCode = 'TE1'.sprintf('%02d',$maTinh).'KT'.sprintf('%08d',$maxValue+=1);
        }
        else
            $bhytCode = 'TE1'.sprintf('%02d',$maTinh).'KT'.'00000001';
		return $bhytCode;
    }    
    
    public function getBhytByBenhNhanIdAndHsbaId($benhNhanId, $hsbaId) {
        $where = [
            ['byht.benh_nhan_id', '=', $benhNhanId],
            ['byht.hsba_id', '=', $hsbaId],
        ];
        
        $result = $this->model->where($where)->get()->first();
        return $result;
    }
}
