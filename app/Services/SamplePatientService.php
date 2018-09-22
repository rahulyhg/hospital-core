<?php

namespace App\Services;

use App\Models\SamplePatients\SamplePatient;
use App\Http\Resources\SamplePatientResource;
use App\Repositories\SamplePatientRepository;
use Illuminate\Http\Request;
use Validator;

class SamplePatientService {
    public function __construct(SamplePatientRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function getDataPatient(Request $request){
        $offset = $request->query('offset',0);
        
        return SamplePatientResource::collection(
           $this->repository->getForDataTable($offset)
        );
    }
    
    public function showPatient($id){
        $patient = SamplePatient::findOrfail($id);
        
        return new SamplePatientResource($patient);
    }
    
    public function makePatient(Request $request)
    {
        $validation = $this->validatePatient($request);
        if ($validation->fails()) {
            return $validation->messages()->first();
        }
        
        $this->repository->create($request->all());
        $patient = SamplePatient::orderBy('id', 'desc')->first();
        
        return new SamplePatientResource($patient);
    }
    
    public function updatePatient(Request $request, $id){
        $validation = $this->validatePatient($request, 'update');
        if ($validation->fails()) {
            return $validation->messages()->first();
        }
        
        $patient = SamplePatient::findOrfail($id);
        $this->repository->update($patient, $request->all());
        $patient = SamplePatient::findOrfail($patient->id);
        
        return new SamplePatientResource($patient);
    }
    
    public function deletePatient($id){
        $patient = SamplePatient::findOrfail($id);
        $this->repository->delete($patient);
        
        // return $this->respond([
        //     'message' => trans('alerts.backend.sample_patients.deleted'),
        // ]);
        return array('result' => 'success');
    }
    
    /**
     * validate SamplePatient.
     *
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validatePatient(Request $request, $action = 'insert')
    {
        $validation = Validator::make($request->all(), [
            'first_name'    => 'required|max:191',
            'last_name'     => 'required|max:191',
            'birth_date'    => 'required',
            'email'         => 'required',
            'phone_no'      => 'required',
        ]);
        return $validation;
    }
}