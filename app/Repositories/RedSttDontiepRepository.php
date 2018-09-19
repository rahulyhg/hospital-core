<?php

namespace App\Repositories;

use DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Models\RedSttDontiep;
use Carbon\Carbon;

class RedSttDontiepRepository extends BaseRepository
{
    const MODEL = RedSttDontiep::class;
    
    public function show($id){
        return RedSttDontiep::findOrfail($id);
    }
    
    public function create(array $input)
    {
        DB::transaction(function () use ($input) {
            if (RedSttDontiep::create($input)) {
                return true;
            }
            
            throw new GeneralException(
                trans('exceptions.backend.red_stt_dontiep.create_error')
            );
        });
    }
    
    public function update(RedSttDontiep $stt_dontiep, array $input)
    {
        DB::transaction(function () use ($stt_dontiep, $input) {
            if ($stt_dontiep->update($input)) {
                return true;
            }
            
            throw new GeneralException(
                trans('exceptions.backend.red_stt_dontiep.update_error')
            );
        });
    }

}