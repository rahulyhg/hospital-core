<?php
namespace App\Repositories\BenhNhan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\BenhNhan;


class BenhNhanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return BenhNhan::class;
    }
    
    public function createDataBenhNhan(array $input)
    {
         $id = BenhNhan::create($input)->id;
         return $id;
    }
}