<?php
namespace App\Repositories\VienPhi;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\VienPhi;


class VienPhiRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return VienPhi::class;
    }

    public function createDataVienPhi(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateVienPhi($vienPhiId, $params)
    {
        $vienPhi = $this->model->findOrFail($vienPhiId);
		$vienPhi->update($params);
    }
 
}