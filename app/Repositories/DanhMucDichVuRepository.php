<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class DanhMucDichVuRepository extends BaseRepository
{

    public function getDataYeuCauKham($request)
    {
        $data = DB::table('danh_muc_dich_vu')
                ->where('servicegrouptype',$request->servicegrouptype)
                ->orderBy('servicepricename')
                ->get();
        return $data;    
    }
    
}