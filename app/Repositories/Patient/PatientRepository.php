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
    public function getModel()
    {
        return Patient::class;
    }
    
    public function CreateDataPatient(array $input)
    {
         $id = Patient::create($input)->patientid;
         return $id;
    }
    
}