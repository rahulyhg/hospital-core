<?php

namespace App\Services;

use App\Repositories\Kho\TheKhoRepository;
use Illuminate\Http\Request;
use DB;
use Validator;

class TheKhoService {
    public function __construct(
        TheKhoRepository $theKhoRepository)
    {
        $this->theKhoRepository = $theKhoRepository;
    }
    
    public function getTonKhaDungById($id,$khoId)
    {
        $data = $this->theKhoRepository->getTonKhaDungById($id,$khoId);
        return $data;
    } 
}