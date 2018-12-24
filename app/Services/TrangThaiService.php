<?php
namespace App\Services;

use App\Repositories\TrangThaiRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;


class TrangThaiService
{
    public function __construct(TrangThaiRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function changeToState($tableModel, $attributes) {
        $this->repository->changeToState($tableModel, $attributes);
    } 
}