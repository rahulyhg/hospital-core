<?php

namespace App\Repositories\Patient;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Patient;

/**
 * Class PatientRepository.
 */
class PatientRepository extends BaseRepositoryV2
{
    /**
     * @return mixed
     */
    /*public function getForDataTable($offset,$limit=10)
    {
        $patient = DB::table('sample_patients')
                ->offset($offset)
                ->limit($limit)
                ->get();
        return $patient;        
    }
    public function CreateData(array input)
    {
        DB::transaction(function()
            {
                $newAcct = Account::create(input);
            });
    }*/
    public function getModel()
    {
        return Patient::class;
    }
    //public function getTypePatient(){
    
        //$typepatient = $this->_model->all();
        //->with('hosobenhan')->get()
       // return $typepatient;
    //}
    
}