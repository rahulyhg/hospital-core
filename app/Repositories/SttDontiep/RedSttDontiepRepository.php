<?php

namespace App\Repositories\SttDontiep;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\RedSttDontiep;

class RedSttDontiepRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return RedSttDontiep::class;
    }
    
    

}