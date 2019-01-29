<?php
namespace App\Repositories\YLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\YLenh;
use Carbon\Carbon;

class YLenhRepository extends BaseRepositoryV2
{
    const Y_LENH_CODE_YEU_CAU_KHAM = 1;
    const Y_LENH_CODE_XET_NGHIEM = 2;
    const Y_LENH_CODE_CHAN_DOAN_HINH_ANH = 3;
    const Y_LENH_CODE_CHUYEN_KHOA = 4;
    const Y_LENH_CODE_THUOC = 5;
    const Y_LENH_CODE_VAT_TU = 6;
    const Y_LENH_CODE_CAN_LAM_SANG = [2, 3, 4];
    const Y_LENH_CODE_THUOC_VAT_TU = [5, 6];
    
    const Y_LENH_TEXT_YEU_CAU_KHAM = 'YÊU CẦU KHÁM';
    const Y_LENH_TEXT_XET_NGHIEM = 'XÉT NGHIỆM';
    const Y_LENH_TEXT_CHAN_DOAN_HINH_ANH = 'CHẨN ĐOÁN HÌNH ẢNH';
    const Y_LENH_TEXT_CHUYEN_KHOA = 'CHUYÊN KHOA';
    const Y_LENH_TEXT_THUOC = 'THUỐC';
    const Y_LENH_TEXT_VAT_TU = 'VẬT TƯ';
    
    public function getModel()
    {
        return YLenh::class;
    }
    
    public function createDataYLenh(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function saveYLenh(array $input)
    {
        $this->model->insert($input);
    }
    
    public function getLichSuYLenh(array $input)
    {
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.loai_y_lenh',
            'y_lenh.thoi_gian_chi_dinh',
            'y_lenh.phieu_y_lenh_id',
            'phong.ten_phong'
        ];
        
        $data = $this->model->whereIn('loai_y_lenh', self::Y_LENH_CODE_CAN_LAM_SANG)
                            ->orWhere('loai_y_lenh', '=', self::Y_LENH_CODE_YEU_CAU_KHAM)
                            ->join('phieu_y_lenh', function($join) use ($input) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.benh_nhan_id', '=', $input['benh_nhan_id'])
                                    ->where('phieu_y_lenh.hsba_id', '=', $input['hsba_id']);
                            })
                            ->leftJoin('phong', 'phong.id', '=', 'y_lenh.phong_id')
                            ->orderBy('y_lenh.id')
                            ->get($column);
        
        if($data) {
            $itemXetNghiem = 0;
            $itemChanDoanHinhAnh = 0;
            $itemChuyenKhoa = 0;
            $array = [];
            $type = null;
            
            foreach($data as $item) {
                if($item->loai_y_lenh == self::Y_LENH_CODE_YEU_CAU_KHAM) {
                    $type = self::Y_LENH_TEXT_YEU_CAU_KHAM;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_XET_NGHIEM) {
                    $itemXetNghiem++;
                    $type = self::Y_LENH_TEXT_XET_NGHIEM;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                    $itemChanDoanHinhAnh++;
                    $type = self::Y_LENH_TEXT_CHAN_DOAN_HINH_ANH;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHUYEN_KHOA) {
                    $itemChuyenKhoa++;
                    $type = self::Y_LENH_TEXT_CHUYEN_KHOA;
                }
                    
                $datetime = Carbon::parse($item->thoi_gian_chi_dinh)->format('d/m/Y');
                $phong = $item->ten_phong;
                $phieuYLenh = $item->phieu_y_lenh_id;
                $item['key'] = $item->id;
                $array[$phong][$datetime][$phieuYLenh][$type][] = $item;
            }
            
            $result['itemXetNghiem'] = $itemXetNghiem;
            $result['itemChanDoanHinhAnh'] = $itemChanDoanHinhAnh;
            $result['itemChuyenKhoa'] = $itemChuyenKhoa;
            $result['data'] = $array;
            
            return $result;
        } else {
            return null;
        }
    }
    
    public function getLichSuThuocVatTu(array $input)
    {
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.loai_y_lenh',
            'y_lenh.thoi_gian_chi_dinh',
            'y_lenh.phieu_y_lenh_id',
            'y_lenh.so_luong',
            'phong.ten_phong'
        ];
        
        $data = $this->model->whereIn('loai_y_lenh', self::Y_LENH_CODE_THUOC_VAT_TU)
                            ->join('phieu_y_lenh', function($join) use ($input) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.benh_nhan_id', '=', $input['benh_nhan_id'])
                                    ->where('phieu_y_lenh.hsba_id', '=', $input['hsba_id']);
                            })
                            ->leftJoin('phong', 'phong.id', '=', 'y_lenh.phong_id')
                            ->orderBy('y_lenh.id')
                            ->get($column);
        
        if($data) {
            $itemThuoc = 0;
            $itemVatTu = 0;
            $array = [];
            $type = null;
            
            foreach($data as $item) {
                if($item->loai_y_lenh == self::Y_LENH_CODE_THUOC) {
                    $itemThuoc++;
                    $type = self::Y_LENH_TEXT_THUOC;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_VAT_TU) {
                    $itemVatTu++;
                    $type = self::Y_LENH_TEXT_VAT_TU;
                }
                    
                $datetime = Carbon::parse($item->thoi_gian_chi_dinh)->format('d/m/Y');
                $phong = $item->ten_phong;
                $phieuYLenh = $item->phieu_y_lenh_id;
                $item['key'] = $item->id;
                $array[$phong][$datetime][$phieuYLenh][$type][] = $item;
            }
            
            $result['itemThuoc'] = $itemThuoc;
            $result['itemVatTu'] = $itemVatTu;
            $result['data'] = $array;
            
            return $result;
        } else {
            return null;
        }
    }
    
    public function getYLenhByHsbaId($hsbaId)
    {
        $total = 0;
        $result = [];
        $resultYeuCau = [];
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.gia',
            'y_lenh.gia_bhyt',
            'y_lenh.bhyt_tra',
            'y_lenh.vien_phi',
            'y_lenh.so_luong',
            'y_lenh.loai_y_lenh',
            'y_lenh.phieu_thu_id',
            'y_lenh.ma',
            'y_lenh.loai_thanh_toan_moi'
        ];
        
        $data = $this->model->join('phieu_y_lenh', function($join) use ($hsbaId) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.hsba_id', '=', $hsbaId);
                            })
                            ->orderBy('y_lenh.id')
                            ->get($column);
        if(!empty($data)) {
            $itemYeuCauKham = [];
            $itemXetNghiem = [];
            $itemChanDoanHinhAnh = [];
            $itemChuyenKhoa = [];
            $itemThuoc = [];
            $itemVatTu = [];
            
            $priceYeuCauKham = 0;
            $priceXetNghiem = 0;
            $priceChuanDoanHinhAnh = 0;
            $priceChuyenKhoa = 0;
            $priceThuoc = 0;
            $priceVatTu = 0;
            
            $priceBhytTra = 0;
            $priceVienPhi = 0;
            
            foreach($data as $item) {
                $donViTinh=$this->model
                                ->leftJoin('danh_muc_dich_vu','danh_muc_dich_vu.ma','=','y_lenh.ma')
                                ->where('y_lenh.ma','=',$item->ma)
                                ->get(['danh_muc_dich_vu.don_vi_tinh'])
                                ->first();
                $item['don_vi_tinh']=!empty($donViTinh)?$donViTinh->don_vi_tinh:null;
                $item['gia']            = !empty($item['gia']) ? (int)$item['gia'] : 0;
                $item['gia_bhyt']  = !empty($item['gia_bhyt']) ? (int)$item['gia_bhyt'] : 0;
                $item['bhyt_tra']   = !empty($item['bhyt_tra']) ? (int)$item['bhyt_tra'] : 0;
                $item['so_luong']       = !empty($item['so_luong']) ? (int)$item['so_luong'] : 0;
                // $item['thanh_tien'] = ($item['gia'] - $item['bhyt_tra']) * $item['so_luong'];
                $item['key']        = rand() . $item['id'];
                
                if($item->loai_y_lenh == self::Y_LENH_CODE_YEU_CAU_KHAM) {
                    $itemYeuCauKham[] = $item;
                    $priceYeuCauKham += $item['vien_phi'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_XET_NGHIEM) {
                    $itemXetNghiem[] = $item;
                    $priceXetNghiem += $item['vien_phi'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                    $itemChanDoanHinhAnh[] = $item;
                    $priceChuanDoanHinhAnh += $item['vien_phi'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHUYEN_KHOA) {
                    $itemChuyenKhoa[] = $item;
                    $priceChuyenKhoa += $item['vien_phi'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_THUOC) {
                    $itemThuoc[] = $item;
                    $priceThuoc += $item['vien_phi'];
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_VAT_TU) {
                    $itemVatTu[] = $item;
                    $priceVatTu += $item['vien_phi'];
                }
                
                $priceBhytTra += $item['bhyt_tra'];
                $priceVienPhi += $item['vien_phi'];
            }
            
            if(!empty($itemYeuCauKham)) {
                $result[] = [
                    'key'         => 'YC',
                    'ten'         => 'YÊU CẦU (' . number_format($priceYeuCauKham) . ' đ)',
                    'children'    => array([
                        'key'           => rand() . 'YCK',
                        'ten'           => 'Khám bệnh (' . number_format($priceYeuCauKham) . ' đ)',
                        'children'      => $itemYeuCauKham    
                    ])
                ];
                
                $total += $priceYeuCauKham;
            }
            
            if(!empty($itemXetNghiem)) {
                $resultYeuCau[] = [
                    'key'           => rand() . 'XN',
                    'ten'           => 'Xét nghiệm (' . number_format($priceXetNghiem) . ' đ)',
                    'children'      => $itemXetNghiem
                ]; 
            }
            
            if(!empty($itemChanDoanHinhAnh)) {
                $resultYeuCau[] = [
                    'key'           => rand() . 'CDHA',
                    'ten'           => 'Chẩn đoán hình ảnh (' . number_format($priceChuanDoanHinhAnh) . ' đ)',
                    'children'      => $itemChanDoanHinhAnh
                ]; 
            }
            
            if(!empty($itemChuyenKhoa)) {
                $resultYeuCau[] = [
                    'key'           => rand(). 'CK',
                    'ten'           => 'Chuyên khoa (' . number_format($priceChuyenKhoa) . ' đ)',
                    'children'      => $itemChuyenKhoa
                ]; 
            }
            
            if(!empty($itemThuoc)) {
                $resultYeuCau[] = [
                    'key'           => rand(). 'TH',
                    'ten'           => 'Thuốc (' . number_format($priceThuoc) . ' đ)',
                    'children'      => $itemThuoc
                ]; 
            }
            
            if(!empty($itemVatTu)) {
                $resultYeuCau[] = [
                    'key'           => rand(). 'VT',
                    'ten'           => 'Vật tư (' . number_format($priceVatTu) . ' đ)',
                    'children'      => $itemVatTu
                ]; 
            }
            
            if(!empty($resultYeuCau)) {
                $totalYeuCau = $priceXetNghiem + $priceChuanDoanHinhAnh + $priceChuyenKhoa + $priceThuoc + $priceVatTu;
                $result[] = [
                    'key'         => 'CD',
                    'ten'         => 'CHỈ ĐỊNH (' . number_format($totalYeuCau) . ' đ)',
                    'children'    => $resultYeuCau
                ];
                
                $total += $totalYeuCau;
            }
            
            $dataResult['data'] = $result;
            $dataResult['total'] = $priceBhytTra + $priceVienPhi;
            $dataResult['bhyt_tra'] = $priceBhytTra;
            $dataResult['vien_phi'] = $priceVienPhi;
            
            return $dataResult;
        } else {
            return [];
        }
    }
    
    public function updatePhieuThuIdByHsbaId($hsbaId, array $input)
    {
        $column = [
            'y_lenh.id',
            'y_lenh.ten',
            'y_lenh.gia',
            'y_lenh.bhyt_tra',
            'y_lenh.mien_giam',
            'y_lenh.so_luong',
            'y_lenh.loai_y_lenh',
            'y_lenh.phieu_thu_id'
        ];
        
        $data = $this->model->join('phieu_y_lenh', function($join) use ($hsbaId) {
                        $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                            ->where('phieu_y_lenh.hsba_id', '=', $hsbaId);
                    })
                    ->update(['phieu_thu_id' => $input['phieu_thu_id']]);
    }
  
    public function getDetailPhieuYLenh($phieuYLenhId,$type)
    {
        $typeCanLamSang = [2,3,4];
        $typeThuocVatTu = [5,6];
        $result = $this->model
                ->where('phieu_y_lenh_id',$phieuYLenhId)
                ->where('loai_y_lenh',$type)
                ->orderBy('id')
                ->get();
        if($result){
            if(in_array($type,$typeCanLamSang)){
                foreach($result as $item){
                    $phongThucHienId = DB::table('danh_muc_dich_vu')->where('ma',$item->ma)->first();
                    if($phongThucHienId->phong_thuc_hien){
                        $phongThucHienName = DB::table('phong')->where('id',$phongThucHienId->phong_thuc_hien)->first();
                        $item['phong_thuc_hien']=$phongThucHienName?$phongThucHienName->ten_phong:'';
                    }
                }
            }
            if(in_array($type,$typeThuocVatTu)){
                foreach($result as $item){
                    $dmThuocVatTu = DB::table('danh_muc_thuoc_vat_tu')->where('ma',$item->ma)->first();
                    $item['don_vi_tinh']=$dmThuocVatTu->don_vi_tinh;
                    $item['dang_dung']=$dmThuocVatTu->dang_dung;
                }
            }
            return $result;
        }
        else
            return [];
    }
    
    public function getYLenhByVienPhiId($vienPhiId)
    {
        $where = [
            ['y_lenh.vien_phi_id', '=', $vienPhiId],
            ['y_lenh.phieu_thu_id']//is null
        ];
        
        $column = [
            'y_lenh.ten',
            'y_lenh.gia',
            'y_lenh.gia_bhyt',
            'vien_phi.loai_vien_phi',
            'bhyt.ms_bhyt'
        ];
        
        $result = $this->model
            ->leftJoin('vien_phi','vien_phi.id','=','y_lenh.vien_phi_id')
            ->leftJoin('bhyt','bhyt.id','=','vien_phi.bhyt_id')
            ->where($where)
            ->get($column);
        return $result;
    }
    
    public function countItemYLenh($hsbaId)
    {
        $data = $this->model->select('loai_y_lenh', DB::raw('count(loai_y_lenh) as total'))
                            ->join('phieu_y_lenh', function($join) use ($hsbaId) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.hsba_id', '=', $hsbaId);
                            })
                            ->whereIn('loai_y_lenh', self::Y_LENH_CODE_CAN_LAM_SANG)
                            ->groupBy('loai_y_lenh')
                            ->get();
        
        if($data) {
            $itemXetNghiem = 0;
            $itemChanDoanHinhAnh = 0;
            $itemChuyenKhoa = 0;
            
            foreach($data as $item) {
                if($item->loai_y_lenh == self::Y_LENH_CODE_XET_NGHIEM) {
                    $itemXetNghiem = $item->total;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHAN_DOAN_HINH_ANH) {
                    $itemChanDoanHinhAnh = $item->total;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_CHUYEN_KHOA) {
                    $itemChuyenKhoa = $item->total;
                }
            }
            
            $result['itemXetNghiem'] = $itemXetNghiem;
            $result['itemChanDoanHinhAnh'] = $itemChanDoanHinhAnh;
            $result['itemChuyenKhoa'] = $itemChuyenKhoa;
            
            return $result;
        } else {
            return null;
        }
    }
    
    public function countItemThuocVatTu($hsbaId)
    {
        $data = $this->model->select('loai_y_lenh', DB::raw('count(loai_y_lenh) as total'))
                            ->join('phieu_y_lenh', function($join) use ($hsbaId) {
                                $join->on('phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                                    ->where('phieu_y_lenh.hsba_id', '=', $hsbaId);
                            })
                            ->whereIn('loai_y_lenh', self::Y_LENH_CODE_THUOC_VAT_TU)
                            ->groupBy('loai_y_lenh')
                            ->get();
        
        if($data) {
            $itemThuoc = 0;
            $itemVatTu = 0;
            
            foreach($data as $item) {
                if($item->loai_y_lenh == self::Y_LENH_CODE_THUOC) {
                    $itemThuoc = $item->total;
                }
                if($item->loai_y_lenh == self::Y_LENH_CODE_VAT_TU) {
                    $itemVatTu = $item->total;
                }
            }
            
            $result['itemThuoc'] = $itemThuoc;
            $result['itemVatTu'] = $itemVatTu;
            
            return $result;
        } else {
            return null;
        }
    }
    
    public function getListYLenhByVienPhiId($vienPhiId,$keyWords)
    {
        $column = [
            'y_lenh.*',
            'khoa.ten_khoa',
            'phong.ten_phong',
            'phieu_y_lenh.auth_users_id'
            ];
        $query = $this->model;
        if($keyWords!=null){
            $query->where('y_lenh.id','like','%'.$keyWords.'%');
        }
        $data = $query->where('y_lenh.vien_phi_id',$vienPhiId)
                    ->leftJoin('khoa', 'khoa.id', '=', 'y_lenh.khoa_id')
                    ->leftJoin('phong', 'phong.id', '=', 'y_lenh.phong_id')
                    ->leftJoin('phieu_y_lenh', 'phieu_y_lenh.id', '=', 'y_lenh.phieu_y_lenh_id')
                    ->orderBy('y_lenh.id','asc')
                    ->get($column);
        return $data;
    }
    
    public function updateYLenhById($id, array $input)
    {
        $data = $this->model->findOrFail($id);
		$data->update($input);
    }
}
