<?php

namespace App\Services;

use App\Repositories\ThuNgan\SoThuNganRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class SoThuNganService {
  const TRANG_THAI_STN_OPEN = 1;
  
  public function __construct(SoThuNganRepository $soThuNganRepository)
  {
    $this->soThuNganRepository = $soThuNganRepository;
  }
  
  public function createSoThuNgan(array $input)
  {
    $input['ngay_lap']=Carbon::now();
    $input['trang_thai']=self::TRANG_THAI_STN_OPEN;
    $id = $this->soThuNganRepository->createDataSoThuNgan($input);
    return $id;
  }
}