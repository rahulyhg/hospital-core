<?php

namespace App\Services;

use App\Models\Phong;
use App\Http\Resources\PhongResource;
use App\Repositories\PhongRepository;
use Illuminate\Http\Request;
use Validator;

class PhongService {
    public function __construct(PhongRepository $phongRepository)
    {
        $this->phongRepository = $phongRepository;
    }

    public function getListPhong($loaiPhong,$khoaId)
    {
        return PhongResource::collection(
           $this->phongRepository->getListPhong($loaiPhong,$khoaId)
        );
    }

    public function getNhomPhong($loaiPhong,$khoaId)
    {
        return PhongResource::collection(
           $this->phongRepository->getNhomPhong($loaiPhong,$khoaId)
        );
    }

    public function getListPhongByBenhVienIdKeywords($benhVienId, $limit, $page, $keyWords)
    {
        return $this->phongRepository->getListPhongByBenhVienIdKeywords($benhVienId, $limit, $page, $keyWords);
    }

    public function createPhong($khoaId, array $input)
    {
        return $this->phongRepository->createPhong($khoaId, $input);
    }

    public function updatePhong($id, array $input)
    {
        $this->phongRepository->updatePhong($id, $input);
    }

    public function deletePhong($id)
    {
        $this->phongRepository->deletePhong($id);
    }

    public function getPhongById($id)
    {
        return $this->phongRepository->getPhongById($id);
    }
}
