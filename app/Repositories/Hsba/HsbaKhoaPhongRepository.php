<?php
namespace App\Repositories\Hsba;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\HsbaKhoaPhong;
use Carbon\Carbon;

class HsbaKhoaPhongRepository extends BaseRepositoryV2
{
    const BENH_AN_KHAM_BENH = 24;
    
    public function getModel()
    {
        return HsbaKhoaPhong::class;
    }
    
    public function getList($phongId, $benhVienId, $dataBenhVienThietLap, $startDay, $endDay, $limit = 20, $page = 1, $keyword = '', $status = -1)
    {
        $khoaHienTai = $dataBenhVienThietLap['khoaHienTai']; //khoa kham benh
        $phongDonTiepID = $dataBenhVienThietLap['phongDonTiepID'];

        $loaiBenhAn = 24; //kham benh
        $offset = ($page - 1) * $limit;
        
        if($phongId != $phongDonTiepID) {  //phong kham
            $where = [
                ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
                ['hsba_khoa_phong.phong_hien_tai', '=', $phongId],
                ['hsba_khoa_phong.benh_vien_id', '=', $benhVienId]
            ];
        } else {    //hanh chanh don tiep
            $where = [
                ['hsba_khoa_phong.loai_benh_an', '=', $loaiBenhAn],
                ['hsba_khoa_phong.khoa_hien_tai', '=', $khoaHienTai],
                ['hsba_khoa_phong.benh_vien_id', '=', $benhVienId]
            ];
        }
        
        $column = [
            'hsba.id as hsba_id',
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba.ten_benh_nhan',
            'hsba.nam_sinh',
            'hsba.ms_bhyt',
            'hsba.trang_thai_hsba',
            'hsba.ngay_tao',
            'hsba.ngay_ra_vien',
            'hsba_khoa_phong.thoi_gian_vao_vien',
            'hsba_khoa_phong.thoi_gian_ra_vien',
            'hsba_khoa_phong.trang_thai_cls',
            'tt1.diengiai as ten_trang_thai_cls',
            'hsba_khoa_phong.trang_thai',
            'tt2.diengiai as ten_trang_thai',
        ];
        
        $query = $this->model
            ->leftJoin('hsba', 'hsba.id', '=', 'hsba_khoa_phong.hsba_id')
            ->leftJoin('red_trangthai as tt1', function($join) {
                $join->on('tt1.giatri', '=', 'hsba_khoa_phong.trang_thai_cls')
                    ->where('tt1.tablename', '=', 'canlamsang');
            })
            ->leftJoin('red_trangthai as tt2', function($join) {
                $join->on('tt2.giatri', '=', 'hsba_khoa_phong.trang_thai')
                    ->where('tt2.tablename', '=', 'patientstatus');
            });
            
        // if($phongId != $phongDonTiepID) {
        //     $query = $query->leftJoin('stt_phong_kham as sttpk', function($join) use ($phongId) {
        //         $join->on('sttpk.hsba_id', '=', 'hsba_khoa_phong.hsba_id')
        //             ->where('sttpk.phong_id', '=', $phongId);
        //     });
            
        //     $arrayColumn = [
        //         'sttpk.loai_stt',
        //         'sttpk.so_thu_tu',
        //         'sttpk.stt_don_tiep_id',
        //     ];
            
        //     $column = array_merge($column, $arrayColumn);
        // }
            
        $query = $query->where($where);
        
        if($startDay == $endDay){
            $query = $query->whereDate('thoi_gian_vao_vien', '=', $startDay);
        } else {
            $query = $query->whereBetween('thoi_gian_vao_vien', [Carbon::parse($startDay)->startOfDay(), Carbon::parse($endDay)->endOfDay()]);
        }
        
        if($keyword != '') {
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $queryAdv->where('hsba.ten_benh_nhan', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan', 'like', '%'.$keyword.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$upperCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$lowerCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$titleCase.'%')
                        ->orWhere('hsba.ten_benh_nhan_khong_dau', 'like', '%'.$keyword.'%')
                        ->orWhereRaw("cast(hsba.id as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$keyword%'")
                        ->orWhereRaw("cast(hsba.ms_bhyt as text) like '%$upperCase%'");
            });
        }
        
        if($status != -1 && $phongId != $phongDonTiepID) {
            $query = $query->where(function($queryAdv) use ($status) {
                $queryAdv->where('hsba_khoa_phong.trang_thai', '=', $status);
            });
        }
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('thoi_gian_vao_vien', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
                        
            $data->each(function ($item, $key) {
                $item->hsba_id = sprintf('%012d', $item->hsba_id);
            });
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
    
    public function createData(array $input)
    {
         $id = $this->model->create($input)->id;
         return $id;
    }
    
    public function update($hsbaKhoaPhongId,array $params)
    {
        $hsbaKhoaPhong = $this->model->findOrFail($hsbaKhoaPhongId);
		$hsbaKhoaPhong->update($params);
    }
    
    public function getById($hsbaKhoaPhongId)
    {
        $where = [
            ['hsba_khoa_phong.id', '=', $hsbaKhoaPhongId],
        ];
        
        $result = $this->model->where($where)->get()->first();
        
        return $result;
    }
    
    public function getByHsbaId($hsbaId)
    {
        $where = [
            ['hsba_khoa_phong.loai_benh_an', '=', self::BENH_AN_KHAM_BENH],
            ['hsba.id', '=', $hsbaId]
        ];
        
        $column = [
            'hsba.id as hsba_id',
            'hsba.benh_nhan_id',
            'tt1.diengiai as loai_benh_an',
            'hsba.so_luu_tru',
            'hsba.so_vao_vien',
            //'vienphi.vienphicode',
            'khoa.ten_khoa',
            'phong.ten_phong',
            'hsba.ten_benh_nhan',
            'hsba.ngay_sinh',
            'hsba.nam_sinh',
            'hsba.gioi_tinh_id as gioi_tinh',
            'hsba.nghe_nghiep_id',
            'hsba.dan_toc_id',
            'hsba.quoc_tich_id',
            'hsba.so_nha',
            'hsba.duong_thon',
            'hsba.phuong_xa_id',
            'hsba.quan_huyen_id',
            'hsba.tinh_thanh_pho_id',
            'hsba.ten_phuong_xa',
            'hsba.ten_quan_huyen',
            'hsba.ten_tinh_thanh_pho',
            'hsba.noi_lam_viec',
            'hsba.dien_thoai_benh_nhan',
            'hsba.email_benh_nhan',
            'hsba.dia_chi_lien_he',
            'hsba.url_hinh_anh',
            'hsba.loai_nguoi_than',
            'hsba.ten_nguoi_than',
            'hsba.dien_thoai_nguoi_than',
            'hsba.ms_bhyt',
            'hsba.thx_gplace_json',
            'bhyt.ma_cskcbbd',
            'bhyt.tu_ngay',
            'bhyt.den_ngay',
            'bhyt.ma_noi_song',
            'bhyt.du5nam6thangluongcoban',
            'bhyt.dtcbh_luyke6thang',
            'tt2.diengiai as doi_tuong_benh_nhan',
            'hsba_khoa_phong.doi_tuong_benh_nhan as doi_tuong_benh_nhan_id',
            'hsba_khoa_phong.trang_thai',
            'hsba_khoa_phong.khoa_hien_tai',
            'hsba_khoa_phong.id as hsba_khoa_phong_id',
            'hsba_khoa_phong.cdvv_icd10_text',
            'hsba_khoa_phong.cdvv_icd10_code',
            'hsba_khoa_phong.ly_do_vao_vien',
            'hsba_khoa_phong.qua_trinh_benh_ly',
            'hsba_khoa_phong.tien_su_benh_ban_than',
            'hsba_khoa_phong.tien_su_benh_gia_dinh',
            'hsba_khoa_phong.cdtd_icd10_text',
            'hsba_khoa_phong.cdtd_icd10_code',
            'hsba_khoa_phong.noi_gioi_thieu_id',
            'hsba_khoa_phong.phong_hien_tai',
            'hsba_khoa_phong.thoi_gian_vao_vien',
            'hsba_khoa_phong.hinh_thuc_vao_vien_id',
            'hsba_khoa_phong.thoi_gian_ra_vien',
            'hsba_khoa_phong.cdrv_icd10_code',
            'hsba_khoa_phong.cdrv_icd10_text',
            'hsba_khoa_phong.cdrv_kt_icd10_code',
            'hsba_khoa_phong.cdrv_kt_icd10_text',
            'hsba_khoa_phong.ket_qua_dieu_tri',
            'hsba_khoa_phong.hinh_thuc_ra_vien',
            'hsba_khoa_phong.kham_toan_than',
            'hsba_khoa_phong.kham_bo_phan',
            'hsba_khoa_phong.ket_qua_can_lam_san',
            'hsba_khoa_phong.huong_xu_ly',
            'hsba_khoa_phong.tom_tat_benh_an',
            'hsba_khoa_phong.tien_luong',
            'hsba_khoa_phong.mach',
            'hsba_khoa_phong.nhiet_do',
            'hsba_khoa_phong.nhip_tho',
            'hsba_khoa_phong.sp_o2',
            'hsba_khoa_phong.can_nang',
            'hsba_khoa_phong.chieu_cao',
            'hsba_khoa_phong.thi_luc_mat_trai',
            'hsba_khoa_phong.thi_luc_mat_phai',
            'hsba_khoa_phong.kl_thi_luc_mat_trai',
            'hsba_khoa_phong.kl_thi_luc_mat_phai',
            'hsba_khoa_phong.nhan_ap_mat_trai',
            'hsba_khoa_phong.nhan_ap_mat_phai',
            'hsba_khoa_phong.huyet_ap_thap',
            'hsba_khoa_phong.huyet_ap_cao',
            'hsba_khoa_phong.chan_doan_ban_dau',
            'vien_phi.loai_vien_phi',
            'vien_phi.id as vien_phi_id',
            'bhyt.tuyen_bhyt',
            'sttpk.loai_stt',
            'sttpk.stt_don_tiep_id',
            'dieu_tri.id as phieu_dieu_tri_id'
        ];
        
        $query = $this->model
                ->rightJoin('hsba', 'hsba.id', '=', 'hsba_khoa_phong.hsba_id')
                ->leftJoin('red_trangthai as tt1', function($join) {
                    $join->on('tt1.giatri', '=', 'hsba_khoa_phong.loai_benh_an')
                        ->where('tt1.tablename', '=', 'loaibenhanid');
                })
                ->leftJoin('red_trangthai as tt2', function($join) {
                    $join->on('tt2.giatri', '=', 'hsba_khoa_phong.doi_tuong_benh_nhan')
                        ->where('tt2.tablename', '=', 'doituongbenhnhan');
                })
                ->leftJoin('khoa', 'khoa.id', '=', 'hsba_khoa_phong.khoa_hien_tai')
                ->leftJoin('phong', 'phong.id', '=', 'hsba_khoa_phong.phong_hien_tai')
                ->leftJoin('bhyt', 'bhyt.id', '=', 'hsba_khoa_phong.bhyt_id')
                ->leftJoin('vien_phi', 'vien_phi.hsba_id', '=', 'hsba.id')
                ->leftJoin('stt_phong_kham as sttpk', function($join) use ($hsbaId) {
                    $join->on('sttpk.hsba_id', '=', 'hsba_khoa_phong.hsba_id')
                        ->where('sttpk.hsba_id', '=', $hsbaId)
                        ->orderBy('sttpk.id', 'desc');
                })
                ->leftJoin('dieu_tri', function($join) use ($hsbaId) {
                    $join->on('dieu_tri.hsba_khoa_phong_id', '=', 'hsba_khoa_phong.id')
                        ->on('dieu_tri.khoa_id', '=', 'hsba_khoa_phong.khoa_hien_tai')
                        ->on('dieu_tri.phong_id', '=', 'hsba_khoa_phong.phong_hien_tai')
                        ->where('dieu_tri.hsba_id', '=', $hsbaId);
                });
            
        $data = $query->where($where)->get($column);
          
        $array = json_decode($data, true);
        
        return collect($array)->first();
    }
    
    public function getLichSuKhamDieuTri($id)
    {
        $where = [
            ['hsba_khoa_phong.benh_nhan_id', '=', $id],
        ];
        $column=[
            'phong.ten_phong',
            'hsba_khoa_phong.thoi_gian_vao_vien',
            'hsba_khoa_phong.thoi_gian_ra_vien',
            'hsba_khoa_phong.cdrv_icd10_text'
        ];
        $result = $this->model->leftJoin('phong','phong.id','=','hsba_khoa_phong.phong_hien_tai')
                            ->where($where)
                            ->get($column);
        return $result;
    }    
}