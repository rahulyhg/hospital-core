<?php
namespace App\Services;

use App\Repositories\CompareDB\CompareDBRepository;
use Illuminate\Http\Request;
use Validator;

class CompareDBService
{
    public function __construct(CompareDBRepository $compareDBRepository)
    {
        $this->compareDBRepository = $compareDBRepository;
    }
    
    public function getInfoTable($input)
    {
        $data = $this->compareDBRepository->getInfoTable($input);
        return $data;
    }    
}