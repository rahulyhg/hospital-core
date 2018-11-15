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
        $id = VienPhi::create($input)->id;
        return $id;
    }
    
    public function updateVienPhi($vienPhiId, $params)
    {
        $vienPhi = VienPhi::findOrFail($vienPhiId);
		$vienPhi->update($params);
    }
 
}