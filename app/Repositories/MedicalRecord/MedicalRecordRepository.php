<?php

namespace App\Repositories\MedicalRecord;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Medicalrecord;
use Carbon\Carbon;

class MedicalRecordRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Medicalrecord::class;
    }
    
    public function getListBN_HC($start_day, $end_day, $offset, $limit = 20, $keyword = '')
    {
        $loaibenhanid = 24; //kham benh
        $departmentgroupid = 3; //khoa kham benh
        
        $where = [
            ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
            ['medicalrecord.departmentgroupid', '=', $departmentgroupid],
        ];
        
        $column = [
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            'hosobenhan.birthday_year',
            'hosobenhan.bhytcode',
            'hosobenhan.hosobenhanid',
            'hosobenhan.hosobenhanstatus',
            'hosobenhan.hosobenhandate',
            'hosobenhan.hosobenhandate_ravien',
            'medicalrecord.thoigianvaovien',
            'medicalrecord.thoigianravien',
            'medicalrecord.medicalrecordid',
            'medicalrecord.medicalrecordcode',
            'medicalrecord.canlamsangstatus',
            'tt1.diengiai as canlamsang_name',
            'medicalrecord.medicalrecordstatus',
            'tt2.diengiai as medicalrecord_name',
        ];
        
        $query = DB::table('medicalrecord')
            //->select($column, DB::raw('convertTVkdau(hosobenhan.patientname) as patient_name'))
            ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'medicalrecord.canlamsangstatus')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'medicalrecord.medicalrecordstatus')
                    ->where('tt2.tablename', '=', 'patientstatus');
            })
            ->where($where);
        
        if($start_day == $end_day){
            $query = $query->whereDate('thoigianvaovien', '=', $start_day);
        } else {
            $query = $query->whereBetween('thoigianvaovien', [$start_day, $end_day]);
        }
        
        if($keyword != ''){
            $query = $query->where(function($query_adv) use ($keyword) {
                $uppercase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowercase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titlecase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $query_adv->where('hosobenhan.patientname', 'like', '%'.$uppercase.'%')
                        ->orWhere('hosobenhan.patientname', 'like', '%'.$lowercase.'%')
                        ->orWhere('hosobenhan.patientname', 'like', '%'.$titlecase.'%')
                        ->orWhere('hosobenhan.patientname', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw('convertTVkdau(hosobenhan.patientname)'), 'like', '%'.$uppercase.'%')
                        ->orWhere('hosobenhan.patientcode', 'like', '%'.$keyword.'%')
                        ->orWhere('hosobenhan.bhytcode', 'like', '%'.$keyword.'%')
                        ->orWhere('hosobenhan.bhytcode', 'like', '%'.$uppercase.'%');
            });
        }
        
        $data = $query->orderBy('thoigianvaovien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
        return $data;
    }
    
    public function getListBN_PK($departmentid, $start_day, $end_day, $offset, $limit = 20, $keyword = '')
    {
        $loaibenhanid = 24; //kham benh
        
        $where = [
            ['medicalrecord.loaibenhanid', '=', $loaibenhanid],
            ['medicalrecord.departmentid', '=', $departmentid],
        ];
        
        $column = [
            'hosobenhan.patientcode',
            'hosobenhan.patientname',
            'hosobenhan.birthday_year',
            'hosobenhan.bhytcode',
            'hosobenhan.hosobenhanid',
            'hosobenhan.hosobenhanstatus',
            'hosobenhan.hosobenhandate',
            'hosobenhan.hosobenhandate_ravien',
            'medicalrecord.thoigianvaovien',
            'medicalrecord.thoigianravien',
            'medicalrecord.medicalrecordid',
            'medicalrecord.medicalrecordcode',
            'medicalrecord.canlamsangstatus',
            'tt1.diengiai as canlamsang_name',
            'medicalrecord.medicalrecordstatus',
            'tt2.diengiai as medicalrecord_name'
        ];
        
        $query = DB::table('medicalrecord')
            ->join('hosobenhan', 'hosobenhan.hosobenhanid', '=', 'medicalrecord.hosobenhanid')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'medicalrecord.canlamsangstatus')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'medicalrecord.medicalrecordstatus')
                    ->where('tt2.tablename', '=', 'patientstatus');
            })
            ->where($where);
        
        if($start_day == $end_day){
            $query = $query->whereDate('thoigianvaovien', '=', $start_day);
        } else {
            $query = $query->whereBetween('thoigianvaovien', [$start_day, $end_day]);
        }   
                
        if($keyword != ''){
            $query = $query->where(function($query_adv) use ($keyword) {
                $uppercase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowercase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titlecase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $query_adv->where('hosobenhan.patientname', 'like', '%'.$uppercase.'%')
                        ->orWhere('hosobenhan.patientname', 'like', '%'.$lowercase.'%')
                        ->orWhere('hosobenhan.patientname', 'like', '%'.$titlecase.'%')
                        ->orWhere('hosobenhan.patientname', 'like', '%'.$keyword.'%')
                        ->orWhere(DB::raw('convertTVkdau(hosobenhan.patientname)'), 'like', '%'.$uppercase.'%')
                        ->orWhere('hosobenhan.patientcode', 'like', '%'.$keyword.'%')
                        ->orWhere('hosobenhan.bhytcode', 'like', '%'.$keyword.'%')
                        ->orWhere('hosobenhan.bhytcode', 'like', '%'.$uppercase.'%');
            });
        }
        
        $data = $query->orderBy('thoigianvaovien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
        
        return $data;
    }
    
    
}