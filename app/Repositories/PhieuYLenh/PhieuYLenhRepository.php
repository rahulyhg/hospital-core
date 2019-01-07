<?php
namespace App\Repositories\PhieuYLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuYLenh;

class PhieuYLenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhieuYLenh::class;
    }
    
    public function getPhieuYLenhId(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}