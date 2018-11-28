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
        $id = Bhyt::create($input)->id;
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
        $bhyt = Bhyt::where('hsba_id', '=', $hsbaId);
		$bhyt->update($request->all());
    }
    
    public function getMaBhytTreEm($maTinh)
    {
        $result = Bhyt::where('ms_bhyt', 'LIKE', 'TE1'.'%')
                        ->orderBy('ms_bhyt','desc')
                        ->first();
        if($result){
            $result = substr($result->ms_bhyt,7,8);
            $code = $result+1;
            $bhytCode = 'TE-1'.'-'.$maTinh.'-'.'KT'.'-'.sprintf('%08d',$code);
        }
        else
            $bhytCode = 'TE-1'.'-'.$maTinh.'-'.'KT'.'-'.'00000001';
		return $bhytCode;
    }    
}
