<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\SamplePatientResource;
use App\Models\SamplePatients\SamplePatient;
use App\Repositories\SamplePatientsRepository;
use Illuminate\Http\Request;
use Validator;

class SamplePatientController extends APIController
{
    protected $repository;
    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(SamplePatientsRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Return the SamplePatient.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $offset = $request->query('offset',0);
        
        //return array('result' => 'success');
        return SamplePatientResource::collection(
           $this->repository->getForDataTable($offset)
        );
    }
    
    /**
     * Return the specified resource.
     *
     * @param SamplePatient $patient
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $patient = SamplePatient::findOrfail($id);
        return new SamplePatientResource($patient);
    }
    
    /**
     * Creates the Resource for SamplePatient.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validation = $this->validatePatient($request);
        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->first());
        }
        $this->repository->create($request->all());
        $patient = SamplePatient::orderBy('id', 'desc')->first();
        
        return new SamplePatientResource($patient);
    }
    
    /**
     * Update SamplePatient.
     *
     * @param SamplePatient     $patient
     * @param Request           $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validation = $this->validatePatient($request, 'update');
        if ($validation->fails()) {
            return $this->throwValidation($validation->messages()->first());
        }
        $patient = SamplePatient::findOrfail($id);
        $this->repository->update($patient, $request->all());
        $patient = SamplePatient::findOrfail($patient->id);
        
        return new SamplePatientResource($patient);
    }
    /**
     * Delete SamplePatient.
     *
     * @param SamplePatient     $patient
     * @param Request           $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id)
    {
        $patient = SamplePatient::findOrfail($id);
        $this->repository->delete($patient);
        return $this->respond([
            'message' => trans('alerts.backend.sample_patients.deleted'),
        ]);
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