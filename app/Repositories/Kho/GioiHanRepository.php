<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\GioiHan;

class GioiHanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return GioiHan::class;
    }
    
    public function createGioiHan($input)
    {
        $this->model->create($input);
    }
}