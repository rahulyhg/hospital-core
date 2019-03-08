<?php
namespace App\Services;

// Repositories
use App\Repositories\Hsba\HsbaKhoaPhongRepository;
use App\Repositories\Hsba\HsbaRepository;
use App\Repositories\VienPhi\VienPhiRepository;
use App\Repositories\Bhyt\BhytRepository;
use App\Repositories\PhongRepository;

class NoiTruService {
    //trạng thái hsba khoa phòng
    const TT_KET_THUC_DIEU_TRI = 99;
    const TT_CHO_DIEU_TRI = 0;
    const TT_DANG_DIEU_TRI = 2;
    
    //hình thức vào viện
    const NHAN_TU_KKB = 2;
    
    // Vien Phi
    const VIEN_PHI_TRANG_THAI = 0;
    const VIEN_PHI_TRANG_THAI_BH = 0;
    
    public function __construct(
        HsbaKhoaPhongRepository $hsbaKhoaPhongRepository,
        HsbaRepository $hsbaRepository, 
        VienPhiRepository $vienPhiRepository,
        PhongRepository $phongRepository,
        BhytRepository $bhytRepository
    )
    {
        $this->hsbaKhoaPhongRepository = $hsbaKhoaPhongRepository;
        $this->hsbaRepository = $hsbaRepository;
        $this->vienPhiRepository = $vienPhiRepository;
        $this->phongRepository = $phongRepository;
        $this->bhytRepository = $bhytRepository;
    }
    
    public function luuNhapKhoa(array $request)
    {
        //1. update hsba_kp : trạng thái = 2, phong_id; giường, thời gian vào viện, hình thức vào viện = 2
        //2. update hsba : khoa_id, phong_id, loai_benh_an, hình thức vào viện = 2
        //3. tạo viện phí mới
        $result = DB::transaction(function () use ($request) {
            try {
                $hsbaKp = $this->hsbaKhoaPhongRepository->getById($request['hsba_khoa_phong_id']);
                
                //1.1
                //viện phí ?
                $khoaHienTai = $hsbaKp['khoa_hien_tai'];
                $phongHienTai = $hsbaKp['phong_hien_tai'];
                //1. update hsba_kp : trạng thái = 2, phong_id; giường, thời gian vào viện, hình thức vào viện = 2
                $phong = $this->phongRepository->getPhongHanhChinhByKhoaID($khoaHienTai);
                $hsbaKp['loai_benh_an'] = $phong->loai_benh_an;
                $hsbaKp['trang_thai'] = self::TT_DANG_DIEU_TRI;
                $hsbaKp['giuong_hien_tai'] = NULL;
                $hsbaKp['thoi_gian_vao_vien'] = Carbon::now()->toDateTimeString();
                $hsbaKp['hinh_thuc_vao_vien_id'] = self::NHAN_TU_KKB;
                $this->hsbaKhoaPhongRepository->update($request['hsba_khoa_phong_id'], $hsbaKp);
                
                //2. update hsba : khoa_id, phong_id, loai_benh_an, hình thức vào viện = 2
                $hsba = $this->hsbaRepository->getById($request['hsba_id']);
                $hsba['loai_benh_an'] = $phong->loai_benh_an;
                $hsba['khoa_id'] = $khoaHienTai;
                $hsba['phong_id'] = $phongHienTai;
                $hsba['hinh_thuc_vao_vien'] = self::NHAN_TU_KKB;
                $this->hsbaRepository->updateHsba($request['hsba_id'], $hsba);
                
                //3. tạo viện phí mới
                $bhyt = $this->bhytRepository->getBhytByBenhNhanIdAndHsbaId($request['benh_nhan_id'], $request['hsba_id']);
                $dataVienPhi['loai_vien_phi'] = $hsbaKp['doi_tuong_benh_nhan'] == 1 ? 2 : 1;
                $dataVienPhi['trang_thai'] = self::VIEN_PHI_TRANG_THAI;// TODO - define constant
                $dataVienPhi['khoa_id'] = $this->dataHsba['khoa_id'];
                $dataVienPhi['doi_tuong_benh_nhan'] = $hsbaKp['doi_tuong_benh_nhan'];
                $dataVienPhi['bhyt_id'] = $bhyt['id'];
                $dataVienPhi['benh_nhan_id'] = $request['benh_nhan_id'];
                $dataVienPhi['hsba_id'] = $request['hsba_id'];
                $dataVienPhi['trang_thai_thanh_toan_bh'] = self::VIEN_PHI_TRANG_THAI_BH;// TODO - define constant
                $this->vienPhiRepository->createDataVienPhi($dataVienPhi);
            }
            catch (\Exception $ex) {
                throw $ex;
            }
        });
        return $result;
    }
}