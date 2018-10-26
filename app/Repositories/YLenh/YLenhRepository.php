<?php
namespace App\Repositories\YLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\YLenh;

class YLenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return YLenh::class;
    }
    
    public function createDataYLenh(array $input)
    {
        $id = YLenh::create($input)->id;
        return $id;
    }
}