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
}