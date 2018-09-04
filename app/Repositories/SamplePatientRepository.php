<?php

namespace App\Repositories;
use DB;

/**
 * Class SamplePatientRepository.
 */
class SamplePatientRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    public function getForDataTable($offset,$limit=10)
    {
        $patient = DB::table('sample_patients')
                ->offset($offset)
                ->limit($limit)
                ->get();
        return $patient;        
    }
}