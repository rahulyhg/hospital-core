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
    const Y_LENH_CODE_THUOC = 5;
    const Y_LENH_CODE_VAT_TU = 6;
    
    const Y_LENH_TEXT_XET_NGHIEM = 'XÉT NGHIỆM';
    const Y_LENH_TEXT_CHAN_DOAN_HINH_ANH = 'CHẨN ĐOÁN HÌNH ẢNH';
    const Y_LENH_TEXT_CHUYEN_KHOA = 'CHUYÊN KHOA';
    const Y_LENH_TEXT_THUOC = 'THUỐC';
    const Y_LENH_TEXT_VAT_TU = 'VẬT TƯ';
    
    const LOAI_CAN_LAM_SANG = 1;
    
    public function getModel()
    {
        return PhacDoDieuTri::class;
    }
    
    public function createPhacDoDieuTri(array $input)
    {
        $arrXetNghiem = [];
        $arrChanDoanHinhAnh = [];
        $arrChuyenKhoa = [];
        $arrThuoc = [];
        $arrVatTu = [];
        $dataPddt = [];
        $dataPddt['icd10id'] = $input['icd10id']; 
        $dataPddt['loai_nhom'] = $input['loai_nhom']; 
        
        foreach($input['data'] as $item) {
            $arrTemp = explode('---', $item);
            $arr = explode('--', $arrTemp[1]);
            
            if($arrTemp[0] == self::Y_LENH_CODE_XET_NGHIEM) {
                $arrXetNghiem[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                $arrChanDoanHinhAnh[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_CHUYEN_KHOA) {
                $arrChuyenKhoa[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_THUOC) {
                $arrThuoc[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_VAT_TU) {
                $arrVatTu[] = $arr[0];
            }
        }
        
        if(count($arrXetNghiem) > 0)
            $dataPddt['xet_nghiem'] = implode(',', $arrXetNghiem);
        if(count($arrChanDoanHinhAnh) > 0)
            $dataPddt['chan_doan_hinh_anh'] = implode(',', $arrChanDoanHinhAnh);
        if(count($arrChuyenKhoa) > 0)
            $dataPddt['chuyen_khoa'] = implode(',', $arrChuyenKhoa);
        if(count($arrThuoc) > 0)
            $dataPddt['thuoc'] = implode(',', $arrThuoc);
        if(count($arrVatTu) > 0)
            $dataPddt['vat_tu'] = implode(',', $arrVatTu);
            
        $this->create($dataPddt);
    }
    
    public function getPddtByIcd10Id($icd10Id)
    {
        $listPddt = $this->model->where('icd10id', $icd10Id)->orderBy('id')->get(); 
        $listIdCls = [];
        $listIdTvt = [];
        
        if($listPddt) {
            foreach($listPddt as $obj) {
                if($obj->loai_nhom == self::LOAI_CAN_LAM_SANG) {
                    $arrXetNghiem = $obj->xet_nghiem ? explode(',', $obj->xet_nghiem) : []; 
                    $arrChanDoanHinhAnh = $obj->chan_doan_hinh_anh ? explode(',', $obj->chan_doan_hinh_anh) : [];
                    $arrChuyenKhoa = $obj->chuyen_khoa ? explode(',', $obj->chuyen_khoa) : [];
                    $listIdCls = array_merge($listIdCls, $arrXetNghiem, $arrChanDoanHinhAnh, $arrChuyenKhoa);
                } else {
                    $arrThuoc = $obj->thuoc ? explode(',', $obj->thuoc) : []; 
                    $arrVatTu = $obj->vat_tu ? explode(',', $obj->vat_tu) : [];
                    $listIdTvt = array_merge($listIdTvt, $arrThuoc, $arrVatTu);
                }
            }
        }
        
        $result['listIdCls'] = array_map('intval', $listIdCls);
        $result['listIdTvt'] = array_map('intval', $listIdTvt);
        $result['list'] = $listPddt;
        
        return $result;
    }
    
    public function getPddtById($pddtId)
    {
        $column = [
            'phac_do_dieu_tri.id',
            'phac_do_dieu_tri.icd10id',
            'phac_do_dieu_tri.xet_nghiem',
            'phac_do_dieu_tri.chan_doan_hinh_anh',
            'phac_do_dieu_tri.chuyen_khoa',
            'phac_do_dieu_tri.thuoc',
            'phac_do_dieu_tri.vat_tu',
            'phac_do_dieu_tri.loai_nhom',
            'icd10.icd10code',
            'icd10.icd10name'
        ];
        $obj = $this->model->where('id', $pddtId)->leftJoin('icd10', 'icd10.icd10id', '=', 'phac_do_dieu_tri.icd10id')->get($column)->first();
        $listId = [];
        
        if($obj) {
            if($obj->loai_nhom == self::LOAI_CAN_LAM_SANG) {
                $arrXetNghiem = $obj->xet_nghiem ? explode(',', $obj->xet_nghiem) : []; 
                $arrChanDoanHinhAnh = $obj->chan_doan_hinh_anh ? explode(',', $obj->chan_doan_hinh_anh) : [];
                $arrChuyenKhoa = $obj->chuyen_khoa ? explode(',', $obj->chuyen_khoa) : [];
                $listId = array_merge($listId, $arrXetNghiem, $arrChanDoanHinhAnh, $arrChuyenKhoa); 
            } else {
                $arrThuoc = $obj->thuoc ? explode(',', $obj->thuoc) : []; 
                $arrVatTu = $obj->vat_tu ? explode(',', $obj->vat_tu) : []; 
                $listId = array_merge($listId, $arrThuoc, $arrVatTu); 
            }
        }
        
        $result['listId'] = array_map('intval', $listId);
        $result['obj'] = $obj;
        
        return $result;
    }
    
    public function updatePhacDoDieuTri($pddtId, array $input)
    {
        $pddt = $this->model->findOrFail($pddtId); 
        $arrXetNghiem = [];
        $arrChanDoanHinhAnh = [];
        $arrChuyenKhoa = [];
        $arrThuoc = [];
        $arrVatTu = [];
        $dataPddt = [];
        
        foreach($input['data'] as $item) {
            $arrTemp = explode('---', $item);
            $arr = explode('--', $arrTemp[1]);
            
            if($arrTemp[0] == self::Y_LENH_CODE_XET_NGHIEM) {
                $arrXetNghiem[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                $arrChanDoanHinhAnh[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_CHUYEN_KHOA) {
                $arrChuyenKhoa[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_THUOC) {
                $arrThuoc[] = $arr[0];
            }
            if($arrTemp[0] == self::Y_LENH_CODE_VAT_TU) {
                $arrVatTu[] = $arr[0];
            }
        }
        
        if(count($arrXetNghiem) > 0)
            $dataPddt['xet_nghiem'] = implode(',', $arrXetNghiem);
        if(count($arrChanDoanHinhAnh) > 0)
            $dataPddt['chan_doan_hinh_anh'] = implode(',', $arrChanDoanHinhAnh);
        if(count($arrChuyenKhoa) > 0)
            $dataPddt['chuyen_khoa'] = implode(',', $arrChuyenKhoa);
        if(count($arrThuoc) > 0)
            $dataPddt['thuoc'] = implode(',', $arrThuoc);
        if(count($arrVatTu) > 0)
            $dataPddt['vat_tu'] = implode(',', $arrVatTu);
            
        $pddt->update($dataPddt);
    }
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $column = [
            'phac_do_dieu_tri.id',
            'phac_do_dieu_tri.icd10id',
            'phac_do_dieu_tri.xet_nghiem',
            'phac_do_dieu_tri.chan_doan_hinh_anh',
            'phac_do_dieu_tri.chuyen_khoa',
            'phac_do_dieu_tri.thuoc',
            'phac_do_dieu_tri.vat_tu',
            'phac_do_dieu_tri.loai_nhom',
            'icd10.icd10code',
            'icd10.icd10name'
        ];
        $icd10Code = str_replace(' ', '', $icd10Code);
        $arrIcd10Code = explode(',', $icd10Code);
        $result = $this->model->join('icd10', 'icd10.icd10id', '=', 'phac_do_dieu_tri.icd10id')
                                ->whereIn('icd10code', $arrIcd10Code)
                                ->orderBy('phac_do_dieu_tri.id', 'asc')
                                ->get($column); 
          
        $listIdCls = [];
        $listIdTvt = [];
        $data = [];
        if($result) {
            foreach($result as $obj) {
                if($obj->loai_nhom == self::LOAI_CAN_LAM_SANG) {
                    $arrXetNghiem = $obj->xet_nghiem ? explode(',', $obj->xet_nghiem) : []; 
                    $arrChanDoanHinhAnh = $obj->chan_doan_hinh_anh ? explode(',', $obj->chan_doan_hinh_anh) : [];
                    $arrChuyenKhoa = $obj->chuyen_khoa ? explode(',', $obj->chuyen_khoa) : [];
                    $listIdCls = array_merge($listIdCls, $arrXetNghiem, $arrChanDoanHinhAnh, $arrChuyenKhoa);
                } else {
                    $arrThuoc = $obj->thuoc ? explode(',', $obj->thuoc) : []; 
                    $arrVatTu = $obj->vat_tu ? explode(',', $obj->vat_tu) : [];
                    $listIdTvt = array_merge($listIdTvt, $arrThuoc, $arrVatTu);
                }
            }
            
            $data['listIdCls'] = array_map('intval', $listIdCls);
            $data['listIdTvt'] = array_map('intval', $listIdTvt);
            $data['list'] = $result;
        }
        
        return $data;
    }
    
//     public function getListPhacDoDieuTri($limit = 100, $page = 1, $keyword = '')
//     {
//         $offset = ($page - 1) * $limit;
        
//         $query = $this->model;
        
//         if($keyword != '') {
//             $query = $query->where(function($queryAdv) use ($keyword) {
//                 $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
//                 $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
//                 $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
//                 $ucfirst = ucfirst($keyword);
                
//                 $queryAdv->where('icd10name', 'like', '%'.$upperCase.'%')
//                         ->orWhere('icd10name', 'like', '%'.$lowerCase.'%')
//                         ->orWhere('icd10name', 'like', '%'.$titleCase.'%')
//                         ->orWhere('icd10name', 'like', '%'.$keyword.'%')
//                         ->orWhere('icd10name', 'like', '%'.$ucfirst.'%')
//                         ->orWhere('icd10code', 'like', '%'.$upperCase.'%')
//                         ->orWhere('icd10code', 'like', '%'.$lowerCase.'%')
//                         ->orWhere('icd10code', 'like', '%'.$titleCase.'%')
//                         ->orWhere('icd10code', 'like', '%'.$keyword.'%');
//             });
//         }
        
//         $totalRecord = $query->count();
//         if($totalRecord) {
//             $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
//             $data = $query->orderBy('id', 'asc')
//                         ->offset($offset)
//                         ->limit($limit)
//                         ->get();
//         } else {
//             $totalPage = 0;
//             $data = [];
//             $page = 0;
//             $totalRecord = 0;
//         }
            
//         $result = [
//             'data'          => $data,
//             'page'          => $page,
//             'totalPage'     => $totalPage,
//             'totalRecord'   => $totalRecord
//         ];
        
//         return $result;
//     }
    
//     public function getDataPddtByCode($icd10Code)
//     {
//         $result = $this->model->where('icd10code', $icd10Code)->first(); 
//         return $result;
//     }
    
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