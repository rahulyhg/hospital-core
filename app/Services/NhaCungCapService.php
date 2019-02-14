<?php

namespace App\Services;

use App\Repositories\DanhMuc\NhaCungCapRepository;
use Illuminate\Http\Request;
use Validator;

class NhaCungCapService {
    public function __construct(
        NhaCungCapRepository $nhaCungCapRepository)
    {
        $this->nhaCungCapRepository = $nhaCungCapRepository;
    }

    public function getListNhaCungCap($limit, $page, $keyWords)
    {
        $data = $this->nhaCungCapRepository->getListNhaCungCap($limit, $page, $keyWords);
        return $data;
    }
    
    public function createNhaCungCap(array $input)
    {
        $id = $this->nhaCungCapRepository->createNhaCungCap($input);
        return $id;
    } 
    
    public function updateNhaCungCap($id, array $input)
    {
        $this->nhaCungCapRepository->updateNhaCungCap($id, $input);
    }
    
    public function deleteNhaCungCap($id)
    {
        $this->nhaCungCapRepository->deleteNhaCungCap($id);
    }
    
    public function getNhaCungCapById($id)
    {
        $data = $this->nhaCungCapRepository->getNhaCungCapById($id);
        return $data;
    }    

}