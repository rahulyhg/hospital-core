<?php
namespace App\Repositories\Sqs\Hsba;

use DB;
use App\Repositories\Sqs\BaseSQSRepository;
use App\Models\Sqs\HsbaKhoaPhong as HsbaKhoaPhongMessage;
use Carbon\Carbon;

class HsbaKhoaPhongRepository extends BaseSQSRepository
{
    public function __construct() {
        $this->init(HsbaKhoaPhongMessage::class,'hsba-khoa-phong-to-redis');
    }
    
    /*
    public function init($benhVienId,$khoaId,$phongId){
        $key = 'add-patient-hsba-to-cache-list';
        parent::init();
    }
    */
}