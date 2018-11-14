<?php
namespace App\Services;

use App\Http\Resources\QuayResource;
use App\Repositories\QuayRepository;
use Illuminate\Http\Request;
use Validator;

class QuayService
{
    public function __construct(QuayRepository $quayRepository)
    {
        $this->quayRepository = $quayRepository;
    }
    
    public function getListQuay($khuVucId,$benhVienId)
    {
        return QuayResource::collection(
           $this->quayRepository->getListQuay($khuVucId,$benhVienId)
        );        
    }
    
}