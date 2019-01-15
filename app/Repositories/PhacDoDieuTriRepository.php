<?php
namespace App\Repositories;

use DB;
use App\Models\PhacDoDieuTri;
use App\Repositories\BaseRepositoryV2;
use Carbon\Carbon;

class PhacDoDieuTriRepository extends BaseRepositoryV2
{
    const Y_LENH_CODE_XET_NGHIEM = 2;
    const Y_LENH_CODE_CHAN_DOAN_HINH_ANH = 3;
    const Y_LENH_CODE_CHUYEN_KHOA = 4;
    
    const Y_LENH_TEXT_XET_NGHIEM = 'XÉT NGHIỆM';
    const Y_LENH_TEXT_CHAN_DOAN_HINH_ANH = 'CHẨN ĐOÁN HÌNH ẢNH';
    const Y_LENH_TEXT_CHUYEN_KHOA = 'CHUYÊN KHOA';
    
    public function getModel()
    {
        return PhacDoDieuTri::class;
    }
    
    public function getListPhacDoDieuTri($limit = 100, $page = 1, $keyword = '')
    {
        $offset = ($page - 1) * $limit;
        
        $query = $this->model;
        
        if($keyword != '') {
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                $ucfirst = ucfirst($keyword);
                
                $queryAdv->where('icd10name', 'like', '%'.$upperCase.'%')
                        ->orWhere('icd10name', 'like', '%'.$lowerCase.'%')
                        ->orWhere('icd10name', 'like', '%'.$titleCase.'%')
                        ->orWhere('icd10name', 'like', '%'.$keyword.'%')
                        ->orWhere('icd10name', 'like', '%'.$ucfirst.'%')
                        ->orWhere('icd10code', 'like', '%'.$upperCase.'%')
                        ->orWhere('icd10code', 'like', '%'.$lowerCase.'%')
                        ->orWhere('icd10code', 'like', '%'.$titleCase.'%')
                        ->orWhere('icd10code', 'like', '%'.$keyword.'%');
            });
        }
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }
    
    public function getDataPhacDoDieuTriById($pddtId)
    {
        $obj = $this->model->where('id', $pddtId)->first(); 
        $listId = [];
        $giaiTrinh = [];
        if($obj) {
           $arrXetNghiem = $obj->xet_nghiem ? json_decode($obj->xet_nghiem) : []; 
           $arrChanDoanHinhAnh = $obj->chan_doan_hinh_anh ? json_decode($obj->chan_doan_hinh_anh) : [];
           $arrChuyenKhoa = $obj->chuyen_khoa ? json_decode($obj->chuyen_khoa) : [];
           $listId = array_merge($arrXetNghiem, $arrChanDoanHinhAnh, $arrChuyenKhoa);
           
           $arrData = $obj->giai_trinh ? json_decode($obj->giai_trinh) : [];
           if($arrData) {
               foreach($arrData as $item) {
                   $temp = explode('|', $item);
                   $arr = explode('---', $temp[0]);
                   $str = explode('--', $arr[1]);
                   $giaiTrinh[$temp[1]][$temp[2]][$arr[0].'---'.$str[0]] = $str[1];
               }
           }
        }
        
        $result['listId'] = array_map('intval', $listId);
        $result['giaiTrinh'] = $giaiTrinh;
        return $result;
    }
    
    public function savePhacDoDieuTri($pddtId, array $input)
    {
        $arrXetNghiem = [];
        $arrChanDoanHinhAnh = [];
        $arrChuyenKhoa = [];
        $dataPddt = [];
        
        foreach($input['data'] as $item) {
            $arrTemp = explode('---', $item);
            $arr = explode('--', $arrTemp[1]);
            
            if(!in_array($arr[0], $input['remove'])) {
                if($arrTemp[0] == self::Y_LENH_CODE_XET_NGHIEM) {
                    $arrXetNghiem[] = $arr[0];
                }
                if($arrTemp[0] == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                    $arrChanDoanHinhAnh[] = $arr[0];
                }
                if($arrTemp[0] == self::Y_LENH_CODE_CHUYEN_KHOA) {
                    $arrChuyenKhoa[] = $arr[0];
                }
            }
        }
        
        if(count($arrXetNghiem) > 0)
            $dataPddt['xet_nghiem'] = json_encode($arrXetNghiem);
        if(count($arrChanDoanHinhAnh) > 0)
            $dataPddt['chan_doan_hinh_anh'] = json_encode($arrChanDoanHinhAnh);
        if(count($arrChuyenKhoa) > 0)
            $dataPddt['chuyen_khoa'] = json_encode($arrChuyenKhoa);
            
        $pddt = $this->model->findOrFail($pddtId);
		$pddt->update($dataPddt);
    }
    
    public function getDataPddtByCode($icd10Code)
    {
        $result = $this->model->where('icd10code', $icd10Code)->first(); 
        return $result;
    }
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $icd10Code = str_replace(' ', '', $icd10Code);
        $arrIcd10 = explode(',', $icd10Code);
        $result = $this->model->whereIn('icd10code', $arrIcd10)
                                ->orderBy('id', 'asc')
                                ->get(); 
        $listId = [];
        $data['list'] = $result;
        if($result) {
            foreach($result as $obj) {
                $arrXetNghiem = $obj->xet_nghiem ? json_decode($obj->xet_nghiem) : []; 
                $arrChanDoanHinhAnh = $obj->chan_doan_hinh_anh ? json_decode($obj->chan_doan_hinh_anh) : [];
                $arrChuyenKhoa = $obj->chuyen_khoa ? json_decode($obj->chuyen_khoa) : [];
                $listId = array_merge($listId, $arrXetNghiem, $arrChanDoanHinhAnh, $arrChuyenKhoa);
            }
            $data['listId'] = array_map('intval', $listId);
        }
        
        return $data;
    }
    
    public function saveYLenhGiaiTrinh(array $input)
    {
        $arr = [];
        foreach($input['icd10code'] as $item) {
            $str = explode('-', $item);
            
            foreach($input['dataYLenh'] as $yLenh) {
                if($yLenh['id'] == $str[1]) {
                    $arr[$str[0]][$yLenh['id']] = $yLenh['loai_nhom'] . '---' . $yLenh['id'] . '--' . $yLenh['ten'] . '|' . $input['username'] . '|' . Carbon::now()->toDateTimeString();
                    break;
                }
            }
        }
        
        foreach($arr as $id=>$item) {
            $pddt = $this->model->findOrFail($id);
            $data = json_decode($pddt->giai_trinh, true);
            $dataTmp = json_decode($pddt->giai_trinh_tmp, true);
            if($data) {
                $data = array_merge($data, $item);
                $dataTmp = array_merge($dataTmp, $item);
            }
            else {
                $data = $item;
                $dataTmp = $item;
            }
            $params['giai_trinh'] = json_encode($data);
            $params['giai_trinh_tmp'] = json_encode($dataTmp);
		    $pddt->update($params);
        }
    }
    
    public function confirmGiaiTrinh(array $input)
    {
        $pddt = $this->model->findOrFail($input['id']);
        $data = json_decode($pddt->giai_trinh, true);
        if($input['type'] == 'remove') {
            foreach($data as $keyItem => $dataItem) {
                foreach($input['data'] as $keyInput => $dataInput) {
                    if(strpos($dataItem, $dataInput) !== false) {
                        unset($data[$keyItem]);
                    }
                }
            }
            
            $pddt->giai_trinh = json_encode($data);
            $pddt->save();
        }
        else {
            $arrXetNghiem = json_decode($pddt->xet_nghiem, true);
            $arrChanDoanHinhAnh = json_decode($pddt->chuan_doan_hinh_anh, true);
            $arrChuyenKhoa = json_decode($pddt->chuyen_khoa, true);
            
            foreach($data as $keyItem => $dataItem) {
                foreach($input['data'] as $keyInput => $dataInput) {
                    if(strpos($dataItem, $dataInput) !== false) {
                        unset($data[$keyItem]);
                    }
                    
                    $arrTemp = explode('---', $dataInput);
                    
                    if($arrTemp[0] == self::Y_LENH_CODE_XET_NGHIEM) {
                        $arrXetNghiem[] = $arrTemp[1];
                    }
                    if($arrTemp[0] == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                        $arrChanDoanHinhAnh[] = $arrTemp[1];
                    }
                    if($arrTemp[0] == self::Y_LENH_CODE_CHUYEN_KHOA) {
                        $arrChuyenKhoa[] = $arrTemp[1];
                    }
                    
                    if(count($arrXetNghiem) > 0)
                        $dataPddt['xet_nghiem'] = json_encode($arrXetNghiem);
                    if(count($arrChanDoanHinhAnh) > 0)
                        $dataPddt['chan_doan_hinh_anh'] = json_encode($arrChanDoanHinhAnh);
                    if(count($arrChuyenKhoa) > 0)
                        $dataPddt['chuyen_khoa'] = json_encode($arrChuyenKhoa);
                    
                    $dataPddt['giai_trinh'] = json_encode($data);
            		$pddt->update($dataPddt);
                }
            }
        }
    }
}