<?php
namespace App\Repositories\ThuNgan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\SoThuNgan;

class SoThuNganRepository extends BaseRepositoryV2
{
  public function getModel()
  {
    return SoThuNgan::class;
  }
  
  public function createDataSoThuNgan(array $input)
  {
    $id = $this->model->create($input)->id;
    return $id;
  }
 
}