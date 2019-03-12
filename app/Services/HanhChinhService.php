<?php
namespace App\Services;

use DB;
use Carbon\Carbon;
// Repositories
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\VienPhi\VienPhiRepository;
use App\Repositories\Bhyt\BhytRepository;
use App\Repositories\PhongRepository;
use App\Repositories\PhongBenhRepository;
use App\Repositories\GiuongBenhRepository;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\HanhChinhRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Repositories\Hsba\HsbaDonViRepository;
use App\Repositories\DieuTri\DieuTriRepository;
use App\Repositories\PhongGiuongChiTietRepository;
// Service
use App\Services\VienPhiService;

class HanhChinhService {
    //trạng thái hsba khoa phòng
    const TT_KET_THUC_DIEU_TRI = 99;
    const TT_CHO_DIEU_TRI = 0;
    const TT_DANG_DIEU_TRI = 2;
    
    //hình thức vào viện
    const NHAN_TU_KKB = 2;
    
    //loại phòng
    const PHONG_DIEU_TRI_NOI_TRU = 3;
    const PHONG_DIEU_TRI_NGOAI_TRU = 9;
    const PHONG_HANH_CHINH = 1;
    
    // Vien Phi
    const VIEN_PHI_TRANG_THAI = 0;
    const VIEN_PHI_TRANG_THAI_BH = 0;
    
    // Tinh trang giuong benh
    const DANG_SU_DUNG = 1;
    
    const PHIEU_DIEU_TRI = 3;
    
    // Y Lenh
    const TRANG_THAI = 0;
    const PHONG_OC = 7;
    
    public function __construct(
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository,
        HsbaRepository $hsbaRepository, 
        VienPhiRepository $vienPhiRepository,
        PhongRepository $phongRepository,
        BhytRepository $bhytRepository,
        PhongBenhRepository $phongBenhRepository,
        GiuongBenhRepository $giuongBenhRepository,
        YLenhRepository $yLenhRepository, 
        PhieuYLenhRepository $phieuYLenhRepository,
        HanhChinhRepository $hanhChinhRepository,
        DanhMucDichVuRepository $danhMucDichVuRepository,
        HsbaDonViRepository $hsbaDonViRepository,
        DieuTriRepository $dieuTriRepository,
        PhongGiuongChiTietRepository $phongGiuongChiTietRepository,
        VienPhiService $vienPhiService
    )
    {
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->hsbaRepository = $hsbaRepository;
        $this->vienPhiRepository = $vienPhiRepository;
        $this->phongRepository = $phongRepository;
        $this->bhytRepository = $bhytRepository;
        $this->phongBenhRepository = $phongBenhRepository;
        $this->giuongBenhRepository = $giuongBenhRepository;
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->yLenhRepository = $yLenhRepository;
        $this->hanhChinhRepository = $hanhChinhRepository;
        $this->danhMucDichVuRepository = $danhMucDichVuRepository;
        $this->hsbaDonViRepository = $hsbaDonViRepository;
        $this->dieuTriRepository = $dieuTriRepository;
        $this->phongGiuongChiTietRepository = $phongGiuongChiTietRepository;
        $this->vienPhiService = $vienPhiService;
    }
    
    public function luuNhapKhoa(array $request)
    {
        //0. Update hsbakp cũ
        //1. Tạo hsba mới
        //2. Tạo hsba_don_vi
        //3. tạo viện phí mới
        //4. Tạo điều trị mới
        //5. update giuong benh
        //6. update so luong giuong o phong benh
        //7. Tạo phong giuong chi tiet moi
        //8. tao phieu y lenh
        //9. tạo y lệnh
        $result = DB::transaction(function () use ($request) {
            try {
                $hsbaKp = $this->hsbaKhoaPhongRepository->getById($request['hsba_khoa_phong_id']);
                //viện phí ?
                $request['doi_tuong_benh_nhan'] = $hsbaKp['doi_tuong_benh_nhan'];
                //0. update hsbakp cu
                $this->updateOldHSBAKP($request);
                
                //1. Tao hsba
                $request['hsba_id'] = $this->createHSBA($request);
                
                //2. Tao hsba_don_vi
                $request['hsba_khoa_phong_id'] = $this->createHSBADV($request, $hsbaKp);
                
                //3. tạo viện phí mới
                $request['vien_phi_id'] = $this->createVienPhi($request, $hsbaKp);
                
                //4. Tao phieu dieu tri
                $request['dieu_tri_id'] = $this->createDieuTri($request);
                
                //5. Update giuong benh
                $dataGiuongBenh['benh_nhan_id'] = $request['benh_nhan_id'];
                $dataGiuongBenh['ten_benh_nhan'] = $request['ten_benh_nhan'];
                $dataGiuongBenh['tinh_trang'] = self::DANG_SU_DUNG;
                $this->giuongBenhRepository->update($request['giuong_id'], $dataGiuongBenh);
                
                //6. Update so luong giuong o phong benh
                $dataPhongBenh = $this->phongBenhRepository->getById($request['phong_benh_id']);
                $dataPhongBenhParams['con_trong'] = $dataPhongBenh['con_trong'] - 1;
                $this->phongBenhRepository->update($request['phong_benh_id'], $dataPhongBenhParams);
                
                //7. Tao phong giuong chi tiet
                $this->createPhongGiuongDetail($request);
                
                //8. tao phieu y lenh
                //$request['phieu_y_lenh_id'] = $this->createPhieuYLenh($request);
                
                //9. Tạo y lệnh
                //$this->createYLenh($request, $hsbaKp);
            }
            catch (\Exception $ex) {
                throw $ex;
            }
        });
        return $result;
    }
    
    private function createPhongGiuongDetail($request) {
        $phongGiuongDetailParams['benh_nhan_id'] = $request['benh_nhan_id'];
        $phongGiuongDetailParams['hsbadv_id'] = $request['hsba_khoa_phong_id'];
        $phongGiuongDetailParams['hsba_id'] = $request['hsba_id'];
        $phongGiuongDetailParams['phong_benh_id'] = $request['phong_benh_id'];
        $phongGiuongDetailParams['giuong_benh_id'] = $request['giuong_id'];
        $phongGiuongDetailParams['thoi_gian_bat_dau'] = Carbon::now()->toDateTimeString();
        $this->phongGiuongChiTietRepository->create($phongGiuongDetailParams);
    }
    
    private function updateOldHSBAKP($request) {
        $hsbaKp['khoa_chuyen_den'] = NULL;
        $hsbaKp['phong_chuyen_den'] = NULL;
        $this->hsbaKhoaPhongRepository->update($request['hsba_khoa_phong_id'], $hsbaKp);
    }

    private function createPhieuYLenh(array $input) {
        $phieuYLenhParams['benh_nhan_id'] = $input['benh_nhan_id'];
        $phieuYLenhParams['vien_phi_id'] = $input['vien_phi_id'];
        $phieuYLenhParams['hsba_id'] = $input['hsba_id'];
        $phieuYLenhParams['dieu_tri_id'] = $input['dieu_tri_id'];
        $phieuYLenhParams['khoa_id'] = $input['khoa_id'];
        $phieuYLenhParams['phong_id'] = $input['phong_id'];
        $phieuYLenhParams['loai_phieu_y_lenh'] = self::PHIEU_DIEU_TRI;
        $phieuYLenhParams['trang_thai'] = self::TRANG_THAI;
        $phieuYLenhId = $this->phieuYLenhRepository->getPhieuYLenhId($phieuYLenhParams);
        return $phieuYLenhId;
    }
    
    private function createYLenh(array $input, $hsbaKp) {
        // Get ten y lenh va gia theo danh muc dich vu
        $dataDanhMucDichVu = $this->danhMucDichVuRepository->getDataDanhMucDichVuById($input['loai_phong']);
        // Insert y lenh
        $yLenhParams = [
            'vien_phi_id'           => $input['vien_phi_id'],
            'phieu_y_lenh_id'       => $input['phieu_y_lenh_id'],
            'doi_tuong_benh_nhan'   => $input['doi_tuong_benh_nhan'],
            'khoa_id'               => $input['khoa_id'],
            'phong_id'              => $input['phong_id'],
            'ma'                    => $dataDanhMucDichVu['ma'],
            'ten'                   => $dataDanhMucDichVu['ten'],
            'ten_bhyt'              => $dataDanhMucDichVu['ten_bhyt'] ?? null,
            'ten_nuoc_ngoai'        => $dataDanhMucDichVu['ten_nuoc_ngoai'] ?? null,
            'trang_thai'            => self::TRANG_THAI,
            'gia'                   => $dataDanhMucDichVu['gia'],
            'gia_bhyt'              => $dataDanhMucDichVu['gia_bhyt'],
            'gia_nuoc_ngoai'        => $dataDanhMucDichVu['gia_nuoc_ngoai'],
            'loai_y_lenh'           => self::PHONG_OC,
            'thoi_gian_chi_dinh'    => Carbon::now()->toDateTimeString(),
            'muc_huong'             => $this->getMucHuong($hsbaKp),
        ];
        $this->yLenhRepository->saveYLenh($yLenhParams);
    }
    
    private function createHSBADV(array $request, $hsbaKp) {
        $hsbaDonViParams = null;
        $hsbaDonViParams['doi_tuong_benh_nhan'] = $hsbaKp['doi_tuong_benh_nhan'];
        $hsbaDonViParams['yeu_cau_kham_id'] = $hsbaKp['yeu_cau_kham_id'];
        $hsbaDonViParams['benh_vien_id'] = $hsbaKp['benh_vien_id'];
        $hsbaDonViParams['khoa_hien_tai'] = $request['khoa_id'];
        
        // //phòng hành chính của khoa chuyển đến
        $phong = $this->phongRepository->getPhongHanhChinhByKhoaID($request['khoa_id']);
        $hsbaDonViParams['phong_hien_tai'] = $phong->id;
        $hsbaDonViParams['loai_benh_an'] = $phong->loai_benh_an;
        $hsbaDonViParams['trang_thai'] = self::TT_CHO_DIEU_TRI; //0: chờ điều trị
        $hsbaDonViParams['hsba_id'] = $request['hsba_id'];
        $hsbaDonViParams['benh_nhan_id'] = $hsbaKp['benh_nhan_id'];
        $hsbaDonViParams['hinh_thuc_vao_vien_id'] = self::NHAN_TU_KKB; //2: nhận từ khoa khám bệnh
        $hsbaDonViParams['vien_phi_id'] = $hsbaKp['vien_phi_id'];
        $hsbaDonViParams['bhyt_id'] = $hsbaKp['bhyt_id'];
        $hsbaDonViParams['giuong_hien_tai'] = $request['giuong_id'];
        $hsbaDonViParams['thoi_gian_vao_vien'] = Carbon::now()->toDateTimeString();
        // //kiểm tra phòng chuyển đến có phải là phòng điều trị -> nếu đúng -> lấy trạng thái = 2: đang điều trị ngược lại 0: đang chờ điều trị
        $hsbaDonViParams['trang_thai'] = $phong->loai_phong == self::PHONG_DIEU_TRI_NOI_TRU || $phong->loai_phong == self::PHONG_DIEU_TRI_NGOAI_TRU ? self::TT_DANG_DIEU_TRI : self::TT_CHO_DIEU_TRI; 
        $hsbaDonViId = $this->hsbaDonViRepository->create($hsbaDonViParams);
        return $hsbaDonViId;
    }
    
    private function createHSBA(array $request) {
        $phong = $this->phongRepository->getPhongHanhChinhByKhoaID($request['khoa_id']);
        $hsba = $this->hsbaRepository->getById($request['hsba_id']);
        $hsbaNew = $hsba->toArray();
        $hsbaNew['loai_benh_an'] = $phong->loai_benh_an;
        $hsbaNew['benh_nhan_id'] = $request['benh_nhan_id'];
        $hsbaNew['khoa_id'] = $request['khoa_id'];
        $hsbaNew['phong_id'] = $request['phong_id'];
        $hsbaNew['hinh_thuc_vao_vien'] = self::NHAN_TU_KKB;
        $hsbaNew['trang_thai_hsba'] = 0;
        $hsbaNew['ngay_tao'] = Carbon::now()->toDateTimeString();
        $hsbaId = $this->hsbaRepository->createDataHsba($hsbaNew);
        return $hsbaId;
    }
    
    private function createVienPhi(array $request, $hsbaKp) {
        //$bhyt = $this->bhytRepository->getBhytByBenhNhanIdAndHsbaId($request['benh_nhan_id'], $request['hsba_id']);
        $dataVienPhi['loai_vien_phi'] = $hsbaKp['doi_tuong_benh_nhan'] == 1 ? 2 : 1;
        $dataVienPhi['trang_thai'] = self::VIEN_PHI_TRANG_THAI;// TODO - define constant
        $dataVienPhi['khoa_id'] = $request['khoa_id'];
        $dataVienPhi['doi_tuong_benh_nhan'] = $hsbaKp['doi_tuong_benh_nhan'];
        $dataVienPhi['benh_nhan_id'] = $request['benh_nhan_id'];
        $dataVienPhi['hsba_id'] = $request['hsba_id'];
        $dataVienPhi['trang_thai_thanh_toan_bh'] = self::VIEN_PHI_TRANG_THAI_BH;// TODO - define constant
        $vienPhiId = $this->vienPhiRepository->createDataVienPhi($dataVienPhi);
        return $vienPhiId;
    }
    
    private function createDieuTri(array $request) {
        $dataDieuTri['hsba_khoa_phong_id'] = $request['hsba_khoa_phong_id'];
        $dataDieuTri['hsba_id'] = $request['hsba_id'];
        $dataDieuTri['khoa_id'] = $request['khoa_id'];
        $dataDieuTri['phong_id'] = $request['phong_id'];
        $dataDieuTri['benh_nhan_id'] =  $request['benh_nhan_id'];
        $dieuTriId = $this->dieuTriRepository->createDataDieuTri($dataDieuTri);
        return $dieuTriId;
    }
    
    private function getMucHuong($data) {
        if($data['ms_bhyt']) {
            $input['ms_bhyt'] = $data['ms_bhyt'];
            $input['vien_phi_id'] = $data['vien_phi_id'];
            $input['loai_vien_phi'] = $data['loai_vien_phi'];
            //cần check thời hạn thẻ BHYT
            $data['muc_huong'] = $this->vienPhiService->getMucHuong($input);
        } else {
            $data['muc_huong'] = 0;
        }
        return $data['muc_huong'];
    }
    
    public function getListTinh()
    {
        $data = $this->hanhChinhRepository->getListTinh();
        return $data;
    }
    
    public function getListHuyen($maTinh)
    {
        $data = $this->hanhChinhRepository->getListHuyen($maTinh);
        return $data;
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        $data = $this->hanhChinhRepository->getListXa($maHuyen,$maTinh);
        return $data;
    }  
    
    public function getThxByKey($thxKey)
    {
        $data = $this->hanhChinhRepository->getThxByKey($thxKey);
        return $data;
    }   
}