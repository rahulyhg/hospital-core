<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\HsbaDonVi;
use Carbon\Carbon;

class HsbaDonViRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return HsbaDonVi::class;
    }
    
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}