<?php

namespace App\Repositories\Redis\Hsba;

use App\Models\Redis\HsbaKhoaPhong as HsbaKhoaPhongRedis;
use App\Repositories\Redis\BaseRedisRepository;
use Carbon\Carbon;

class HsbaKhoaPhongRepository extends BaseRedisRepository
{
    public function __construct() {
        
    }
    
    public function _init(){
        $prefix = 'hsba_khoa_phong';
        parent::init(HsbaKhoaPhongRedis::class, HsbaKhoaPhongRedis::HASH_TYPE, $prefix);
    }
    
    public function getList($phongId, $benhVienId, $startDay, $endDay, $limit = 20, $page = 1, $keyword = '', $status = -1) {
        $suffix = '*2018-11-18';
        return $this->find($suffix);
         
    }
    
    public function set(array $item) {
        $suffix = $benhVienId.":".$phongId;
        $this->hmset($suffix,$item);
    }
    
}