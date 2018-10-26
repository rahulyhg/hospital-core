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
    
    public function getInfoPatientByBhytCode($bhytcode)
    {
        $result = $this->model->where('bhytcode', $bhytcode)->first();
        
        if ($result == null) {
            return ['message' => 'not found'];
        } else {
            $data = Hsba::findOrFail($result['hosobenhanid']);
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
}