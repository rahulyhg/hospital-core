<?php
namespace App\Repositories;
use DB;
use App\Repositories\BaseRepository;

class ServicepricerefRepository extends BaseRepository
{

    public function getDataYeuCauKham($offset, $request)
    {
        $data = DB::table('servicepriceref')
                ->where('servicegrouptype',$request->servicegrouptype)
                ->offset($offset)
                ->orderBy('servicepricename')
                ->get();
        return $data;    
    }
    
}