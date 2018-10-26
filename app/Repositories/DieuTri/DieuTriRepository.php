<?php
namespace App\Repositories\DieuTri;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DieuTri;
use App\Http\Resources\HsbaResource;

class DieuTriRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DieuTri::class;
    }
    
    public function createDataDieuTri(array $input)
    {
        $id = DieuTri::create($input)->id;
        return $id;
    }
}