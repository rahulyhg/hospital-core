<?php

namespace App\Services;
use App\Http\Resources\PatientResource;
use App\Http\Resources\HosobenhanResource;
//use App\Repositories\Patient\PatientRepositoryInterface;
use App\Repositories\Patient\PatientRepository;
use App\Repositories\Hosobenhan\HosobenhanRepository;
use App\Repositories\MedicalRecord\MedicalRecordRepository;
use Illuminate\Http\Request;
use DB;
use Validator;

class PatientService{
    public function __construct(PatientRepository $PatientRepository, HosobenhanRepository $HosobenhanRepository, MedicalRecordRepository $MedicalRecordRepository)
    {
        $this->PatientRepository = $PatientRepository;
        $this->HosobenhanRepository = $HosobenhanRepository;
        $this->MedicalRecordRepository = $MedicalRecordRepository;
    }
    
    public function getDataPatient(Request $request)
    {
        $offset = $request->query('offset',0);
        //return array('result' => 'success');
        $Patient = $this->PatientRepository->getAll();
        return PatientResource::collection(
           //$this->repository->getForDataTable($offset)
           $Patient
        );
    }
    
    public function getDataPatientByStt($stt, $id_phong, $id_benh_vien)
    {
        
    }
    
    public function CreateDataPatient(Request $request)
    {
         $array = $request->all();
         $patientParams = $request->only('patientname', 'birthday', 'birthday_year', 'gioitinhcode', 'nghenghiepcode', 'hc_dantoccode', 'hc_quocgiacode', 'hc_sonha'
                                        , 'hc_thon', 'hc_xacode', 'hc_huyencode', 'hc_tinhcode', 'noilamviec', 'nguoithan', 'nguoithan_name', 'nguoithan_phone'
                                        , 'nguoithan_address', 'nguoithan_cmnn_cccd', 'registerstt'
         );
        
         $hsbaParams = $request->only( 'userid', 'patientname', 'birthday', 'birthday_year', 'gioitinhcode', 'nghenghiepcode', 'hc_dantoccode'
                                     , 'hc_quocgiacode', 'hc_sonha', 'hc_thon', 'hc_xacode', 'hc_huyencode', 'hc_tinhcode', 'noilamviec', 'nguoithan'
                                     , 'nguoithan_name', 'nguoithan_phone', 'nguoithan_address', 'gioitinhname', 'nghenghiepname', 'hc_dantocname'
                                     , 'hc_quocgianame', 'hc_xaname', 'hc_huyenname', 'hc_tinhname', 'departmentgroupid', 'departmentid'
                                     , 'hinhthucvaovienid', 'ketquadieutriid', 'ketquadieutriid', 'xutrikhambenhid', 'hinhthucravienid', 'hosobenhanstatus'
                                     , 'chandoanvaovien_code', 'chandoanvaovien', 'chandoanravien_code', 'chandoanravien', 'chandoanravien_kemtheo_code'
                                     , 'chandoanravien_kemtheo', 'bhytcode'
         );
         
         $medicalRecord_Params = $request -> only ( 'userid', 'departmentgroupid', 'departmentid', 'userid', 'doituongbenhnhanid', 'yeucaukham', 'chandoantuyenduoi'
                                    , 'chandoantuyenduoi_code', 'noigioithieucode', 'uutienkhamid', 'chandoanbandau', 'xutrikhambenhid'
                                    , 'hinhthucravienid', 'ketquadieutriid', 'nextdepartmentid', 'nexthospitalid'
                                    , 'giaiphaubenhid', 'cv_chuyenvien_hinhthucid', 'cv_chuyenvien_lydoid'
                                    , 'cv_chuyendungtuyen', 'cv_chuyenvuottuyen'
             
         );
         
         $id = '';
        //validate data
        $result = DB::transaction(function () use ($patientParams, $hsbaParams, $medicalRecord_Params) {
            try {
                //insert Patient
                $id = $this->PatientRepository->CreateDataPatient($patientParams);
                //insert intp hosobenhan
                $hsbaParams['loaibenhanid'] = 24;
                $hsbaParams['patientcode'] = sprintf('%12d', $id);
                $hsbaParams['patientid'] = $id;
                $hosobenhbanid = $this->HosobenhanRepository->CreateDataHosobenhan($hsbaParams);
                //insert into medicalRecord
                $medicalRecord_Params['sothutuid'] = 0;//chưa xử lý
                $medicalRecord_Params['sothutuid'] = 0;//chưa xử lý
                $medicalRecord_Params['hosobenhanid'] = $hosobenhbanid;
                $medicalRecord_Params['patientid'] = $id;
                $medicalRecord_Params['lastuserupdated'] = $medicalRecord_Params['userid'];
                $medicalRecord_Params['medicalrecordstatus'] = 0;
                $medicalRecord_Params['loaibenhanid'] = 24;
                $medicalRecord_Params['medicalrecordid_master'] = 0;
                $medicalRecord_Params['isthuthuat'] = 0;
                $medicalRecord_Params['isphauthuat'] = 0;
                $medicalRecord_Params['isbienchung'] = 0;
                $medicalRecord_Params['istaibien'] = 0;
                $medicalRecord_Params['hinhthucvaovienid'] = 0;
                $medicalRecord_Params['backdepartmentid'] = 0;
                $medicalRecord_Params['vaoviencungbenhlanthu'] = 0;
                $medicalRecord_Params['canlamsangstatus'] = 0;
                $medicalRecord_Params['keylock'] = 0;
                $medicalRecord_Params['nextbedrefid'] = 0;
                $medicalrecordid = $this->MedicalRecordRepository->CreateDataMedicalRecord($medicalRecord_Params);
                return $medicalrecordid;
            } catch (\Exception $ex) {
                   return $ex;
            }
            
            
        });
        
        return $result;
       
       
       
       
       
       
    }
   
}