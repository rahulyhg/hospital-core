<?php

namespace App\Services;

use App\Repositories\GiuongBenhRepository;
use Illuminate\Http\Request;
use Validator;

class PhongBenhService {
  
    public function __construct(GiuongBenhRepository $giuongBenhRepository)
    {
        $this->giuongBenhRepository = $giuongBenhRepository;
    }
    
    public function create(array $input)
    {
        $id = $this->giuongBenhRepository->create($input);
        return $id;
    }
    
    public function getById($id)
    {
        $data = $this->giuongBenhRepository->getById($id);
        return $data;
    }
    
    public function update($id, array $input)
    {
        $this->giuongBenhRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->giuongBenhRepository->delete($id);
    }
}