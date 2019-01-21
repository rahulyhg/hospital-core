<?php
namespace App\Repositories;

use DB;
use App\Models\Phong;
use App\Repositories\BaseRepositoryV2;

class PhongRepository extends BaseRepositoryV2
{
    const BENH_AN_KHAM_BENH = 24;
    const TRANG_THAI_HOAT_DONG = 1;
    const PHONG_HANH_CHINH = 1;

    public function getModel()
    {
        return Phong::class;
    }

    public function getListPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_phong')
                            ->get();
        return $phong;
    }

    public function getNhomPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_nhom')
                            ->distinct()
                            ->get(['ten_nhom','ma_nhom']);
        return $phong;
    }

    public function getDataById($id)
    {
        $phong = $this->model->where(['id'=>$id])
                            ->get()
                            ->first();
        return $phong;
    }

    public function getPhongHanhChinhByKhoaID($khoaId)
    {
        $phong = $this->model->where([
                                ['khoa_id', '=', $khoaId],
                                ['loai_phong', '=', self::PHONG_HANH_CHINH],
                                ['loai_benh_an', '!=', self::BENH_AN_KHAM_BENH]
                            ])
                            ->get()
                            ->first();
        return $phong;
    }

    public function getListPhongByBenhVienIdKeywords($benhVienId, $limit = 100, $page = 1, $keyWords = '')
    {
        $offset = ($page - 1) * $limit;

        $columns = [
          'phong.id as phong_id',
          'khoa_id',
          'ten_khoa',
          'so_phong',
          'ma_nhom',
          'ten_phong',
          'loai_phong',
          'loai_benh_an',
          'danh_muc_trang_thai.dien_giai as loai_benh_an_ct',
          'danh_muc_tong_hop.dien_giai as loai_phong_ct',
          'trang_thai',
          'ten_nhom'
        ];

        $query = $this->model->leftJoin('khoa','khoa_id','=','khoa.id')
                             ->leftJoin('benh_vien','khoa.benh_vien_id','=','benh_vien.id')
                             ->leftJoin('danh_muc_trang_thai','danh_muc_trang_thai.gia_tri','=','loai_benh_an')
                             ->leftJoin('danh_muc_tong_hop','danh_muc_tong_hop.gia_tri','=','loai_phong')
                             ->where([
                               ['benh_vien.id', '=', $benhVienId],
                               ['danh_muc_trang_thai.khoa', '=', 'loai_benh_an'],
                               ['danh_muc_tong_hop.khoa', '=', 'loai_phong']
                             ]);

        if($keyWords != ''){
           $query->whereRaw('LOWER(ten_phong) LIKE ? or LOWER(ten_khoa) LIKE ?',
           ['%'.strtolower($keyWords).'%', '%'.strtolower($keyWords).'%']);
        }

        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);

            $data = $query->orderBy('ten_khoa', 'asc')
                          ->orderBy('ten_phong', 'asc')
                          ->orderBy('so_phong', 'asc')
                          ->offset($offset)
                          ->limit($limit)
                          ->get($columns);
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

    public function createPhong($khoaId, array $input)
    {
        $input['khoa_id'] = $khoaId;
        return $this->model->create($input)->id;
    }

    public function updatePhong($id, array $input)
    {
        $khoa = $this->model->findOrFail($id);
        $khoa->update($input);
    }

    public function deletePhong($id)
    {
        $this->model->destroy($id);
    }

    public function getPhongById($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
