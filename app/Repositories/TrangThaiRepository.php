<?php
namespace App\Repositories;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\SttPhongKham;
use Carbon\Carbon;

class TrangThaiRepository extends BaseRepositoryV2
{
    public function getModel() {}
    public function setModel() {}
    
    public function changeToState($tableModel, $attributes) {
        if(sizeof($attributes) > 0) 
        {
            $updateAttributes = [ $attributes['statusColumn'] => $attributes['newStatus'] ];
            $extraUpdate = $attributes['extraUpdate'];
            if(sizeof($extraUpdate) > 0) $updateAttributes = array_merge($updateAttributes, $extraUpdate);
            $tableModel->where($attributes['idColumn'], '=', $attributes['idValue'])->update($updateAttributes);
        }
    } 
    
}