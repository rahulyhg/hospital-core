<?php

namespace App\Repositories;

use DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Models\SamplePatients\SamplePatient;
use Carbon\Carbon;

/**
 * Class SamplePatientsRepository.
 */
class SamplePatientsRepository extends BaseRepository
{
    const MODEL = SamplePatient::class;
    
    /**
     * @return mixed
     */
    public function getForDataTable($offset,$limit=10)
    {
        $patient = DB::table('sample_patients')
                ->offset($offset)
                ->limit($limit)
                ->orderBy("id", "desc")
                ->get();
        return $patient;        
    }
    
    /**
     * @param array $input
     *
     * @throws \App\Exceptions\GeneralException
     *
     * @return bool
     */
    public function create(array $input)
    {
        DB::transaction(function () use ($input) {
            if (SamplePatient::create($input)) {
                return true;
            }
            
            throw new GeneralException(
                trans('exceptions.backend.sample_patients.create_error')
            );
        });
    }
    
    /**
     * Update SamplePatient.
     *
     * @param \App\Models\SamplePatients\SamplePatient  $patient
     * @param array                                     $input
     */
    public function update(SamplePatient $patient, array $input)
    {
        DB::transaction(function () use ($patient, $input) {
            if ($patient->update($input)) {
                return true;
            }
            
            throw new GeneralException(
                trans('exceptions.backend.sample_patients.update_error')
            );
        });
    }
    
    /**
     * @param \App\Models\SamplePatients\SamplePatient $patient
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(SamplePatient $patient)
    {
        DB::transaction(function () use ($patient) {
            if ($patient->delete()) {
                return true;
            }
            
            throw new GeneralException(
                trans('exceptions.backend.blogs.delete_error')
            );
        });
    }
}